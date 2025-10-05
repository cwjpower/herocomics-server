<?php
require __DIR__."/functions.php";
require __DIR__."/_auth.php";
require __DIR__."/_layout.php";
require __DIR__."/_data.php";
require_login();

$rows = data_load("posts");
$id   = isset($_GET["id"]) ? (int)$_GET["id"] : 0;
$cur  = null;
if ($id) { foreach ($rows as $r){ if ((int)$r["id"]===$id){ $cur=$r; break; } } }

$err = "";
if ($_SERVER["REQUEST_METHOD"]==="POST") {
  verify_csrf();
  $title = trim($_POST["title"]??"");
  $author= trim($_POST["author"]??"");
  $publisher = trim($_POST["publisher"]??"");
  $status= $_POST["status"]??"draft";
  $cover = trim($_POST["cover"]??"");

  if ($title==="") { $err="제목은 필수입니다."; }
  if (!$err){
    if ($id && $cur){ // update
      foreach ($rows as &$r){
        if ((int)$r["id"]===$id){
          $r["title"]=$title; $r["author"]=$author; $r["publisher"]=$publisher; $r["status"]=$status; $r["cover"]=$cover;
        }
      }
    } else { // create
      $rows[] = ["id"=>data_next_id($rows),"title"=>$title,"author"=>$author,"publisher"=>$publisher,"status"=>$status,"cover"=>$cover];
    }
    data_save("posts",$rows);
    header("Location: ".ADMIN_URL."/posts.php"); exit;
  }
}

admin_header($id?"작품 수정":"새 작품");
if ($err) echo "<div class='card' style='border-color:#fca5a5;color:#b91c1c'>".htmlspecialchars($err,ENT_QUOTES|ENT_SUBSTITUTE,"UTF-8")."</div>";

function v($k,$d=""){ global $cur; $v=$cur[$k]??$d; return htmlspecialchars((string)$v,ENT_QUOTES|ENT_SUBSTITUTE,"UTF-8"); }

echo "<form class='card' method='post'>";
csrf_field();
echo "<label>제목<input name='title' value='".v("title")."' required></label>";
echo "<label>작가<input name='author' value='".v("author")."'></label>";
echo "<label>출판사<input name='publisher' value='".v("publisher")."'></label>";
echo "<label>상태<select name='status'>
        <option value='draft' ".((($cur["status"]??"")=="draft")?"selected":"").">임시</option>
        <option value='published' ".((($cur["status"]??"")=="published")?"selected":"").">게시</option>
      </select></label>";
echo "<label>표지 URL<input name='cover' value='".v("cover")."'></label>";
echo "<button class='btn' type='submit'>저장</button> ";
echo "<a class='btn' style='background:#64748b' href='".ADMIN_URL."/posts.php'>목록</a>";
echo "</form>";
admin_footer();
