<?php

// $protocol = isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0';
// header( $protocol.' 301 Moved Permanently' );
// header( 'Location: '.strtolower($_SERVER['REQUEST_URI']) );
header( 'Location: https://'.$_SERVER['HTTP_HOST'].strtolower($_SERVER['REQUEST_URI']), true, 301 );
exit();