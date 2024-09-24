<?php
    // Start the sessiom
    session_start();
    if(!isset($_SESSION['user'])) header('location: login.php');

      $user = $_SESSION['user'];

?> 
<!DOCTYPE html>
<html>
<head>
	<title>Dashboard - Fertilizer Shop Management System</title>
	<link rel="stylesheet" type="text/css" href="css/login.css">
	<script src="https://use.fontawesome.com/0c7a3095b5.js"></script>
</head>
<body>
	  <div id="dashboardMainContainer">
		<?php include('partials/app-sidebar.php') ?>
     <div class="dashboard_content_container" id="dashboard_content_container">
	    <?php include('partials/app-topnav.php') ?>
	    <div class="dashboard_content">
		    <div class="dashboard_content_main">
		    </div>
	    </div>
     </div>
  <script src="js/script.js"></script>
  <script>
    	var sideBarIsOpen = true;

    	toggleBtn.addEventListener( 'click', (event) => {
    		event.preventDefault();

    	if (sideBarIsOpen) {
    		dashboard_sidebar.style.width = '10%';
    		dashboard_sidebar.style.transition = '0.3s all';
    		dashboardMainContainer.style.width = '90%';
    		dashboard_logo.style.fontsize = '60px';
    		userImage.style.width = '60px';

    		menuIcons = document.getElementByClassName('menuText');
    		for (var i = 0; i<menuIcons.length;i++){
    			menuIcons[i].style.display = 'none';
    		}
    		document.getElementByClassName('dashboard_menu_lists')[0].style.textAlign = 'center';
    		 sideBarIsOpen = false;
    	}else{
    			dashboard_sidebar.style.width = '20%';
    		    dashboardMainContainer.style.width = '80%';
    		    dashboard_logo.style.fontsize = '80px';
    		    userImage.style.width = '80px';

    		    menuIcons = document.getElementByClassName('menuText');
    		    for (var i = 0; i<menuIcons.length;i++){
    			menuIcons[i].style.display = 'inline-block';
    		    }
    		    document.getElementByClassName('dashboard_menu_lists')[0].style.textAlign = 'left';
    		    sideBarIsOpen = true;
    		  }

    	});

  </script>
</body>
</html>