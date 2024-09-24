<?php
    // Start the sessiom
    session_start();
    if(!isset($_SESSION['user'])) header('location: dashboard.php');
	  $_SESSION['table']= 'users';
      $user = $_SESSION['user'];
      $users = include('database/show-users.php');  
	  
?> 
<!DOCTYPE html>
<html>
<head>
	
	<title>Dashboard - Fertilizer Shop Management System</title>
	<link rel="stylesheet" type="text/css" href="css/login.css?v=<?= time(); ?>">
	<link rel="stylesheet" href="css/font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.35.4/css/bootstrap-dialog.min.css" integrity="sha512-PvZCtvQ6xGBLWHcXnyHD67NTP+a+bNrToMsIdX/NUqhw+npjLDhlMZ/PhSHZN4s9NdmuumcxKHQqbHlGVqc8ow==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
	  <div id="dashboardMainContainer">
		<?php include('partials/app-sidebar.php') ?>
     <div class="dashboard_content_container" id="dashboard_content_container">
     	<?php include('partials/app-topnav.php') ?>
	    <div class="dashboard_content">
		 <div class="dashboard_content_main">
		   <div class="row">
	        <div class="column column-12">
			<h1 class="section_header"><i class="fa fa-list"></i>List Of User</h1>
			<div class="section_content">
			<div class="users">
			<p class="userCount".<?= 1 ?>> Users </P>
			<table>
			 <thead>
			  <tr>
			     <th>#</th>
			     <th>First Name</th>
			     <th>Last Name</th>
			     <th>Email</th>
			     <th>Created At</th>
			     <th>Updated At</th>
				  <th>Action</th>
			  </tr>
			</thead>
		<tbody>
		<?php foreach($users as $index => $user){ ?>
			<tr>
			    <td><?= $index + 1 ?></td>
			    <td class="firstName"><?= $user['first_name'] ?></td>
			    <td class="lastName"><?= $user['last_name'] ?></td>
			    <td class="email"><?= $user['email'] ?></td>
			    <td><?= date('M d,Y @ h:i:s A', strtotime($user['created_at'])) ?></td>
			    <td><?= date('M d,Y @ h:i:s A', strtotime($user['updated_at'])) ?></td>
				<td>
				   <a href=" " class="updateUser" data-userid="<?= $user['id'] ?>"> <i class="fa fa-pencil"></i>Edit</a>
				   <a href=" " class="deleteUser" data-userid="<?= $user['id'] ?>" data-fname="<?= $user['first_name'] ?>"  data-lname="<?= $user['last_name'] ?>" > <i class="fa fa-trash"></i>Delete</a>
				</td>
			  </tr>
		<?php } ?>
			</tbody>
		</table>
		<p class="userCount"><?= count($users) ?> Users </p>
	         </div>
	        </div>
		  </div>
	    </div>
	  </div>
    </div>
  </div>
 </div>
  <script src="js/script.js?v=<?= time(); ?>"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <!-- <script src="js/jQuery 3.7.1 Released_ Reliable Table Row Dimensions.js"></script> -->
  
  <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
  <!-- Optional theme -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
  <!-- Latest compiled and minified JavaScript -->
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.35.4/js/bootstrap-dialog.js" integrity="sha512-AZ+KX5NScHcQKWBfRXlCtb+ckjKYLO1i10faHLPXtGacz34rhXU8KM4t77XXG/Oy9961AeLqB/5o0KTJfy2WiA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
       <script> 
	   
	   function script(){
	  
	  this.initialize = function(){
		  this.registerEvents();
		  
	  },
	  
	  this.registerEvents = function(){
		  document.addEventListener('click', function(e){
			  targetElement = e.target;
			  classList = targetElement.classList;
			  
			  
			  if(classList.contains('deleteUser')){
		         e.preventDefault();
				 userId = targetElement.dataset.userid;
				 fname = targetElement.dataset.fname;
				 lname = targetElement.dataset.lname;
				 fullName = fname + ' ' + lname;
				 
				 BootstrapDialog.confirm({
					 type: BootstrapDialog.TYPE_DANGER,
					 message: 'Are you sure to delete '+ fullName +'?',
					 callback: function(isDelete){
						 $.ajax({
						 method: 'POST',
						 data: {
							 user_id: userId,
							 f_name: fname,
							 l_name: lname
						 },
						 
						 url: 'database/delete-user.php',
						 dataType: 'json',
						 success: function(data){
							   if(data.success){
								 BootstrapDialog.alert({
									type: BootstrapDialog.Type_SUCCESS,
									message: data.message,
									callback: function(){
										location.reload();
									}
								 });
							 }else 
								 BootstrapDialog.alert({
									type: BootstrapDialog.Type_SUCCESS,
									message: data.message,
								 });
						 }
					 });
					 }
				 })
			  }
			  if(classList.contains('updateUser')){
				  e.preventDefault(); //prevent loading
				  
				  //Get data
				  firstName = targetElement.closest('tr').querySelector('td.firstName').innerHTML;
				  lastName = targetElement.closest('tr').querySelector('td.lastName').innerHTML;
				  email = targetElement.closest('tr').querySelector('td.email').innerHTML;
				  userId = targetElement.dataset.userid;
				  
				  BootstrapDialog.confirm({
					 title: 'Update ' + firstName + ' ' + lastName,
					 message: '<form>\
					     <div class="form-group">\
					     <label for="firstName">First Name:</label>\
						 <input type="text" class="form-control" id="firstName" value="'+ firstName +'">\
						 </div>\
						 <div class="form-group">\
					     <label for="lastName">Last Name:</label>\
						 <input type="text" class="form-control" id="lastName" value="'+ lastName +'">\
						 </div>\
					     <div class="form-group">\
					     <label for="email">Email address:</label>\
						 <input type="email" class="form-control" id="emailUpdate" value="'+ email +'">\
						 </div>\
						 </form>',
					  callback: function(isUpdate){
						  if(isUpdate){//if user click 'Ok' button.
						    $.ajax({
						 method: 'POST',
						 data: {
							 userId: userId,
							 f_name: document.getElementById('firstName').value,
							 l_name: document.getElementById('lastName').value,
							 email: document.getElementById('emailUpdate').value,
						 },
						 
						 url: 'database/update-user.php',
						 dataType: 'json',
						 success: function(data){
							 if(data.success){
								 BootstrapDialog.alert({
									type: BootstrapDialog.Type_SUCCESS,
									message: data.message,
									callback: function(){
										location.reload();
									}
								 });
							 }else BootstrapDialog.alert({
									type: BootstrapDialog.Type_SUCCESS,
									message: data.message,
								 });
						 }
					   });	  
					  }
					 }
				  });
			  }
		  });
	  }
  }
  
  var script = new script;
  script.initialize();
  </script>
</body>
</html>