<?php
header('Content-Type: text/plain; charset=utf-8');
echo 'DOCROOT=' . $_SERVER['DOCUMENT_ROOT'] . PHP_EOL;
echo 'SCRIPT=' . __FILE__ . PHP_EOL;
echo 'PWD=' . getcwd() . PHP_EOL;