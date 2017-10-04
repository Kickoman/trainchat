<?php 
    // SETTING UP STRING CONSTANTS
    const wrongUsernameFormat = "Прыбярыце%20недапушчальныя%20сымбалі%20з%20імя!%20Можна%20выкарыстоўваць%20сымбалі%20лацінскага%20альфабэту,%20злучок%20і%20падкрэсьліваньне,%20а%20таксама%20мусіць%20быць%20даўжынёй%20больш%20за%203%20і%20менш%20за%2015%20сымбаляў.";
    const usernameUnavailable = "Карыстальнік%20з%20такім%20імем%20ужо%20існуе";
    const requestError = "PAMYLKA%20Ŭ%20ZAPYCIE";
    const success = "Цяпер%20можаце%20ўвайсьці";
    const emptyFields = "ЗАПОЎНІЦЕ%20ЎСЕ%20ПАЛІ";


    // error_reporting( E_ALL ); 
    $lifetime=600; // ten minutes
    session_start();
    setcookie(session_name(),session_id(),time()+$lifetime);

    function check(&$SCOPE, $NAME) {
        if (isset($SCOPE[$NAME]) && $SCOPE[$NAME] != NULL)
            return true;
        else
            return false;
    }

    function isMatch($username) {
        if (preg_match("/^[a-z0-9_-]{3,15}$/", $username) != 1)
            return false;
        else
            return true;
    }

    function isAvailable($username, &$connection) {
        $rquery = "SELECT user FROM users WHERE user = ?";

        if ($stmt = $connection->prepare($rquery)) {
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $rawresult = $stmt->get_result();
            $stmt->close();
            $result = array();
            while ($row = $rawresult->fetch_assoc())
                $result[] = $row;
            if (count($result) > 0) {
                return false;
            } else {
                return true;
            }
        } else {
            return "error";
        }
    }

    function proceedRegistration($username, $password, &$connection) {
        $username = strtolower($username);
        $hash = crypt($password);

        $rquery = "INSERT INTO users (user, passwd) VALUES (?, ?)";
        if ($stmt = $connection->prepare($rquery)) {
            $stmt->bind_param("ss", $username, $hash);
            $stmt->execute();
            $stmt->close();
        } else return false;
        return true;
    }

    function getUsernameId($username, &$connection) {
        $username = strtolower($username);
        $rquery = "SELECT id FROM users WHERE user = ?";
        if ($stmt = $connection->prepare($rquery)) {
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $stmt->close();

            $id = -1;
            while ($row = $result->fetch_assoc())
                $id = $row['id'];
        } else return NULL;
        if ($id !== -1)
            return $id;
        else
            return NULL;
    }

    function createInfo($username, &$connection) {
        $id = getUsernameId($username, $connection);
        if ($id === NULL) {
            die("Some error occuried.");
            return false;
        }

        $rquery = "INSERT INTO userinfo (id, email, bio, graded) VALUES (?, '', '', false)";
        if ($stmt = $connection->prepare($rquery)) {
            $stmt->bind_param("s", $id);
            $stmt->execute();
            $stmt->close();
        } else return false;
        return true;
    }

    if (count($_POST)) {
        if (check($_POST, "login") && check($_POST, "passwd")) {
            //register

            $servername = "localhost";  
            $db_un = "root";
            $db_pw = "passwd";
            $db_nm = "dbname";


            // establishing a connection to MySQL DB
            $conn = new mysqli($servername, $db_un, $db_pw, $db_nm);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }


            $__username = strtolower($_POST['login']);
            if (!isMatch($__username)) {
                header("Location: register.php?wrong=".wrongUsernameFormat);
                die("username is incorrect!");
            }

            if (!isAvailable($__username, $conn)) {
                header("Location: register.php?wrong=".usernameUnavailable);
                die("user with such username is already exist");
            }

            // Congratulations! You can add such a user.
            if (!proceedRegistration($__username, $_POST['passwd'], $conn)){
                // !createInfo($__username, $conn)) {
                header("Location: register.php?wrong=".requestError);   
                die();
            }

            $conn->close();

            header("Location: login.php?success=".success);

        } else header("Location: register.php?wrong=".emptyFields);
    } else {
        header("Location: register.php?wrong=".emptyFields);
    }

    print_r($_POST);
?>