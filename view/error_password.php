<div class="container" style="position: relative; text-align: center">
    <div class="card" style="width: 400px; margin-top: 50px; display: inline-block; border: 2px solid black;">
    <div class="card-body" style="text-align: left; text-align: center;">
            <h4 class="card-title">Password Required</h4>
            <?= $return['status'] == 'invalid' ? '<h6 style="color: red;">Invalid Password</h4>' : "" ?>
        </div>
        <div class="card-body" style="text-align: left;">
        <div class="login-form">
            <form method="POST">
                <div class="form-group" style="margin-bottom: 25px;">
                    <input name="password" type="password" class="form-control" id="password" placeholder="Password" required="required">
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-block">Download</button>
                </div>    
            </form>
        </div>
        </div>
    </div>
</div>
</body>
</html>
<?php
die();
?>