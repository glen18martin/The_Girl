<?php

exec("~/mitmdump  -nr /var/www/html/The_Girl/tcore/mitmdump -w /var/www/html/The_Girl/filtered \"~m post\"");


$str = file_get_contents("/var/www/html/The_Girl/filtered");

$re = '/&identifier=(.*)&password=(.*)&/';

preg_match_all($re, $str, $matches, PREG_SET_ORDER, 0);

// Print the entire match result
var_dump($matches);
?>