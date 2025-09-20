<?php
/* htaccess
php_flag display_errors 1
php_flag short_open_tag 1
php_flag allow_url_include 0
php_flag ignore_repeated_errors 0
php_flag expose_php 1
php_value allow_url_fopen 1
php_value max_input_vars 10000
php_value register_argc_argv 0
php_value file_uploads 1
php_value max_file_uploads 20

DirectoryIndex index.php index.html index.htm index.shtml default.htm default.html binero-default.html
 */
/*RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^(.*)＄ index.php

 */
/*
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^(.*+)$ index.php?q=$1 [L]
 */
echo 'simple';
?>
