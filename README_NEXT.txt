[다음 작업 체크리스트]
1) Admin 복사:
   .\scripts\copy_admin.ps1 "C:\herocomic\server\_tmp_extract\Admin\HerosComics_Admin-master"
   → http://localhost:8081/admin/

2) DB 덤프 복원(있다면):
   .\scripts\db_import.ps1 C:\path\to\dump.sql
   → http://localhost:8081/_dbcheck.php

3) 업로드/캐시 권한:
   .\scripts\set_perms.ps1

4) 운영 모드 전환(오류 숨김 + 보안 헤더):
   .\scripts\switch_prod.ps1

5) 백업:
   .\scripts\backup.ps1

6) (선택) SSL 템플릿: ops\nginx\default_ssl.conf (인증서 경로 맞추세요)

7) 앱은 맨 마지막에 (BASE_URL 교체 후 테스트)
