<?php
// Silence is golden.

// header( 'Location: https://excurs-orel.ru/', true, 301 );
header('HTTP/1.1 200 OK');
header('Location: http://'.$_SERVER['HTTP_HOST']);
exit();