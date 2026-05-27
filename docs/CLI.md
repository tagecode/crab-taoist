# CLI 使用说明

## 查看命令

```bash
php crab list
```

## 环境检测

```bash
php crab env:check
```

## 查看路由

```bash
php crab route:list
```

## 创建控制器

```bash
php crab make:controller User --module=Api --api-version=V1
```

生成文件路径：`application/modules/Api/controllers/V1/User.php`

类名规则：`V1_UserController`
