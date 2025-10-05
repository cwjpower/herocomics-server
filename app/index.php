<!-- /index.php -->
<?php /** 작품 리스트 */ ?>
<!doctype html><meta charset="utf-8">
<title>히어로코믹스</title>
<style>
    body{font:16px/1.5 system-ui,apple sd gothic neo,segoe ui,arial,sans-serif;margin:24px}
    header{display:flex;align-items:center;gap:8px;margin-bottom:16px}
    .card{border:1px solid #e5e7eb;border-radius:12px;padding:16px;margin:12px 0}
    a{color:#0ea5e9;text-decoration:none} a:hover{text-decoration:underline}
</style>
<header><h1>히어로코믹스</h1><small>목록</small></header>
<div id="list">불러오는 중…</div>
<script>
    (async () => {
        try{
            const res = await fetch('/api/comics/list.php?limit=50');
            const json = await res.json();
            const el = document.getElementById('list');
            if(!json.ok){ el.textContent = '오류: '+(json.error||''); return; }
            el.innerHTML = json.items.map(c => `
      <div class="card">
        <h3><a href="/comic.php?slug=${encodeURIComponent(c.slug)}">${c.title}</a></h3>
        <div>${c.synopsis ?? ''}</div>
        <div style="color:#6b7280">by ${c.author ?? '-'}</div>
      </div>
    `).join('');
        }catch(e){ document.getElementById('list').textContent = '요청 실패'; }
    })();
</script>
