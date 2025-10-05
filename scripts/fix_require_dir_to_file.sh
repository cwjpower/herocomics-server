#!/usr/bin/env bash
set -euo pipefail
ROOT="${1:-/var/www/html/web}"
echo "[fix] scanning under $ROOT"

# 찾기: functions/로 끝나는 include/require 호출 치환 -> functions/functions-post.php
# 백업: .bak 저장
while IFS= read -r -d '' f; do
  if grep -Eq "require|include" "$f"; then
    cp -f "$f" "$f.bak" || true
    # 1) '/var/www/html/web/functions/' -> '/var/www/html/web/functions/functions-post.php'
    sed -E -i "s#((require|include)(_once)?\s*\(\s*['\"])((/var/www/html)?/web/functions/)(['\"]\s*\))#\1/web/functions/functions-post.php\7#g" "$f"
    # 2) __DIR__ . '/functions/' -> __DIR__ . '/functions/functions-post.php'
    sed -E -i "s#((require|include)(_once)?\s*\(\s*__DIR__\s*\.\s*['\"]/functions/)['\"]\s*\)#\1functions-post.php')#g" "$f"
    # 3) 상대경로 '.../functions/' -> '.../functions/functions-post.php'
    sed -E -i "s#((require|include)(_once)?\s*\(\s*['\"][^'\"]*/functions/)['\"]\s*\)#\1functions-post.php')#g" "$f"
    echo "[fixed] $f"
  fi
done < <(find "$ROOT" -type f -name "*.php" -print0)

echo "[fix] done."
