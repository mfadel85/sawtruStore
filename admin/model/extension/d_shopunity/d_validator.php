<?php

class ModelExtensionDShopunityDValidator extends Model
{
    public $codename = 'd_validator';

    public function reinstallEvents($modules)
    {
        $this->load->model('extension/module/d_event_manager');
        $this->model_extension_module_d_event_manager->deleteEvent($this->codename);

        foreach ($modules as $module) {
            $this->model_extension_module_d_event_manager->addEvent($this->codename, $module['trigger'], $module['action'], 1);
        }
    }

    public function installCompatibility()
    {
        $this->load->config($this->codename);
        $modules = $this->config->get($this->codename . '_modules');

        if (!$this->isInstalledTable()){
            $this->createTable();
        }

        if (!$this->isFillEventsTable() || (int)$this->isFillEventsTable()['COUNT(*)'] != count($modules)){
            $this->reinstallEvents($modules);
        }
    }

    public function uninstallModule()
    {
        $this->load->model('setting/event');

        $this->model_setting_event->deleteEventByCode($this->codename);

        $this->deleteTable();
    }

    public function deleteEvents()
    {
        $this->load->model('setting/event');

        $this->model_setting_event->deleteEventByCode($this->codename);
    }

    public function getTimeByCodename($codename)
    {
        $query = $this->db->query("SELECT *  FROM " . DB_PREFIX . "d_validator WHERE codename = '" . $this->db->escape($codename) . "'");

        return $query->row;
    }

    public function insertTime($data)
    {
        return $this->db->query("INSERT INTO " . DB_PREFIX . "d_validator SET
        codename = '" . $this->db->escape($data['codename']) . "',
        date_show = '" . $this->db->escape($data['date_show']) . "',
        date_created = '" . $this->db->escape($data['date_created']) . "'");
    }

    public function updateTimeByCodename($codename, $data)
    {
        $this->db->query("UPDATE " . DB_PREFIX . "d_validator SET 
            `date_show` = '" . $this->db->escape($data['date_show']) . "',
            `date_created` = '" . $this->db->escape($data['date_created']) . "' 
            WHERE codename = '" . $this->db->escape($codename) . "'");
    }

    public function createTable()
    {
        $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "d_validator` (
            `validator_id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, 
            `codename` varchar(191) NOT NULL,
            `date_show` datetime NOT NULL,
            `date_created` datetime DEFAULT NULL
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;");
    }

    public function deleteTable()
    {
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "d_validator`");
    }

    public function uninstallDatabase()
    {
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "d_validator`");
    }

    public function isInstalledTable()
    {
        return $this->db->query("SELECT * FROM information_schema.TABLES WHERE TABLE_SCHEMA='" . $this->db->escape(DB_DATABASE) . "' AND TABLE_NAME='" . DB_PREFIX . "d_validator'")->num_rows > 0;
    }

    public function isFillEventsTable()
    {
        $query = $this->db->query("SELECT COUNT(*) FROM `" . DB_PREFIX . "event` WHERE code = '" . $this->codename . "'");

        return $query->row;
    }
}