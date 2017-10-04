<!DOCTYPE html>
<html>
<head>
    <title>Login or register</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="styles/style.css">
    <?php
        $lifetime=600;
        session_start();
        setcookie(session_name(),session_id(),time()+$lifetime);

        if (isset($_SESSION['user'])) {
            header("Location: index.php");
        }

    ?>
    </head>
<body>
    <div class="centered">
       <form id="loginform" method="post" action="loginproc.php">
            <label class="inputlbl" for="login">Login</label><br>
            <input type="text" class="logininput" id="login" name="login"><br>
            <label class="inputlbl" for="passwd">Password</label><br>
            <input type="password" class="logininput" id="passwd" name="passwd"><br>
            <input type="submit" class="submitbtn" value="Get into"><br>
            <a href="register.php">Register, fucking faggot</a>
       </form> 
    </div>
</body>
</html>