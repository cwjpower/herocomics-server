server {
listen 80;
server_name _;

root /var/www/html;
index index.php index.html;

location / {
try_files $uri $uri/ /index.php?$args;
}

# /admin/은 /admin/index.php 로 폴백(브리지 파일에서 web/admin을 require/include)
location /admin/ {
try_files $uri $uri/ /admin/index.php?$args;
}

location ~ \.php$ {
try_files $uri =404;
include fastcgi_params;
fastcgi_pass php:9000;
fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
fastcgi_index index.php;
}

charset utf-8;
}
