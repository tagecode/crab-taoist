# 快速开始

## 环境要求

- PHP 7.0+
- Composer
- Yaf PHP 扩展（**同时支持 `yaf.use_namespace=0` 与 `yaf.use_namespace=1`**）
- Docker 与 Docker Compose（可选，用于本地一键启动）

## Yaf 模式说明

Crab Taoist 会自动识别 Yaf 类加载模式，无需手动改业务代码：

| 配置 | 类名风格 | 示例 |
| --- | --- | --- |
| `yaf.use_namespace=0` | 传统下划线 | `Yaf_Application` |
| `yaf.use_namespace=1` | 命名空间 | `Yaf\Application` |

脚手架通过 `application/library/Support/Yaf/` 兼容层统一封装 Application 创建、路由、控制器与插件基类。两种模式均可运行。

查看当前模式：

```bash
php crab env:check
```

成功时会输出类似：

```text
Yaf mode: namespace (yaf.use_namespace=1)
```

或：

```text
Yaf mode: legacy (yaf.use_namespace=0)
```

## 本地启动（Docker）

```bash
composer install
cp .env.example .env
php crab env:check
docker compose up -d --build
docker compose exec php composer install
curl http://localhost:8080/health
curl http://localhost:8080/v1/ping
```

Docker 镜像默认使用 `yaf.use_namespace=0`，与多数 Yaf 项目习惯一致；本地 PHP 若为 `=1` 也可直接开发。

## 本地启动（PHP 内置服务器）

已安装 Yaf 扩展时，可不依赖 Docker：

```bash
composer install
cp .env.example .env
php crab env:check
php -S localhost:8888 -t public public/index.php
curl http://localhost:8888/health
curl http://localhost:8888/v1/ping
```

## 目录说明

- `application/`：Yaf 应用代码（Bootstrap、模块、插件、Support 库）
- `application/library/Support/Yaf/`：Yaf 双模式兼容层
- `conf/`：Yaf INI 配置、路由、插件注册
- `config/`：PHP 配置文件（读取 `.env`）
- `public/`：Web 入口
- `src/`：CLI 命令源码
- `crab`：命令行入口

## 业务开发规范

本节说明日常 API 开发时应遵循的约定。遵循这些规范后，无论本地是 `yaf.use_namespace=0` 还是 `=1`，业务代码都无需改动。

### 推荐写法

**控制器**继承兼容基类，使用统一 JSON 响应：

```php
<?php

use Support\Http\JsonResponse;
use Support\Yaf\ControllerBase;

class V1_UserController extends ControllerBase
{
    public function listAction()
    {
        $this->getResponse()->clearBody();
        echo JsonResponse::success(array('items' => array()));
        return false;
    }
}
```

**插件**继承 `Support\Yaf\PluginBase`：

```php
<?php

use Support\Yaf\PluginBase;

class CorsPlugin extends PluginBase
{
    public function routerStartup($request, $response)
    {
        // 跨域逻辑
    }
}
```

**新建控制器**优先使用 CLI，保持目录与类名一致：

```bash
php crab make:controller User --module=Api --api-version=V1
```

生成规则：

| 项目 | 约定 |
| --- | --- |
| 文件路径 | `application/modules/Api/controllers/V1/User.php` |
| 类名 | `V1_UserController` |
| 模块 | 业务 API 放在 `Api` 模块 |
| 版本 | 通过 `V1`、`V2` 目录隔离 |

**路由**在 `conf/routes.php` 维护，不要硬编码在控制器里：

```php
array(
    'name' => 'api.v1.users',
    'type' => 'rewrite',
    'match' => '/v1/users',
    'route' => array(
        'module' => 'Api',
        'controller' => 'V1_User',
        'action' => 'list',
    ),
    'methods' => array('GET'),
    'auth' => false,
),
```

**配置**通过 `.env` + `config/` 读取，不要在业务代码里写死环境差异：

```php
use Support\Config;

$debug = Config::get('app.debug', false);
$version = Config::get('app.version', '0.1.0');
```

**响应格式**统一使用 `JsonResponse`，保持接口结构一致：

```json
{
  "code": 200,
  "message": "success",
  "data": {},
  "request_id": "..."
}
```

### 避免写法

以下写法会破坏双模式兼容，或导致部分环境无法运行：

| 避免 | 原因 | 替代方案 |
| --- | --- | --- |
| `extends Yaf_Controller_Abstract` | `=1` 模式下类不存在 | `extends Support\Yaf\ControllerBase` |
| `extends Yaf_Plugin_Abstract` | 同上 | `extends Support\Yaf\PluginBase` |
| `new Yaf_Application(...)` | Application 类名因模式而异 | 入口已用 `ApplicationFactory`，业务勿重复创建 |
| `new Yaf_Route_Rewrite(...)` | 路由类名因模式而异 | 在 `conf/routes.php` 配置，由 `RouteFactory` 加载 |
| 插件方法强类型提示 `Yaf_Request_Abstract` | 两种模式下类名不一致 | 使用 `$request`、`$response` 参数，不加 Yaf 类型提示 |
| 从外部复制的 Yaf 示例代码 | 可能混用另一种命名风格 | 按本项目基类与目录规范改写 |

### 目录与模块约定

- 根模块 `Index`：保留，用于默认首页与健康类兜底路由，一般不写业务 API。
- 业务模块 `Api`：所有对外 API 放此模块。
- 版本目录：`controllers/V1/`、`controllers/V2/`，便于长期维护。
- 公共能力：放 `application/library/Support/`，不要堆在控制器里。
- 插件注册：在 `conf/plugins.php` 添加类名，文件放在 `application/plugins/`。

### 异常与错误

- 业务可预期错误：抛出 `Support\Exceptions\ApiException`。
- 参数校验失败：抛出 `Support\Exceptions\ValidationException`。
- 不要手动输出 HTML 错误页；`ErrorController` 会统一返回 JSON。
- 生产环境保持 `APP_DEBUG=false`，避免堆栈泄露。

### 开发自检清单

提交代码前可快速检查：

```bash
php crab env:check          # 环境是否正常
php crab route:list         # 路由是否已注册
vendor/bin/phpunit          # 单元测试是否通过
```

业务代码自检：

1. 控制器是否继承 `ControllerBase`？
2. 插件是否继承 `PluginBase`？
3. 是否使用了 `JsonResponse` 而不是手写 `json_encode`？
4. 新接口是否已在 `conf/routes.php` 注册？
5. 是否避免直接使用 `Yaf_*` / `Yaf\*` 原生类名？

### 与 Yaf 双模式的关系

业务层只需面向 `Support\Yaf\*Base` 和 `Support\Http\*` 开发；`yaf.use_namespace` 的差异由框架层消化。本地与 Docker 配置不一致时，不需要改业务代码，执行 `php crab env:check` 确认当前模式即可。
