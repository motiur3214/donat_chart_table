
 <?php  

 //intial data for pie chart 
require("config.php");

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
 ?> 


<!DOCTYPE html>
<html lang="en">
<head>
	<title>Car Distributions</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
	 <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css" />
	<link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css" /> 
	
</head>
<body>
	<!-- nav bar -->
	<nav class="navbar navbar-default">
		<div class="container-fluid">
			<div class="navbar-header">

			</div>
			<ul class="nav navbar-nav">
				<li></li><li></li>
				<li class="active"><h3 id="headline" style="color:#c5c3c9 ; text-decoration:underline;"><span id="home_reload"> Car Distributions</span></h3></li>

			</ul>
			<div class="navbar-form navbar-right" style=" padding-top: .80%" >
				<button class="btn btn-primary btn-sm"  id="insertion_csv"  style=" outline: none; float: right;">Import CSV</button>
			</div>
		</div>
	</nav>
	<!-- nav bar end -->
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-3" style="padding-left: 4%">
				
				<!-- search bar -->
				<div class="form-group">
					<label><span style="font-size: .81em; color: tomato;">Search by any option</span></label>
					<div class="input-group" style="width: 90%;">
						<input type="text" id="search_data" placeholder="" autocomplete="off" class="form-control input-md" />

						<div class="input-group-btn">

							<button type="button" class="btn btn-info btn-md" id="search" style="outline: none;">Search</button>

						</div>

					</div>
					<div class="form-group"> 
						<select class="form-control" id="show_options" size="4" style="display: none; width: 85%; outline: none; border: none; overflow: auto;"></select>
					</div>

				</div>
				<!-- search bar end -->
				<br><br><br>
				<!-- donut chaRT -->
				<div id="chart" style="width: 90%; height: 300px; text-align:left !important;">
					<br>
					<p style="text-align: center; color: tomato;">Donut Chart</p>
				</div> 
				<!-- END OF donut chat -->


			</div>
			<!-- show table -->
			<div class="col-sm-9">


				<div class="table-responsive-sm" id="table_show"></div>
			</div>
			<!-- end of table -->
			
		</div>


	</div>
	<!-- footer -->
	<footer class="page_footer" style="background-color:#eaf2b6;
	text-align: left; padding-left: 1%;">
	<p>Author: <strong>Md. Motiur Rahman</strong></p>
	<p>Another project link: <a href="https://github.com/motiur3214/Simple-blogging-site" target="_blank">Simple Bloging Site</a></p>

</footer>
<!-- end of footer -->

<!-- Modal update  details  -->
<div class="modal" id="update_modal">
	<div class="modal-dialog">
		<div class="modal-content">

			<!-- Modal Header -->
			<div class="modal-header">
				<h4 class="modal-title" id="modal_headline"></h4>
				<button type="button" class="close" data-dismiss="modal" style="outline: none;">&times;</button>
			</div>
			<div class="modal-body inputform">

				<form method="post" id="update_car_details">

					<div class="row">
						<div class="col-sm-6">
							<label class="lebel_show"> Manufacturer</label>				
							<input type="text" class="form-control"  autocomplete="off" data-index="1" id="manufacturer" required>

							<input type="hidden" name="hidden_id" id="hidden_id" >
						</div>
						<div class="col-sm-6">
							<label class="lebel_show" >Model</label>			
							<input type="text" class="form-control"  autocomplete="off" data-index="2"  id="model"  required >
						</div>	
					</div>
					<p></p>				
					<div class="row">
						<div class="col-sm-6">
							<label class="lebel_show" > Year</label>			
							<input type="text" class="form-control" autocomplete="off" data-index="3"  id="year"  required>

						</div>
						<div class="col-sm-6">
							<label class="lebel_show" >Producing Country</label>			
							<input type="text" class="form-control" autocomplete="off" data-index="4"  id="producing_country"  required>

						</div></div>	
					</form>
					<br>
					<div class="modal-footer">
						<button style="font-size: 12px; outline: none;" id="submitbtn" class="btn btn-success"  data-index="5" >Submit</button>


					</div>

				</div>
			</div>
		</div>
	</div>
	<!-- end of Modal -->



	<!-- modal for csv upload -->


	<div class="modal" id="csv_upload_modal">
		<div class="modal-dialog">
			<div class="modal-content">

				<!-- Modal Header -->
				<div class="modal-header" style="text-align: center;">
					<h4 class="modal-title" id="modal_headline">Import CSV File Data
						<button type="button" class="close" id="modalclose"  data-dismiss="modal" style="outline: none;">&times;</button>
					</h4>

				</div>
				<div class="modal-body">
					<span id="message" style="text-align: center;"></span>
					<form id="sample_form" method="POST" enctype="multipart/form-data" class="form-horizontal">
						<input type="hidden" name="hidden_field" value="1" />
						<div>
							<img src="file/img/csvformat.png" width="550" height="200">
							<span style="font-size: .8em;color: tomato; padding-left: 2.5%;">**follow this CSV format strictly**</span>
						</div>
						<div class="row">

							<!-- <div class="col-sm-1"></div> -->
							<div class="col-sm-4" style="text-align: left; padding-left: 5%;" >
								<input type="hidden" name="hidden_field" value="1" />
								<input type="file" class="form-input" name="file" id="file"   />

							</div>	
							<div class="col-sm-4"></div>		
							<div class="col-sm-4" >

								<input type="submit" name="import" id="import" class="btn btn-success" value="Import" style=" outline: none; width: 10em;" />
							</div>

						</div>


					</form>

				</div>

				<div class="modal-footer">

				</div>
			</div>

		</div>
	</div>
	<!--csv import modal end -->
</body>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script src=https://cdnjs.cloudflare.com/ajax/libs/notify/0.4.2/notify.min.js></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
	<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>  
	<script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
	<script type="text/javascript">

		$(document).ready(function () 
		{
			var clear_timer;
// initial call for table data show
			showAllData();

// csv modal options
$("#insertion_csv").click(function(){
	 $('#message').html('');
        $("#csv_upload_modal").modal({
            backdrop: 'static',
            keyboard: false
        });
    });

// modal close option
$(document).on('click','#modalclose', function(){  
			 $('#message').html('');
			 $('#file').val('');

    });

// call for initial donut chart
 var donut_chart = Morris.Donut({
     element: 'chart',
     data: <?php echo $data; ?>
        });



// on hover to header cursor change
$('#home_reload').css('cursor', 'pointer');

     $("span#home_reload").click(function(){

   setTimeout(function(){location.reload(); });
});


		

// function for all data show table
 function showAllData()
		{

			$.ajax({  
				url:"backend",  
				method:"post",  
				data:{
					flagreq:'fetch_all',
					contentType: false,
					cache: false,
					processData: false,  
				},  
				success:function(data){
					$('#table_show').html(data);  

					$('#carDetailsTable').DataTable({
						"lengthChange": false,
						"ordering": false,
						"searching": false,
						"pageLength": 10,});
					
				
                  }
			});

		}
// get data for update

		$(document).on('click','.updateDetails', function(){  
			{

				var id=$(this).attr("id");
				$.ajax({  
					url:"backend",  
					method:"post",  
					data:{
						id:id,
						flagreq:'update_request',
						contentType: false,
						cache: false,
						processData: false,  
					},  
					success:function(data){

						$("#update_modal").modal()
						$('#manufacturer').html(data);

					}
				});

			}
		});



// on update modal press enter to change input field

		$('.inputform').on('keydown', 'input', function (event) {

			if (event.which == 13) {
				event.preventDefault();
				var $this = $(event.target);
				var index = parseFloat($this.attr('data-index'));
				$('[data-index="' + (index + 1).toString() + '"]').focus();
			}
		});


		// on submit details update 
		$( "#submitbtn" ).click(function(event) {
			event.preventDefault();

			var form_data = new FormData();
			form_data.append('flagreq',"update_details");


			form_data.append("manufacturer", document.getElementById('manufacturer').value);
			form_data.append("hidden_id", document.getElementById('hidden_id').value);
			form_data.append("model", document.getElementById('model').value);
			form_data.append("year", document.getElementById('year').value);
			form_data.append("producing_country", document.getElementById('producing_country').value);
			$.ajax({
				url: "backend",
				type: 'post',
				data:form_data,

				contentType: false,
				cache: false,
				processData: false,
				success:function(data){

					var str = data;
					var n = str.search("Unsuccessful");
					if (n < 0)

					{   
						$.notify("car details updated", "success");
						document.getElementById("update_car_details").reset();
						$('#table_show').html(data); 
						$('#update_modal').modal('hide');
						$('#carDetailsTable').DataTable({
							"lengthChange": false,
							"searching": false,
						    "ordering": false,
						    "pageLength": 10,});

						after_action_donut();

					}
					else{

						$.notify("Something went wrong", "error");


					}
				}

			});
		});



// delete car data

		$(document).on('click','.delete_details', function(){  
			{
 if (confirm("Are you sure?")){
				var id=$(this).attr("id");
				$.ajax({  
					url:"backend",  
					method:"post",  
					data:{
						id:id,
						flagreq:'delete_request',
						contentType: false,
						cache: false,
						processData: false,  
					},  
					success:function(data){
						var str = data;
						var n = str.search("Unsuccessful");
						if (n < 0)

						{   
							$.notify("car details Deleted", "success");
							$('#table_show').html(data); 
							$('#carDetailsTable').DataTable({
								"lengthChange": false,
								"searching": false,
						        "ordering": false,
						        "pageLength": 10,});
						
                         after_action_donut();
						}
						else{

							$.notify("Something went wrong", "error");
						}
					}
				});

			}
		}
		});


// csv file upload to folder file
  $('#sample_form').on('submit', function(event){
   $('#message').html('');
   event.preventDefault();

if( document.getElementById("file").files.length == 0 ){
    $('#message').html('<div class="alert alert-danger">Please Select a CSV file</div>');
}

else{
var form_data = new FormData();
form_data.append('flagreq',"csv_upload_request");
form_data.append("file", document.getElementById('file').files[0]);


   $.ajax({
    url:"backend",
    method:"POST",
    data: form_data,
    dataType:"json",
    contentType:false,
    cache:false,
    processData:false,
    beforeSend:function(){
     $('#modal').attr('disabled','disabled');
     $('#import').val('Importing');
    },
    success:function(data)
    {
     if(data.success)
     {
      $('#total_data').text(data.total_line);
       $('#modalclose').attr('disabled','disabled');
      start_import();
     $('#message').html('<div class="alert alert-warning">Please Wait..It may take few minute</div>');
     $('#import').attr('disabled','disabled');
     }
     if(data.error)
     {
      $('#message').html('<div class="alert alert-danger">'+data.error+'</div>');
      $('#import').attr('disabled',false);
      $('#modalclose').attr('disabled',false);
      $('#import').val('Import');
     }
    }
   })
}
  });


// csv dile data to database

  function start_import()
  {


  	var form_data = new FormData();
    form_data.append('flagreq',"import_to_database");
    
    $.ajax({
    url:"backend",
     method:"POST",
    data: form_data,
    dataType:"json",
    contentType:false,
    cache:false,
    processData:false,
 
    success:function(data)
    {

     $('#file').val('');
      $('#message').html('<div class="alert alert-success">Data Successfully Imported</div>');
      showAllData();
      donut_chart.setData(data);
      $("#import").removeAttr('disabled');

      $('#modalclose').attr('disabled',false);
      $('#import').val('Import');
      setTimeout(function(){ $("#csv_upload_modal").modal("hide"); },1000);
     
      

    }
   })
  }


// search option show

   $(document).on('input', '#search_data', function() {

 var search_word=document.getElementById('search_data').value;


 if (search_word==" ") {
 	alert("please write Something first");
 }else
{
	$.ajax({  
                    url:"backend",  
                    method:"post",  
                    data:{
                    search_word:search_word,
                    flagreq:'suggetion_for_search',
                    contentType: false,
                    cache: false,
                    processData: false,  
                    },  
                    success:function(data){
                        var str = data;
                     
                        $("#show_options").show();
                    
                        $('#show_options').html(str);
                    }
                    });

}
});
// get suggetion for search data

$(document).on('click','#show_options', function(){  
var search_value_get=document.getElementById('show_options').value;

$('#search_data').val(search_value_get);
      
  $("#show_options").hide();

      });



   // request for search
   $(document).on('click','#search', function(){  
   

   $("#show_options").hide();
var search_values=document.getElementById('search_data').value;
 


 if (search_values.length == 0) {
 	alert("please write Something first");
 }else
{

$.ajax({  
                    url:"backend",  
                    method:"post",  
                    data:{
                    search_values:search_values,
                    flagreq:'search_result',
                    contentType: false,
                    cache: false,
                    processData: false, 

                    },  
                    success:function(data){
                        var str = data;
                        
                       document.getElementById('search_data').value = '';
                        var n = str.search("Unsuccessful");
                        if (n < 0)

                        {   
                            
                            $('#table_show').html(data); 
                            $('#carDetailsTable').DataTable({
                                "lengthChange": false,
                                "searching": false,
						        "ordering": false,
						        "pageLength": 10,
                            });

                             getdonut(search_values);
                            }
                        else{

                            $.notify("Something went wrong", "error");
                        }
                    }
                });
}
}); 



  
  // get searched value for donut chart
function getdonut(search_values)
{

var search_donut_data =search_values;

var form_data = new FormData();
form_data.append('flagreq',"donut_data");
form_data.append("search_donut_data", search_donut_data);



   
   $.ajax({
    url:"backend",
     method:"POST",
    data: form_data,
    dataType:"json",
    contentType:false,
    cache:false,
    processData:false,
   
    success:function(data)
    {
        
    
   donut_chart.setData(data);
    }
   });
  
 }

//get donat data after a action
function after_action_donut()
{

var form_data = new FormData();
form_data.append('flagreq',"after_action_data");
 
  $.ajax({
    url:"backend",
     method:"POST",
    data: form_data,
    dataType:"json",
    contentType:false,
    cache:false,
    processData:false,
   
    success:function(data)
    {
        
    
   donut_chart.setData(data);
    }
   });
  
 }


          
});
	</script>



	</html>