<?php
	header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="styles/style.css">
    <script type="text/javascript">
        // document.
    </script>
</head>
<body>
    <?php
        $servername = "localhost";
        $db_un = "root";
        $db_pw = "password";
        $db_nm = "dbname";
        $conn = new mysqli($servername, $db_un, $db_pw, $db_nm);
        if ($conn->connect_error) {
            die('FUCK!!!!!!!!!!!');
        }
        
        function getUserById($connection, $id) {
            $rquery = "SELECT * FROM users WHERE id = ?";
            if ($stmt = $connection->prepare($rquery)) {
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $result = $stmt->get_result();
                $stmt->close();

                while ($row = $result->fetch_assoc()) 
                    return $row['user'];
            }
            return 'None';
        }

        function getMessages($connection) {
            $rquery = "SELECT * FROM messages";
            if ($stmt = $connection->prepare($rquery)) {
                // $stmt->bind_param("s", $username);
                $stmt->execute();
                $result = $stmt->get_result();
                $stmt->close();
                $toreturn = array();
                while ($row = $result->fetch_assoc()) {
                    $tmp = array('uid' => $row['uid'], 'user' => getUserById($connection, $row['uid']), 'message' => $row['message']);
                    $toreturn[] = $tmp;
                    // print_r($tmp);
                    // print_r("<br><br>");
                }
                return $toreturn;
            } else return "NULL";
        }

        $messages = getMessages($conn);
        // print_r($messages);
        foreach ($messages as &$message) {
            print("<span class=\"user".($message['uid'] % 8)."\">".$message['user']."</span>: <span class=\"message\">".$message['message']."</span><br>");
        }
    ?>
</body>
</html>