<?php

exec("~/mitmdump  -nr /var/www/html/The_Girl/tcore/mitmdump -w /var/www/html/The_Girl/filtered \"~m post\"");


$data = file_get_contents("/var/www/html/The_Girl/filtered");

preg_match('/indentifier=(.*)&password=(.*)/', 'foobarbaz', $matches, PREG_OFFSET_CAPTURE);
print_r($matches);
?>