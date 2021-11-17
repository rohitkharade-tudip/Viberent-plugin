    <script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#all_category_btn").click(function() {
                $("#col-all-items").show();
            });
            $(".selected_category_btn").click(function() {

                $("#col-all-items").hide();
            });
        });
    </script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo plugins_url(); ?>/viberent/assets/css/category_base.css" type="text/css" media="screen" />


    <?php


    $result = $wpdb->get_results("SELECT * from wp_viberent_clients_company_info");
    $page_no = 1;
    $curlgetcategorylist = curl_init();
    $companyID = $result[0]->companyID;
    curl_setopt_array($curlgetcategorylist, array(
        CURLOPT_URL => 'https://viberent-api.azurewebsites.net/api/item/subcategories?companyid=' . $companyID .  '&pageSize=10&pageNumber=' . $page_no,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_POSTFIELDS => array('companyid' => $companyID),
        CURLOPT_HTTPHEADER => array(
            'Cookie: ARRAffinity=79e06db539acb57119e709978d2cf1da299e8341753d6f6345007fcab3f69bc5; ARRAffinitySameSite=79e06db539acb57119e709978d2cf1da299e8341753d6f6345007fcab3f69bc5'
        ),
    ));

    $response2 = curl_exec($curlgetcategorylist);

    curl_close($curlgetcategorylist);

    $resp2 = json_decode($response2, 1);

    usort($resp2, function ($a, $b) {
        return $a['subCategoryName'] <=> $b['subCategoryName'];
    });

    ?>



    <form method="post">
        <label for="start-date">Start Date:</label>
        <input type="date" id="start-date" name="start-date" placeholder="Select Start Date" required>

        <label for="end-date">End Date:</label>
        <input type="date" id="end-date" name="end-date" placeholder="Select End Date" required>

        <button type="submit" name="my-dates" id="my-dates">Check Availability</button>
        <br>
    </form>




    <div id="main-container">

        <div class="col-1">

            <form method="post" id="date-search">
                <label for="search" id="search-label">SEARCH OUR STORE:</label>
                <div>
                    <input type="text" placeholder="Search.." name="search" id="search">
                    <button type="submit"><i class="fa fa-search"></i></button>
                </div>
            </form>
            <?php
            $sucategoryName = $_GET['category'];
            ?>
            <div class="categories">
                <h4 class="heading_category">Categories</h4>
                <ul>
                    <li class="active">
                        <a href="http://localhost/wpTest/index.php/viberent-based-listing/" class="all_category_btn" name="selected_category_btn">All Categories</a>
                    </li>
                    <?php
                    foreach ($resp2 as $retrieved_data1) {
                    ?>
                        <li class="<?php if ($sucategoryName == $retrieved_data1["subCategoryName"]) {
                                        echo 'active';
                                    } ?>">
                            <a href="http://localhost/wpTest/index.php/viberent-based-listing/?category=<?php echo $retrieved_data1["subCategoryName"]; ?>&pageno=1" class="selected_category_btn" name="selected_category_btn"><?php echo $retrieved_data1["subCategoryName"]; ?></a>
                        <?php
                    }
                        ?>
                        </li>
                </ul>
            </div>
        </div>

        <?php
        // print_r($_GET);
        // print_r($_GET['pageno']);
        // die;
        if (isset($_GET["category"])) {
            $page_no_cat = $_GET['pageno'];
            if (isset($page_no_cat)) {
        ?>
                <script>
                    $(document).ready(function() {
                        $("#col-all-items").hide();
                        $(".categories ul li:first").removeClass('active');
                        // $(".pagination .pagination_btn").click(function() {
                        //     $(".pagination ul li").addClass('active');
                        // });

                    });
                </script>

            <?php
            }
            // print_r($_GET);
            $result = $wpdb->get_results("SELECT * from wp_viberent_clients_company_info");
            $companyID = $result[0]->companyID;
            $curlcatwise = curl_init();
            curl_setopt_array($curlcatwise, array(
                CURLOPT_URL => 'https://viberent-api.azurewebsites.net/api/Item/item-list?&companyid=' . $companyID . '&pageSize=10&pageNumber=' . $page_no_cat . '&subcategory=' . $_GET['category'],
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_POSTFIELDS => array('companyid' => $companyID, 'pageNumber' => $page_no_cat, 'subCategoryName' => $_GET['category']),
                CURLOPT_HTTPHEADER => array(
                    'Cookie: ARRAffinity=79e06db539acb57119e709978d2cf1da299e8341753d6f6345007fcab3f69bc5; ARRAffinitySameSite=79e06db539acb57119e709978d2cf1da299e8341753d6f6345007fcab3f69bc5'
                ),
            ));

            $response4 = curl_exec($curlcatwise);

            curl_close($curlcatwise);
            $resp4 = json_decode($response4, 1);


            if (isset($resp4)) {
            ?>

                <div id="col-catwise-items" class="col-catwise-items">

                    <?php



                    $my_from_date = date("j/M/Y");
                    $my_to_date = date("j/M/Y", strtotime('+1 day'));

                    $show_from_date = date("D M j, Y");
                    $show_to_date = date("D M j, Y", strtotime('+1 day'));


                    if (isset($_POST["my-dates"])) {

                        $my_from_date = $_POST["start-date"];
                        $my_to_date = $_POST["end-date"];

                        $show_from_date = date('D M j, Y', strtotime($_POST["start-date"]));
                        $show_to_date = date('D M j, Y', strtotime($_POST["end-date"]));
                    }


                    foreach ($resp4 as $retrieved_datas) {

                        $curlavail = curl_init();

                        curl_setopt_array($curlavail, array(
                            CURLOPT_URL => 'https://viberent-api.azurewebsites.net/api/Item/item-availability?itemGUID=' . $retrieved_datas["itemGUID"] . '&companyid=' . $companyID . '&fromDate=' . $my_from_date . '&todate=' . $my_to_date . '&locationID=0',
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => '',
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 0,
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => 'GET',
                            CURLOPT_POSTFIELDS => array(),
                            CURLOPT_HTTPHEADER => array(
                                'Cookie: ARRAffinity=79e06db539acb57119e709978d2cf1da299e8341753d6f6345007fcab3f69bc5; ARRAffinitySameSite=79e06db539acb57119e709978d2cf1da299e8341753d6f6345007fcab3f69bc5'
                            ),
                        ));

                        $responseavail = curl_exec($curlavail);

                        curl_close($curlavail);

                        $respavail = json_decode($responseavail, 1);
                    ?>


                        <div class="item-category-box ng-star-inserted" id="catwise-item-box">
                            <div class="inner" id="item-on-category-row-2058-0" data-itemid="56971">
                                <div class="item-display">
                                    <a analytics-click="product-click" class="item-img" href="#">
                                        <img src=<?php
                                                    if (empty($retrieved_datas["images"])) {
                                                        echo "https://viberent.blob.core.windows.net/attachement/no_image.png";
                                                    } else {
                                                        echo $image['blobUrl'];
                                                    }

                                                    ?>>
                                        <span class="img-title ng-star-inserted">(Click for more info)
                                        </span>
                                    </a>
                                </div>
                                <div class="item-actions">
                                    <div class="item-details">
                                        <h4><span class="field-Name"><?php echo $retrieved_datas["itemName"]; ?></span></h4>
                                    </div>
                                    <item-price class="ng-star-inserted">
                                        <b>
                                            <?php
                                            if (!empty($retrieved_datas["rentalRates"])) {
                                                foreach ($retrieved_datas["rentalRates"] as $rentalRate) {
                                                    if ($rentalRate['rentalratesName'] == 'Daily') {
                                                        echo "$";
                                                        echo $rentalRate['rentalratesvalue'];
                                                        echo " per day";
                                                    }
                                                }
                                            }
                                            ?>
                                        </b>
                                    </item-price>
                                    <div>
                                        <p><?php echo "Available: " . $respavail[0]['available']; ?></p>
                                        <div class="item-summary">

                                            <p><?php echo $retrieved_datas["itemDescription"]; ?></p>

                                        </div>
                                    </div>
                                    <add-to-cart>
                                        <div class="add-to-cart-component ng-star-inserted">
                                            <div class="add-to-cart-con with-plusminus">
                                                <!---->
                                                <!---->
                                                <div class="buy-items-btn ng-star-inserted">
                                                    <button class="has-click" type="button">
                                                        <span class="text">Add to Cart</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </add-to-cart>
                                </div>
                            </div>
                        </div>

                    <?php
                    }



                    //echo $retrieved_datas["totalRows"];
                    $are_pages_cat = $retrieved_datas["totalRows"] % 10;
                    $not_final_pages_cat = $retrieved_datas["totalRows"] / 10;


                    if ($are_pages_cat == 0) {
                        $total_pages_cat = $not_final_pages_cat;
                    } else {
                        $total_pages_cat = $not_final_pages_cat + 1;
                    }



                    ?>


                    <!-- <form id="pagination-1" method="post"> -->


                    <div class="pagination">
                        <ul>
                            <?php
                            $query = $_GET;
                            $query['pageno'] =  $x;
                            $query_result = http_build_query($query);
                            if ($page_no_cat >= 1) {
                                echo "<li class='prev'><span><a href='http://localhost/wpTest/index.php/viberent-based-listing/?pageno=" . ($page_no_cat - 1) . "'>Prev</a></span></li>";
                            }

                            for ($x = 1; $x <= $total_pages_cat; $x++) {
                            ?>

                                <li class="<?php if ($page_no_cat == $x) {echo 'active';} ?>">
                                    <span><a href="<?php echo $_SERVER['PHP_SELF']; ?>?<?php echo $query_result; ?>" name="pagination_btn" class="pagination_btn"><?php echo $x; ?></a></span>
                                </li>
                            <?php
                            }

                            ?>
                        </ul>
                    </div>
                    <!-- </form> -->
                </div>
    </div>

    <?php


            }
        }

        if (isset($_GET["pageno"])) {
            $page_nos  = $_GET["pageno"];
        } else {
            $page_nos = 1;
        }

        $curlall = curl_init();
        curl_setopt_array($curlall, array(
            CURLOPT_URL => 'https://viberent-api.azurewebsites.net/api/Item/item-list?&companyid=' . $companyID . '&pageSize=10&pageNumber=' . $page_nos,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_POSTFIELDS => array('companyid' => $companyID, 'pageNumber' => $page_nos),
            CURLOPT_HTTPHEADER => array(
                'Cookie: ARRAffinity=79e06db539acb57119e709978d2cf1da299e8341753d6f6345007fcab3f69bc5; ARRAffinitySameSite=79e06db539acb57119e709978d2cf1da299e8341753d6f6345007fcab3f69bc5'
            ),
        ));

        $response3 = curl_exec($curlall);

        curl_close($curlall);

        $resp3 = json_decode($response3, 1);

        if (isset($resp3)) {
    ?>
    <div class="col-2">

        <div id="col-all-items" class="col-all-items">

            <?php


            $my_from_date = date("j/M/Y");
            $my_to_date = date("j/M/Y", strtotime('+1 day'));

            $show_from_date = date("D M j, Y");
            $show_to_date = date("D M j, Y", strtotime('+1 day'));


            if (isset($_POST["my-dates"])) {

                $my_from_date = $_POST["start-date"];
                $my_to_date = $_POST["end-date"];

                $show_from_date = date('D M j, Y', strtotime($_POST["start-date"]));
                $show_to_date = date('D M j, Y', strtotime($_POST["end-date"]));
            }


            foreach ($resp3 as $retrieved_data) {

                $curlavail = curl_init();

                curl_setopt_array($curlavail, array(
                    CURLOPT_URL => 'https://viberent-api.azurewebsites.net/api/Item/item-availability?itemGUID=' . $retrieved_data["itemGUID"] . '&companyid=' . $companyID . '&fromDate=' . $my_from_date . '&todate=' . $my_to_date . '&locationID=0',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'GET',
                    CURLOPT_POSTFIELDS => array(),
                    CURLOPT_HTTPHEADER => array(
                        'Cookie: ARRAffinity=79e06db539acb57119e709978d2cf1da299e8341753d6f6345007fcab3f69bc5; ARRAffinitySameSite=79e06db539acb57119e709978d2cf1da299e8341753d6f6345007fcab3f69bc5'
                    ),
                ));

                $responseavail = curl_exec($curlavail);
                curl_close($curlavail);

                $respavail = json_decode($responseavail, 1);
            ?>

                <div class="item-category-box ng-star-inserted" id="all-item-box">
                    <div class="inner" id="item-on-category-row-2058-0" data-itemid="56971">
                        <div class="item-display">
                            <a analytics-click="product-click" class="item-img" href="#">
                                <img src=<?php
                                            if (empty($retrieved_data["images"])) {
                                                echo "https://viberent.blob.core.windows.net/attachement/no_image.png";
                                            } else {
                                                foreach ($retrieved_data["images"] as $image) {
                                                    echo $image['blobUrl'];
                                                }
                                            }
                                            ?>>
                                <span class="img-title ng-star-inserted">(Click for more info)
                                </span>
                            </a>
                        </div>
                        <div class="item-actions">
                            <div class="item-details">
                                <h4><span class="field-Name"><?php echo $retrieved_data["itemName"]; ?></span></h4>
                            </div>
                            <item-price class="ng-star-inserted">
                                <b>
                                    <?php
                                    if (!empty($retrieved_data["rentalRates"])) {
                                        $daily_present = 0;
                                        foreach ($retrieved_data["rentalRates"] as $rentalRate) {
                                            if ($rentalRate['rentalratesName'] == 'Daily') {
                                                echo "$";
                                                echo $rentalRate['rentalratesvalue'];
                                                echo " per day";
                                                $daily_present = 1;
                                            }
                                        }
                                        if ($daily_present !== 1) {
                                            echo "n/a";
                                        }
                                    }
                                    ?>
                                </b>
                            </item-price>

                            <p><?php echo "Available: " . $respavail[0]['available']; ?></p>
                            <div class="item-summary">

                                <p><?php echo $retrieved_data["itemDescription"]; ?></p>

                            </div>

                            <add-to-cart>
                                <div class="add-to-cart-component ng-star-inserted">
                                    <div class="add-to-cart-con with-plusminus">
                                        <!---->
                                        <!---->
                                        <div class="buy-items-btn ng-star-inserted">
                                            <button class="has-click" type="button">
                                                <span class="text">Add to Cart</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </add-to-cart>
                        </div>
                    </div>
                </div>

            <?php
            }




            $are_pages = $retrieved_data["totalRows"] % 10;
            $not_final_pages = $retrieved_data["totalRows"] / 10;


            if ($are_pages == 0) {
                $total_pages = $not_final_pages;
            } else {
                $total_pages = $not_final_pages + 1;
            }

            ?>

            <div class="pagination">
                <ul>
                    <?php
                    $query = $_GET;
                    $pagLink = "";
                    if ($page_nos >= 1) {
                        echo "<li class='prev'><span><a href='http://localhost/wpTest/index.php/viberent-based-listing/?pageno=" . ($page_nos - 1) . "'>Prev</a></span></li>";
                    }

                    for ($x = 1; $x <= $page_nos; $x++) {
                        $query['pageno'] =  $x;
                        $query_result = http_build_query($query);
                        if ($x == $page_nos) {
                            $pagLink .= "<li class='active'><span><a href='http://localhost/wpTest/index.php/viberent-based-listing/?pageno="
                                . $x . "'>" . $x . " </a></span></li>";
                        } else {

                            $pagLink .= "<li><span><a href='http://localhost/wpTest/index.php/viberent-based-listing/?pageno=" . $x . "'>   
                                                " . $x . " </a></span></li>";
                        }
                    }
                   
                    if ($page_nos < $total_pages) {
                        $pagLink .= '<li class="disabled"><span>...</span></li>';
                        $pagLink .= "<li><span><a href='http://localhost/wpTest/index.php/viberent-based-listing/?pageno=" . ($page_nos + 1) . "'>Next</a></span></li>";
                    }
                    echo $pagLink;
                    
                    ?>

                </ul>
            </div>


        </div>
    <?php
        }
    ?>

    </div>
    </div>