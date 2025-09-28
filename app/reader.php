<!-- /reader.php -->
<?php $chapter_id = (int)($_GET['chapter_id'] ?? 0); ?>
<!doctype html><meta charset="utf-8">
<title>뷰어</title>
<style>
    body{font:16px/1.5 system-ui,apple sd gothic neo,segoe ui,arial,sans-serif;margin:0;background:#0b0f19;color:#e5e7eb}
    header{position:sticky;top:0;background:#0b0f19cc;backdrop-filter:saturate(160%) blur(6px);padding:12px 16px;border-bottom:1px solid #111827}
    a{color:#60a5fa;text-decoration:none} a:hover{text-decoration:underline}
    .page{display:flex;justify-content:center;margin:12px 0}
    img{max-width:min(100%,1000px);height:auto;border-radius:8px;box-shadow:0 10px 30px rgba(0,0,0,.35)}
    .muted{color:#9ca3af}
</style>
<header><a href="/index.php">← 목록</a> · <a href="javascript:history.back()">이전</a></header>
<main id="root" style="padding:16px 0;min-height:100vh">불러오는 중…</main>
<script>
    (async () => {
        const id = <?php echo json_encode($chapter_id); ?>;
        const root = document.getElementById('root');
        if(!id){ root.textContent='잘못된 접근'; return; }
        const r = await fetch('/api/pages/by-chapter.php?chapter_id='+id);
        const j = await r.json();
        if(!j.ok){ root.textContent='로드 실패'; return; }
        if(!j.pages.length){ root.innerHTML='<div class="muted" style="text-align:center;margin-top:24px">아직 페이지가 없습니다.</div>'; return; }
        root.innerHTML = j.pages.map(p => `
    <div class="page"><img loading="lazy" alt="p${p.page_no}" src="${p.image_path}"></div>
  `).join('');
    })();
</script>
