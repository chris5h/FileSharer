<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login Required</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
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
    <div class="container">
        <div class="login-form">
            <form method="POST">
                <h2 class="text-center">Login Required</h2>       
                <div class="form-group">
                    <input name="username" type="text" class="form-control" id="username" placeholder="Username" required="required">
                </div>
                <div class="form-group">
                    <input name="password" type="password" class="form-control" id="password" placeholder="Password" required="required">
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-block">Log in</button>
                </div>    
            </form>
        </div>
    </div>
</body>
</html>
<?php   die();  ?>