# Crab Taoist 蟹道人

Crab Taoist 是一个基于 Yaf + Composer 的轻量级 API 快速开发脚手架，目标是在保留 Yaf C 扩展性能优势的同时，提供现代 PHP API 项目常用工程能力。

## 快速开始

```bash
composer install
cp .env.example .env
php crab env:check
docker compose up -d --build
docker compose exec php composer install
curl http://localhost:8080/health
```

## 常用命令

```bash
php crab list
php crab env:check
php crab route:list
php crab make:controller User --module=Api --api-version=V1
```

## 测试

```bash
vendor/bin/phpunit
```

## 文档

- [快速开始](docs/QuickStart.md)
- [CLI 使用说明](docs/CLI.md)
- [部署说明](docs/Deployment.md)
- [产品需求文档](docs/PRD.md)
- [MVP 开发计划](docs/MVP.md)
