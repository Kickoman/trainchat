<?php 
    $lifetime=600;
    session_start();
    setcookie(session_name(),session_id(),time()+$lifetime);

    if (!isset($_SESSION['user'])) {
        header("Location: login.php");
    }

    if (!isset($_POST['messages'])) {
        die("0");
    }

    function getNumberOfMessages() {
        $servername = "localhost";
        $db_un = "root";
        $db_pw = "passwd";
        $db_nm = "dbname";
        $connection = new mysqli($servername, $db_un, $db_pw, $db_nm);
        if ($connection->connect_error) {
            die('0');
        }
        $rquery = "SELECT COUNT(*) FROM messages";
        if ($stmt = $connection->prepare($rquery)) {
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();

            $numberOfRows = 0;
            // return $result;
            while ($row = $result->fetch_assoc())
                return ($row['COUNT(*)']);
                // $numberOfRows = $row['COUNT(*)'];
        } else return 0;
    }

    print_r(getNumberOfMessages());
?>