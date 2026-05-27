# 部署说明

## Docker 本地运行

```bash
docker compose up -d --build
docker compose exec php composer install
curl http://localhost:8080/health
```

## 传统 PHP-FPM + Nginx 部署

1. 安装 PHP 7.0+ 与 Yaf 扩展。
2. 将 Web 根目录指向 `public/`。
3. 配置 Nginx 将所有非静态请求转发到 `public/index.php`。
4. 设置环境变量 `APPLICATION_ENV=production`。
5. 复制 `.env.example` 为 `.env` 并填写生产配置。

## 生产建议

- 使用 PHP-FPM + Nginx。
- 开启 opcache。
- 执行 `composer install --no-dev --optimize-autoloader`。
- 设置 `APP_ENV=production`。
- 设置 `APP_DEBUG=false`。
- 确保 `runtime/logs` 和 `runtime/cache` 可写。
