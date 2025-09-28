<?php
// Book helpers (stubs)
if (!function_exists('book_cover_url')) {
  function book_cover_url(array $b) {
    return $b['cover'] ?? (defined('IMG_URL') ? IMG_URL . '/no-cover.png' : '');
  }
}
if (!function_exists('book_detail_url')) {
  function book_detail_url(array $b) {
    if (isset($b['url'])) return $b['url'];
    $id = $b['id'] ?? null;
    return $id ? ((defined('WEB_URL') ? WEB_URL : '/web') . '/book.php?id=' . $id) : '#';
  }
}
if (!function_exists('book_title'))  { function book_title(array $b){ return $b['title']  ?? '????'; } }
if (!function_exists('book_author')) { function book_author(array $b){ return $b['author'] ?? ''; } }
