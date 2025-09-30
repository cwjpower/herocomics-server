<?php
$root = getcwd();
$f = $root . "/config/console.php";
$src = file_get_contents($f);

// 1) bootstrap 배열에서 'gii' 제거 + 빈 요소/중복 콤마 정리
$src = preg_replace_callback("/('bootstrap'\\s*=>\\s*\\[)([^\\]]*)(\\])/s", function($m){
    $inner = $m[2];
    $inner = preg_replace("/\\s*(['\"])gii\\1\\s*,?/", '', $inner);    // gii 제거
    $inner = preg_replace('/,\\s*,+/', ',', $inner);                   // , ,  -> ,
    $inner = preg_replace('/^\\s*,+\\s*/', '', $inner);                // 선행 콤마 제거
    $inner = preg_replace('/\\s*,+\\s*$/', '', $inner);                // 후행 콤마 정리
    return $m[1].$inner.$m[3];
}, $src);

// 2) modules에서 gii 블록 제거
$src = preg_replace("/,\\s*'gii'\\s*=>\\s*\\[[^\\]]*\\]\\s*,?/s", "", $src);
$src = preg_replace('/,\\s*"gii"\\s*=>\\s*\\[[^\\]]*\\]\\s*,?/s', "", $src);

// 3) 배열 전반 정리(보수)
$src = preg_replace('/,\\s*,+/', ',', $src);
$src = preg_replace('/\\[\\s*,+\\s*/', '[', $src);
$src = preg_replace('/\\s*,+\\s*\\]/', ']', $src);

file_put_contents($f, $src);
echo "patched\n";
