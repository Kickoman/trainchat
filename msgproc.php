<?php 
    $lifetime=600;
    session_start();
    setcookie(session_name(),session_id(),time()+$lifetime);

    if (!isset($_SESSION['user'])) {
        header("Location: login.php");
    }

    if (!isset($_POST['message'])) {
        header("Location: index.php");
    }

    $servername = "localhost";
    $db_un = "root";
    $db_pw = "passwd";
    $db_nm = "dbname";
    $conn = new mysqli($servername, $db_un, $db_pw, $db_nm);
    if ($conn->connect_error) {
        die('FUCK!!!!!!!!!!!');
    }

    function getUserID($connection, $username) {
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

    function addMessage($connection, $uid, $message) {
        $message = htmlspecialchars($message);
        $rquery = "INSERT INTO messages (uid, message) VALUES (?, ?)";
        if ($stmt = $connection->prepare($rquery)) {
            $stmt->bind_param("is", $uid, $message);
            $stmt->execute();
            $stmt->close();
        }
    }

    if (empty($_POST['message'])) {
        header("Location: index.php");
        die();
    }

    print_r(empty($_POST['message']));
    print_r($_POST['message'] === "");
    print_r('<br>');

    $uID = getUserID($conn, $_SESSION['user']);

    addMessage($conn, $uID, $_POST['message']);
    // $sID = 0;
    // if ($uID !== NULL)
    //     $sID = $uID % 4;

    // $handle = fopen('chatbox.html', 'a');
    // $towrite = "<span class=\"user{$sID}\">{$_SESSION['user']}</span>: ".htmlspecialchars($_POST['message'])."<br>";
    // fwrite($handle, $towrite);
    // print_r($handle);
    // // print_r('<br>');
    // print_r($towrite);
    // print_r($_POST);
    header("Location: index.php");
?>