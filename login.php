<?php
    // Start the sessiom
    session_start();
    if(isset($_SESSION['user'])) header('location: dashboard.php');

    $error_message = '';

    if($_POST){
        include('database/connection.php');

        $username = $_POST['username'];
        $password = $_POST['password'];
        
        $query = 'SELECT * FROM users WHERE users.email="' . $username . '" AND users.password="' . $password . '"';
        $stmt = $conn->prepare($query);
        $stmt->execute();

        if($stmt->rowCount() > 0){
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $user = $stmt->fetchAll()[0];
			
			//Captures data of currently login users.
            $_SESSION['user'] = $user;

            header('Location: dashboard.php');
        } else $error_message = 'Please make sure that username and password are correct.';

        $stmt = $conn->prepare("SELECT * FROM users");
        $stmt -> execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $users = $stmt->fetchAll();

        $user_exist = false;
        foreach($users as $user){
        	$upass = $user['password'];

        	if(password_verify($password, $upass)){
        		$user_exist = true;
        		$_SESSION['user'] = $user;
        		break;
        	}
        }
        if($user_exist) header('location: dashboard.php');
        else $error_message = 'Please make sure that username and password are correct';
    }

?>


<!DOCTYPE html>
<html>
<head>
	
	<title>C & W Fertilizer</title>

	<link rel="stylesheet" type="text/css" href="css/login.css">
</head>
<body id="loginBody">
    <?php if(!empty($error_message)) { ?>
        <div id="errorMessage">
            <strong>Error:</strong><p><?= $error_message ?></p>
        </div>
    <?php } ?>
<div class="container">
	    <div class="loginHeader">
          <h1>C & W Fertilizer</h1>
          <p>Shop Management System</p>
       </div>
    <div class="loginBody">
	    <form action="login.php" method="POST">
		    <div class = "loginInputsContainer">
			    <label for="">Username</label>
			    <input placeholder="username" name="username" type="text" />
		    </div>
		    <div class = "loginInputsContainer">
			    <label for="">Password</label>
			    <input placeholder="password" name="password" type="Password" />
		    </div>
		    <div class="loginButtonContainer">
			    <button>Login</button>
		    </div>
	    </form>
    </div>
</div>

</body>
</html>