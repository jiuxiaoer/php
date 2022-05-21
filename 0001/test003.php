<?php



$socket = stream_socket_server("tcp://127.0.0.1:8000", $errno,
    $errstr);
stream_set_blocking($socket, 0);

$connections = [];
$read = [];
$write = null;
$except = null;

while (1) {
    // look for new connections
    if ($c = @stream_socket_accept($socket, empty($connections) ? -1 : 0, $peer)) {
        echo $peer.' connected'.PHP_EOL;
        fwrite($c, 'Hello '.$peer.PHP_EOL);
        $connections[$peer] = $c;
    }
    // wait for any stream data
    $read = $connections;
    if (stream_select($read, $write, $except, 5)) {
        foreach ($read as $c) {
            $peer = stream_socket_get_name($c, true);

            if (feof($c)) {
                echo 'Connection closed '.$peer.PHP_EOL;
                fclose($c);
                unset($connections[$peer]);
            } else {
                $contents = fread($c, 1024);
                echo $peer.': '.trim($contents).PHP_EOL;
            }
        }
    }
}
?>