<!DOCTYPE html>
<html>
<head>
    <title>processing...</title>

</head>
<body>
    <?php

        const wrongPassword = "Няправільны%20пароль";
        const noSuchUser = "Няма%20такога%20карыстальніка";
        const authError = "Памылка аўтарызацыі (справа ня ў вас)";
        const emptyFields = "ЗАПОЎНІЦЕ%20ЎСЕ%20ПАЛІ";

        $lifetime=600; // ten minutes
        session_start();
        setcookie(session_name(),session_id(),time()+$lifetime);


        function check(&$SCOPE, $NAME) {
            if (isset($SCOPE[$NAME]) && $SCOPE[$NAME] != NULL)
                return true;
            else
                return false;
        }

        function auth($username, $password) {
            $success = false;
            $username = strtolower($username);
            $servername = "localhost";
            $dbUsername = "root";
            $dbPassword = "dbpass";
            $dbName = "dbname";
            $connection = new mysqli($servername, $dbUsername, $dbPassword, $dbName);
            $rquery = "SELECT user, passwd FROM users WHERE user = ?";

            if ($stmt = $connection->prepare($rquery)) {
                $stmt->bind_param("s", $username);
                $stmt->execute();
                $rawresult = $stmt->get_result();
                $stmt->close();
                $connection->close();
                $result = array();
                while ($row = $rawresult->fetch_assoc())
                    $result[] = $row;
                if (count($result) > 0) {
                    $hash = $result[0]['passwd'];
                    if (hash_equals($hash, crypt($password, $hash))) {
                        $_SESSION['user'] = $username;
                        $success = true;
                    } else {
                        header("Location: login.php?wrong=".wrongPassword);
                        die();
                    }
                } else {
                    header("Location: login.php?wrong=".noSuchUser);
                    die();
                }
            } else {
                $connection->close();
                header("Location: login.php?wrong=".authError);
                die();
            }
            return false;
        }

        $result = "___";
        if (count($_POST)) {
            print_r($_POST."<br>");
            if ((check($_POST, 'login') && check($_POST, 'passwd')) && auth($_POST['login'], $_POST['passwd']))
            {
                $_SESSION['user'] = $_POST['login'];
                header("Location: index.php");
            } else {
                $result = "something is broken!";
                header("Location: login.php?wrong=".emptyFields);
            }
        } else {
            print("EMPTY POST!");
        }
    ?>
    <?php
        print($result);
    ?>
</body>
</html>