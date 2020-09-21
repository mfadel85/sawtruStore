<?php

class ControllerextensionDShopunityDValidator extends Controller
{
    protected $pack;
    protected $error;
    protected $expire;
    protected $module;
    protected $validator;
    protected $d_validator;
    protected $module_name;
    protected $validate_error;
    protected $module_codename;
    protected $d_opencart_patch;
    protected $codename = 'd_validator';
    protected $route = 'extension/d_shopunity/d_validator';

    public function __construct($registry)
    {
        parent::__construct($registry);

        $this->load->language($this->route);
        $this->d_validator = (file_exists(DIR_SYSTEM . 'library/d_shopunity/extension/d_validator.json'));
        $this->d_opencart_patch = (file_exists(DIR_SYSTEM . 'library/d_shopunity/extension/d_opencart_patch.json'));
    }

    public function index()
    {
        $this->installCompatibility();
    }

    public function installCompatibility()
    {
        $this->load->model('extension/d_shopunity/d_validator');
        $this->model_extension_d_shopunity_d_validator->installModule();
    }

    public function form()
    {
        $json = array();

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->load->language($this->route);

            $market = $this->request->post['market'];
            $email = $this->request->post['email'];
            $order_id = $this->request->post['order_id'];
            $codename = trim($this->request->post['codename']);
            $result = json_decode($this->request($market, $email, $order_id, $codename));

            if (isset($result->success) && isset($result->licence)) {
                $key = $this->strToHex($codename);

                $encrypted = base64_decode($result->licence);
                $decrypted = SaferCrypto::decrypt($encrypted, $key);

                if ($decrypted) {
                    $this->saveLicenceToFile($codename, $result->licence);

                    if($this->validateLicense($decrypted, $codename)){
                        $json['success'] = $this->language->get('license_is_valid');
                    }else{
                        $json['warning'] = $this->validate_error;
                    }

                }else{
                    $json['warning'] = $this->language->get('license_is_expired');
                }
            } else {
                if (isset($result->error)) {
                    $json['warning'] = $result->error;
                }
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public
    function validate()
    {
        $fields = [
            'market',
            'email',
            'order_id',
            'codename'
        ];

        foreach ($fields as $field) {
            if (!$this->request->post[$field]) {
                $this->error[$field] = $this->language->get('field_require');
            }
        }

        return !$this->error;
    }

    function strToHex($string)
    {
        $hex = '';
        for ($i = 0; $i < strlen($string); $i++) {
            $hex .= dechex(ord($string[$i]));
        }
        return $hex;
    }

    public
    function view(&$route, &$args, &$output)
    {
        $this->load->language($this->route);
        $this->load->model('extension/d_shopunity/d_validator');
        $this->model_extension_d_shopunity_d_validator->createTable();

        $this->expire = false;

        $url_parts = explode("/", $route);

        foreach ($url_parts as $url_part) {
            if (strpos($url_part, 'd_') !== false) {
                $this->module = trim($url_part);
            }
        }

        if ($this->module && $this->validationFileJsonCommercialByRoute($route)) {
            $licence = $this->getLicenceByFile();

            if ($licence) {
                $key = $this->strToHex($this->module_codename);

                $encrypted = base64_decode($licence);
                $decrypted = SaferCrypto::decrypt($encrypted, $key);

                if ($decrypted) {

                    if(!$this->validateLicense($decrypted, $this->module_codename)){
                        $this->decideShowOrNot();
                    }

                }else{
                    $this->decideShowOrNot();
                }

            } else {
                $this->decideShowOrNot();
            }
        }

        if ($this->expire) {

            $html = $this->expire;

            $html_dom = new d_simple_html_dom();

            $html_dom->load((string)$output, $lowercase = true, $stripRN = false, $defaultBRText = DEFAULT_BR_TEXT);

            $findSelector = $html_dom->find('#content>.page-header', 0);

            if ($findSelector) {
                $html_dom->find('#content>.page-header', 0)->outertext .= $html;
            }

            $output = (string)$html_dom;
        }
    }

    public function validationFileJsonCommercialByRoute($route)
    {
        $this->load->config($this->codename);
        $modules = $this->config->get($this->codename . '_modules');

        foreach ($modules as $module) {
            if (isset($module['route']) && $module['route'] == $route) {

                if (is_array($module['json'])) {
                    foreach ($module['json'] as $key => $json) {
                        $filePath = DIR_SYSTEM . "library/d_shopunity/extension/" . $json;

                        if (false !== ($contents = @file_get_contents($filePath, true))) {
                            $file = json_decode($contents);
                        }

                        if (isset($file) && isset($file->license->type) && $file->license->type == 'commercial') {
                            $this->module_codename = $module['codename'][$key];
                            $this->module_name = $file->name;

                            return true;
                        }
                    }
                }

                $filePath = DIR_SYSTEM . "library/d_shopunity/extension/" . $module['json'];

                if (false !== ($contents = @file_get_contents($filePath, true))) {
                    $file = json_decode($contents);
                }

                if (isset($file) && isset($file->license->type) && $file->license->type == 'commercial') {
                    $this->module_codename = $module['codename'];
                    $this->module_name = $file->name;

                    return true;
                }
            }
        }

        return false;
    }

    public function getLicenceByFile()
    {
        $filePath = DIR_SYSTEM . "library/d_shopunity/license/" . $this->module_codename . ".txt";
        $fileExists = (file_exists($filePath));

        if ($fileExists) {
            $content = file_get_contents($filePath);

            if ($content) {
                return $content;
            }
        }

        return false;
    }

    public function validateLicense($decoded_licence, $codename)
    {
        $this->load->language($this->route);

        $data = json_decode($decoded_licence);

        $url = $this->getSiteURl();

        if ($data && isset($data->url) && isset($data->codename) && isset($data->date_expired)) {
            if ($data->url != $url){
                $this->validate_error = $this->language->get('license_error_url');
            }

            if ($data->codename != $codename) {
                $this->validate_error = $this->language->get('license_error_codename');
            }

            if ($this->isExpire($data->date_expired)){
                $this->validate_error = $this->language->get('license_is_expired');
            }

        }else{
            $this->validate_error = $this->language->get('license_error');
        }

        return !$this->validate_error;
    }

    public function saveLicenceToFile($codename, $license)
    {
        $fonderPath = DIR_SYSTEM . "library/d_shopunity/license";
        $fileExists = (file_exists($fonderPath));

        if (!$fileExists) {
            $tags = explode('/', $fonderPath);
            $mkDir = "";

            foreach ($tags as $folder) {
                $mkDir = $mkDir . $folder . "/";
                if (!is_dir($mkDir)) {
                    mkdir($mkDir, 0777);
                }
            }
        }

        $filePath = DIR_SYSTEM . "library/d_shopunity/license/" . $codename . ".txt";
        return file_put_contents($filePath, $license, LOCK_EX);
    }

    public function decideShowOrNot()
    {
        $this->load->model('extension/d_shopunity/d_validator');
        $result = $this->model_extension_d_shopunity_d_validator->getTimeByCodename($this->module_codename);

        if ($result) {
            if ($this->isShow($result['date_show'])) {
                $view = $this->generateExpireView();

                $this->expire = $view;
            }
        } else {
            $view = $this->generateExpireView();

            $this->expire = $view;
        }
    }

    public
    function generateExpireView()
    {
        $data = array();

        $data['module_name'] = $this->module_name;
        $data['codename'] = $this->module_codename;

        $data['text'] = sprintf($this->language->get('license_expired'), $data['module_name']);
        $data['help'] = $this->language->get('license_visit_text');

        $this->load->model('extension/d_opencart_patch/url');
        $data['action'] = $this->model_extension_d_opencart_patch_url->ajax($this->route . '/form');
        $data['action_remind'] = $this->model_extension_d_opencart_patch_url->ajax($this->route . '/remind');

        $this->load->model('extension/d_opencart_patch/load');
        $view = $this->model_extension_d_opencart_patch_load->view('extension/d_shopunity/d_validator', $data);

        $this->expire = $view;

        return $view;
    }

    public function remind()
    {
        $json = array();

        if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            $module = $this->request->post['module'];

            $data = array();
            $data['codename'] = $this->request->post['module'];
            $data['date_created'] = date("Y-m-d H:i:s");
            $data['date_show'] = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s") . " + 7 day"));

            $this->load->model('extension/d_shopunity/d_validator');
            $result = $this->model_extension_d_shopunity_d_validator->getTimeByCodename($module);

            if ($result) {
                $result = $this->model_extension_d_shopunity_d_validator->updateTimeByCodename($module, $data);
            } else {
                $result = $this->model_extension_d_shopunity_d_validator->insertTime($data);
            }

            $json['success'] = true;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function request($market, $email, $order_id, $codename)
    {
        $url = $this->getSiteURl();

        $data = array(
            'codename' => $codename,
            'market' => $market,
            'order_id' => $order_id,
            'email' => $email,
            'url' => $url
        );

        $payload = json_encode($data);

        $this->load->config($this->codename);
        $setting = $this->config->get($this->codename . '_setting');

        $ch = curl_init($setting['api_url']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($payload))
        );

        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }

    public
    function isExpire($date_expire)
    {
        $now = new DateTime();
        $expire = new DateTime($date_expire);

        if ($expire > $now) {
            return 0;
        } else {
            return 1;
        }
    }

    public
    function isShow($date_show)
    {
        $now = new DateTime();
        $expire = new DateTime($date_show);

        if ($now > $expire) {
            return 1;
        } else {
            return 0;
        }
    }

    public function getSiteURl()
    {
        $url = null;

        if (defined('HTTPS_CATALOG')) {
            $url = HTTPS_CATALOG;
        } elseif (defined('HTTP_CATALOG')) {
            $url = HTTP_CATALOG;
        } else {
            $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ?
                    "https" : "http") . "://" . $_SERVER['HTTP_HOST'];
        }

        return $url;
    }
}


class UnsafeCrypto
{
    const METHOD = 'aes-256-ctr';

    /**
     * Encrypts (but does not authenticate) a message
     *
     * @param string $message - plaintext message
     * @param string $key - encryption key (raw binary expected)
     * @param boolean $encode - set to TRUE to return a base64-encoded
     * @return string (raw binary)
     */
    public static function encrypt($message, $key, $encode = false)
    {
        $nonceSize = openssl_cipher_iv_length(self::METHOD);
        $nonce = openssl_random_pseudo_bytes($nonceSize);

        $ciphertext = openssl_encrypt(
            $message,
            self::METHOD,
            $key,
            OPENSSL_RAW_DATA,
            $nonce
        );

        // Now let pack the IV and the ciphertext together
        // Naively, we can just concatenate
        if ($encode) {
            return base64_encode($nonce . $ciphertext);
        }
        return $nonce . $ciphertext;
    }

    /**
     * Decrypts (but does not verify) a message
     *
     * @param string $message - ciphertext message
     * @param string $key - encryption key (raw binary expected)
     * @param boolean $encoded - are we expecting an encoded string?
     * @return string
     */
    public static function decrypt($message, $key, $encoded = false)
    {
        if ($encoded) {
            $message = base64_decode($message, true);
            if ($message === false) {
                throw new Exception('Encryption failure');
            }
        }

        $nonceSize = openssl_cipher_iv_length(self::METHOD);
        $nonce = mb_substr($message, 0, $nonceSize, '8bit');
        $ciphertext = mb_substr($message, $nonceSize, null, '8bit');

        $plaintext = openssl_decrypt(
            $ciphertext,
            self::METHOD,
            $key,
            OPENSSL_RAW_DATA,
            $nonce
        );

        return $plaintext;
    }
}

class SaferCrypto extends UnsafeCrypto
{
    const HASH_ALGO = 'sha256';

    /**
     * Encrypts then MACs a message
     *
     * @param string $message - plaintext message
     * @param string $key - encryption key (raw binary expected)
     * @param boolean $encode - set to TRUE to return a base64-encoded string
     * @return string (raw binary)
     */
    public static function encrypt($message, $key, $encode = false)
    {
        list($encKey, $authKey) = self::splitKeys($key);

        // Pass to UnsafeCrypto::encrypt
        $ciphertext = parent::encrypt($message, $encKey);

        // Calculate a MAC of the IV and ciphertext
        $mac = hash_hmac(self::HASH_ALGO, $ciphertext, $authKey, true);

        if ($encode) {
            return base64_encode($mac . $ciphertext);
        }
        // Prepend MAC to the ciphertext and return to caller
        return $mac . $ciphertext;
    }

    /**
     * Decrypts a message (after verifying integrity)
     *
     * @param string $message - ciphertext message
     * @param string $key - encryption key (raw binary expected)
     * @param boolean $encoded - are we expecting an encoded string?
     * @return string (raw binary)
     */
    public static function decrypt($message, $key, $encoded = false)
    {
        list($encKey, $authKey) = self::splitKeys($key);
        if ($encoded) {
            $message = base64_decode($message, true);
            if ($message === false) {
                throw new Exception('Encryption failure');
            }
        }

        // Hash Size -- in case HASH_ALGO is changed
        $hs = mb_strlen(hash(self::HASH_ALGO, '', true), '8bit');
        $mac = mb_substr($message, 0, $hs, '8bit');

        $ciphertext = mb_substr($message, $hs, null, '8bit');

        $calculated = hash_hmac(
            self::HASH_ALGO,
            $ciphertext,
            $authKey,
            true
        );

        if (!self::hashEquals($mac, $calculated)) {
            throw new Exception('Encryption failure');
        }

        // Pass to UnsafeCrypto::decrypt
        $plaintext = parent::decrypt($ciphertext, $encKey);

        return $plaintext;
    }

    /**
     * Splits a key into two separate keys; one for encryption
     * and the other for authenticaiton
     *
     * @param string $masterKey (raw binary)
     * @return array (two raw binary strings)
     */
    protected static function splitKeys($masterKey)
    {
        // You really want to implement HKDF here instead!
        return [
            hash_hmac(self::HASH_ALGO, 'ENCRYPTION', $masterKey, true),
            hash_hmac(self::HASH_ALGO, 'AUTHENTICATION', $masterKey, true)
        ];
    }

    /**
     * Compare two strings without leaking timing information
     *
     * @param string $a
     * @param string $b
     * @ref https://paragonie.com/b/WS1DLx6BnpsdaVQW
     * @return boolean
     */
    protected static function hashEquals($a, $b)
    {
        if (function_exists('hash_equals')) {
            return hash_equals($a, $b);
        }
        $nonce = openssl_random_pseudo_bytes(32);
        return hash_hmac(self::HASH_ALGO, $a, $nonce) === hash_hmac(self::HASH_ALGO, $b, $nonce);
    }
}