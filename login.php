<?php
session_start();

require_once './config/config.php';
$token = bin2hex(openssl_random_pseudo_bytes(16));

//If User has already logged in, redirect to dashboard page.
if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === TRUE) {
    header('Location:verify_user_with_token.php');
}

//If user has previously selected "remember me option" :
if (isset($_COOKIE['series_id']) && isset($_COOKIE['remember_token'])) {

    //Get user credentials from cookies.
    $series_id = filter_var($_COOKIE['series_id']);
    $remember_token = filter_var($_COOKIE['remember_token']);
    $db = getDbInstance();
    //Get user By serirs ID :
    $db->where("series_id", $series_id);
    $row = $db->get('admin_accounts');


    if ($db->count >= 1) {

        //User found. verify remember token
        if (password_verify($remember_token, $row[0]['remember_token'])) {
            //Verify if expiry time is modified.

            $expires = strtotime($row[0]['expires']);

            if(strtotime(date()) > $expires){

                //Remember Cookie has expired.
                clearAuthCookie();
                header('Location:login.php');
                exit;
            }

            $_SESSION['user_logged_in'] = TRUE;
            $_SESSION['admin_type'] = $row[0]['admin_type'];
            header('Location:verify_user_with_token.php');
            exit;
        } else {
            clearAuthCookie();
            header('Location:login.php');
            exit;
        }
    } else {
        clearAuthCookie();
        header('Location:login.php');
        exit;
    }
}

//require_once 'includes/header.php';
?>
<html>
<head>
<meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Main Church Application">
        <meta name="author" content="IT Unit">

        <title>Main Church Application</title>

        <!-- Bootstrap Core CSS -->
        <link  rel="stylesheet" href="assets/css/bootstrap.min.css"/>

        <!-- MetisMenu CSS -->
        <link href="assets/js/metisMenu/metisMenu.min.css" rel="stylesheet">

        <!-- Custom CSS -->
        <link href="assets/css/sb-admin-2.css" rel="stylesheet">
        <!-- Custom Fonts -->
        <link href="assets/fonts/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
        <script src="assets/js/jquery.min.js" type="text/javascript"></script>
</head>
<body style="padding-top: 100px;background-image: url('assets/img/bground.png');background-position:center;background-repeat:no-repeat;background-size:cover;">

<div id="page-" class="col-md-4 col-md-offset-4">
    <form class="form loginform" method="POST" action="authenticate.php">
        <div class="login-panel panel panel-default" >
        <div class="panel-heading">Treasury: Please Sign in</div>
            <div class="panel-body">
                <div class="form-group">
                <i class="fa fa-user icon"></i>
                    <label class="control-label">Username</label>
                    <input type="text" name="username" placeholder="Username" class="form-control" required="required">
                </div>
                <div class="form-group">
                <i class="fa fa-key icon"></i>
                    <label class="control-label">Password</label>
                    <input type="password" name="passwd" placeholder="Password" class="form-control" required="required">
                </div>
                <div class="checkbox">
                    <label>
                        <input name="remember" type="checkbox" value="1">Remember Me
                    </label>
                </div>
                <?php
                if (isset($_SESSION['login_failure'])) {?>
                <div class="alert alert-danger alert-dismissable fade in">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <?php echo $_SESSION['login_failure'];unset($_SESSION['login_failure']); ?>
                </div>
                <?php }?>
                <button type="submit" class="btn btn-success loginField" ><i class="fa fa-sign-in"></i> Login</button>
            </div>
        </div>
    </form>
                </div>

</body>
</html>
<?php include_once 'includes/footer.php';?>