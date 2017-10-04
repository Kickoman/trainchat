console.log('Welcome to the best chat ever!');

function iframeReload() {
    var newDocument = $.ajax({type: "GET", url: "chatbox.php", async: false}).responseText;
    var z = document.getElementById('chatbox').contentWindow;
    z.document.open();
    z.document.write(newDocument);
    z.document.close();
}

function iframeScrollDown() {
    var iframe = document.getElementById('chatbox').contentWindow;
    var maxy = 1000000;
    iframe.scrollTo(0, maxy);
}

var last = 1;

function check() {
    $.ajax({
            url: 'numberOfMessages.php',
            type: 'POST',
            data: {
                messages: '0'
            },
            success: function(response) {
                if (response != last) {
                    last = response;
                    iframeReload();
                    iframeScrollDown();
                }
            },
            fail: function() {
                console.log("FAILED!");
            }
    });
}

$(document).ready(function() {
    var go = setInterval(check, 1000);
})