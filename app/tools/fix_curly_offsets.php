<?php
/**
 * 파일: tools/fix_curly_offsets.php
 * 사용법: php tools/fix_curly_offsets.php /path/to/PHPExcel
 * 설명 : ${var}{i} → ${var}[i], ->prop{j} → ->prop[j] 로 치환
 */
if ($argc < 2) {
    fwrite(STDERR, "Usage: php {$argv[0]} /absolute/path/to/PHPExcel\n");
    exit(1);
}
$root = rtrim($argv[1], "/");
if (!is_dir($root)) {
    fwrite(STDERR, "Not a directory: $root\n");
    exit(1);
}
$backup = "/tmp/PHPExcel_backup_" . date("Ymd_His") . ".tgz";
@exec("tar -czf $backup -C " . escapeshellarg($root) . " .", $o, $r);
if ($r === 0) {
    echo "[*] Backup: $backup\n";
} else {
    echo "[!] Backup failed. Continuing anyway…\n";
}

$rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root));
$cnt = 0; $err = 0;
foreach ($rii as $file) {
    if ($file->isDir()) continue;
    if (substr($file->getFilename(), -4) !== ".php") continue;
    $path = $file->getPathname();
    $src = file_get_contents($path);
    if ($src === false) { echo "[!] Read error: $path\n"; $err++; continue; }

    // 1) $var{idx} → $var[idx]
    $src2 = preg_replace('/\$([A-Za-z_][A-Za-z0-9_]*)\{([^}]+)\}/', '\$$1[$2]', $src);
    // 2) ->prop{idx} → ->prop[idx]
    $src2 = preg_replace('/(->[A-Za-z_][A-Za-z0-9_]*)\{([^}]+)\}/', '$1[$2]', $src2);

    if ($src2 !== $src) {
        if (file_put_contents($path, $src2) === false) { echo "[!] Write error: $path\n"; $err++; continue; }
        $cnt++;
        // 구문체크 (php -l)
        $ret = 0;
        $out = [];
        @exec("php -l " . escapeshellarg($path) . " 2>&1", $out, $ret);
        if ($ret !== 0) {
            echo "[!] Lint error after patch: $path\n" . implode("\n", $out) . "\n";
            $err++;
        }
    }
}
echo "[✓] Patched files: $cnt, Errors: $err\n";