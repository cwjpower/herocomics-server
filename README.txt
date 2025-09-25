[compose-override.php74 사용법]

1) 이 폴더 구조 그대로 프로젝트 루트에 복사:
   - compose-override.php74.yml
   - docker/php74/Dockerfile
   - docker/php74/php.ini

2) (선택) 기존 docker-compose.yml 맨 위의 'version:' 키는 제거해도 됩니다. (경고 방지)

3) 빌드 & 기동:
   docker compose -f docker-compose.yml -f compose-override.php74.yml up -d --build
   docker compose restart nginx

4) 확인:
   docker compose exec php php -v
   docker compose exec php php -m

5) 볼륨 경로 확인:
   compose-override.php74.yml 의
     volumes: - ./app:/var/www/html
   부분을, 형 프로젝트의 실제 웹루트 경로에 맞게 조정하세요.
   (예: ./server/app:/var/www/html 등)

롤백은 간단합니다:
   docker compose up -d        # override 파일 없이 실행
