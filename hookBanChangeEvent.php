<?php
//Alle Aufrufe loggen (Eigentlich unnötig)
$myfile = fopen("bce-recieved", "a") or die("Unable to open file!");
fwrite($myfile, "\n:" . $_SERVER['QUERY_STRING']  . ":");
fclose($myfile);

// Auch wenn der Parameter eigentlich hub.challenge heißt, übersetzt ihn php als hub_challenge
if (isset($_GET['hub_challenge'])) {
    //Ein neuer Webhook wurde angefragt


    //Loggen mit Zeitstempel und hub_challenge (Eigentlich unnötig)
    $myfile = fopen("bce-recieved-hookreqests", "a") or die("Unable to open file!");
    fwrite($myfile, "\n:" . time() . ":" . $_GET['hub_challenge'] . ":");
    fclose($myfile);

    // Annehmen durch Rückmeldung von hub_challenge und einem Status 200
    http_response_code(200);
    echo  $_GET['hub_challenge'];
} else {
    //Neues Event (Neuer Ban oder neuer Unban)


    //Daten aus dem Body einlesen
    $data = json_decode(file_get_contents('php://input'), true);

    //In die Log schreiben (Eigentlich unnötig)
    $myfile = fopen("bce-events", "a") or die("Unable to open file!");
    fwrite($myfile, "\n:" . json_encode($data) . ":");
    fclose($myfile);

    //Es ist möglich dass mehrere BanEvents auf einmal kommen, je nach laune der API
    //Das ist was man eigentlich will
    foreach ($data['data'] as $event) {
        $myfile = fopen("bce-events-formatted", "a") or die("Unable to open file!");
        fwrite($myfile, "\n" . $event['event_timestamp'] . " " . $event['event_type'] . " " . $event['event_data']['user_id'] . " " . $event['event_data']['user_name']);
        fclose($myfile);
    }
}
