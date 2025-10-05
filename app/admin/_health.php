<?php
declare(strict_types=1);
require __DIR__.'/config.php';
header('Content-Type: text/plain; charset=UTF-8');
try{ $ok = $pdo->query('SELECT 1')->fetchColumn(); echo ($ok==1) ? "ADMIN DB: OK\n" : "ADMIN DB: FAIL\n"; }
catch(Throwable $e){ http_response_code(500); echo "ERROR: ".$e->getMessage(); }
