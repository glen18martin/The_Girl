<?php

exec("~/mitmdump  -nr /var/www/html/The_Girl/tcore/mitmdump -w /var/www/html/The_Girl/filtered \"~m post\"");


echo file_get_contents("/var/www/html/The_Girl/filtered");
?>