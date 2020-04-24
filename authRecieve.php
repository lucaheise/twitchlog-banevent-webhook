<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>

    <?php
    require("utils.inc.php");

    //Berechtigungen anfragen
    echo '<a href="https://id.twitch.tv/oauth2/authorize?client_id=' . Utils::$clientId . '&redirect_uri=' . Utils::$refUrlBase . 'authRecieve.php&response_type=code&scope=openid moderation:read">Retry</a>';
    echo "<br>";


    if (isset($_GET['code'])) {
        //Nutzer hat das hinzufügen erlaubt
        $authCode = $_GET['code'];
        echo "Authcode: " . $authCode;
        echo "<br>";
        echo "<br>";


        //Mit dem authCode die Berechtigungsinformationen holen (für den access_token)
        $authArr = Utils::getAuthArr($authCode);


        //Ausgeben auf der Webseite (zum Testen)
        echo "---BerechtigunsInfo ---<br>";
        foreach ($authArr as $key => $value) {
            echo $key . ':' . $value;
            echo '<br>';
        }
        foreach ($authArr['scope'] as $key => $value) {
            echo "scope:" . $key . ':' . $value;
            echo '<br>';
        }
        echo '<br>';


        //UserInfo holen (für die UserId)
        $userInfoArr = Utils::getUserInfoEndpoint($authArr['access_token']);

        //Ausgeben auf der Webseite (zum Testen)
        echo "---UserInfo ---<br>";
        foreach ($userInfoArr as $key => $value) {
            echo $key . ':' . $value;
            echo '<br>';
        }
        echo '<br>';


        //WebHook erstellen
        echo Utils::toggleHookBanChangeEvent(true, $userInfoArr['sub'], $authArr['access_token']);

        //WebHooks-Abbonements auflisten
        echo "---WebHooks (eventuell erst nach dem zweitem Laden sichtbar) ---<br>";
        $appAuth = Utils::getAppAuth($authArr['access_token']);
        $webhookSubscriptions = Utils::getWebhookSubscriptions($appAuth['access_token']);

        echo 'total:' . $webhookSubscriptions['total'];
        echo '<br>';
        foreach ($webhookSubscriptions['data'] as $x) {
            foreach ($x as $key => $value) {
                echo $key . ':' . $value;
                echo '<br>';
            }
        }
    }
    ?>

</body>

</html>