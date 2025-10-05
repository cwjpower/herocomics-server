#!/usr/bin/env sh
set -e

ROOT="${1:-/var/www/html/web/functions}"
echo "[fix] scanning $ROOT for directory includes..."

# Backup and replace any 'require/include ... /functions/' with loader file
find "$ROOT" -type f -name "*.php" | while read -r f; do
  if grep -Eq "(require|include)(_once)?\s*\(.*?/functions/['\"]\s*\)" "$f"; then
    cp -f "$f" "$f.bak" || true
    # 1) absolute/relative ".../functions/" → loader
    sed -r -i "s#((require|include)(_once)?\s*\()\s*(['\"])[^'\"]*/functions/\4\s*(\))#\1 __DIR__ . '/functions-post.php' \5#g" "$f"
    # 2) __DIR__ . '/functions/' → loader
    sed -r -i "s#((require|include)(_once)?\s*\()\s*__DIR__\s*\.\s*(['\"])\/functions\/\3\s*(\))#\1 __DIR__ . '/functions-post.php' \5#g" "$f"
    echo "[fixed] $f"
  fi
done

echo "[fix] done."
