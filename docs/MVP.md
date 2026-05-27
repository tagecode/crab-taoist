# Crab Taoist MVP 开发计划

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [x]`) syntax for tracking.

**Goal:** 交付一个基于 Yaf + Composer 的最小可用 API 脚手架，使开发者能够安装依赖、启动服务、访问示例 API、使用 CLI 生成控制器，并通过 Docker 完成本地运行。

**Architecture:** MVP 只保留 API 脚手架闭环所需的核心能力：Yaf 原生入口、Bootstrap、Composer autoload、多环境配置、轻量路由封装、统一 JSON 响应、异常处理、基础日志、CLI 生成器、健康检查和 Docker 环境。数据库、Redis、JWT、迁移等企业能力在 MVP 中只做连接与配置骨架，不做完整业务抽象。

**Tech Stack:** PHP 7.0+、Yaf 扩展、Composer、Symfony Console、vlucas/phpdotenv、Monolog、Nginx、PHP-FPM、Docker Compose、PHPUnit。

**实施状态（已完成）：** Task 1–10 均已落地；Docker 与 PHPUnit 验收通过。下文 Task 步骤复选框已全部勾选，仅作实施记录。

### Yaf 双模式兼容（`yaf.use_namespace` 0 / 1）

- `application/library/Support/Yaf/` 提供 `ApplicationFactory`、`RouteFactory`、`ControllerBase` / `BootstrapBase` / `PluginBase`，按运行时自动适配 legacy 与 namespace 类名。
- `php crab env:check` 会输出当前 Yaf 模式。

### Index 默认模块

- `conf/application.ini` 配置 `application.modules = "Index,Api"`，并设置 `defaultModule = Index`。
- 原因：Yaf 在单模块场景下会把 `Api/controllers/V1/*` 错误解析到 `application/controllers/`，导致 V1 控制器无法加载；增加 Index 模块后 Api 模块目录结构按预期工作。

### CLI `make:controller` 参数

- 使用 **`--api-version=V1`**（非 `--version`），避免与 Symfony Console 全局 `--version` 冲突。

### 日志通道（M8）

| 通道 | 文件 | 用途 |
| --- | --- | --- |
| `request` | `runtime/logs/request.log` | 每个 HTTP 请求一条（RequestLogPlugin） |
| `error` | `runtime/logs/error.log` | 未捕获异常与 5xx（ErrorController） |
| `app` | `runtime/logs/app.log` | 应用级事件；`/health` 探测时写入，亦可业务侧调用 `LoggerFactory::info()` |

---

## 1. MVP 定位

MVP 版本的目标不是一次性实现 PRD 中所有企业级能力，而是先验证 Crab Taoist 的核心价值：

1. Yaf 的高性能请求生命周期可以和 Composer 生态稳定共存。
2. 新项目可以用标准目录结构快速启动。
3. API 服务具备统一响应、统一异常、多环境配置、健康检查等基础生产能力。
4. 开发者可以通过 `php crab make:controller` 快速生成 API 控制器。
5. 本地可以通过 Docker 一键启动 Nginx + PHP-FPM + Yaf 运行环境。

MVP 完成后，项目应当能支撑一个真实 API 服务的初始开发，而不是只停留在静态模板。

## 2. MVP 必须实现的功能

| 编号 | 功能 | MVP 要求 |
| --- | --- | --- |
| M1 | 项目骨架 | 提供 Yaf 标准目录、入口、Bootstrap、Api/V1 示例控制器 |
| M2 | Composer 集成 | Bootstrap 加载 `vendor/autoload.php`，支持 PSR-4 |
| M3 | 多环境配置 | 支持 `.env`、`.env.example`、`conf/application.ini` 分环境配置 |
| M4 | 环境检测 | 提供 `php crab env:check`，检测 PHP、Yaf、Composer、目录权限 |
| M5 | 路由封装 | 基于 Yaf Router 支持 `/health` 和 `/v1/ping` 示例路由 |
| M6 | 统一响应 | 提供 JSON 成功、失败、分页响应辅助类 |
| M7 | 全局异常 | API 异常统一返回 JSON；路由/控制器不存在返回 **404 JSON**；生产隐藏堆栈 |
| M8 | 日志 | Monolog：`request`（每请求）、`error`（5xx/异常）、`app`（应用事件，如 health check） |
| M9 | CLI 工具 | 提供 `php crab list`、`env:check`、`make:controller`、`route:list` |
| M10 | Docker | 提供 Dockerfile、docker-compose、Nginx Yaf Rewrite 配置 |
| M11 | 测试 | 提供 PHPUnit 基础配置和响应辅助类单测 |
| M12 | 文档 | 提供 README、快速开始、CLI、Docker 运行说明 |

## 3. MVP 暂缓范围

以下能力属于 PRD 中的重要功能，但不进入 MVP 首轮交付。MVP 只预留配置结构或扩展点，避免过早复杂化。

| 功能 | MVP 处理方式 | 后续版本 |
| --- | --- | --- |
| Eloquent ORM 完整封装 | 仅预留 `config/database.php` 和 env 字段 | V1.1 |
| Redis Cache 类 | 仅预留 `config/cache.php` 和 env 字段 | V1.1 |
| JWT 鉴权插件 | 仅预留插件注册结构和免认证路由约定 | V1.1 |
| CORS 插件完整配置 | MVP 可先提供基础响应头配置 | V1.1 |
| 数据库迁移 | 不集成 Phinx，仅保留 `database/migrations/` 目录 | V1.2 |
| 参数验证组件 | 不接入复杂验证库，先提供异常与错误响应约定 | V1.1 |
| 限流插件 | 不实现 | V1.2 |
| phpstan/php-cs-fixer | 不作为 MVP 阻塞项 | V1.1 |
| OpenAPI 文档生成 | 不实现 | V1.3 |

## 4. MVP 验收标准

### 4.1 基础运行

- 执行 `composer install` 后，`vendor/autoload.php` 存在。
- 执行 `php crab env:check` 能输出检测结果。
- 通过 Docker 启动后，访问 `GET /health` 返回 JSON。
- 访问 `GET /v1/ping` 返回统一 JSON 响应。
- 访问不存在路由时返回 **404 JSON**（`{"code":404,"message":"Not Found",...}`），不返回 HTML 错误页。

### 4.2 CLI

- `php crab list` 能列出可用命令。
- `php crab env:check` 能检测 PHP 版本、Yaf 扩展、Composer autoload、目录权限、`.env` 文件。
- `php crab make:controller User --module=Api --api-version=V1` 能生成 `application/modules/Api/controllers/V1/User.php`。
- `php crab route:list` 能显示 MVP 内置路由。

### 4.3 Docker

- `docker compose up -d` 或 `docker-compose up -d` 能启动 Nginx 和 PHP-FPM。
- PHP 容器内已安装或启用 Yaf 扩展。
- Nginx Rewrite 能把非静态请求转发到 `public/index.php`。
- 容器日志可看到 PHP-FPM 或 Nginx 基础访问日志。

### 4.4 测试

- `vendor/bin/phpunit` 能执行基础测试。
- 响应辅助类成功响应、失败响应测试通过。
- CLI 生成器的路径生成逻辑可通过单元测试验证。

## 5. 推荐 MVP 文件结构

```text
crab-taoist/
├── application/
│   ├── Bootstrap.php
│   ├── controllers/
│   │   └── Error.php
│   ├── modules/
│   │   ├── Index/
│   │   │   └── controllers/
│   │   │       └── Index.php
│   │   └── Api/
│   │       └── controllers/
│   │           └── V1/
│   │               ├── Health.php
│   │               └── Ping.php
│   ├── plugins/
│   │   ├── RequestIdPlugin.php
│   │   └── RequestLogPlugin.php
│   └── library/
│       └── Support/
│           ├── Config.php
│           ├── Env.php
│           ├── Exceptions/
│           │   ├── ApiException.php
│           │   ├── ValidationException.php
│           │   └── YafExceptionResolver.php
│           ├── Http/
│           │   └── JsonResponse.php
│           ├── Logging/
│           │   └── LoggerFactory.php
│           ├── Routing/
│           │   └── RouteLoader.php
│           └── Yaf/
│               ├── ApplicationFactory.php
│               ├── Bases.php
│               ├── Compat.php
│               └── RouteFactory.php
├── bin/
│   └── crab
├── conf/
│   ├── application.ini
│   ├── routes.php
│   └── plugins.php
├── config/
│   ├── app.php
│   ├── cache.php
│   ├── database.php
│   └── logging.php
├── docker/
│   ├── nginx/
│   │   └── default.conf
│   └── php/
│       ├── Dockerfile
│       └── php.ini
├── public/
│   └── index.php
├── runtime/
│   ├── cache/
│   └── logs/
├── src/
│   └── Console/
│       ├── Application.php
│       └── Commands/
│           ├── EnvCheckCommand.php
│           ├── MakeControllerCommand.php
│           └── RouteListCommand.php
├── tests/
│   ├── bootstrap.php
│   ├── Console/
│   │   └── MakeControllerCommandTest.php
│   └── Support/
│       ├── JsonResponseTest.php
│       └── YafExceptionResolverTest.php
├── .env.example
├── .env.testing.example
├── composer.json
├── docker-compose.yml
├── phpunit.xml
├── README.md
└── crab
```

## 6. 开发任务总览

| 任务 | 名称 | 产出 | 验收方式 |
| --- | --- | --- | --- |
| Task 1 | 初始化 Composer 与目录骨架 | 基础文件、目录、autoload | `composer validate` |
| Task 2 | Yaf 入口与 Bootstrap | `public/index.php`、`Bootstrap.php` | `php -l` 语法检查 |
| Task 3 | 配置与环境变量 | `.env.example`、配置读取类 | `php crab env:check` 部分可运行 |
| Task 4 | JSON 响应与异常 | 响应辅助类、异常类、ErrorController | PHPUnit |
| Task 5 | 路由与 API 示例 | `/health`、`/v1/ping` | 浏览器或 curl |
| Task 6 | 日志与请求 ID | Monolog、request_id、请求日志插件 | 日志文件存在 |
| Task 7 | CLI 基础命令 | `list`、`env:check`、`route:list` | CLI 输出 |
| Task 8 | 控制器生成器 | `make:controller` | 文件生成和单测 |
| Task 9 | Docker 环境 | Nginx、PHP-FPM、Yaf 扩展 | docker compose 启动 |
| Task 10 | 测试与文档 | PHPUnit、README、快速开始 | 测试命令通过 |

---

## Task 1: 初始化 Composer 与目录骨架

**Files:**

- Create: `composer.json`
- Create: `.env.example`
- Create: `.env.testing.example`
- Create: `application/Bootstrap.php`
- Create: `application/controllers/.gitkeep`
- Create: `application/modules/Api/controllers/V1/.gitkeep`
- Create: `application/plugins/.gitkeep`
- Create: `application/library/.gitkeep`
- Create: `bin/.gitkeep`
- Create: `conf/.gitkeep`
- Create: `config/.gitkeep`
- Create: `database/migrations/.gitkeep`
- Create: `database/seeds/.gitkeep`
- Create: `docker/.gitkeep`
- Create: `public/.gitkeep`
- Create: `runtime/cache/.gitkeep`
- Create: `runtime/logs/.gitkeep`
- Create: `src/.gitkeep`
- Create: `tests/.gitkeep`

- [x] **Step 1: 创建目录结构**

Run:

```bash
mkdir -p application/controllers application/modules/Api/controllers/V1 application/plugins application/library bin conf config database/migrations database/seeds docker public runtime/cache runtime/logs src tests
touch application/controllers/.gitkeep application/modules/Api/controllers/V1/.gitkeep application/plugins/.gitkeep application/library/.gitkeep bin/.gitkeep conf/.gitkeep config/.gitkeep database/migrations/.gitkeep database/seeds/.gitkeep docker/.gitkeep public/.gitkeep runtime/cache/.gitkeep runtime/logs/.gitkeep src/.gitkeep tests/.gitkeep
```

Expected:

```text
命令退出码为 0，目录全部创建成功。
```

- [x] **Step 2: 创建 PHP 7.0 兼容的 Composer 配置**

Create `composer.json`:

```json
{
  "name": "crab-taoist/crab-taoist",
  "description": "A lightweight Yaf + Composer API scaffold for production-ready services.",
  "type": "project",
  "license": "MIT",
  "require": {
    "php": ">=7.0",
    "vlucas/phpdotenv": "^2.6",
    "monolog/monolog": "^1.27",
    "symfony/console": "^3.4"
  },
  "require-dev": {
    "phpunit/phpunit": "^6.5"
  },
  "autoload": {
    "psr-4": {
      "Crab\\": "src/",
      "Support\\": "application/library/Support/"
    }
  },
  "autoload-dev": {
    "psr-4": {
      "Tests\\": "tests/"
    }
  },
  "scripts": {
    "test": "phpunit"
  },
  "config": {
    "sort-packages": true
  }
}
```

- [x] **Step 3: 创建环境变量示例**

Create `.env.example`:

```dotenv
APP_NAME=Crab Taoist
APP_ENV=local
APP_DEBUG=true
APP_KEY=base64-change-this-key
APP_TIMEZONE=Asia/Shanghai
APP_VERSION=0.1.0

LOG_CHANNEL=daily
LOG_LEVEL=debug
LOG_PATH=runtime/logs

DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=crab_taoist
DB_USERNAME=crab
DB_PASSWORD=secret

REDIS_HOST=redis
REDIS_PORT=6379
REDIS_PASSWORD=
REDIS_DATABASE=0
```

Create `.env.testing.example`:

```dotenv
APP_NAME=Crab Taoist Testing
APP_ENV=testing
APP_DEBUG=true
APP_KEY=base64-testing-key
APP_TIMEZONE=Asia/Shanghai
APP_VERSION=0.1.0

LOG_CHANNEL=single
LOG_LEVEL=debug
LOG_PATH=runtime/logs
```

- [x] **Step 4: 安装依赖并验证 Composer 配置**

Run:

```bash
composer validate && composer install
```

Expected:

```text
./composer.json is valid
Generating autoload files
```

---

## Task 2: 实现 Yaf 入口与 Bootstrap

**Files:**

- Create: `public/index.php`
- Modify: `application/Bootstrap.php`
- Create: `conf/application.ini`

- [x] **Step 1: 创建 Yaf 入口文件**

Create `public/index.php`:

```php
<?php

define('BASE_PATH', dirname(__DIR__));
define('APPLICATION_PATH', BASE_PATH . '/application');

$autoload = BASE_PATH . '/vendor/autoload.php';
if (is_file($autoload)) {
    require $autoload;
}

$app = new Yaf_Application(BASE_PATH . '/conf/application.ini');
$app->bootstrap()->run();
```

- [x] **Step 2: 创建 Yaf 基础配置**

Create `conf/application.ini`:

```ini
[common]
application.directory = APPLICATION_PATH
application.dispatcher.catchException = 1
application.modules = "Index,Api"
application.view.ext = "phtml"
application.bootstrap = APPLICATION_PATH "/Bootstrap.php"

[local : common]
application.showErrors = 1

[testing : common]
application.showErrors = 1

[production : common]
application.showErrors = 0
```

- [x] **Step 3: 创建 Bootstrap 初始化流程**

Update `application/Bootstrap.php`:

```php
<?php

use Support\Env;
use Support\Logging\LoggerFactory;
use Support\Routing\RouteLoader;

class Bootstrap extends Yaf_Bootstrap_Abstract
{
    public function _initEnv(Yaf_Dispatcher $dispatcher)
    {
        Env::load(BASE_PATH);
        date_default_timezone_set(Env::get('APP_TIMEZONE', 'Asia/Shanghai'));
    }

    public function _initLogger(Yaf_Dispatcher $dispatcher)
    {
        LoggerFactory::boot(BASE_PATH);
    }

    public function _initRoutes(Yaf_Dispatcher $dispatcher)
    {
        RouteLoader::load($dispatcher->getRouter(), BASE_PATH . '/conf/routes.php');
    }
}
```

- [x] **Step 4: 运行语法检查**

Run:

```bash
php -l public/index.php && php -l application/Bootstrap.php
```

Expected:

```text
No syntax errors detected in public/index.php
No syntax errors detected in application/Bootstrap.php
```

---

## Task 3: 实现配置与环境变量读取

**Files:**

- Create: `application/library/Support/Env.php`
- Create: `application/library/Support/Config.php`
- Create: `config/app.php`
- Create: `config/logging.php`
- Create: `config/database.php`
- Create: `config/cache.php`

- [x] **Step 1: 实现 Env 工具类**

Create `application/library/Support/Env.php`:

```php
<?php

namespace Support;

use Dotenv\Dotenv;

class Env
{
    private static $loaded = false;

    public static function load($basePath)
    {
        if (self::$loaded) {
            return;
        }

        if (is_file($basePath . '/.env')) {
            $dotenv = new Dotenv($basePath);
            $dotenv->load();
        }

        self::$loaded = true;
    }

    public static function get($key, $default = null)
    {
        $value = getenv($key);

        if ($value === false && isset($_ENV[$key])) {
            $value = $_ENV[$key];
        }

        if ($value === false && isset($_SERVER[$key])) {
            $value = $_SERVER[$key];
        }

        if ($value === false) {
            return $default;
        }

        if ($value === 'true') {
            return true;
        }

        if ($value === 'false') {
            return false;
        }

        return $value;
    }
}
```

- [x] **Step 2: 实现 Config 工具类**

Create `application/library/Support/Config.php`:

```php
<?php

namespace Support;

class Config
{
    private static $items = array();

    public static function get($key, $default = null)
    {
        $segments = explode('.', $key);
        $file = array_shift($segments);

        if (!isset(self::$items[$file])) {
            $path = BASE_PATH . '/config/' . $file . '.php';
            self::$items[$file] = is_file($path) ? require $path : array();
        }

        $value = self::$items[$file];
        foreach ($segments as $segment) {
            if (!is_array($value) || !array_key_exists($segment, $value)) {
                return $default;
            }
            $value = $value[$segment];
        }

        return $value;
    }
}
```

- [x] **Step 3: 创建基础配置文件**

Create `config/app.php`:

```php
<?php

use Support\Env;

return array(
    'name' => Env::get('APP_NAME', 'Crab Taoist'),
    'env' => Env::get('APP_ENV', 'local'),
    'debug' => Env::get('APP_DEBUG', false),
    'key' => Env::get('APP_KEY', ''),
    'timezone' => Env::get('APP_TIMEZONE', 'Asia/Shanghai'),
    'version' => Env::get('APP_VERSION', '0.1.0'),
);
```

Create `config/logging.php`:

```php
<?php

use Support\Env;

return array(
    'channel' => Env::get('LOG_CHANNEL', 'daily'),
    'level' => Env::get('LOG_LEVEL', 'debug'),
    'path' => BASE_PATH . '/' . Env::get('LOG_PATH', 'runtime/logs'),
);
```

Create `config/database.php`:

```php
<?php

use Support\Env;

return array(
    'default' => array(
        'host' => Env::get('DB_HOST', '127.0.0.1'),
        'port' => Env::get('DB_PORT', '3306'),
        'database' => Env::get('DB_DATABASE', 'crab_taoist'),
        'username' => Env::get('DB_USERNAME', 'root'),
        'password' => Env::get('DB_PASSWORD', ''),
    ),
);
```

Create `config/cache.php`:

```php
<?php

use Support\Env;

return array(
    'redis' => array(
        'host' => Env::get('REDIS_HOST', '127.0.0.1'),
        'port' => Env::get('REDIS_PORT', '6379'),
        'password' => Env::get('REDIS_PASSWORD', ''),
        'database' => Env::get('REDIS_DATABASE', '0'),
    ),
);
```

- [x] **Step 4: 验证配置文件语法**

Run:

```bash
php -l application/library/Support/Env.php && php -l application/library/Support/Config.php && php -l config/app.php && php -l config/logging.php && php -l config/database.php && php -l config/cache.php
```

Expected:

```text
No syntax errors detected
```

---

## Task 4: 实现 JSON 响应与全局异常

**Files:**

- Create: `application/library/Support/Http/JsonResponse.php`
- Create: `application/library/Support/Exceptions/ApiException.php`
- Create: `application/library/Support/Exceptions/ValidationException.php`
- Create: `application/controllers/Error.php`
- Create: `tests/bootstrap.php`
- Create: `tests/Support/JsonResponseTest.php`
- Create: `phpunit.xml`

- [x] **Step 1: 实现 JsonResponse**

Create `application/library/Support/Http/JsonResponse.php`:

```php
<?php

namespace Support\Http;

class JsonResponse
{
    public static function success($data = array(), $message = 'success', $code = 200, $httpStatus = 200)
    {
        return self::make($code, $message, $data, $httpStatus);
    }

    public static function error($message = 'error', $code = 500, $data = array(), $httpStatus = 500)
    {
        return self::make($code, $message, $data, $httpStatus);
    }

    public static function paginate($items, $page, $pageSize, $total)
    {
        return self::success(array(
            'items' => $items,
            'pagination' => array(
                'page' => (int) $page,
                'page_size' => (int) $pageSize,
                'total' => (int) $total,
            ),
        ));
    }

    public static function make($code, $message, $data, $httpStatus)
    {
        if (!headers_sent()) {
            http_response_code($httpStatus);
            header('Content-Type: application/json; charset=utf-8');
        }

        return json_encode(array(
            'code' => $code,
            'message' => $message,
            'data' => $data,
            'request_id' => isset($_SERVER['HTTP_X_REQUEST_ID']) ? $_SERVER['HTTP_X_REQUEST_ID'] : '',
        ), JSON_UNESCAPED_UNICODE);
    }
}
```

- [x] **Step 2: 实现业务异常类**

Create `application/library/Support/Exceptions/ApiException.php`:

```php
<?php

namespace Support\Exceptions;

class ApiException extends \Exception
{
    private $httpStatus;
    private $payload;

    public function __construct($message, $code = 500, $httpStatus = 500, $payload = array())
    {
        parent::__construct($message, $code);
        $this->httpStatus = $httpStatus;
        $this->payload = $payload;
    }

    public function getHttpStatus()
    {
        return $this->httpStatus;
    }

    public function getPayload()
    {
        return $this->payload;
    }
}
```

Create `application/library/Support/Exceptions/ValidationException.php`:

```php
<?php

namespace Support\Exceptions;

class ValidationException extends ApiException
{
    public function __construct($message, $errors = array())
    {
        parent::__construct($message, 422, 422, array('errors' => $errors));
    }
}
```

- [x] **Step 3: 实现 ErrorController**

Create `application/controllers/Error.php`:

```php
<?php

use Support\Config;
use Support\Exceptions\ApiException;
use Support\Http\JsonResponse;
use Support\Logging\LoggerFactory;

class ErrorController extends Yaf_Controller_Abstract
{
    public function errorAction($exception)
    {
        $this->getResponse()->clearBody();

        $debug = Config::get('app.debug', false);
        $message = '服务器内部错误';
        $code = 500;
        $httpStatus = 500;
        $data = array();

        if ($exception instanceof ApiException) {
            $message = $exception->getMessage();
            $code = $exception->getCode();
            $httpStatus = $exception->getHttpStatus();
            $data = $exception->getPayload();
        } elseif ($debug) {
            $message = $exception->getMessage();
            $data = array(
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => explode("\n", $exception->getTraceAsString()),
            );
        }

        LoggerFactory::error($exception->getMessage(), array(
            'exception' => get_class($exception),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
        ));

        echo JsonResponse::error($message, $code, $data, $httpStatus);
        return false;
    }
}
```

- [x] **Step 4: 添加 JsonResponse 单元测试**

Create `tests/bootstrap.php`:

```php
<?php

define('BASE_PATH', dirname(__DIR__));
require BASE_PATH . '/vendor/autoload.php';
```

Create `phpunit.xml`:

```xml
<?xml version="1.0" encoding="UTF-8"?>
<phpunit bootstrap="tests/bootstrap.php" colors="true">
    <testsuites>
        <testsuite name="Crab Taoist Test Suite">
            <directory>tests</directory>
        </testsuite>
    </testsuites>
</phpunit>
```

Create `tests/Support/JsonResponseTest.php`:

```php
<?php

namespace Tests\Support;

use PHPUnit\Framework\TestCase;
use Support\Http\JsonResponse;

class JsonResponseTest extends TestCase
{
    public function testSuccessResponseContainsStandardFields()
    {
        $json = JsonResponse::success(array('pong' => true));
        $data = json_decode($json, true);

        $this->assertSame(200, $data['code']);
        $this->assertSame('success', $data['message']);
        $this->assertSame(array('pong' => true), $data['data']);
        $this->assertArrayHasKey('request_id', $data);
    }

    public function testErrorResponseContainsMessageAndCode()
    {
        $json = JsonResponse::error('失败', 400, array(), 400);
        $data = json_decode($json, true);

        $this->assertSame(400, $data['code']);
        $this->assertSame('失败', $data['message']);
    }
}
```

- [x] **Step 5: 运行响应测试**

Run:

```bash
vendor/bin/phpunit tests/Support/JsonResponseTest.php
```

Expected:

```text
OK (2 tests
```

---

## Task 5: 实现路由封装与 API 示例

**Files:**

- Create: `application/library/Support/Routing/RouteLoader.php`
- Create: `conf/routes.php`
- Create: `application/modules/Api/controllers/V1/Health.php`
- Create: `application/modules/Api/controllers/V1/Ping.php`

- [x] **Step 1: 创建路由配置**

Create `conf/routes.php`:

```php
<?php

return array(
    array(
        'name' => 'health',
        'type' => 'rewrite',
        'match' => '/health',
        'route' => array(
            'module' => 'Api',
            'controller' => 'V1_Health',
            'action' => 'index',
        ),
        'methods' => array('GET'),
        'auth' => false,
    ),
    array(
        'name' => 'api.v1.ping',
        'type' => 'rewrite',
        'match' => '/v1/ping',
        'route' => array(
            'module' => 'Api',
            'controller' => 'V1_Ping',
            'action' => 'index',
        ),
        'methods' => array('GET'),
        'auth' => false,
    ),
);
```

- [x] **Step 2: 实现 RouteLoader**

Create `application/library/Support/Routing/RouteLoader.php`:

```php
<?php

namespace Support\Routing;

class RouteLoader
{
    public static function load(\Yaf_Router $router, $path)
    {
        if (!is_file($path)) {
            return;
        }

        $routes = require $path;
        foreach ($routes as $definition) {
            if (!isset($definition['type']) || $definition['type'] !== 'rewrite') {
                continue;
            }

            $route = new \Yaf_Route_Rewrite($definition['match'], $definition['route']);
            $router->addRoute($definition['name'], $route);
        }
    }

    public static function all($path)
    {
        return is_file($path) ? require $path : array();
    }
}
```

- [x] **Step 3: 创建健康检查控制器**

Create `application/modules/Api/controllers/V1/Health.php`:

```php
<?php

use Support\Config;
use Support\Http\JsonResponse;

class V1_HealthController extends Yaf_Controller_Abstract
{
    public function indexAction()
    {
        $this->getResponse()->clearBody();

        echo JsonResponse::success(array(
            'status' => 'ok',
            'version' => Config::get('app.version', '0.1.0'),
            'timestamp' => time(),
        ));

        return false;
    }
}
```

- [x] **Step 4: 创建 Ping 示例控制器**

Create `application/modules/Api/controllers/V1/Ping.php`:

```php
<?php

use Support\Http\JsonResponse;

class V1_PingController extends Yaf_Controller_Abstract
{
    public function indexAction()
    {
        $this->getResponse()->clearBody();
        echo JsonResponse::success(array('pong' => true));
        return false;
    }
}
```

- [x] **Step 5: 运行语法检查**

Run:

```bash
php -l application/library/Support/Routing/RouteLoader.php && php -l conf/routes.php && php -l application/modules/Api/controllers/V1/Health.php && php -l application/modules/Api/controllers/V1/Ping.php
```

Expected:

```text
No syntax errors detected
```

---

## Task 6: 实现日志与请求 ID

**Files:**

- Create: `application/library/Support/Logging/LoggerFactory.php`
- Create: `application/plugins/RequestIdPlugin.php`
- Create: `application/plugins/RequestLogPlugin.php`
- Create: `conf/plugins.php`
- Modify: `application/Bootstrap.php`

- [x] **Step 1: 实现 LoggerFactory**

Create `application/library/Support/Logging/LoggerFactory.php`:

```php
<?php

namespace Support\Logging;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Support\Config;

class LoggerFactory
{
    private static $loggers = array();

    public static function boot($basePath)
    {
        $path = Config::get('logging.path', $basePath . '/runtime/logs');
        if (!is_dir($path)) {
            mkdir($path, 0775, true);
        }
    }

    public static function get($channel = 'app')
    {
        if (isset(self::$loggers[$channel])) {
            return self::$loggers[$channel];
        }

        $logger = new Logger($channel);
        $path = Config::get('logging.path', BASE_PATH . '/runtime/logs');
        $logger->pushHandler(new StreamHandler($path . '/' . $channel . '.log', Logger::DEBUG));

        self::$loggers[$channel] = $logger;
        return $logger;
    }

    public static function info($message, array $context = array())
    {
        self::get('app')->info($message, $context);
    }

    public static function error($message, array $context = array())
    {
        self::get('error')->error($message, $context);
    }
}
```

- [x] **Step 2: 实现请求 ID 插件**

Create `application/plugins/RequestIdPlugin.php`:

```php
<?php

class RequestIdPlugin extends Yaf_Plugin_Abstract
{
    public function routerStartup(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response)
    {
        if (empty($_SERVER['HTTP_X_REQUEST_ID'])) {
            $_SERVER['HTTP_X_REQUEST_ID'] = bin2hex(openssl_random_pseudo_bytes(8));
        }
    }
}
```

- [x] **Step 3: 实现请求日志插件**

Create `application/plugins/RequestLogPlugin.php`:

```php
<?php

use Support\Logging\LoggerFactory;

class RequestLogPlugin extends Yaf_Plugin_Abstract
{
    private $startedAt;

    public function routerStartup(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response)
    {
        $this->startedAt = microtime(true);
    }

    public function dispatchLoopShutdown(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response)
    {
        $duration = $this->startedAt ? round((microtime(true) - $this->startedAt) * 1000, 2) : 0;

        LoggerFactory::get('request')->info('request handled', array(
            'method' => isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'CLI',
            'uri' => isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '',
            'duration_ms' => $duration,
            'request_id' => isset($_SERVER['HTTP_X_REQUEST_ID']) ? $_SERVER['HTTP_X_REQUEST_ID'] : '',
        ));
    }
}
```

- [x] **Step 4: 注册插件**

Create `conf/plugins.php`:

```php
<?php

return array(
    'RequestIdPlugin',
    'RequestLogPlugin',
);
```

Update `application/Bootstrap.php` by adding:

```php
    public function _initPlugins(Yaf_Dispatcher $dispatcher)
    {
        $plugins = is_file(BASE_PATH . '/conf/plugins.php') ? require BASE_PATH . '/conf/plugins.php' : array();
        foreach ($plugins as $pluginClass) {
            $pluginFile = APPLICATION_PATH . '/plugins/' . $pluginClass . '.php';
            if (!class_exists($pluginClass) && is_file($pluginFile)) {
                require_once $pluginFile;
            }

            if (class_exists($pluginClass)) {
                $dispatcher->registerPlugin(new $pluginClass());
            }
        }
    }
```

- [x] **Step 5: 验证日志相关文件语法**

Run:

```bash
php -l application/library/Support/Logging/LoggerFactory.php && php -l application/plugins/RequestIdPlugin.php && php -l application/plugins/RequestLogPlugin.php && php -l conf/plugins.php && php -l application/Bootstrap.php
```

Expected:

```text
No syntax errors detected
```

---

## Task 7: 实现 CLI 基础命令

**Files:**

- Create: `crab`
- Create: `bin/crab`
- Create: `src/Console/Application.php`
- Create: `src/Console/Commands/EnvCheckCommand.php`
- Create: `src/Console/Commands/RouteListCommand.php`

- [x] **Step 1: 创建根目录 CLI 入口**

Create `crab`:

```php
#!/usr/bin/env php
<?php

define('BASE_PATH', __DIR__);
define('APPLICATION_PATH', BASE_PATH . '/application');

require BASE_PATH . '/vendor/autoload.php';

$application = new Crab\Console\Application();
$application->run();
```

Create `bin/crab`:

```php
#!/usr/bin/env php
<?php

require dirname(__DIR__) . '/crab';
```

Run:

```bash
chmod +x crab bin/crab
```

Expected:

```text
命令退出码为 0，crab 与 bin/crab 具备执行权限。
```

- [x] **Step 2: 创建 Console Application**

Create `src/Console/Application.php`:

```php
<?php

namespace Crab\Console;

use Crab\Console\Commands\EnvCheckCommand;
use Crab\Console\Commands\RouteListCommand;
use Symfony\Component\Console\Application as SymfonyApplication;

class Application extends SymfonyApplication
{
    public function __construct()
    {
        parent::__construct('Crab Taoist CLI', '0.1.0');

        $this->add(new EnvCheckCommand());
        $this->add(new RouteListCommand());
    }
}
```

- [x] **Step 3: 创建 env:check 命令**

Create `src/Console/Commands/EnvCheckCommand.php`:

```php
<?php

namespace Crab\Console\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class EnvCheckCommand extends Command
{
    protected function configure()
    {
        $this->setName('env:check')->setDescription('Check Crab Taoist runtime requirements.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $failed = 0;
        $checks = array(
            'PHP >= 7.0' => version_compare(PHP_VERSION, '7.0.0', '>='),
            'Yaf extension' => extension_loaded('yaf'),
            'JSON extension' => extension_loaded('json'),
            'OpenSSL extension' => extension_loaded('openssl'),
            'Composer autoload' => is_file(BASE_PATH . '/vendor/autoload.php'),
            '.env file' => is_file(BASE_PATH . '/.env'),
            'runtime/logs writable' => is_writable(BASE_PATH . '/runtime/logs'),
            'runtime/cache writable' => is_writable(BASE_PATH . '/runtime/cache'),
        );

        foreach ($checks as $name => $passed) {
            $output->writeln(sprintf('%s %s', $passed ? '[OK]' : '[FAIL]', $name));
            if (!$passed) {
                $failed++;
            }
        }

        if ($failed > 0) {
            $output->writeln('Environment check failed. Please fix failed items before running the application.');
            return 1;
        }

        $output->writeln('Environment check passed.');
        return 0;
    }
}
```

- [x] **Step 4: 创建 route:list 命令**

Create `src/Console/Commands/RouteListCommand.php`:

```php
<?php

namespace Crab\Console\Commands;

use Support\Routing\RouteLoader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RouteListCommand extends Command
{
    protected function configure()
    {
        $this->setName('route:list')->setDescription('List configured routes.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $routes = RouteLoader::all(BASE_PATH . '/conf/routes.php');

        foreach ($routes as $route) {
            $methods = isset($route['methods']) ? implode(',', $route['methods']) : 'ANY';
            $output->writeln(sprintf('%-16s %-8s %s', $route['name'], $methods, $route['match']));
        }

        return 0;
    }
}
```

- [x] **Step 5: 验证 CLI 命令列表**

Run:

```bash
php crab list
```

Expected:

```text
env:check
route:list
```

---

## Task 8: 实现控制器生成器

**Files:**

- Create: `src/Console/Commands/MakeControllerCommand.php`
- Modify: `src/Console/Application.php`
- Create: `tests/Console/MakeControllerCommandTest.php`

- [x] **Step 1: 创建 make:controller 命令**

Create `src/Console/Commands/MakeControllerCommand.php`:

```php
<?php

namespace Crab\Console\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class MakeControllerCommand extends Command
{
    protected function configure()
    {
        $this->setName('make:controller')
            ->setDescription('Create a Yaf API controller.')
            ->addArgument('name', InputArgument::REQUIRED, 'Controller name, for example User')
            ->addOption('module', null, InputOption::VALUE_OPTIONAL, 'Yaf module name', 'Api')
            ->addOption('api-version', null, InputOption::VALUE_OPTIONAL, 'API version directory', 'V1');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = ucfirst($input->getArgument('name'));
        $module = ucfirst($input->getOption('module'));
        $version = strtoupper($input->getOption('api-version'));

        $directory = BASE_PATH . '/application/modules/' . $module . '/controllers/' . $version;
        $path = $directory . '/' . $name . '.php';

        if (!is_dir($directory)) {
            mkdir($directory, 0775, true);
        }

        if (is_file($path)) {
            $output->writeln('Controller already exists: ' . $path);
            return 1;
        }

        $class = $version . '_' . $name . 'Controller';
        $content = $this->renderController($class);
        file_put_contents($path, $content);

        $output->writeln('Controller created: ' . $path);
        return 0;
    }

    public function renderController($class)
    {
        return "<?php\n\n"
            . "use Support\\Http\\JsonResponse;\n\n"
            . "class " . $class . " extends Yaf_Controller_Abstract\n"
            . "{\n"
            . "    public function indexAction()\n"
            . "    {\n"
            . "        \$this->getResponse()->clearBody();\n"
            . "        echo JsonResponse::success(array());\n"
            . "        return false;\n"
            . "    }\n"
            . "}\n";
    }
}
```

- [x] **Step 2: 注册 make:controller 命令**

Update `src/Console/Application.php`:

```php
<?php

namespace Crab\Console;

use Crab\Console\Commands\EnvCheckCommand;
use Crab\Console\Commands\MakeControllerCommand;
use Crab\Console\Commands\RouteListCommand;
use Symfony\Component\Console\Application as SymfonyApplication;

class Application extends SymfonyApplication
{
    public function __construct()
    {
        parent::__construct('Crab Taoist CLI', '0.1.0');

        $this->add(new EnvCheckCommand());
        $this->add(new RouteListCommand());
        $this->add(new MakeControllerCommand());
    }
}
```

- [x] **Step 3: 创建生成器单元测试**

Create `tests/Console/MakeControllerCommandTest.php`:

```php
<?php

namespace Tests\Console;

use Crab\Console\Commands\MakeControllerCommand;
use PHPUnit\Framework\TestCase;

class MakeControllerCommandTest extends TestCase
{
    public function testRenderControllerCreatesYafControllerClass()
    {
        $command = new MakeControllerCommand();
        $content = $command->renderController('V1_UserController');

        $this->assertContains('class V1_UserController extends Yaf_Controller_Abstract', $content);
        $this->assertContains('JsonResponse::success', $content);
        $this->assertContains('indexAction', $content);
    }
}
```

- [x] **Step 4: 验证控制器生成命令**

Run:

```bash
php crab make:controller User --module=Api --api-version=V1
```

Expected:

```text
Controller created: 
```

Then verify:

```bash
php -l application/modules/Api/controllers/V1/User.php
```

Expected:

```text
No syntax errors detected in application/modules/Api/controllers/V1/User.php
```

- [x] **Step 5: 运行生成器测试**

Run:

```bash
vendor/bin/phpunit tests/Console/MakeControllerCommandTest.php
```

Expected:

```text
OK (1 test
```

---

## Task 9: 实现 Docker 本地运行环境

**Files:**

- Create: `docker/php/Dockerfile`
- Create: `docker/php/php.ini`
- Create: `docker/nginx/default.conf`
- Create: `docker-compose.yml`

- [x] **Step 1: 创建 PHP-FPM Dockerfile**

Create `docker/php/Dockerfile`:

```dockerfile
FROM php:7.4-fpm

RUN apt-get update \
    && apt-get install -y --no-install-recommends git unzip libzip-dev \
    && docker-php-ext-install pdo_mysql zip \
    && pecl install yaf \
    && docker-php-ext-enable yaf \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY docker/php/php.ini /usr/local/etc/php/conf.d/crab-taoist.ini

WORKDIR /var/www/html
```

- [x] **Step 2: 创建 PHP 配置**

Create `docker/php/php.ini`:

```ini
date.timezone=Asia/Shanghai
display_errors=On
log_errors=On
opcache.enable=1
opcache.enable_cli=0
yaf.use_namespace=0
yaf.environ=local
```

- [x] **Step 3: 创建 Nginx 配置**

Create `docker/nginx/default.conf`:

```nginx
server {
    listen 80;
    server_name localhost;
    root /var/www/html/public;
    index index.php index.html;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass php:9000;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_param APPLICATION_ENV local;
    }
}
```

- [x] **Step 4: 创建 docker-compose 配置**

Create `docker-compose.yml`:

```yaml
version: "3.8"

services:
  php:
    build:
      context: .
      dockerfile: docker/php/Dockerfile
    working_dir: /var/www/html
    volumes:
      - ./:/var/www/html

  nginx:
    image: nginx:1.25-alpine
    ports:
      - "8080:80"
    volumes:
      - ./:/var/www/html
      - ./docker/nginx/default.conf:/etc/nginx/conf.d/default.conf
    depends_on:
      - php

  mysql:
    image: mysql:5.7
    environment:
      MYSQL_DATABASE: crab_taoist
      MYSQL_USER: crab
      MYSQL_PASSWORD: secret
      MYSQL_ROOT_PASSWORD: root
    ports:
      - "33060:3306"

  redis:
    image: redis:6-alpine
    ports:
      - "63790:6379"
```

- [x] **Step 5: 验证 Docker 启动**

Run:

```bash
docker compose up -d --build
```

Expected:

```text
nginx
php
mysql
redis
```

Run:

```bash
docker compose exec php php -m | rg '^yaf$|^Yaf$'
```

Expected:

```text
yaf
```

Run:

```bash
curl -s http://localhost:8080/health
```

Expected:

```json
{"code":200,"message":"success"
```

---

## Task 10: 完成测试、文档与 MVP 验收

**Files:**

- Modify: `README.md`
- Create: `docs/QuickStart.md`
- Create: `docs/CLI.md`
- Create: `docs/Deployment.md`
- Modify: `docs/MVP.md`

- [x] **Step 1: 创建 README**

Create or update `README.md`:

````markdown
# Crab Taoist 蟹道人

Crab Taoist 是一个基于 Yaf + Composer 的轻量级 API 快速开发脚手架，目标是在保留 Yaf C 扩展性能优势的同时，提供现代 PHP API 项目常用工程能力。

## 快速开始

```bash
composer install
cp .env.example .env
php crab env:check
docker compose up -d --build
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
````

- [x] **Step 2: 创建快速开始文档**

Create `docs/QuickStart.md`:

````markdown
# 快速开始

## 环境要求

- PHP 7.0+
- Composer
- Yaf PHP 扩展
- Docker 与 Docker Compose

## 本地启动

```bash
composer install
cp .env.example .env
php crab env:check
docker compose up -d --build
curl http://localhost:8080/health
curl http://localhost:8080/v1/ping
```
````

- [x] **Step 3: 创建 CLI 文档**

Create `docs/CLI.md`:

````markdown
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
````

- [x] **Step 4: 创建部署文档**

Create `docs/Deployment.md`:

````markdown
# 部署说明

## Docker 本地运行

```bash
docker compose up -d --build
curl http://localhost:8080/health
```

## 生产建议

- 使用 PHP-FPM + Nginx。
- 开启 opcache。
- 执行 `composer install --no-dev --optimize-autoloader`。
- 设置 `APP_ENV=production`。
- 设置 `APP_DEBUG=false`。
- 确保 `runtime/logs` 和 `runtime/cache` 可写。
````

- [x] **Step 5: 运行完整 MVP 验收命令**

Run:

```bash
composer validate
vendor/bin/phpunit
php crab list
php crab route:list
php crab env:check
docker compose up -d --build
curl -s http://localhost:8080/health
curl -s http://localhost:8080/v1/ping
```

Expected:

```text
composer.json is valid
PHPUnit OK
CLI commands are listed
Routes are listed
Environment check prints pass/fail items
Docker services start
/health returns JSON
/v1/ping returns JSON
```

---

## 7. MVP 完成定义

MVP 只有在以下条件全部满足时才视为完成：

1. `composer install` 可成功生成 autoload。
2. `php crab list`、`php crab env:check`、`php crab route:list`、`php crab make:controller` 均可执行。
3. `vendor/bin/phpunit` 测试通过。
4. Docker 环境可以构建并启动。
5. `/health` 和 `/v1/ping` 返回统一 JSON。
6. 异常响应统一为 JSON；不存在路由返回 404 JSON。
7. 日志可以写入 `runtime/logs`。
8. README 和 QuickStart 能指导新开发者启动项目。

## 8. MVP 后续迭代建议

MVP 交付后，建议按以下顺序继续实现 PRD 中的完整能力：

1. V1.1：参数验证、CORS 插件、JWT 鉴权、Redis Cache、数据库 Capsule 初始化。
2. V1.2：Phinx 迁移、限流插件、php-cs-fixer、phpstan、更多 CLI 生成器。
3. V1.3：OpenAPI 文档约定、请求签名、幂等锁、Prometheus 指标端点。
4. V1.4：队列 Worker、定时任务、生产部署模板增强。
