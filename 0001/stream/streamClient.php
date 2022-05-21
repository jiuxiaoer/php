<?php

send_message('localhost', '8000', 'ok very good');

function send_message($ipServer, $portServer, $message) {
    $fp = stream_socket_client("tcp://$ipServer:$portServer", $errno, $errstr);
    if (!$fp) {
        echo "ERREUR : $errno - $errstr<br />\n";
    } else {
        fwrite($fp, "$message\n");
        $response = fread($fp, 4);
        if ($response != "OK\n") {
            echo 'The command couldn\'t be executed...\ncause :' . $response;
        } else {
            echo 'Execution successfull...';
        }
        fclose($fp);
    }
}

?>