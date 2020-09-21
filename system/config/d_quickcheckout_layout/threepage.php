<?php 

$_['d_quickcheckout_layout'] = array(
    "codename" => "threepage",
    "header_footer" => 1,
    "pages" => array( 
        "page0" => array(
            "id" => "page0",
            "deleted" => 0,
            "sort_order" => 0,
            "type" => "page",
            "display" => "1",
            "text" => "Delivery details",
            "description" => "Provide address information",
            "children" => array( 
                "row0" => array(
                    "id" => "row0", 
                    "type" => "row", 
                    "sort_order" => 0,
                    "children" => array( 
                        "col0" => array(
                            "id" => "col0",
                            "size" => 4,
                            "type" => "col", 
                            "sort_order" => 0,
                            "children" => array( 
                                "child0" => array(
                                    "id" => "child0",
                                    "name" => "account",
                                    "type" => "item",
                                    "sort_order" => 0,
                                ),
                                "child1" => array( 
                                    "id" => "child1",
                                    "name" => "payment_address",
                                    "type" => "item",
                                    "sort_order" => 1,
                                ),
                                "child2" => array(
                                    "id" => "child2",
                                    "name" => "shipping_address",
                                    "type" => "item",
                                    "sort_order" => 2,
                                ) 
                            ) 
                        ),
                        "col1" => array(
                            "id" => "col1",
                            "size" => 8,
                            "type" => "col",
                            "sort_order" => 1,
                            "children" => array( 
                                "child3" => array(
                                    "id" => "child3",
                                    "name" => "cart",
                                    "type" => "item",
                                    "sort_order" => 3,
                                ),
                                
                                "child4" => array(
                                    "id" => "child4",
                                    "name" => "confirm",
                                    "type" => "item",
                                    "sort_order" => 4,
                                ) 
                            ) 
                        ) 
                    ) 
                ) 
            ) 
        ),
        "page1" => array( 
            "id" => "page1",
            "deleted" => 0,
            "sort_order" => 1,
            "type" => "page",
            "display" => 1,
            "text" => "Shipping info",
            "description" => "Provide shipping information",
            "children" => array( 
                "row1" => array(
                    "id" => "row1",
                    "type" => "row",
                    "sort_order" => 0,
                    "children" => array( 
                        "col2" => array(
                            "id" => "col2",
                            "size" => 6,
                            "type" => "col", 
                            "sort_order" => 0,
                            "children" => array( 
                                "child5" => array( 
                                    "id" => "child5",
                                    "name" => "shipping_method",
                                    "type" => "item",
                                    "sort_order" => 0,
                                )
                            ) 
                        ),
                        "col3" => array( 
                            "id" => "col3",
                            "size" => 6,
                            "type" => "col",
                            "sort_order" => 1,
                            "children" => array( 
                                "child6" => array(
                                    "id" => "child6", 
                                    "name" => "cart",
                                    "type" => "item",
                                    "sort_order" => 0,
                                ),
                                "child7" => array(
                                    "id" => "child7",
                                    "name" => "confirm",
                                    "type" => "item",
                                    "sort_order" => 4,
                                ) 
                            ) 
                        ) 
                    )
                ) 
            ) 
        ),
        "page2" => array( 
            "id" => "page2",
            "deleted" => 0,
            "sort_order" => 1,
            "type" => "page",
            "display" => 1,
            "text" => "Payment info",
            "description" => "Provide payment information",
            "children" => array( 
                "row1" => array(
                    "id" => "row1",
                    "type" => "row",
                    "sort_order" => 0,
                    "children" => array( 
                        "col2" => array(
                            "id" => "col2",
                            "size" => 6,
                            "type" => "col", 
                            "sort_order" => 0,
                            "children" => array(
                                "child8" => array( 
                                    "id" => "child8",
                                    "name" => "payment_method",
                                    "type" => "item",
                                    "sort_order" => 1,
                                ),
                                "child9" => array( 
                                    "id" => "child9",
                                    "name" => "payment",
                                    "type" => "item",
                                    "sort_order" => 2,
                                ) 
                            ) 
                        ),
                        "col3" => array( 
                            "id" => "col3",
                            "size" => 6,
                            "type" => "col",
                            "sort_order" => 1,
                            "children" => array( 
                                "child10" => array(
                                    "id" => "child10", 
                                    "name" => "cart",
                                    "type" => "item",
                                    "sort_order" => 0,
                                ),
                                "child11" => array(
                                    "id" => "child11",
                                    "name" => "custom",
                                    "type" => "item",
                                    "sort_order" => 1,
                                ),
                                "child12" => array(
                                    "id" => "child12", 
                                    "name" => "confirm",
                                    "type" => "item",
                                    "sort_order" => 2,
                                ) 
                            ) 
                        ) 
                    )
                ) 
            ) 
        ) 
    ) 
);