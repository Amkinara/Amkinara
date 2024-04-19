<?php
session_start();
error_reporting(0);
include('includes/config.php');

// Code user Registration
if(isset($_POST['submit'])) {
    $name = $_POST['fullname'];
    $email = $_POST['emailid'];
    $contactno = $_POST['contactno'];
    $password = $_POST['password'];

    // Password strength checking
    $passwordStrength = validatePasswordStrength($password);
    if (!$passwordStrength['isValid']) {
        $strengthMsg = $passwordStrength['message'];
        // Redirect back with error message
        header("Location: ".$_SERVER['PHP_SELF']."?strength_msg=".urlencode($strengthMsg));
        exit();
    }

    // Hash the password securely
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert into the database
    $query = mysqli_query($con,"INSERT INTO users(name,email,contactno,password) VALUES('$name','$email','$contactno','$hashedPassword')");
    if($query) {
        $_SESSION['success'] = "Registration successful. You can now login.";
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    } else {
        echo "<script>alert('Not registered, something went wrong');</script>";
    }
}

// Code for user Login
if(isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Fetch user details from the database
    $query = mysqli_query($con,"SELECT * FROM users WHERE email='$email'");
    $num = mysqli_fetch_array($query);

    if($num > 0) {
        // Verify password
        if(password_verify($password, $num['password'])) {
            $_SESSION['login']=$_POST['email'];
            $_SESSION['id']=$num['id'];
            header("location:my-account.php");
        } else {
            echo "<script>alert('Invalid Email or Password');</script>";
        }
    } else {
        echo "<script>alert('Invalid Email or Password');</script>";
    }
}

// Function to validate password strength
function validatePasswordStrength($password) {
    $strengthMsg = '';
    $isValid = true;
    if (strlen($password) < 8) {
        $strengthMsg .= "Password should be at least 8 characters long.<br>";
        $isValid = false;
    }
    if (!preg_match("#[0-9]+#", $password)) {
        $strengthMsg .= "Password should contain at least one number.<br>";
        $isValid = false;
    }
    if (!preg_match("#[a-z]+#", $password)) {
        $strengthMsg .= "Password should contain at least one lowercase letter.<br>";
        $isValid = false;
    }
    if (!preg_match("#[A-Z]+#", $password)) {
        $strengthMsg .= "Password should contain at least one uppercase letter.<br>";
        $isValid = false;
    }
    if (!preg_match("/[!@#$%^&*]/", $password)) {
        $strengthMsg .= "Password should contain at least one special character (!@#$%^&*).<br>";
        $isValid = false;
    }

    return ['isValid' => $isValid, 'message' => $strengthMsg];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="keywords" content="MediaCenter, Template, eCommerce">
    <meta name="robots" content="all">

    <title>Shopping Portal | Sign-in | Signup</title>

    <!-- Bootstrap Core CSS -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    
    <!-- Customizable CSS -->
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/green.css">
    <link rel="stylesheet" href="assets/css/owl.carousel.css">
    <link rel="stylesheet" href="assets/css/owl.transitions.css">
    <!--<link rel="stylesheet" href="assets/css/owl.theme.css">-->
    <link href="assets/css/lightbox.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/animate.min.css">
    <link rel="stylesheet" href="assets/css/rateit.css">
    <link rel="stylesheet" href="assets/css/bootstrap-select.min.css">

    <!-- Demo Purpose Only. Should be removed in production -->
    <link rel="stylesheet" href="assets/css/config.css">

    <link href="assets/css/green.css" rel="alternate stylesheet" title="Green color">
    <link href="assets/css/blue.css" rel="alternate stylesheet" title="Blue color">
    <link href="assets/css/red.css" rel="alternate stylesheet" title="Red color">
    <link href="assets/css/orange.css" rel="alternate stylesheet" title="Orange color">
    <link href="assets/css/dark-green.css" rel="alternate stylesheet" title="Darkgreen color">
    <!-- Demo Purpose Only. Should be removed in production : END -->

    
    <!-- Icons/Glyphs -->
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">

    <!-- Fonts --> 
    <link href='http://fonts.googleapis.com/css?family=Roboto:300,400,500,700' rel='stylesheet' type='text/css'>
    
    <!-- Favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico">
</head>
<body class="cnt-home">
<header class="header-style-1">
    <!-- ============================================== TOP MENU ============================================== -->
    <?php include('includes/top-header.php');?>
    <!-- ============================================== TOP MENU : END ============================================== -->
    <?php include('includes/main-header.php');?>
    <!-- ============================================== NAVBAR ============================================== -->
    <?php include('includes/menu-bar.php');?>
    <!-- ============================================== NAVBAR : END ============================================== -->
</header>
<!-- ============================================== HEADER : END ============================================== -->
<div class="breadcrumb">
    <div class="container">
        <div class="breadcrumb-inner">
            <ul class="list-inline list-unstyled">
                <li><a href="home.html">Home</a></li>
                <li class='active'>Authentication</li>
            </ul>
        </div><!-- /.breadcrumb-inner -->
    </div><!-- /.container -->
</div><!-- /.breadcrumb -->
<div class="body-content outer-top-bd">
    <div class="container">
        <div class="sign-in-page inner-bottom-sm">
            <div class="row">
                <!-- Sign-in -->            
                <div class="col-md-6 col-sm-6 sign-in">
                    <h4 class="">sign in</h4>
                    <p class="">Hello, Welcome to your account.</p>
                    <form class="register-form outer-top-xs" method="post">
                        <span style="color:red;" >
                            <?php
                            echo htmlentities($_SESSION['errmsg']);
                            ?>
                            <?php
                            echo htmlentities($_SESSION['errmsg']="");
                            ?>
                        </span>
                        <div class="form-group">
                            <label class="info-title" for="exampleInputEmail1">Email Address <span>*</span></label>
                            <input type="email" name="email" class="form-control unicase-form-control text-input" id="exampleInputEmail1" >
                        </div>
                        <div class="form-group">
                            <label class="info-title" for="exampleInputPassword1">Password <span>*</span></label>
                            <input type="password" name="password" class="form-control unicase-form-control text-input" id="exampleInputPassword1" >
                        </div>
                        <div class="radio outer-xs">
                            <a href="forgot-password.php" class="forgot-password pull-right">Forgot your Password?</a>
                        </div>
                        <button type="submit" class="btn-upper btn btn-primary checkout-page-button" name="login">Login</button>
                    </form>                 
                </div>
                <!-- Sign-in -->
                <!-- create a new account -->
                <div class="col-md-6 col-sm-6 create-new-account">
                    <h4 class="checkout-subtitle">create a new account</h4>
                    <?php if(isset($_SESSION['success'])): ?>
                        <div class="alert alert-success"><?php echo $_SESSION['success']; ?></div>
                        <?php unset($_SESSION['success']); ?>
                    <?php endif; ?>
                    <p class="text title-tag-line">Create your own Shopping account.</p>
                    <?php if(isset($_GET['strength_msg'])): ?>
                        <div class="alert alert-danger"><?php echo $_GET['strength_msg']; ?></div>
                    <?php endif; ?>
                    <form class="register-form outer-top-xs" role="form" method="post" name="register" onSubmit="return valid();">
                        <div class="form-group">
                            <label class="info-title" for="fullname">Full Name <span>*</span></label>
                            <input type="text" class="form-control unicase-form-control text-input" id="fullname" name="fullname" required="required">
                        </div>
                        <div class="form-group">
                            <label class="info-title" for="exampleInputEmail2">Email Address <span>*</span></label>
                            <input type="email" class="form-control unicase-form-control text-input" id="email" onBlur="userAvailability()" name="emailid" required >
                            <span id="user-availability-status1" style="font-size:12px;"></span>
                        </div>
                        <div class="form-group">
                            <label class="info-title" for="contactno">Contact No. <span>*</span></label>
                            <input type="text" class="form-control unicase-form-control text-input" id="contactno" name="contactno" maxlength="10" required >
                        </div>
                        <div class="form-group">
                            <label class="info-title" for="password">Password. <span>*</span></label>
                            <input type="password" class="form-control unicase-form-control text-input" id="password" name="password"  required >
                        </div>
                        <div class="form-group">
                            <label class="info-title" for="confirmpassword">Confirm Password. <span>*</span></label>
                            <input type="password" class="form-control unicase-form-control text-input" id="confirmpassword" name="confirmpassword" required >
                        </div>
                        <button type="submit" name="submit" class="btn-upper btn btn-primary checkout-page-button" id="submit">Sign Up</button>
                    </form>
                    <span class="checkout-subtitle outer-top-xs">Sign Up Today And You'll Be Able To :  </span>
                    <div class="checkbox">
                        <label class="checkbox">
                            Speed your way through the checkout.
                        </label>
                        <label class="checkbox">
                            Track your orders easily.
                        </label>
                        <label class="checkbox">
                            Keep a record of all your purchases.
                        </label>
                    </div>
                </div>  
                <!-- create a new account -->         
            </div><!-- /.row -->
        </div>
    </div>
</div>
<?php include('includes/footer.php');?>
<script src="assets/js/jquery-1.11.1.min.js"></script>

<script src="assets/js/bootstrap.min.js"></script>

<script src="assets/js/bootstrap-hover-dropdown.min.js"></script>
<script src="assets/js/owl.carousel.min.js"></script>

<script src="assets/js/echo.min.js"></script>
<script src="assets/js/jquery.easing-1.3.min.js"></script>
<script src="assets/js/bootstrap-slider.min.js"></script>
<script src="assets/js/jquery.rateit.min.js"></script>
<script type="text/javascript" src="assets/js/lightbox.min.js"></script>
<script src="assets/js/bootstrap-select.min.js"></script>
<script src="assets/js/wow.min.js"></script>
<script src="assets/js/scripts.js"></script>

<!-- For demo purposes – can be removed on production -->

<script src="switchstylesheet/switchstylesheet.js"></script>
c
<script>
    $(document).ready(function(){ 
        $(".changecolor").switchstylesheet( { seperator:"color"} );
        $('.show-theme-options').click(function(){
            $(this).parent().toggleClass('open');
            return false;
        });
    });

    $(window).bind("load", function() {
       $('.show-theme-options').delay(2000).trigger('click');
    });
</script>
<!-- For demo purposes – can be removed on production : End -->



</body>
</html>
