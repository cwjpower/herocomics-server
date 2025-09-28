<!-- /comic.php -->
<?php $slug = $_GET['slug'] ?? ''; ?>
<!doctype html><meta charset="utf-8">
<title>작품 상세</title>
<style>
    body{font:16px/1.5 system-ui,apple sd gothic neo,segoe ui,arial,sans-serif;margin:24px}
    a{color:#0ea5e9;text-decoration:none} a:hover{text-decoration:underline}
    .muted{color:#6b7280}
    .card{border:1px solid #e5e7eb;border-radius:12px;padding:16px;margin:12px 0}
</style>
<nav><a href="/index.php">← 목록</a></nav>
<h1 id="title">불러오는 중…</h1>
<p class="muted" id="meta"></p>
<p id="synopsis"></p>
<h2>챕터</h2>
<div id="chapters">불러오는 중…</div>
<script>
    (async () => {
        const slug = <?php echo json_encode($slug, JSON_UNESCAPED_UNICODE); ?>;
        if(!slug){ document.getElementById('title').textContent='잘못된 접근'; throw new Error('no slug'); }
        const r = await fetch('/api/comics/show.php?slug='+encodeURIComponent(slug));
        const j = await r.json();
        if(!j.ok){ document.getElementById('title').textContent='없음'; return; }
        const c = j.comic;
        document.getElementById('title').textContent = c.title;
        document.getElementById('meta').textContent = `slug=${c.slug} · author=${c.author??'-'}`;
        document.getElementById('synopsis').textContent = c.synopsis ?? '';
        const el = document.getElementById('chapters');
        el.innerHTML = j.chapters.length
            ? j.chapters.map(ch => `<div class="card">
         <b>${ch.number}화</b> ${ch.title??''}
         <div><a href="/reader.php?chapter_id=${ch.id}">읽기</a></div>
       </div>`).join('')
            : '<div class="muted">등록된 챕터가 없습니다.</div>';
    })();
</script>
