<?php
    // Start the sessiom
    session_start();
    if(!isset($_SESSION['user'])) header('location: login.php');
	  
	  $show_table = 'suppliers';
      $suppliers = include('database/show.php');  
	  
?> 
<!DOCTYPE html>
<html>
<head>
	
	<title>View Suppliers - Fertilizer Shop Management System</title>
	
	<?php include('partials/app-header-scripts.php'); ?>
	
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
			<h1 class="section_header"><i class="fa fa-list"></i>List Of Suppliers</h1>
			<div class="section_content">
			<div class="users">
			<table>
			 <thead>
			  <tr>
			     <th>#</th>
			     <th>Supplier Name</th>
			     <th>Supplier Location</th>
				 <th>Contact Details</th>
				 <th>Products</th>
			     <th>Created By</th>
			     <th>Created At</th>
			     <th>Updated At</th>
				 <th>Action</th>
			  </tr>
			</thead>
		<tbody>
		<?php foreach($suppliers as $index => $supplier){ ?>
			<tr>
			    <td><?= $index + 1 ?></td>
			    <td>
			       <?= $supplier['supplier_name'] ?>
				</td>
			    <td><?= $supplier['supplier_location'] ?></td>
			    <td><?= $supplier['email'] ?></td>
				<td>
                  <?php
				    $product_list = '-';
					
				    $sid = $supplier['id']; 
				    $stmt = $conn->prepare("
				       SELECT product_name
				         FROM products, productsuppliers
				             WHERE 
							    productsuppliers.supplier=:sid
				                   AND
								  productsuppliers.product = products.id
								  ");
				 $stmt -> bindParam(':sid' , $sid);
                 $stmt->execute();
                 $row = $stmt->fetch(PDO::FETCH_ASSOC);
				
				if($row){
					$product_arr = array_column($row, 'product_name');
				    $product_list = '<li>' . implode("</li><li>", $supplier_arr);
				}
				
				echo $product_list;
				?>
				</td>
			<?php
				 $uid = $supplier['created_by']; 
				 $stmt = $conn->prepare("SELECT * FROM users WHERE id=:uid");
				 $stmt -> bindParam(':uid' , $uid);
                 $stmt->execute();
                 $row = $stmt->fetch(PDO::FETCH_ASSOC);
				 if($row){
				 $created_by_name = $row['first_name'] . '' . $row['last_name'];
				 echo $created_by_name;
				 }else{
					 echo "No user found with the specified ID.";
				 }
				  ?>
			    <td><?= date('M d,Y @ h:i:s A', strtotime($supplier['created_at'])) ?></td>
			    <td><?= date('M d,Y @ h:i:s A', strtotime($supplier['updated_at'])) ?></td>
				<td>
				   <a href=" " class="updateSupplier" data-sid="<?= $supplier['id'] ?>"> <i class="fa fa-pencil"></i>Edit</a>
				   <a href=" " class="deleteSupplier" data-name="<?= $supplier['supplier_name'] ?>"data-sid="<?= $supplier['id'] ?>"> <i class="fa fa-trash"></i>Delete</a>
				</td>
			  </tr>
		<?php } ?>
			</tbody>
		</table>
		<p class="userCount"><?= count($suppliers) ?> Suppliers </p>
	         </div>
	        </div>
		  </div>
	    </div>
	  </div>
    </div>
  </div>
 </div>
 
 <?php 
 include('partials/app-scripts.php'); 
 
		$show_table = 'suppliers';
        $suppliers = include('database/show.php');
		
		$suppliers_arr = [];
							 
		foreach($suppliers as $product){
		$suppliers_arr[$supplier['id']] = $supplier['supplier_name'] ;
			}
			
			$suppliers_arr = json_encode($suppliers_arr);
 ?>
 
 <script> 
 var suppliersList + <?= $suppliers_arr ?>;
 
   function script(){
	   
	   var vm = this;
	   
	   this.registerEvents = function(){	 
	   document.addEventListener('click', function(e){
		   
		   
		      targetElement = e.target; //Target element
			  classList = targetElement.classList;
			  
			   if(classList.contains('deleteSupplier')){
		         e.preventDefault(); //This prevents the default mechanism.
				 
				 pId = targetElement.dataset.sid;
				 supplierName = targetElement.dataset.name;
				 
				 BootstrapDialog.confirm({
					 type: BootstrapDialog.TYPE_DANGER,
					 title: 'Delete Supplier',
					 message: 'Are you sure to delete <strong>'+ supplierName +'</strong>?',
					 callback: function(isDelete){
						 if(isDelete){
						 alert('will be deleting');
						 }else{
							 alert('cancelled');
						 }
						 $.ajax({
						 method: 'POST',
						 data: {
							 id: sId,
							 table: 'suppliers'
						 },
						 
						 url: 'database/delete.php',
						 dataType: 'json',
						 success: function(data){
							 message = data.success ?
							 supplierName + ' successfully deleted!' : 'Error processing your request!';
							 
							 
							  BootstrapDialog.alert({
									type: data.success ? BootstrapDialog.Type_SUCCESS : BootstrapDialog.TYPE_DANGER ,
									message: message,
									callback: function(){
										if(data.success) location.reload();	
									}
								 });
						 }
					 });
					 }
				 });
			  } 


             if(classList.contains('updateSupplier')){
			 e.preventDefault(); //This prevents the default mechanism.
				 
				 sId = targetElement.dataset.sid;
				vm.showEditDialog(sId);
			}
		});   
		  document.addEventListner('submit', function(e){
			e.preventDefault();
			targetElement = e.target;
			
			if(targetElement.id === 'editSupplierForm'){
			   vm.saveUpdateData(targetElement);
		     }
	     })

	   },
	   
	   this.saveUpdateData = function(form){
		                 $.ajax({
						 method: 'POST',
						 data: {
							 supplier_name: document.getElementById('supplier_name').value,
							 supplier_location: document.getElementById('supplier_location').value,
							 email: document.getElementById('email').value,
							 products: $('#products').val(),
							 sid: document.getElementById('sid').value
						 },
						 url: 'database/update-supplier.php',
						 dataType: 'json',
						 success: function(data){
							 BootstrapDialog.alert({
									type:data.success ?  BootstrapDialog.Type_SUCCESS : BootstrapDialog.Type_DANGER,
									message: data.message,
							        callback: function(){
										if (data.success)location.reload();
									}
							 });
						 }
					   });
	   },
	
	   this.showEditDialog = function(id){
		  $.get('database/get-supplier.php',{id: id}, function(supplierDetails){
			  let curProducts = supplierDetails['products'];
			  let productOptions = '';
			  
			  for (const [pId, pName] of Object.entries(productsList)){
				  selected = curSuppliers.indexOf(pId) > -1 ? 'selected' : '';
				  productOption += "<option "+ selected +" value='"+ pId +"'>"+ pName + "</option>";
			  }
			  


			         BootstrapDialog.confirm({
					 title: 'Update  <strong>' + supplierDetails.supplier_name + '</strong>',
					 message: '<form action="database/add.php" method="POST" enctype="multipart/form-data" id ="editSupplierForm">\
					  <div class="appFormInputContainer">\
		    			<label for="supplier_name">Supplier Name</label>\
		    			<input type="text" class="appFormInput" id="supplier_name" value ="' + supplierDetails.supplier_name + '" placeholder="Enter supplier name..." name="supplier_name" />\
		    		</div>\
		    		<div class="appFormInputContainer">\
		    			<label for="supplier_location">Location</label>\
						<input type="text" class="appFormInput" value ="' + supplierDetails.supplier_location + '"  placeholder="Enter product supplier location..." id="supplier_location" name="supplier_location">\
		    		</div>\
					<div class="appFormInputContainer">\
		    			<label for="email">Email</label>\
						<input type="text" class="appFormInput" value ="' + supplierDetails.email + '" placeholder="Enter supplier email..." id="email" name="email">\
		    		</div>\
					   <div class="appFormInputContainer">\
		    			<label for="products">Products</label>\
						<select name = "suppliers[]" id = "suppliersSelect" multiple= "">\
						   <option value="">Select Product</option>\
						   ' + productOptions + '\
						   </select>\
		    		</div>\
						<input type="hidden" name="sid" id="sid" value="'+ supplierDetails.id +'" />\
						<input type="submit" value="submit" id="editSupplierSubmitBtn" class="hidden"/>\
						</form>\
					',
					 
					  callback: function(isUpdate){
						  if(isUpdate){//if user click 'Ok' button.
						 document.getElementById('editSupplierSubmitBtn').click();
						  	  
					  }
					 }
				  });
	     }, 'json');
		   	  
	   },
	   this.initialize = function(){
		   this.registerEvents();
	   }
	}
   var script = new script;
   script.initialize();
  </script>
</body>
</html>