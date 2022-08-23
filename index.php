<? 
    $conf_path = 'inc/conf.json';
    require_once 'inc/conf.php';
    require_once 'model/Settings.php';
    Settings::Load();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Download</title>
    <meta charset="utf-8">
    <link href="icon.png" rel="icon" type="image/x-icon" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <style>
        .login-form {
            width: 340px;
            margin: 50px auto;
            font-size: 15px;
        }
        .login-form form {
            margin-bottom: 15px;
            background: #f7f7f7;
            box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
            padding: 30px;
        }
        .login-form h2 {
            margin: 0 0 15px;
        }
    </style>
</head>
<body>
<?
if (!$_GET){
    require_once 'view/error_invalid.php';
}
require_once 'model/Link.php';
$bob = new Link($_GET['guid']);
$check = $bob->checkFile();
if ($check['status'] == "password" && $_POST['password']){
    $return = $bob->downloadFile($_POST['password']);
    if (!$return['success']){
        require_once 'view/error_password.php';
    }
}   else if ($check['success']  && !$_POST['password']){
    $bob->downloadFile();
}  else    {
    switch ($check['status']){
        case 'invalid':
            require_once 'view/error_invalid.php';
            break;
        case 'expired':
            require_once 'view/error_expired.php';
            break;
        case 'password':
            require_once 'view/error_password.php';
            break;
    }
}
?>