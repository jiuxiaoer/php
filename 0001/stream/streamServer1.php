<?php
while (true) {
// disconnected every 5 seconds...
    receive_message('127.0.0.1', '85', 5);
}

function receive_message($ipServer, $portNumber, $nbSecondsIdle) {
    // creating the socket...
    $socket = stream_socket_server('tcp://' . $ipServer . ':' . $portNumber, $errno, $errstr);
    if (!$socket) {
        echo "$errstr ($errno)<br />\n";
    } else {
        // while there is connection, i'll receive it... if I didn't receive a message within $nbSecondsIdle seconds, the following function will stop.
        while ($conn = @stream_socket_accept($socket, $nbSecondsIdle)) {
            $message = fread($conn, 1024);
            echo 'I have received that : ' . $message;
            fputs($conn, "OK\n");
            fclose($conn);
        }
        fclose($socket);
    }
}

?>