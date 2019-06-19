<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../content/css/chat.css" />
    <title>Chat</title>
</head>
<body>
    <a href="../index.html">Home</a>
    <section>
        <ul id="chat">
            <li>Bienvenido al Chat <?= $_SESSION["username"] . ", " . $_SESSION["age"] ?></li>
            <?php foreach($messages as $msg): ?>
            <li><?= $msg->time . " - " . $msg->user . " : " . $msg->message?></li>
            <?php endforeach; ?>
        </ul>
    </section>
    <div>
        <form action="../controller/chat_controller.php">
            <input type="text" name="msg" autocomplete="off">
            <input type="submit" value="Send">
        </form>
    </div>
    <script>
    
    var xhr = new XMLHttpRequest();
    var ul = document.getElementById("chat");

    setInterval(function() {
        xhr.open("GET", "../controller/chat_controller.php?update=1");
        xhr.send();
        var res = "";
        var num = 0;
        xhr.onreadystatechange = function() {
            if(xhr.readyState === 4 && xhr.status === 200){
                res = JSON.parse(xhr.responseText);
                num = res.length-1;
                if(num >= getLis()){
                    ul.insertAdjacentHTML( 'beforeend', '<li>' + res[res.length-1].message + '</li>');
                }
                console.log(res[res.length-1].message);
            }
        }
    }, 5000);

    function getLis(){
        var lis = document.getElementsByTagName("li");
        console.log(lis.length);
        return lis.length;
    }
    
    </script>
</body>
</html>