<?php
require('config.php');

header('Content-type: text/html; charset=utf-8');
header("Cache-Control: no-cache, must-revalidate");
header ("Pragma: no-cache");
session_start();


$flag = $_POST['flagreq'];



//initial table show 
if($flag == 'fetch_all')
{
	tabledetails();
}

//fetch request of preduct details for update
if($flag == 'update_request')
{
  $idNo = trim($_POST['id']);
  $idNo = mysqli_real_escape_string($conn, $idNo);	

  $response = array();
  $stmt = "SELECT * FROM tbl_car_details WHERE id = '$idNo'";
  $result = $conn->query($stmt);
  if ($result->num_rows > 0) {
   // output data of each row
   while($row = $result->fetch_assoc()) {
    $response=$row;
  }

  echo '<script>';
  echo '$("#manufacturer").val("'.$response['manufacturer'].'");';
  echo '$("#model").val("'.$response['model'].'");';             
  echo '$("#year").val("'.$response['year'].'");';
  echo '$("#producing_country").val("'.$response['producing_country'].'");';
  echo '$("#hidden_id").val("'.$response['id'].'");'; 
  echo '</script>';

}

echo json_encode($response);
$conn=null;
}

// update details
if($flag == 'update_details')
{

  $manufacturer = trim($_POST['manufacturer']);
  $manufacturer = mysqli_real_escape_string($conn, $manufacturer);
  if ($manufacturer==" ") {
    $manufacturer="Unspecified";
  }
  $hidden_id = trim($_POST['hidden_id']);
  $hidden_id = mysqli_real_escape_string($conn, $hidden_id);

  $model = trim($_POST['model']);
  $model = mysqli_real_escape_string($conn, $model);
  if ($model==" ") {
    $model="Unspecified";
  }

  $year = trim($_POST['year']);
  $year = mysqli_real_escape_string($conn, $year);
  if ($year==" ") {
    $year=666;
  }

  $producing_country = trim($_POST['producing_country']);
  $producing_country = mysqli_real_escape_string($conn, $producing_country);
  if ($producing_country==" ") {
    $producing_country="Unspecified";
  }

  $updateQuery = $conn->prepare(" UPDATE tbl_car_details SET manufacturer=?, model=?, year=?, producing_country=?, insertion_time=NOW() WHERE id = '$hidden_id'");

  $updateQuery->bind_param("ssis", $manufacturer, $model, $year, $producing_country);

  if($updateQuery->execute())
  {

   tabledetails();
 }
 
 else  {
  echo "Unsuccessful";
}
$conn = null;


}

// delete car details
if($flag == 'delete_request')
{
  $idNo = trim($_POST['id']);
  $idNo = mysqli_real_escape_string($conn, $idNo);	

  $sql = "DELETE FROM tbl_car_details WHERE id='$idNo'";

  if ($conn->query($sql) === TRUE) 
  {

    tabledetails();
  }
  else
  {
  	echo "Unsuccessful";
  }
$conn = null;
}

// main table show
function tabledetails()
{
  require("config.php");
  $number=1;
  $stmt="SELECT * FROM tbl_car_details ORDER BY manufacturer ASC";
  $result = $conn->query($stmt);
  $table_print='';
  $table_print.='<h4 style="color:tomato; text-align:left; font-size:.83em;">Car Details Table... </h4>';
  $table_print.='<table class="table table-bordered table-striped table-hover table-responsive" id="carDetailsTable"  >

  <thead>
  <tr style="font-size:.9em;">
  <th class="table_headers">No</th>
  <th class="table_headers">Manufacturer</th>
  <th class="table_headers">Model</th>
  <th class="table_headers">Year</th>
  <th class="table_headers">Producing Country</th>
  <th class="table_headers" >Actions</th>

  </tr>
  </thead>
  <tbody style="text-align: center !important;">';

  if ($result->num_rows > 0) {


    foreach ($result as $row) {
     $table_print.='
     <tr style="font-size:.85em;">
     <td>'.$number.'</td>
     <td>'.$row['manufacturer'].'</td>
     <td>'.$row['model'].'</td>
     <td>'.$row['year'].'</td>
     <td>'.$row['producing_country'].'</td>
     <td><span class="dltbtn"><button id="'.$row['id'].'" class="btn btn-info updateDetails btn-sm" style="outline:none">Update</button></span>

     <span class="dltbtn"><button id="'.$row['id'].'" class="btn btn-danger delete_details btn-sm" style="outline:none">Delete</button></span></td>
     </tr>';

     $number++;
   }

   $table_print.='</tbody></table>';


 }

 echo $table_print;
 $conn = null;
}

// csv file upload to folder file 

if($flag == 'csv_upload_request')
{
 $error = '';
 $total_line = '';
 if($_FILES['file']['name'] != '')
 {
  $allowed_extension = array('csv');
  $file_array = explode(".", $_FILES["file"]["name"]);
  $extension = end($file_array);
  if(in_array($extension, $allowed_extension))
  {
   $new_file_name = rand() . '.' . $extension;
   $_SESSION['csv_file_name'] = $new_file_name;
   move_uploaded_file($_FILES['file']['tmp_name'], 'file/'.$new_file_name);
   $file_content = file('file/'. $new_file_name, FILE_SKIP_EMPTY_LINES);
   $total_line = count($file_content);
 }
 else
 {
   $error = 'Only CSV file format is allowed';
 }
}
else
{
  $error = 'Please Select File';
}

if($error != '')
{
  $output = array(
   'error'  => $error
 );
} 
else
{
  $output = array(
   'success'  => true,
   'total_line' => ($total_line - 1)
 );
}

echo json_encode($output);
$conn = null;
}

// csv file data import to database
if($flag=='import_to_database')
{

  set_time_limit(0);

  ob_implicit_flush(1);

  $file_data = fopen('file/' . $_SESSION['csv_file_name'], 'r');

  fgetcsv($file_data);

  while($row = fgetcsv($file_data))
  {

    if ($row[0]=="") {
     $row[0]="Unspecified";
   }
   if ($row[1]=="") {
     $row[1]="Unspecified";
   }
   if ($row[2]=="") {
     $row[2]=666;
   }

   if ($row[3]=="") {
     $row[3]="Unspecified";
   }
   $data = array(
     'manufacturer' => $row[0],
     'model' => $row[1],
     'year' => $row[2],
     'producing_country' => $row[3]
   );

   $manufacturer1=trim($data['manufacturer']);
   $model1=trim($data['model']);
   $year1=trim($data['year']);
   $producing_country1=trim($data['producing_country']);

   if($manufacturer1==" " )
   {
    $manufacturer1="Unspecified";
  }
  if($model1==" " )
  {
    $model1="Unspecified";
  }
  if($year1==" " )
  {
    $year1="Unspecified";
  }
  if($producing_country1==" " )
  {
    $producing_country1="Unspecified";
  }
  $query = "
  INSERT INTO tbl_car_details (manufacturer, model, year, producing_country, insertion_time) 
  VALUES (?, ?, ?, ?, NOW())";

  $statement = $conn->prepare($query);

  $statement->bind_param("ssis", $manufacturer1, $model1, $year1, $producing_country1);
  $statement->execute();
//echo $conn->error;
  sleep(1);

  if(ob_get_level() > 0)
  {
   ob_end_flush();
 }
}

unset($_SESSION['csv_file_name']);


$data = array();

$stmt = "SELECT manufacturer, count(*) as number FROM tbl_car_details GROUP BY manufacturer";  
 $result = $conn->query($stmt);  
 
$data = array();
while($row = $result->fetch_assoc()) 
{
 $data[] = array(
  'label'  => $row["manufacturer"],
  'value'  => $row["number"]
 );
}

 $data = json_encode($data);
 echo $data;
 $conn = null;
}



// search result and table show
if($flag == 'search_result')
{
  $number=1;
  $result_row_show=array();
  $search_value = trim($_POST['search_values']);

  $table_print='';
  $table_print.='<h4 style="color:tomato; text-align:left; font-size:.83em;">Showing search result for '.$search_value.'... </h4>
  <table class="table table-bordered table-striped table-hover table-responsive" id="carDetailsTable"  >

  <thead>
  <tr style="font-size:.9em;">
  <th class="table_headers">No</th>
  <th class="table_headers">Manufacturer</th>
  <th class="table_headers">Model</th>
  <th class="table_headers">Year</th>
  <th class="table_headers">Producing Country</th>
  <th class="table_headers" >Actions</th>

  </tr>
  </thead>
  <tbody style="text-align: center !important;">';


  $search_quary="SELECT DISTINCT(id), manufacturer, model, year, producing_country FROM tbl_car_details WHERE manufacturer='$search_value' OR model='$search_value' OR year='$search_value' OR producing_country='$search_value' order by id ASC";
  $re_qur = $conn->query($search_quary);




  if ($re_qur->num_rows > 0) {
//    // output data of each row
    while($row_result = $re_qur->fetch_assoc()) {
      $result_row_show=$row_result;

          // 
      $table_print.='
      <tr style="font-size:.85em;">
      <td>'.$result_row_show['id'].'</td>
      <td>'.$result_row_show['manufacturer'].'</td>
      <td>'.$result_row_show['model'].'</td>
      <td>'.$result_row_show['year'].'</td>
      <td>'.$result_row_show['producing_country'].'</td>
      <td><span class="dltbtn"><button id="'.$result_row_show['id'].'" class="btn btn-info updateDetails btn-sm" style="outline:none">Update</button></span>

      <span class="dltbtn"><button id="'.$result_row_show['id'].'" class="btn btn-danger delete_details btn-sm" style="outline:none">Delete</button></span></td>
      </tr>';

      $number++;

    }

  }


  $table_print.='</tbody></table>';
  echo $table_print;
$conn = null;
}

// search suggetion generate

if($flag == 'suggetion_for_search')
{
  $out_suggtion='';
  $search_word = trim($_POST['search_word']);

  $stmt = "
  SELECT DISTINCT(manufacturer) FROM tbl_car_details 
  WHERE manufacturer LIKE '".$search_word."%'";

  $stmt1 = "
  SELECT DISTINCT(model) FROM tbl_car_details 
  WHERE model LIKE '".$search_word."%'";
  $stmt2 = "
  SELECT DISTINCT(year) FROM tbl_car_details 
  WHERE year LIKE '".$search_word."%'";
  $stmt3 = "
  SELECT DISTINCT(producing_country) FROM tbl_car_details 
  WHERE producing_country LIKE '".$search_word."%'";


  $result = $conn->query($stmt);
  $result1 = $conn->query($stmt1);
  $result2 = $conn->query($stmt2);
  $result3 = $conn->query($stmt3);
  if ($result->num_rows > 0) {

   while($row = $result->fetch_assoc()) {


    $out_suggtion.='<option>'.trim($row["manufacturer"]).'</option>';
  }
}
if ($result1->num_rows > 0) {

 while($row1 = $result1->fetch_assoc()) {


  $out_suggtion.='<option>'.trim($row1["model"]).'</option>';
}
}
if ($result2->num_rows > 0) {

 while($row2 = $result2->fetch_assoc()) {


  $out_suggtion.='<option>'.trim($row2["year"]).'</option>';
}
}
if ($result3->num_rows > 0) {

 while($row3 = $result3->fetch_assoc()) {


  $out_suggtion.='<option>'.trim($row3["producing_country"]).'</option>';
}
}
echo $out_suggtion;
// echo $conn->error;
$conn = null;
}

// donuta chart data after search
if($flag == 'donut_data')
{

  $data = array();
  $search_donut_data=trim($_POST["search_donut_data"]);


  $stmt = "SELECT manufacturer, count(*) as number FROM tbl_car_details where manufacturer='$search_donut_data' OR model ='$search_donut_data' OR year ='$search_donut_data' OR producing_country ='$search_donut_data'  GROUP BY manufacturer";  
  $result = $conn->query($stmt);  

  while($row =$result->fetch_assoc()) 
  {
   $data[] = array(
    'label'  => $row["manufacturer"],
    'value'  => $row["number"]
  );
 }
 $data = json_encode($data);
 echo $data;
 $conn = null;
}

// donat chart data after action
if($flag == 'after_action_data')
{

  $data = array();

$stmt = "SELECT manufacturer, count(*) as number FROM tbl_car_details GROUP BY manufacturer";  
 $result = $conn->query($stmt);  
 
$data = array();
while($row = $result->fetch_assoc()) 
{
 $data[] = array(
  'label'  => $row["manufacturer"],
  'value'  => $row["number"]
 );
}

 $data = json_encode($data);
 echo $data;
 $conn = null;
}
?>