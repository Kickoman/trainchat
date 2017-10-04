<!DOCTYPE html>
<html>
<head>
    <title>The Project</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="styles/style.css">
    <?php 
        $lifetime=600;
        session_start();
        setcookie(session_name(),session_id(),time()+$lifetime);

        if (!isset($_SESSION['user'])) {
            header("Location: login.php");
        }
    ?>
    <script type="text/javascript" src="script/jquery.js"></script>
    <script type="text/javascript" src="script/main.js"></script>
    <script type="text/javascript">
        function main() {
            $('#submitMessage').on('click', function() {
                $.ajax({
                    url: 'msgproc.php',
                    type: 'POST',
                    data: {
                        message: $('#msgbox').val()
                    },
                    success: function(msg) {
                        $('#msgbox').val("");
                        check();
                    }               
                });
            });
            
            iframeScrollDown();

        }
        
        $(document).ready(main);

        function checkKey(e) {
            if (e.keyCode == 13)
            {
                $('#submitMessage').click();
                return false;
            }
        }

        function increase() {
            var len = document.getElementById('msgbox').value.length;
            document.getElementById('inputlen').innerText = len;
        }

    </script>
</head>
<body>
    <div><iframe src="chatbox.php" class="chatbox" id="chatbox"></iframe><div><br>
    <div class="msgform">
        <input id="msgbox" type="text" name="message" class="message" onkeypress="return checkKey(event)" onkeyup="return increase()" autocomplete="off" required>
        <button id="submitMessage">SUBMIT</button> <span id="inputlen">0</span>
    </div>
</body>
</html>