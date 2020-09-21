<?php

/* catalog/stock_management.twig */
class __TwigTemplate_4b5457291830f0d0240cf6de4d87a76d0f6c2826ea1058821703c1432399e118 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo (isset($context["header"]) ? $context["header"] : null);
        // line 2
        echo (isset($context["column_left"]) ? $context["column_left"] : null);
        echo "
<div id=\"content\">
\t<div class=\"page-header\">
\t\t<div class=\"container-fluid\">
\t\t\t<div class=\"pull-right\">
\t\t\t\t<button type=\"submit\" form=\"form-attribute\" data-toggle=\"tooltip\" title=\"";
        // line 7
        echo (isset($context["button_save"]) ? $context["button_save"] : null);
        echo "\" class=\"btn btn-primary\">
\t\t\t\t\t<i class=\"fa fa-save\"></i>
\t\t\t\t</button>
\t\t\t\t<a href=\"";
        // line 10
        echo (isset($context["cancel"]) ? $context["cancel"] : null);
        echo "\" data-toggle=\"tooltip\" title=\"";
        echo (isset($context["button_cancel"]) ? $context["button_cancel"] : null);
        echo "\" class=\"btn btn-default\">
\t\t\t\t\t<i class=\"fa fa-reply\"></i>
\t\t\t\t</a>
\t\t\t</div>
\t\t\t<h1>";
        // line 14
        echo (isset($context["heading_title"]) ? $context["heading_title"] : null);
        echo "</h1>
\t\t\t<ul class=\"breadcrumb\">";
        // line 16
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["breadcrumbs"]) ? $context["breadcrumbs"] : null));
        foreach ($context['_seq'] as $context["_key"] => $context["breadcrumb"]) {
            // line 17
            echo "\t\t\t\t\t<li>
\t\t\t\t\t\t<a href=\"";
            // line 18
            echo $this->getAttribute($context["breadcrumb"], "href", array());
            echo "\">";
            echo $this->getAttribute($context["breadcrumb"], "text", array());
            echo "</a>
\t\t\t\t\t</li>";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['breadcrumb'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 21
        echo "\t\t\t</ul>
\t\t</div>
\t</div>
\t<div class=\"row container-fluid\">
\t\t<div class=\"col-sm-8\">
\t\t\t<div class=\"container-fluid row\">
\t\t\t\t<div class=\"form-group\">
\t\t\t\t\t<label class=\"col-sm-2 control-label\" for=\"\">Product Barcode</label>
\t\t\t\t\t<div class=\"col-sm-10\">
\t\t\t\t\t\t<input type=\"text\" name=\"productBarcode\" value=\"";
        // line 30
        echo (isset($context["productBarcode"]) ? $context["productBarcode"] : null);
        echo "\" placeholder=\"";
        echo (isset($context["productBarcode"]) ? $context["productBarcode"] : null);
        echo "\" id=\"productBarcode\" class=\"form-control\"/>
\t\t\t\t\t</div>
\t\t\t\t</div>
\t\t\t</div>

\t\t\t<div class=\"container-fluid row\">
\t\t\t\t<div class=\"form-group\">
\t\t\t\t\t<label class=\"col-sm-1 control-label\" for=\"\">Dimensions</label>
\t\t\t\t\t<div class=\"col-sm-2\">
\t\t\t\t\t\tL<label type=\"text\" name=\"length\" value=\"\" placeholder=\"\" id=\"length\" class=\"form-control\"></label>
\t\t\t\t\t</div>
\t\t\t\t\t<div class=\"col-sm-2\">
\t\t\t\t\t\tWidth<label type=\"text\" name=\"width\" value=\"\" placeholder=\"\" id=\"width\" class=\"form-control\"></label>
\t\t\t\t\t</div>
\t\t\t\t\t<div class=\"col-sm-2\">
\t\t\t\t\t\tD<label type=\"text\" name=\"depth\" value=\"\" placeholder=\"\" id=\"depth\" class=\"form-control\"></label>
\t\t\t\t\t</div>
\t\t\t\t\t<div class=\"col-sm-1\">
\t\t\t\t\t\tWeight<label type=\"text\" name=\"weight\" value=\"\" placeholder=\"\" id=\"weight\" class=\"form-control\"></label>
\t\t\t\t\t</div>
\t\t\t\t\t<div class=\"col-sm-2\">
\t\t\t\t\t\tPallet Count<label type=\"text\" name=\"bentCount\" value=\"\" placeholder=\"\" id=\"bentCount\" class=\"form-control\"></label>
\t\t\t\t\t</div>
\t\t\t\t\t<div class=\"col-sm-2\">
\t\t\t\t\t\tProduct ID<input type=\"text\" name=\"productID\" value=\"\" placeholder=\"\" id=\"productID\" class=\"form-control\"/>
\t\t\t\t\t</div>
\t\t\t\t</div>
\t\t\t</div>
\t\t\t<div class=\"container-fluid row\">
\t\t\t\t<div class=\"form-group\">
\t\t\t\t\t<label class=\"col-sm-2 control-label\" for=\"\">Pallet Barcode</label>
\t\t\t\t\t<div class=\"col-sm-10\">
\t\t\t\t\t\t<input type=\"text\" name=\"palletBarcode\" value=\"";
        // line 62
        echo (isset($context["palletBarcode"]) ? $context["palletBarcode"] : null);
        echo "\" placeholder=\"";
        echo (isset($context["palletBarcode"]) ? $context["palletBarcode"] : null);
        echo "\" id=\"palletBarcode\" class=\"form-control\"/>
\t\t\t\t\t</div>
\t\t\t\t</div>
\t\t\t</div>
\t\t</div>
\t\t<div class=\"col-sm-2\">
    \t\t<h3>Out of stock soon!!</h3>
\t\t\t<ul>
\t\t\t\t<li>Milk only 5 pcs left</li>
\t\t\t\t<li>Pepsi only 3 pcs left</li>
\t\t\t\t<li>Lays only 1 pcs left</li>
\t\t\t\t<li>Nescafe 200gr only 2 pcs left</li>

\t\t\t</ul>
\t\t</div>
\t\t<div class=\"col-sm-2\" style=\"background-color:grey;\">
\t\t<h2>Products List</h2>
\t\t<input type=\"text\" placeholder=\"type some product\" />

\t\t<ul>";
        // line 82
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["products"]) ? $context["products"] : null));
        foreach ($context['_seq'] as $context["_key"] => $context["product"]) {
            // line 83
            echo "\t\t\t<li class='draggable' id=\"";
            echo $this->getAttribute($context["product"], "product_id", array(), "array");
            echo "\" data-pallet-count=\"";
            echo $this->getAttribute($context["product"], "bent_count", array(), "array");
            echo "\" >";
            echo $this->getAttribute($context["product"], "name", array(), "array");
            echo "</li>";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['product'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 85
        echo "
\t\t</ul>\t\t
\t\t</div>
\t</div>
  <br><BR><BR>

\t<div class=\"row container-fluid\" id =\"units\" >";
        // line 92
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["map"]) ? $context["map"] : null));
        $context['loop'] = array(
          'parent' => $context['_parent'],
          'index0' => 0,
          'index'  => 1,
          'first'  => true,
        );
        if (is_array($context['_seq']) || (is_object($context['_seq']) && $context['_seq'] instanceof Countable)) {
            $length = count($context['_seq']);
            $context['loop']['revindex0'] = $length - 1;
            $context['loop']['revindex'] = $length;
            $context['loop']['length'] = $length;
            $context['loop']['last'] = 1 === $length;
        }
        foreach ($context['_seq'] as $context["_key"] => $context["unit"]) {
            // line 93
            echo "\t\t\t<div class=\"col-sm-6\">
\t\t\t\t<h3>Unit";
            // line 94
            echo $this->getAttribute($context["loop"], "index", array());
            echo "</h3>
\t\t\t\t<table class=\"table table-striped table-bordered\">
\t\t\t\t\t<thead>
\t\t\t\t\t<tr>
\t\t\t\t\t\t<th scope=\"col\">#</th>
\t\t\t\t\t\t<th scope=\"col\">1</th>
\t\t\t\t\t\t<th scope=\"col\">2</th>
\t\t\t\t\t\t<th scope=\"col\">3</th>
\t\t\t\t\t\t<th scope=\"col\">4</th>
\t\t\t\t\t\t<th scope=\"col\">5</th>
\t\t\t\t\t\t<th scope=\"col\">6</th>
\t\t\t\t\t</tr>
\t\t\t\t</thead>
\t\t\t\t<tbody>";
            // line 108
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable($context["unit"]);
            $context['loop'] = array(
              'parent' => $context['_parent'],
              'index0' => 0,
              'index'  => 1,
              'first'  => true,
            );
            if (is_array($context['_seq']) || (is_object($context['_seq']) && $context['_seq'] instanceof Countable)) {
                $length = count($context['_seq']);
                $context['loop']['revindex0'] = $length - 1;
                $context['loop']['revindex'] = $length;
                $context['loop']['length'] = $length;
                $context['loop']['last'] = 1 === $length;
            }
            foreach ($context['_seq'] as $context["_key"] => $context["item"]) {
                // line 109
                echo "\t\t\t\t\t<tr>
\t\t\t\t\t<td  >";
                // line 110
                echo $this->getAttribute($context["loop"], "index", array());
                echo "</td>";
                // line 111
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable($context["item"]);
                foreach ($context['_seq'] as $context["_key"] => $context["pallet"]) {
                    // line 112
                    echo "
\t\t\t\t\t\t<td id=\"r";
                    // line 113
                    echo $this->getAttribute($context["pallet"], 8, array(), "array");
                    echo "c";
                    echo $this->getAttribute($context["pallet"], 7, array(), "array");
                    echo "\" data-filled=\"";
                    echo $this->getAttribute($context["pallet"], 1, array(), "array");
                    echo "\" data-product-id=\"";
                    echo $this->getAttribute($context["pallet"], 10, array(), "array");
                    echo "\" data-barcode=\"";
                    echo $this->getAttribute($context["pallet"], 9, array(), "array");
                    echo "\" data-pallet-count=\"";
                    echo $this->getAttribute($context["pallet"], 5, array(), "array");
                    echo "\"";
                    // line 114
                    if (($this->getAttribute($context["pallet"], 3, array(), "array") == 0)) {
                        // line 115
                        echo "\t\t\t\t\t\t\tclass=\"full\"";
                    } elseif (($this->getAttribute(                    // line 116
$context["pallet"], 3, array(), "array") ==  -1)) {
                        // line 117
                        echo "\t\t\t\t\t\t\tclass=\"empty\"";
                    } elseif (($this->getAttribute(                    // line 118
$context["pallet"], 3, array(), "array") > 0)) {
                        // line 119
                        echo "\t\t\t\t\t\t\tclass=\"assigned\"";
                    }
                    // line 121
                    echo "\t\t\t\t\t\t
\t\t\t\t\t\t >";
                    // line 122
                    echo $this->getAttribute($context["pallet"], 4, array(), "array");
                    echo " -";
                    echo $this->getAttribute($context["pallet"], 1, array(), "array");
                    echo " /";
                    echo $this->getAttribute($context["pallet"], 6, array(), "array");
                    echo " </td>";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['pallet'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 124
                echo "\t\t\t\t\t</tr>";
                ++$context['loop']['index0'];
                ++$context['loop']['index'];
                $context['loop']['first'] = false;
                if (isset($context['loop']['length'])) {
                    --$context['loop']['revindex0'];
                    --$context['loop']['revindex'];
                    $context['loop']['last'] = 0 === $context['loop']['revindex0'];
                }
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['item'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 126
            echo "\t\t\t\t</tbody>\t
\t\t\t\t</table>
\t\t\t</div>";
            ++$context['loop']['index0'];
            ++$context['loop']['index'];
            $context['loop']['first'] = false;
            if (isset($context['loop']['length'])) {
                --$context['loop']['revindex0'];
                --$context['loop']['revindex'];
                $context['loop']['last'] = 0 === $context['loop']['revindex0'];
            }
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['unit'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 130
        echo "\t  
\t</div>


</div>
<script type=\"text/javascript\">
\t
\tfunction allElementsFromPoint(x, y) {
\t\tconsole.log('movement');
\t\tvar element, elements = [];
\t\telement = document.elementFromPoint(x, y);
\t\tconsole.log('element',element,x,y);
\t\treturn element;
\t}
\t\$(\".draggable\").draggable({ 
\t\trevert: true,
\t\tcursor: \"move\",
\t\tstart: function(event, ui) {
\t\t\t\tconsole.log('event is being dropped');
                 //to get the id
                 //ui.draggable.attr('id') or ui.draggable.get(0).id or ui.draggable[0].id
                console.dir('object is :',\$(event.target).attr(\"id\"));
\t\t\t\tconsole.dir(ui.helper.context.id);
\t\t\t\tconsole.dir(ui.helper.context.innerHTML);

                console.log(ui.helper.clone());
\t\t\t},

\t\tstop:function(event,ui){
\t\t\tvar el = allElementsFromPoint(event.pageX,event.pageY);
\t\t\tconst productName = ui.helper.context.innerHTML;
\t\t\tconsole.log(ui.helper.context);
\t\t\tconst palletCount = ui.helper.context.getAttribute(\"data-pallet-count\");
\t\t\tconst productID = ui.helper.context.getAttribute(\"id\");
\t\t\tvar td = \$(el).filter('td').not(\$(this));
\t\t\t// now as you got the cell get its parent col and row: get the row and col of it
\t\t\tconsole.log('elements',td);
\t\t\tvar pageX  = td[0].offsetLeft;
\t\t\tvar pageY  = td[0].offsetTop;
\t\t\tconsole.log('element id',td[0].id);
\t\t\tconst barCode = td[0].getAttribute(\"data-barcode\");
\t\t\tconst filled = td[0].getAttribute(\"data-filled\");

\t\t\tif(filled != \"0\"){
\t\t\t\talert(\"you can't do this, this bent has already assigned to another product!!\");
\t\t\t\treturn;
\t\t\t}\t\t\t   
\t\t\tconst rIndex = td[0].id.indexOf('r');
\t\t\tconst cIndex = td[0].id.indexOf('c');
\t\t\tconst row = td[0].id.substring(td[0].id.indexOf('r')+1,td[0].id.indexOf('c'));
\t\t\tconst col = td[0].id.substring(td[0].id.indexOf('c')+1,td[0].id.length);
\t\t\tconst maxCol = parseInt(col)+parseInt(palletCount)-1;
\t\t\tconsole.log(\"col\",col,\"palletCount\",palletCount,\"maxCol\",maxCol);

\t\t\tif(maxCol > 6){ // 6 is column count will be dynamic
\t\t\t\talert(\"you can't do this bent, not enought space next to the product !!\");
\t\t\t\treturn;
\t\t\t}
\t\t\tlet nextCells = [];
\t\t\tif(palletCount > 1 )
\t\t\t{

\t\t\t\tfor(let j=1;j<palletCount;j++){
\t\t\t\t\tlet k = parseInt(col)+j;
\t\t\t\t\tconst cellID = \"\\\"#r\"+row+\"c\"+k+\"\\\"\";
\t\t\t\t\tconst adjacentCell = \$(\"#r\"+row+\"c\"+k);
\t\t\t\t\tconst filled = adjacentCell[0].getAttribute(\"data-filled\");
\t\t\t\t\tif (parseInt(filled) >0){
\t\t\t\t\t\t\talert(\"you can't do this bent, this product needs more than one bent,the next bent is assigned to another product!!\");
\t\t\t\t\t\t\treturn;
\t\t\t\t\t}
\t\t\t\t\tnextCells.push(k);
\t\t\t\t}
\t\t\t}

\t\t\tconsole.log('drag ended',pageX,pageY,row,col,barCode);
\t\t\ttd[0].innerHTML = productName;
\t\t\ttd.css({background:'yellow'}); 
\t\t\tnextCells.forEach(function(item){
\t\t\t\t\$(\"#r\"+row+\"c\"+item).css({background:'yellow'});
\t\t\t\t\$(\"#r\"+row+\"c\"+item).html(productName);
\t
\t\t\t});
\t\t\t// needed data: start pallet id, product id,  bentCount, created date, modified date
\t\t\t// we will add the assign functionality
\t\t\t// now we will send ajax request so these changes update in the database , unit_id
\t\t\t\$.ajax({
\t\t\t\turl: 'http://localhost/store/index.php?route=api/pallet/assignPalletProduct',
\t\t\t\tasync: false,
\t\t\t\ttype: 'post',
\t\t\t\tdata: {palletID: barCode, productID:productID,bentCount:palletCount},
\t\t\t\tcrossDomain: true,
\t\t\t});
\t\t}

\t   });
\tproductID = 0;
\t\$.ajaxSetup({ cache: false ,containment: \"#units\", scroll: false});

\tvar api_token = '';

\t\$.ajax({
\t  url: 'http://localhost/store/index.php?route=api/login&key=LA6g3ogGx7lgceCO2uiFZJ4QCwfe93SY54OYi2Pvjnrnxr55sFygOMT1sATi0b7y439oTRZPlM2s9ZY9Qt6tLOYqyDcoVXmhNAChHV2wL3ptKSlaWxMtO5XHhsokshxVyCGiKgMMU775z4IVy549FxY4rTRYb8UVlGNHJBcDIQgkRXdWziUpkzJP6ybm1gUPIIVn5ehCXxQTiRXvqXc6dd0zz4MddwWnQdRMMbdS5wF2IszhxPunqKAYx2If6YZA',
\t  type: 'post',
\t  dataType: 'json',
\t  data: '',
\t  crossDomain: true,
\t  success: function(json) {
\t    \$('.alert').remove();
\t    if (json['error']) {
\t      if (json['error']['key']) {
\t        \$('#content > .container-fluid').prepend('<div class=\"alert alert-danger\"><i class=\"fa fa-exclamation-circle\"></i> ' + json['error']['key'] + ' <button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button></div>');
\t      }
\t      if (json['error']['ip']) {
\t        \$('#content > .container-fluid').prepend('<div class=\"alert alert-danger\"><i class=\"fa fa-exclamation-circle\"></i> ' + json['error']['ip'] + ' <button type=\"button\" id=\"button-ip-add\" data-loading-text=\"";
        // line 244
        echo (isset($context["text_loading"]) ? $context["text_loading"] : null);
        echo "\" class=\"btn btn-danger btn-xs pull-right\"><i class=\"fa fa-plus\"></i>";
        echo (isset($context["button_ip_add"]) ? $context["button_ip_add"] : null);
        echo "</button></div>');
\t      }
\t    }
\t    if (json['api_token']) {
\t      api_token = json['api_token'];
\t    }
\t  },
\t  error: function(xhr, ajaxOptions, thrownError) {
\t  \talert('failure');

\t    alert(thrownError + \"\\r\\n\" + xhr.statusText + \"\\r\\n\" + xhr.responseText);
\t  }
\t});
\t// checksum calculation for GTIN-8, GTIN-12, GTIN-13, GTIN-14, and SSCC
\t// based on http://www.gs1.org/barcodes/support/check_digit_calculator
\tfunction isValidBarcode(barcode) {
\t  // check length
\t  if (barcode.length < 8 || barcode.length > 18 ||
\t      (barcode.length != 8 && barcode.length != 12 &&
\t      barcode.length != 13 && barcode.length != 14 &&\t      
\t      barcode.length != 18)) {
\t    return false;
\t  }

\t  return true;
\t}
\tfunction onClickCell(event, field, value, row, \$element) {
\t\talert(\$element.text());
\t\t\$('.clicked-text').text(\$element.text());
\t\t\$('.clicked-field').text(field);
\t\t\$('.clicked-value').text(value);
\t\t\$('.alert').removeClass('hidden');
\t}\t
\t\$('.unit').on('click-cell.bs.table', onClickCell);

\tfunction getProductID(barcode){
\t\t\$.ajax({
\t\t\turl: 'http://localhost/store/index.php?route=api/product/get',
\t\t\tasync: false,
\t\t  \ttype: 'post',
\t\t  \tdata: {sku: barcode},
\t\t    crossDomain: true,
\t\t\t  success: function(json) {
\t\t\t  \t  
\t\t\t  },
\t\t\t  error: function(xhr, ajaxOptions, thrownError) {
\t\t  \t\talert('Get Product ID failure');
\t\t    \talert(thrownError + \"\\r\\n\" + xhr.statusText + \"\\r\\n\" + xhr.responseText);
\t\t\t    }
\t\t}).done(function(json){
\t    productId = json['product_id'];
      \t// get product model to name it to use it in selecting cells
      \tvar model = json['model'];
      \tallCells = \$(\".\"+model);
      \t//\$(\".\"+model).effect( \"shake\", { direction: \"left\", times: 4, distance: 101}, 2000 );
      \t\$(\".\"+model).animate({
        \topacity: 0.70,
        \tcolor:\"red\"
      \t}).addClass( \"activeCell\" );;
      \t\tconsole.log(allCells);
\t\t\t\$(\"#productID\").val(productId);
\t\t\t\$(\"#bentCount\").html(json['bent_count']);
\t\t\t\$(\"#length\").html(json['length']);
\t\t\t\$(\"#width\").html(json['width']);
\t\t\t\$(\"#depth\").html(json['height']);
\t\t\t\$(\"#weight\").html(json['weight']);

\t\t\treturn productId;
\t    });
\t\treturn productId;
\t}

\t\$(\"#productBarcode\").on(\"change\", function () {
    \t\$('.activeCell').removeClass('activeCell');

\t\t//don't activate this if the input is not 13 digit
\t\tvar barCode = \$(\"#productBarcode\").val();\t
\t\tif(barCode.length > 12){
\t\t\tvar test1 = isValidBarcode(barCode);
\t\t\tif(!test1){
\t\t\t\talert('not valid barcode');
\t\t\t\tevent.preventDefault();
\t\t\t}
\t\t\tproductID = getProductID(barCode);\t
\t\t}
\t\t
\t\t// if true get the product and its details and prepare the map
\t}); 

\t\$(\"#palletBarcode\").on(\"change\", function () {

\t\tif (\$(\"#productBarcode\").val() == '')
\t\t{
\t\t\talert(\"Enter the barcode of the product, first\");
\t\t\tevent.preventDefault();
\t\t\treturn;
\t\t}\t\t\t\t
\t\tsku = \$(\"#productBarcode\").val();
\t\tproductID = \$(\"#productID\").val();

\t\tvar barCode = \$(\"#palletBarcode\").val();\t\t      
\t\tvar palletID = 0;
\t\t// http://localhost/store/ should be updated to";
        // line 346
        echo (isset($context["catalog"]) ? $context["catalog"] : null);
        echo "
\t\t\$.ajax({
\t\t\turl: 'http://localhost/store/index.php?route=api/pallet/getPallet',
\t\t  \ttype: 'post',
\t\t  \t//dataType: 'json',
\t\t  \tdata: {
\t\t  \t\tpalletID: barCode
\t\t  \t},
\t\t    crossDomain: true,
\t\t\t  success: function(json) {
\t\t\t  \t  palletID = json['pallet_id'];
\t\t\t  \t  \$(\"#productID\").val(palletID);
\t\t\t  },
\t\t\t  error: function(xhr, ajaxOptions, thrownError) {
\t\t  \t\talert('Get Pallet ID failure');
\t\t    \talert(thrownError + \"\\r\\n\" + xhr.statusText + \"\\r\\n\" + xhr.responseText);
\t\t    }
\t    });
\t    
\t\t\$.ajax({
\t\t\turl: 'http://localhost/store/index.php?route=api/pallet/getPalletContent',
\t\t  \ttype: 'post',
\t\t  \t//dataType: 'json',
\t\t  \tdata: {
\t\t  \t\tpalletID: barCode,
\t\t  \t\tproductID: productID
\t\t  \t},
\t\t    crossDomain: true,
\t\t\t  success: function(json) {
\t\t\t  \t  palletID = json['pallet_id'];
\t\t\t  \t  \$(\"#productID\").val(palletID);
\t\t\t  },
\t\t\t  error: function(xhr, ajaxOptions, thrownError) {
\t\t\t\tconsole.log('Get content failure');
\t\t\t\tconsole.log(thrownError + \"\\r\\n\" + xhr.statusText + \"\\r\\n\" + xhr.responseText);
\t\t    }
\t    });\t  
\t    // we have to make sure that the productID is set before 
\t    if(typeof productID === 'undefined'){
\t    \talert(\"Couldn't get the Product ID\");
\t\t\tevent.preventDefault();
\t\t\treturn;
\t    }

\t\t\$.ajax({
\t\t\turl: 'http://localhost/store/index.php?route=api/pallet/getAvailableSpace',
\t\t  \ttype: 'post',
\t\t  \t//dataType: 'json',
\t    \tdata: {palletID:barCode,productID:productID},
\t\t    crossDomain: true,
\t\t\t  success: function(json) {
\t\t\t  \t  palletID = json['pallet_id'];
\t\t\t\t  //alert(json);
\t\t\t\t  // we can add the stock here
\t\t\t\t  // change product quantity, oc_product_to_position
\t\t\t\t  //clear this form
\t\t\t\t  },
\t\t\t  error: function(xhr, ajaxOptions, thrownError) {
\t\t\t\tconsole.log('Get space failure');
\t\t\t\tconsole.log(thrownError + \"\\r\\n\" + xhr.statusText + \"\\r\\n\" + xhr.responseText);
\t\t    \t
\t\t    }
\t    }).done(function(json){
\t    \tif(json < 1)
\t    \t{
\t    \t\talert(\"No Available Positions!!!\");
\t\t\t\tevent.preventDefault();
\t\t\t\treturn;
\t    \t}
\t    });\t\t

\t    \$.ajax({
\t    \turl:'http://localhost/store/index.php?route=api/pallet/updateStock',
\t    \ttype:'post',
\t    \tdata: {palletID:barCode,productID:productID},
\t    \tcrossDomain:true,
\t    \tsuccess:function(json){
\t    \t\t\$(\"#productBarcode\").val(\"\");//units
\t    \t\t\$(\"#palletBarcode\").val(\"\");//units
\t    \t\t\$(\"#productID\").val(\"\");
\t    \t\t\$(\"#length\").val(\"\");
\t    \t\t\$(\"#width\").val(\"\");
\t    \t\t\$(\"#depth\").val(\"\");
\t    \t\t\$(\"#weight\").val(\"\");
\t    \t\t\$(\"#bentCount\").val(\"\");

\t    \t},
\t    \terror: function(xhr,ajaxOptions,thrownError){
\t\t\t\tconsole.log('Update Failure');
\t\t\t\tconsole.log(thrownError + \"\\r\\n\" + xhr.statusText + \"\\r\\n\" + xhr.responseText);
\t    \t}
\t    }).done(function(json){
\t    \tif(json == 1)
\t    \t\talert(\"Added to stock\");
\t    });      \t
\t\t// if true get the product and its details and prepare the map
\t}); 
</script>";
        // line 444
        echo (isset($context["footer"]) ? $context["footer"] : null);
        echo "
";
    }

    public function getTemplateName()
    {
        return "catalog/stock_management.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  620 => 444,  520 => 346,  413 => 244,  297 => 130,  281 => 126,  267 => 124,  256 => 122,  253 => 121,  250 => 119,  248 => 118,  246 => 117,  244 => 116,  242 => 115,  240 => 114,  227 => 113,  224 => 112,  220 => 111,  217 => 110,  214 => 109,  197 => 108,  181 => 94,  178 => 93,  161 => 92,  153 => 85,  141 => 83,  137 => 82,  113 => 62,  76 => 30,  65 => 21,  55 => 18,  52 => 17,  48 => 16,  44 => 14,  35 => 10,  29 => 7,  21 => 2,  19 => 1,);
    }
}
/* {{ header }}*/
/* {{ column_left }}*/
/* <div id="content">*/
/* 	<div class="page-header">*/
/* 		<div class="container-fluid">*/
/* 			<div class="pull-right">*/
/* 				<button type="submit" form="form-attribute" data-toggle="tooltip" title="{{ button_save }}" class="btn btn-primary">*/
/* 					<i class="fa fa-save"></i>*/
/* 				</button>*/
/* 				<a href="{{ cancel }}" data-toggle="tooltip" title="{{ button_cancel }}" class="btn btn-default">*/
/* 					<i class="fa fa-reply"></i>*/
/* 				</a>*/
/* 			</div>*/
/* 			<h1>{{ heading_title }}</h1>*/
/* 			<ul class="breadcrumb">*/
/* 				{% for breadcrumb in breadcrumbs %}*/
/* 					<li>*/
/* 						<a href="{{ breadcrumb.href }}">{{ breadcrumb.text }}</a>*/
/* 					</li>*/
/* 				{% endfor %}*/
/* 			</ul>*/
/* 		</div>*/
/* 	</div>*/
/* 	<div class="row container-fluid">*/
/* 		<div class="col-sm-8">*/
/* 			<div class="container-fluid row">*/
/* 				<div class="form-group">*/
/* 					<label class="col-sm-2 control-label" for="">Product Barcode</label>*/
/* 					<div class="col-sm-10">*/
/* 						<input type="text" name="productBarcode" value="{{ productBarcode }}" placeholder="{{ productBarcode }}" id="productBarcode" class="form-control"/>*/
/* 					</div>*/
/* 				</div>*/
/* 			</div>*/
/* */
/* 			<div class="container-fluid row">*/
/* 				<div class="form-group">*/
/* 					<label class="col-sm-1 control-label" for="">Dimensions</label>*/
/* 					<div class="col-sm-2">*/
/* 						L<label type="text" name="length" value="" placeholder="" id="length" class="form-control"></label>*/
/* 					</div>*/
/* 					<div class="col-sm-2">*/
/* 						Width<label type="text" name="width" value="" placeholder="" id="width" class="form-control"></label>*/
/* 					</div>*/
/* 					<div class="col-sm-2">*/
/* 						D<label type="text" name="depth" value="" placeholder="" id="depth" class="form-control"></label>*/
/* 					</div>*/
/* 					<div class="col-sm-1">*/
/* 						Weight<label type="text" name="weight" value="" placeholder="" id="weight" class="form-control"></label>*/
/* 					</div>*/
/* 					<div class="col-sm-2">*/
/* 						Pallet Count<label type="text" name="bentCount" value="" placeholder="" id="bentCount" class="form-control"></label>*/
/* 					</div>*/
/* 					<div class="col-sm-2">*/
/* 						Product ID<input type="text" name="productID" value="" placeholder="" id="productID" class="form-control"/>*/
/* 					</div>*/
/* 				</div>*/
/* 			</div>*/
/* 			<div class="container-fluid row">*/
/* 				<div class="form-group">*/
/* 					<label class="col-sm-2 control-label" for="">Pallet Barcode</label>*/
/* 					<div class="col-sm-10">*/
/* 						<input type="text" name="palletBarcode" value="{{ palletBarcode }}" placeholder="{{ palletBarcode }}" id="palletBarcode" class="form-control"/>*/
/* 					</div>*/
/* 				</div>*/
/* 			</div>*/
/* 		</div>*/
/* 		<div class="col-sm-2">*/
/*     		<h3>Out of stock soon!!</h3>*/
/* 			<ul>*/
/* 				<li>Milk only 5 pcs left</li>*/
/* 				<li>Pepsi only 3 pcs left</li>*/
/* 				<li>Lays only 1 pcs left</li>*/
/* 				<li>Nescafe 200gr only 2 pcs left</li>*/
/* */
/* 			</ul>*/
/* 		</div>*/
/* 		<div class="col-sm-2" style="background-color:grey;">*/
/* 		<h2>Products List</h2>*/
/* 		<input type="text" placeholder="type some product" />*/
/* */
/* 		<ul>*/
/* 		{% for product in products %}*/
/* 			<li class='draggable' id="{{ product['product_id']}}" data-pallet-count="{{ product['bent_count'] }}" >{{ product['name']}}</li>*/
/* 		{% endfor %}*/
/* */
/* 		</ul>		*/
/* 		</div>*/
/* 	</div>*/
/*   <br><BR><BR>*/
/* */
/* 	<div class="row container-fluid" id ="units" >*/
/* 	    {% for unit in map  %}*/
/* 			<div class="col-sm-6">*/
/* 				<h3>Unit {{ loop.index }}</h3>*/
/* 				<table class="table table-striped table-bordered">*/
/* 					<thead>*/
/* 					<tr>*/
/* 						<th scope="col">#</th>*/
/* 						<th scope="col">1</th>*/
/* 						<th scope="col">2</th>*/
/* 						<th scope="col">3</th>*/
/* 						<th scope="col">4</th>*/
/* 						<th scope="col">5</th>*/
/* 						<th scope="col">6</th>*/
/* 					</tr>*/
/* 				</thead>*/
/* 				<tbody>*/
/* 				{% for item in unit %}*/
/* 					<tr>*/
/* 					<td  > {{ loop.index }}</td>*/
/* 					{% for pallet in item %}*/
/* */
/* 						<td id="r{{ pallet[8]}}c{{ pallet[7]}}" data-filled="{{ pallet[1] }}" data-product-id="{{ pallet[10] }}" data-barcode="{{ pallet[9]}}" data-pallet-count="{{ pallet[5]}}"*/
/* 						{% if  pallet[3]  == 0 %}*/
/* 							class="full"*/
/* 						{% elseif pallet[3] == -1  %}*/
/* 							class="empty"*/
/* 						{% elseif pallet[3] > 0  %}*/
/* 							class="assigned"*/
/* 						{% endif %}*/
/* 						*/
/* 						 > {{ pallet[4] }} - {{ pallet[1] }} / {{ pallet[6]}} </td>*/
/* 					{% endfor %}*/
/* 					</tr>*/
/* 				{% endfor %}*/
/* 				</tbody>	*/
/* 				</table>*/
/* 			</div>*/
/*   		{% endfor %}*/
/* 	  */
/* 	</div>*/
/* */
/* */
/* </div>*/
/* <script type="text/javascript">*/
/* 	*/
/* 	function allElementsFromPoint(x, y) {*/
/* 		console.log('movement');*/
/* 		var element, elements = [];*/
/* 		element = document.elementFromPoint(x, y);*/
/* 		console.log('element',element,x,y);*/
/* 		return element;*/
/* 	}*/
/* 	$(".draggable").draggable({ */
/* 		revert: true,*/
/* 		cursor: "move",*/
/* 		start: function(event, ui) {*/
/* 				console.log('event is being dropped');*/
/*                  //to get the id*/
/*                  //ui.draggable.attr('id') or ui.draggable.get(0).id or ui.draggable[0].id*/
/*                 console.dir('object is :',$(event.target).attr("id"));*/
/* 				console.dir(ui.helper.context.id);*/
/* 				console.dir(ui.helper.context.innerHTML);*/
/* */
/*                 console.log(ui.helper.clone());*/
/* 			},*/
/* */
/* 		stop:function(event,ui){*/
/* 			var el = allElementsFromPoint(event.pageX,event.pageY);*/
/* 			const productName = ui.helper.context.innerHTML;*/
/* 			console.log(ui.helper.context);*/
/* 			const palletCount = ui.helper.context.getAttribute("data-pallet-count");*/
/* 			const productID = ui.helper.context.getAttribute("id");*/
/* 			var td = $(el).filter('td').not($(this));*/
/* 			// now as you got the cell get its parent col and row: get the row and col of it*/
/* 			console.log('elements',td);*/
/* 			var pageX  = td[0].offsetLeft;*/
/* 			var pageY  = td[0].offsetTop;*/
/* 			console.log('element id',td[0].id);*/
/* 			const barCode = td[0].getAttribute("data-barcode");*/
/* 			const filled = td[0].getAttribute("data-filled");*/
/* */
/* 			if(filled != "0"){*/
/* 				alert("you can't do this, this bent has already assigned to another product!!");*/
/* 				return;*/
/* 			}			   */
/* 			const rIndex = td[0].id.indexOf('r');*/
/* 			const cIndex = td[0].id.indexOf('c');*/
/* 			const row = td[0].id.substring(td[0].id.indexOf('r')+1,td[0].id.indexOf('c'));*/
/* 			const col = td[0].id.substring(td[0].id.indexOf('c')+1,td[0].id.length);*/
/* 			const maxCol = parseInt(col)+parseInt(palletCount)-1;*/
/* 			console.log("col",col,"palletCount",palletCount,"maxCol",maxCol);*/
/* */
/* 			if(maxCol > 6){ // 6 is column count will be dynamic*/
/* 				alert("you can't do this bent, not enought space next to the product !!");*/
/* 				return;*/
/* 			}*/
/* 			let nextCells = [];*/
/* 			if(palletCount > 1 )*/
/* 			{*/
/* */
/* 				for(let j=1;j<palletCount;j++){*/
/* 					let k = parseInt(col)+j;*/
/* 					const cellID = "\"#r"+row+"c"+k+"\"";*/
/* 					const adjacentCell = $("#r"+row+"c"+k);*/
/* 					const filled = adjacentCell[0].getAttribute("data-filled");*/
/* 					if (parseInt(filled) >0){*/
/* 							alert("you can't do this bent, this product needs more than one bent,the next bent is assigned to another product!!");*/
/* 							return;*/
/* 					}*/
/* 					nextCells.push(k);*/
/* 				}*/
/* 			}*/
/* */
/* 			console.log('drag ended',pageX,pageY,row,col,barCode);*/
/* 			td[0].innerHTML = productName;*/
/* 			td.css({background:'yellow'}); */
/* 			nextCells.forEach(function(item){*/
/* 				$("#r"+row+"c"+item).css({background:'yellow'});*/
/* 				$("#r"+row+"c"+item).html(productName);*/
/* 	*/
/* 			});*/
/* 			// needed data: start pallet id, product id,  bentCount, created date, modified date*/
/* 			// we will add the assign functionality*/
/* 			// now we will send ajax request so these changes update in the database , unit_id*/
/* 			$.ajax({*/
/* 				url: 'http://localhost/store/index.php?route=api/pallet/assignPalletProduct',*/
/* 				async: false,*/
/* 				type: 'post',*/
/* 				data: {palletID: barCode, productID:productID,bentCount:palletCount},*/
/* 				crossDomain: true,*/
/* 			});*/
/* 		}*/
/* */
/* 	   });*/
/* 	productID = 0;*/
/* 	$.ajaxSetup({ cache: false ,containment: "#units", scroll: false});*/
/* */
/* 	var api_token = '';*/
/* */
/* 	$.ajax({*/
/* 	  url: 'http://localhost/store/index.php?route=api/login&key=LA6g3ogGx7lgceCO2uiFZJ4QCwfe93SY54OYi2Pvjnrnxr55sFygOMT1sATi0b7y439oTRZPlM2s9ZY9Qt6tLOYqyDcoVXmhNAChHV2wL3ptKSlaWxMtO5XHhsokshxVyCGiKgMMU775z4IVy549FxY4rTRYb8UVlGNHJBcDIQgkRXdWziUpkzJP6ybm1gUPIIVn5ehCXxQTiRXvqXc6dd0zz4MddwWnQdRMMbdS5wF2IszhxPunqKAYx2If6YZA',*/
/* 	  type: 'post',*/
/* 	  dataType: 'json',*/
/* 	  data: '',*/
/* 	  crossDomain: true,*/
/* 	  success: function(json) {*/
/* 	    $('.alert').remove();*/
/* 	    if (json['error']) {*/
/* 	      if (json['error']['key']) {*/
/* 	        $('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['key'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');*/
/* 	      }*/
/* 	      if (json['error']['ip']) {*/
/* 	        $('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['ip'] + ' <button type="button" id="button-ip-add" data-loading-text="{{ text_loading }}" class="btn btn-danger btn-xs pull-right"><i class="fa fa-plus"></i>{{ button_ip_add }}</button></div>');*/
/* 	      }*/
/* 	    }*/
/* 	    if (json['api_token']) {*/
/* 	      api_token = json['api_token'];*/
/* 	    }*/
/* 	  },*/
/* 	  error: function(xhr, ajaxOptions, thrownError) {*/
/* 	  	alert('failure');*/
/* */
/* 	    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);*/
/* 	  }*/
/* 	});*/
/* 	// checksum calculation for GTIN-8, GTIN-12, GTIN-13, GTIN-14, and SSCC*/
/* 	// based on http://www.gs1.org/barcodes/support/check_digit_calculator*/
/* 	function isValidBarcode(barcode) {*/
/* 	  // check length*/
/* 	  if (barcode.length < 8 || barcode.length > 18 ||*/
/* 	      (barcode.length != 8 && barcode.length != 12 &&*/
/* 	      barcode.length != 13 && barcode.length != 14 &&	      */
/* 	      barcode.length != 18)) {*/
/* 	    return false;*/
/* 	  }*/
/* */
/* 	  return true;*/
/* 	}*/
/* 	function onClickCell(event, field, value, row, $element) {*/
/* 		alert($element.text());*/
/* 		$('.clicked-text').text($element.text());*/
/* 		$('.clicked-field').text(field);*/
/* 		$('.clicked-value').text(value);*/
/* 		$('.alert').removeClass('hidden');*/
/* 	}	*/
/* 	$('.unit').on('click-cell.bs.table', onClickCell);*/
/* */
/* 	function getProductID(barcode){*/
/* 		$.ajax({*/
/* 			url: 'http://localhost/store/index.php?route=api/product/get',*/
/* 			async: false,*/
/* 		  	type: 'post',*/
/* 		  	data: {sku: barcode},*/
/* 		    crossDomain: true,*/
/* 			  success: function(json) {*/
/* 			  	  */
/* 			  },*/
/* 			  error: function(xhr, ajaxOptions, thrownError) {*/
/* 		  		alert('Get Product ID failure');*/
/* 		    	alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);*/
/* 			    }*/
/* 		}).done(function(json){*/
/* 	    productId = json['product_id'];*/
/*       	// get product model to name it to use it in selecting cells*/
/*       	var model = json['model'];*/
/*       	allCells = $("."+model);*/
/*       	//$("."+model).effect( "shake", { direction: "left", times: 4, distance: 101}, 2000 );*/
/*       	$("."+model).animate({*/
/*         	opacity: 0.70,*/
/*         	color:"red"*/
/*       	}).addClass( "activeCell" );;*/
/*       		console.log(allCells);*/
/* 			$("#productID").val(productId);*/
/* 			$("#bentCount").html(json['bent_count']);*/
/* 			$("#length").html(json['length']);*/
/* 			$("#width").html(json['width']);*/
/* 			$("#depth").html(json['height']);*/
/* 			$("#weight").html(json['weight']);*/
/* */
/* 			return productId;*/
/* 	    });*/
/* 		return productId;*/
/* 	}*/
/* */
/* 	$("#productBarcode").on("change", function () {*/
/*     	$('.activeCell').removeClass('activeCell');*/
/* */
/* 		//don't activate this if the input is not 13 digit*/
/* 		var barCode = $("#productBarcode").val();	*/
/* 		if(barCode.length > 12){*/
/* 			var test1 = isValidBarcode(barCode);*/
/* 			if(!test1){*/
/* 				alert('not valid barcode');*/
/* 				event.preventDefault();*/
/* 			}*/
/* 			productID = getProductID(barCode);	*/
/* 		}*/
/* 		*/
/* 		// if true get the product and its details and prepare the map*/
/* 	}); */
/* */
/* 	$("#palletBarcode").on("change", function () {*/
/* */
/* 		if ($("#productBarcode").val() == '')*/
/* 		{*/
/* 			alert("Enter the barcode of the product, first");*/
/* 			event.preventDefault();*/
/* 			return;*/
/* 		}				*/
/* 		sku = $("#productBarcode").val();*/
/* 		productID = $("#productID").val();*/
/* */
/* 		var barCode = $("#palletBarcode").val();		      */
/* 		var palletID = 0;*/
/* 		// http://localhost/store/ should be updated to {{catalog}}*/
/* 		$.ajax({*/
/* 			url: 'http://localhost/store/index.php?route=api/pallet/getPallet',*/
/* 		  	type: 'post',*/
/* 		  	//dataType: 'json',*/
/* 		  	data: {*/
/* 		  		palletID: barCode*/
/* 		  	},*/
/* 		    crossDomain: true,*/
/* 			  success: function(json) {*/
/* 			  	  palletID = json['pallet_id'];*/
/* 			  	  $("#productID").val(palletID);*/
/* 			  },*/
/* 			  error: function(xhr, ajaxOptions, thrownError) {*/
/* 		  		alert('Get Pallet ID failure');*/
/* 		    	alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);*/
/* 		    }*/
/* 	    });*/
/* 	    */
/* 		$.ajax({*/
/* 			url: 'http://localhost/store/index.php?route=api/pallet/getPalletContent',*/
/* 		  	type: 'post',*/
/* 		  	//dataType: 'json',*/
/* 		  	data: {*/
/* 		  		palletID: barCode,*/
/* 		  		productID: productID*/
/* 		  	},*/
/* 		    crossDomain: true,*/
/* 			  success: function(json) {*/
/* 			  	  palletID = json['pallet_id'];*/
/* 			  	  $("#productID").val(palletID);*/
/* 			  },*/
/* 			  error: function(xhr, ajaxOptions, thrownError) {*/
/* 				console.log('Get content failure');*/
/* 				console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);*/
/* 		    }*/
/* 	    });	  */
/* 	    // we have to make sure that the productID is set before */
/* 	    if(typeof productID === 'undefined'){*/
/* 	    	alert("Couldn't get the Product ID");*/
/* 			event.preventDefault();*/
/* 			return;*/
/* 	    }*/
/* */
/* 		$.ajax({*/
/* 			url: 'http://localhost/store/index.php?route=api/pallet/getAvailableSpace',*/
/* 		  	type: 'post',*/
/* 		  	//dataType: 'json',*/
/* 	    	data: {palletID:barCode,productID:productID},*/
/* 		    crossDomain: true,*/
/* 			  success: function(json) {*/
/* 			  	  palletID = json['pallet_id'];*/
/* 				  //alert(json);*/
/* 				  // we can add the stock here*/
/* 				  // change product quantity, oc_product_to_position*/
/* 				  //clear this form*/
/* 				  },*/
/* 			  error: function(xhr, ajaxOptions, thrownError) {*/
/* 				console.log('Get space failure');*/
/* 				console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);*/
/* 		    	*/
/* 		    }*/
/* 	    }).done(function(json){*/
/* 	    	if(json < 1)*/
/* 	    	{*/
/* 	    		alert("No Available Positions!!!");*/
/* 				event.preventDefault();*/
/* 				return;*/
/* 	    	}*/
/* 	    });		*/
/* */
/* 	    $.ajax({*/
/* 	    	url:'http://localhost/store/index.php?route=api/pallet/updateStock',*/
/* 	    	type:'post',*/
/* 	    	data: {palletID:barCode,productID:productID},*/
/* 	    	crossDomain:true,*/
/* 	    	success:function(json){*/
/* 	    		$("#productBarcode").val("");//units*/
/* 	    		$("#palletBarcode").val("");//units*/
/* 	    		$("#productID").val("");*/
/* 	    		$("#length").val("");*/
/* 	    		$("#width").val("");*/
/* 	    		$("#depth").val("");*/
/* 	    		$("#weight").val("");*/
/* 	    		$("#bentCount").val("");*/
/* */
/* 	    	},*/
/* 	    	error: function(xhr,ajaxOptions,thrownError){*/
/* 				console.log('Update Failure');*/
/* 				console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);*/
/* 	    	}*/
/* 	    }).done(function(json){*/
/* 	    	if(json == 1)*/
/* 	    		alert("Added to stock");*/
/* 	    });      	*/
/* 		// if true get the product and its details and prepare the map*/
/* 	}); */
/* </script>*/
/* {{ footer }}*/
/* */
