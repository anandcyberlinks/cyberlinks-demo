<?php require 'header.php'; ?>
<div class="container">
<div id="login-box" fade="1" style="display: block;">
    <ul>         
        <li><h4 class="white">User Login</h4></li>
        <form action="" method="POST">
            <li>
                <input name="username" placeholder="yourname@email.com" class="bar">
            </li>
            <li>
                <input name="password" placeholder="password" type="password" class="bar">
            </li>
            <li>
                <input name="remember" type="checkbox" class="left">
                <p>Remember me</p>
            </li>
            <li>
                <button name="login" class="backcolr">Login</button>
            </li>
        </form>
    </ul>
    <div class="forgot">
        <a href="#">Forget Password?</a>
    </div>

    <div class="clear"></div>
</div>
</div>
<?php require 'footer.php'; ?>