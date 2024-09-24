<?php
    // Start the session
    session_start();
    if (!isset($_SESSION['user'])) header('location: login.php');

    // Get all products
    $show_table = 'products';
    $products = include('database/show.php');
    $products = json_encode($products);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Order Product - Fertilizer Shop Management System</title>

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
                        <h1 class="section_header"><i class="fa fa-plus"></i>Order Product</h1>
                        <div>
                        <form action="database/save-order.php" method="post">
                            <div class="alignRight">
                                <button TYPE="button"orderBtn orderProductBtn" id="orderProductBtn">Add Another Product</button>
                            </div>
                            <div id="orderProductLists">

                            </div>
                            <div class="alignRight marginTop20">
                                <button type="submit" class="orderBtn submitOrderProductBtn">Submit Order</button>
                            </div>
                        </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include('partials/app-scripts.php'); ?>

<script>
    var products = <?= $products ?>;
    var counter = 0;

    function script() {
        var vm = this;

        let productOptions = '\
        <div>\
		    <label for="product_name">PRODUCT NAME</label>\
		    	<select name="order[product_name][]" class="productNameSelect" id="product_name">\
				<option value="">Select Product</option>\
				INSERTPRODUCTHERE\
			</select>\
			<button class="appbtn removeOrderBtn">Remove</button>\
			</div>';

        this.initialize = function () {
            this.registerEvents();
            this.renderProductOptions();
        },
        this.renderProductOptions = function () {
            let optionHtml = '';
            products.forEach((product) => {
                optionHtml += '<option value="' + product.id + '">' + product.product_name + '</option>';
            })
            productOptions = productOptions.replace('INSERTPRODUCTHERE', optionHtml);
        },
        this.registerEvents = function(){

            document.addEventListener('click', function (e) {
                targetElement = e.target; //Target element
                classList = targetElement.classList;

                //Add new product order event
                if (targetElement.id === 'orderProductBtn') {
                    let orderProductListsContainer = document.getElementById('orderProductLists');

                    orderProductListsContainer.innerHTML += '\
				   <div class = "orderProductRow")>\
				  ' + productOptions + '\
				   <div class="suppliersRows" id="suppliersRows_' + counter + '" data-counter= "'+ counter +'"></div>\
				   </div>';

                    counter++;
                }
				
				//If remove button is clicked
				if (targetElement.classList.contains('removeOrderBtn')) {
    let orderRow = targetElement
    .closest('div.orderProductRow');

    //Remove element
    orderRow.remove();
    console.log(orderRow);
					
				}
				
            });
            document.addEventListener('change', function (e) {
                targetElement = e.target; //Target element
                classList = targetElement.classList;

                //Add supplier row on product option change
                if (classList.contains('productNameSelect')) {
                    let pid = targetElement.value;
					
					let counterId = targetElement
					.closest('div.orderProductRow')
					.querySelector('.suppliersRows')
					.dataset.counter;

                    $.get('database/get-product-supplier.php', {id: pid}, function (suppliers) {
                        vm.renderSupplierRows(suppliers);
                    }, 'json');
                }
            });
        },
        this.renderSupplierRows = function (suppliers, counterId) {
            let supplierRows = '';

            suppliers.forEach((supplier) => {
                supplierRows += '\
		                 <div class="row">\
						     <div style="width: 50%;">\
		    			       <p class="supplierName">' + supplier.supplier_name + '</p>\
							 </div>\
						    <div style="width: 50%;">\
		    			        <label for="quantity">Quantity: </label>\
		    			      <input type="number" class="appFormInput orderProductQty" id="quantity" placeholder="Enter quantity..." name="order[quantity]s['+ supplier.id +']" />\
		    		       </div>\
		    		     </div>';
            });
           
		
			//Append to container
			let supplierRowContainer = document.getElementById('suppliersRows_' + counterId);
            supplierRowContainer.innerHTML = supplierRows;
        }
    }

    (new script()).initialize();
</script>
</body>
</html>