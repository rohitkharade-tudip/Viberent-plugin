<?php
/**
Template name: Viberent item-based layout
*/
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

	
<style>

body {
	background-color: #ececec;
	line-height: 1;
}

#main-container img {
	width: 100%;
	height: 200px;
}

#main-container {
	display: flex;
	padding: 5rem;
	text-align: left;
}

#main-container .col-1 {
	width: 20%;
	padding-right: 30px;
}

#main-container .col-2 {
	width: 80%;
}

.col-2 .item-info {
	display: flex;
	margin-top: 20px;
}


#main-container .col-2 p {
	padding: 5px 20px;
	margin: 0;
}

#main-container .col-2 img {
	border-radius: 10px;
}

.btn_available{
	background-color: #d4f296;
	padding: 5px 10px;
	border-radius: 5px;
	border: none;
	font-weight: 600;
	font-size: 15px;
}

.btn_book, .btn_details, .btn_availability {
	background-color: transparent;
	padding: 10px 0px;
	border-radius: 5px;
	border: none;
	font-size: 16px;
}

.product-title h2 {
	margin: 0;
	padding: 0 20px;
	font-weight: 500;
}

.product-title h2.product-pricing {
	font-size: 20px;
}

.product-title {
	padding-bottom: 10px;
}

span.per-day {
	font-size: 16px;
	font-weight: 400;
}

.available {
	width: 20%;
}

.available div {
	padding-bottom: 10px;
}

.available div:not(:first-child) {
	padding-left: 10px;
}

.item-details {
	width: 50%;
}

.product-image {
	width: 30%;
}


/*::-webkit-datetime-edit-year-field:not([aria-valuenow]),
::-webkit-datetime-edit-month-field:not([aria-valuenow]),
::-webkit-datetime-edit-day-field:not([aria-valuenow]) {
    color: transparent;
}
*/

/*  input[type="date"]:before {
    content: attr(placeholder) !important;
    color: #aaa;
    margin-right: 0.5em;
  }
  input[type="date"]:focus:before,
  input[type="date"]:valid:before {
    content: "";
  }
*/

/*input[type="date"], input[type="date"]:focus {
	color: transparent;
	width: 200px;
}
*/
input[placeholder="Select End Date"] {
	padding-left: 6px;
	margin-top: 5px;
	margin-bottom: 10px;
}


input[placeholder="Select Start Date"] {
	padding-left: 6px;
	margin-top: 5px;
	margin-bottom: 10px;
}

.input-group {
	display: flex;
	max-width: 100%;
}

#search input {
	width: 80%;
}

#search {
	margin-top: 5px;
}

.item-summary p {
	font-size: 16px;
}

.product-image img {
	margin-bottom: 20px;
}


#my-dates {
	background-color: #24bcdc;
	color: white;
	border: 0;
	border-radius: 5px;
	padding: 5px 10px;
	font-size: 16px;
}

#pagination {
	display: flex;
	overflow: scroll;
}

#pagination button {
	margin: 0 5px;
}

.item-summary p {
	word-wrap: break-word;
}

</style>



<script>
	

</script>






<?php

if(isset($_POST["pagination_btn"])){

$page_no = $_POST["pagination_btn"];

}

else {
	$page_no = 1;
}


$result = $wpdb->get_results("SELECT * from wp_viberent_clients_company_info");

  $curl2 = curl_init();
  $companyID = $result[0]->companyID;
curl_setopt_array($curl2, array(
  CURLOPT_URL => 'https://viberent-api.azurewebsites.net/api/Item/item-list?&companyid=' . $companyID . '&pageSize=10&pageNumber=' . $page_no,
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

$response2 = curl_exec($curl2);

curl_close($curl2);

$resp2 = json_decode($response2,1);

?>





	
<div id="main-container">

<div class="col-1">

<form method="post">
  <label for="start-date">Start Date:</label>
  <input type="date" id="start-date" name="start-date" placeholder="Select Start Date" required>
  <br>
  <label for="end-date">End Date:</label>
  <input type="date" id="end-date" name="end-date" placeholder="Select End Date" required>
      <button type="submit" name="my-dates" id="my-dates">Check Availability</button>
  <br>
</form>

<form method="post">
    <label for="search">Search Our Store:</label>
  <div class="input-group" id="search">
      <input type="text" placeholder="Search.." name="search">
      <button type="submit"><i class="fa fa-search"></i></button>
  </div>
</form>



</div>


<div class="col-2">

<h2>New Booking: <span>

<?php

$my_from_date = date("j/M/Y");
$my_to_date = date("j/M/Y", strtotime('+1 day'));

$show_from_date = date("D M j, Y");
$show_to_date = date("D M j, Y", strtotime('+1 day'));




if(isset($_POST["my-dates"])){

$my_from_date = $_POST["start-date"];
$my_to_date = $_POST["end-date"];

$show_from_date = date('D M j, Y',strtotime($_POST["start-date"]));
$show_to_date = date('D M j, Y',strtotime($_POST["end-date"]));

}

echo $show_from_date . ' - ' .$show_to_date;


?>
	
</span> </h2>







<?php

if (isset($resp2)) {
    foreach ($resp2 as $retrieved_data){




  $curlavail = curl_init();



curl_setopt_array($curlavail, array(
  CURLOPT_URL => 'https://viberent-api.azurewebsites.net/api/Item/item-availability?itemGUID=' . $retrieved_data["itemGUID"] . '&companyid='. $companyID .'&fromDate=' . $my_from_date . '&todate=' . $my_to_date . '&locationID=0',
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

$respavail = json_decode($responseavail,1);


?>






<div class="item-info">
	<div class="available">
		<div>
      		<button class="btn_available">AVAILABLE</button>
  		</div>
  		<div>
  			<i class="fa fa-check-square" aria-hidden="true"></i>
      		<button class="btn_book">Book Now</button>
  		</div>
		<div>
			<i class="fa fa-file-o" aria-hidden="true"></i>
      		<button class="btn_details">Details</button>
  		</div>
		<div>
			<i class="fa fa-calendar-o" aria-hidden="true"></i>
      		<button class="btn_availability">Availability</button>
  		</div>

	</div>

	<div class="item-details">	

	<div class="product-title">
<h2 class="product-name"><?php echo $retrieved_data["itemName"];?></h2><br>
<h2 class="product-pricing">



            <?php
              if (!empty($retrieved_data["rentalRates"])) {
                $daily_present=0;
                  foreach ($retrieved_data["rentalRates"] as $rentalRate){
                    if ( $rentalRate['rentalratesName']=='Daily' ){  
                      echo "$";                     
                      echo $rentalRate['rentalratesvalue'];
                      echo " per day"; 
                      $daily_present = 1;                  
                    }
                  }
                  if( $daily_present !== 1 ) {
                       echo "n/a";
                  }   
              } 
            ?>


</h2>
	</div>

<p><?php echo "Available: " . $respavail[0]['available'];?></p>


	<div class="item-summary">

<p><?php echo $retrieved_data["itemDescription"];?></p>

	</div>




    </div>

<div class="product-image">


				<img src=
                <?php
                    if (empty($retrieved_data["images"])) {
                        	echo "https://viberent.blob.core.windows.net/attachement/no_image.png";                                 
                    }
                    else {
                    	foreach ($retrieved_data["images"] as $image){
                    		echo $image['blobUrl'];
											}
                    }
             ?> >
</div>

</div>


<hr>



<?php







}


$are_pages = $retrieved_data["totalRows"] % 10;
$not_final_pages = $retrieved_data["totalRows"] / 10;

if( $are_pages == 0) {
	$total_pages = $not_final_pages;
}
else {
	$total_pages = $not_final_pages + 1;
}

}

?>





<form id="pagination" method="post">

<?php

for ($x = 1; $x <= $total_pages; $x++) {
?>
    <button id="pagination_btn_1" name="pagination_btn" value="<?php echo $x ?>"><?php echo $x ?></button>
<?php
}

?>

</form>


</div>


</div>



