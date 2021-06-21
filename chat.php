<!DOCTYPE html>
<html>
<head>
    <title>PHP Chat</title>
</head>
<body>
    <h1>PHP Chat Application</h1>
    <?php
    $users = array(
        "terriblegoodday" => "lolmyweirdlife",
        "lowler" => "wj37HKo7"
    );

    function loadMesssages(): array {
        $string = file_get_contents("./messages.json");
        $messages = json_decode($string, true);

        return $messages;
    }

    function sendMessage($existingUsers, $login, $password, $message) {
        if (empty($login) || $existingUsers[$login] != $password) {
            return;
        }

        $messageObject = array(
            "login" => $login,
            "date" => date("Y/m/d h:i:sa"),
            "message" => $message
        );

        $messagesFile = file_get_contents("./messages.json");

        $messages = json_decode($messagesFile, true);

        $messages[] = $messageObject;

        $newMessages = json_encode($messages);
        file_put_contents("./messages.json", $newMessages);

        echo "<strong>Message sent:</strong> " . $message . "<br>";
    }

    $login = !empty($_GET["login"]) ? $_GET["login"] : '';
    $password = !empty($_GET["password"]) ? $_GET["password"] : '';
    $message = !empty($_GET["message"]) ? $_GET["message"] : '';

    if (empty($login) || $users[$login] != $password) {
        echo "Sorry, incorrect login-password" . "<br>";
    } else {
        echo "Connected as <strong>" . $login . "</strong>.<br>";
        echo '
        <form action="./chat.php?login='.$login.'&password='.$password.'" method="GET">
            <input type="text" name="login" value="'.$login.'" hidden>
            <input type="text" name="password" value="'.$password.'" hidden>
            <input type="text" placeholder="Send a message" name="message">
            <button type="submit">Send</button>
        </form>';

        if (!empty($message)) {
            sendMessage($users, $login, $password, $message);
        };
    }
     
    $messages = loadMesssages();
    foreach ($messages as $message) {
        echo "[<strong>" . $message["login"] . "</strong> " . $message["date"] . "] " . $message["message"] . "<br>";
    }
    ?>
</body>
</html>