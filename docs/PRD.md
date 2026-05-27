# 蟹道人 Crab Taoist 产品需求文档

## 1. 文档信息

| 项目 | 内容 |
| --- | --- |
| 项目中文名 | 蟹道人 |
| 项目英文名 | Crab Taoist |
| 产品形态 | 基于 Yaf + Composer 的 API 快速开发脚手架模板 |
| 目标版本 | V1.0 |
| 适用场景 | 企业级 API 服务、内部系统接口层、中后台服务端接口、轻量级微服务 |
| PHP 版本 | PHP 7.0+ |
| 核心原则 | 轻封装、重整合、高性能、开箱即用、易部署 |

## 2. 背景与问题

Yaf 作为 PHP C 扩展框架，具备非常优秀的运行性能、低内存占用和稳定的请求分发能力，适合高并发、低延迟的 API 服务。但原生 Yaf 更偏底层和工程骨架，缺少现代 PHP 开发生态中常见的能力，例如 Composer 包管理、统一响应、参数验证、环境配置、日志、ORM、迁移、命令行生成器、Docker 本地开发环境等。

Crab Taoist 旨在保留 Yaf 的核心性能优势，不重写框架内核，不引入重型全栈框架，而是在 Yaf 原生机制之上整合 Composer 生态和企业 API 项目常用能力，形成一套可直接用于生产项目启动的快速开发脚手架。

## 3. 产品定位

Crab Taoist 是一套面向 API 服务开发的轻量级企业脚手架模板，帮助团队用 Yaf 快速搭建具备生产基础能力的 PHP 项目。

它不是一个新的全栈框架，也不试图替代 Yaf 的核心机制。它的价值在于：

- 用标准项目结构降低 Yaf 项目启动成本。
- 用 Composer 生态补齐现代 PHP 开发体验。
- 用少量轻封装统一 API 项目的工程规范。
- 用 Docker 和环境检测提升部署与交付一致性。
- 用命令行工具提升控制器、模型、命令等常见代码生成效率。

## 4. 目标与非目标

### 4.1 产品目标

1. 提供一套开箱即用的 Yaf API 服务模板，初始化后即可运行示例接口。
2. 支持 PHP 7.0+，兼容主流 Linux 服务器、Docker、本地开发环境。
3. 保留 Yaf 原生路由、Bootstrap、Plugin、Controller、Module 等核心机制。
4. 通过 Composer 集成常用企业级组件，包括环境变量、日志、验证、ORM、Redis、JWT、迁移、测试和代码质量工具。
5. 提供命令行工具，支持 `php crab make:controller` 等快速开发命令。
6. 提供多环境配置、环境检测、健康检查和 Docker 部署能力。
7. 面向 API 服务优化统一请求、响应、异常、鉴权、跨域、日志等基础能力。

### 4.2 非目标

1. 不实现类似 Laravel、Symfony 的完整重型框架内核。
2. 不引入复杂 Service Container、Repository、Event Bus 等非必要抽象。
3. 不替换 Yaf 原生请求分发、路由和控制器生命周期。
4. 不强制业务使用 ORM，允许直接使用查询构造器或原生数据库连接。
5. 不内置复杂后台管理系统、前端工程、权限管理 UI。
6. 不在 V1.0 阶段实现分布式链路追踪、服务治理、微服务注册发现等大型平台能力。

## 5. 用户与使用场景

### 5.1 目标用户

- PHP 后端开发者：希望使用 Yaf 的性能，同时获得现代 PHP 开发便利性。
- API 服务团队：需要快速搭建企业级接口服务，并保持项目规范一致。
- 中小型技术团队：希望避免重型框架开销，降低服务部署和维护复杂度。
- 运维与 DevOps 工程师：需要项目具备 Docker、健康检查、日志输出和多环境配置能力。

### 5.2 典型场景

1. 新 API 服务初始化：团队基于模板创建项目，配置 `.env` 后即可启动开发。
2. 版本化接口开发：开发者通过 `php crab make:controller User --module=Api --version=V1` 快速创建控制器。
3. 企业接口鉴权：通过内置 JWT 插件完成 Token 校验和用户上下文注入。
4. 容器化交付：通过 `docker-compose up -d` 启动 Nginx、PHP-FPM、MySQL、Redis。
5. 生产健康探测：负载均衡或 K8s 通过 `/health` 检查服务存活与依赖状态。
6. 多环境发布：开发、测试、预发、生产通过不同 `.env` 和 Yaf INI 分段配置切换。

## 6. 产品范围

### 6.1 V1.0 必须交付范围

- 标准项目骨架与目录结构。
- Yaf 与 Composer 自动加载整合。
- 多环境配置与环境检测。
- 基于 Yaf Router 的路由封装。
- API 统一请求、响应与异常处理。
- 参数验证组件。
- 插件体系封装及常用插件。
- Monolog 日志系统。
- Eloquent ORM 与数据库基础配置。
- Redis 缓存组件。
- JWT 认证能力。
- CORS 跨域能力。
- API 版本化目录规范。
- CLI 快速开发工具。
- 数据库迁移能力。
- PHPUnit 测试基础设施。
- Docker 本地开发和部署示例。
- 健康检查端点。
- README、快速开始、配置说明、部署说明。

### 6.2 V1.0 可选增强

- 基于 Redis 的简单限流插件。
- Whoops 开发环境错误页。
- php-cs-fixer 和 phpstan 默认配置。
- 示例用户模块和示例迁移。
- OpenAPI 文档生成约定。

### 6.3 后续版本规划

- 队列 Worker 封装。
- 定时任务管理。
- OpenAPI 自动生成。
- Prometheus 指标端点。
- Swoole 或常驻进程模式适配。
- 更多代码生成命令。

## 7. 核心设计原则

1. 轻量优先：只做 API 项目高频能力封装，避免大而全。
2. 性能优先：不破坏 Yaf C 扩展的加载和分发性能。
3. 原生优先：路由、插件、异常、模块遵循 Yaf 原生机制。
4. 生态复用：通过 Composer 引入成熟组件，不重复造轮子。
5. 配置驱动：路由、插件、日志、数据库、缓存、鉴权均支持配置开关。
6. 生产可用：默认具备日志、异常、健康检查、安全配置、Docker 部署基础。
7. 渐进扩展：业务可按需启用组件，不强制引入全部能力。

## 8. 功能需求

### 8.1 项目骨架与目录结构

#### 需求描述

系统必须提供清晰、稳定、符合 Yaf 习惯的项目目录结构，同时兼容 Composer、Docker、测试、命令行和配置管理。

#### 功能要求

1. 必须包含 Yaf 标准入口 `public/index.php`。
2. 必须包含 `application/` 目录，用于承载 Bootstrap、modules、plugins、library 等 Yaf 相关代码。
3. 必须支持 `application/modules/Api/controllers/V1/` 这类 API 版本化目录。
4. 必须包含 `conf/` 目录，用于放置 Yaf INI 配置、路由配置、插件配置等。
5. 必须包含 `bin/` 或根目录 `crab` 命令入口，用于 CLI 工具。
6. 必须包含 `vendor/`，通过 Composer 管理第三方依赖。
7. 必须包含 `tests/`，用于单元测试和接口基础测试。
8. 必须包含 `docker/` 或等价目录，用于 Nginx、PHP、Supervisor 等部署配置。
9. 必须提供 `.env.example`、`.env.testing.example` 示例文件。

#### 验收标准

- 新项目拉取后执行 Composer 安装和环境配置即可访问默认 API。
- 默认目录结构可直接承载至少一个 Api V1 示例控制器。
- 项目结构不依赖 IDE 或本地绝对路径。

### 8.2 Composer 自动加载与 Yaf 整合

#### 需求描述

系统必须在不破坏 Yaf 原生类加载机制的前提下接入 Composer 自动加载。

#### 功能要求

1. Bootstrap 初始化阶段必须加载 `vendor/autoload.php`。
2. Composer PSR-4 自动加载必须可用于 `App\`、`Crab\`、`Support\` 等自定义命名空间。
3. Yaf 控制器、模型、插件等原生命名规则必须保持可用。
4. 不得通过重写 Yaf 核心加载逻辑实现 Composer 接入。
5. Composer 依赖必须按生产和开发依赖拆分。

#### 推荐依赖

| 类型 | 推荐包 | 说明 |
| --- | --- | --- |
| 环境变量 | `vlucas/phpdotenv` | 加载 `.env` |
| 日志 | `monolog/monolog` | 应用日志、错误日志、请求日志 |
| 验证 | `illuminate/validation` 或 `respect/validation` | 参数校验 |
| ORM | `illuminate/database` | Eloquent ORM 与查询构造器 |
| Redis | `predis/predis` 或 `ext-redis` | 缓存、限流、锁 |
| JWT | `firebase/php-jwt` | Token 签发和校验 |
| CLI | `symfony/console` | 命令行工具 |
| 迁移 | `robmorgan/phinx` | 数据库迁移 |
| 测试 | `phpunit/phpunit` | 单元测试 |
| 错误页 | `filp/whoops` | 开发环境异常展示 |

#### 兼容性要求

由于项目要求支持 PHP 7.0+，依赖版本必须提供兼容策略：

- PHP 7.0 至 7.1 环境使用兼容旧版本的 Composer 约束。
- PHP 7.2+ 或 8.x 环境允许升级到对应主版本。
- `composer.json` 必须明确 `php` 版本约束和关键依赖版本范围。
- 文档必须说明不同 PHP 版本下依赖选择限制。

### 8.3 多环境配置管理

#### 需求描述

系统必须支持开发、测试、预发、生产等多环境配置隔离，并允许通过环境变量覆盖敏感配置。

#### 功能要求

1. 必须支持 `.env` 文件加载。
2. 必须支持 `.env.testing` 测试环境配置。
3. 必须支持 `APP_ENV`、`APP_DEBUG`、`APP_KEY`、`APP_TIMEZONE` 等基础变量。
4. 必须通过 Yaf `application.ini` 分段配置加载不同环境配置。
5. 数据库、Redis、日志、JWT、CORS、插件开关必须支持环境变量配置。
6. 生产环境必须禁止输出详细异常堆栈给客户端。
7. 敏感配置不得提交到代码仓库，必须通过 example 文件说明。

#### 环境类型

| 环境 | APP_ENV | 说明 |
| --- | --- | --- |
| 本地开发 | local | 开启 debug，日志更详细 |
| 测试 | testing | 使用测试数据库和测试缓存 |
| 预发 | staging | 模拟生产配置，用于发布前验证 |
| 生产 | production | 关闭 debug，启用安全错误输出 |

### 8.4 环境检测

#### 需求描述

系统必须提供环境检测能力，帮助开发者和部署人员快速确认运行环境是否满足项目要求。

#### 功能要求

1. 必须提供 `php crab env:check` 命令。
2. 必须检测 PHP 版本是否满足 PHP 7.0+。
3. 必须检测 Yaf 扩展是否安装并启用。
4. 必须检测 Composer autoload 是否存在。
5. 必须检测关键 PHP 扩展，例如 PDO、OpenSSL、JSON、Mbstring、Redis 或 Curl。
6. 必须检测关键目录是否可写，例如 `runtime/`、`logs/`、`cache/`。
7. 必须检测 `.env` 是否存在，并提示从 `.env.example` 初始化。
8. 必须检测数据库和 Redis 连接状态。
9. 检测结果必须以清晰的命令行表格或列表输出。

#### 验收标准

- 环境缺失时命令返回非 0 状态码。
- 检测失败项必须给出明确修复建议。
- 生产环境可用于部署前预检查。

### 8.5 路由封装

#### 需求描述

路由能力必须基于 Yaf Router 封装，提供更易读、更适合 API 开发的配置方式，同时保留 Yaf 原生路由性能。

#### 功能要求

1. 必须支持静态路由、正则路由、Rewrite 路由。
2. 必须支持 RESTful 风格路径，例如 `/v1/users/:id`。
3. 必须支持 HTTP Method 到 Action 的映射。
4. 必须支持 API 版本前缀映射，例如 `/v1` 映射到 `Api` 模块 `V1` 控制器目录。
5. 必须支持路由组配置，例如统一前缀、统一中间插件策略。
6. 必须允许通过配置文件维护路由，不强制业务写复杂 DSL。
7. 必须支持健康检查路由绕过鉴权。

#### 示例行为

| 请求 | 映射目标 |
| --- | --- |
| `GET /v1/users` | `Api_V1_UserController::listAction()` |
| `POST /v1/users` | `Api_V1_UserController::createAction()` |
| `GET /v1/users/1` | `Api_V1_UserController::getAction()` |
| `PUT /v1/users/1` | `Api_V1_UserController::updateAction()` |
| `DELETE /v1/users/1` | `Api_V1_UserController::deleteAction()` |

### 8.6 统一请求封装

#### 需求描述

系统必须提供面向 API 的请求读取能力，减少控制器中重复处理 query、body、JSON、header 的代码。

#### 功能要求

1. 必须支持读取 Query 参数。
2. 必须支持读取 Form 参数。
3. 必须支持读取 JSON Body。
4. 必须支持读取 Header 和 Bearer Token。
5. 必须支持参数默认值。
6. 必须支持基础过滤能力，例如 trim、int、bool、array。
7. 不得破坏 Yaf 原生 `Yaf_Request_Http` 使用方式。

### 8.7 统一响应封装

#### 需求描述

系统必须提供统一 JSON 响应格式，减少业务接口返回结构差异。

#### 默认响应格式

```json
{
  "code": 200,
  "message": "success",
  "data": {},
  "request_id": "optional-request-id"
}
```

#### 功能要求

1. 必须提供成功响应方法。
2. 必须提供失败响应方法。
3. 必须支持自定义 HTTP 状态码。
4. 必须支持自定义业务状态码。
5. 必须支持分页响应结构。
6. 必须支持空数据响应。
7. 必须保证响应 `Content-Type` 为 `application/json; charset=utf-8`。

### 8.8 参数验证组件

#### 需求描述

系统必须集成成熟验证组件，并提供简单、统一的调用方式。

#### 功能要求

1. 必须支持 required、string、integer、numeric、array、email、url、in、min、max 等常用规则。
2. 必须支持自定义错误信息。
3. 必须支持批量参数验证。
4. 验证失败必须抛出统一业务异常。
5. 全局异常处理器必须将验证错误转换为 JSON 响应。
6. 必须支持控制器中快速调用。

#### 验收标准

- 控制器可以用少量代码完成参数校验。
- 验证失败响应结构稳定，可被前端统一处理。

### 8.9 全局异常处理

#### 需求描述

系统必须统一处理业务异常、验证异常、认证异常、系统异常和 Yaf 异常，保证 API 始终返回可预期的 JSON 错误。

#### 功能要求

1. 必须开启 Yaf 异常捕获机制。
2. 必须提供 `ErrorController` 或等价错误处理器。
3. 必须区分业务异常和系统异常。
4. 必须支持生产环境隐藏堆栈信息。
5. 必须支持开发环境输出详细错误信息。
6. 必须自动记录异常日志。
7. 必须为每次异常响应附带 request_id 或 trace_id。

#### 错误响应示例

```json
{
  "code": 422,
  "message": "参数验证失败",
  "data": {
    "errors": {
      "name": ["name 不能为空"]
    }
  },
  "request_id": "9f7c1b8a"
}
```

### 8.10 Yaf 插件系统封装

#### 需求描述

系统必须利用 Yaf Plugin 机制实现类似中间件的请求切面能力，用于鉴权、跨域、日志、限流等通用逻辑。

#### 功能要求

1. 必须支持插件注册和配置开关。
2. 必须支持按环境启用或禁用插件。
3. 必须支持插件执行顺序控制。
4. 必须内置 CORS 插件。
5. 必须内置 JWT 鉴权插件。
6. 必须内置请求日志插件。
7. 可选提供 Redis 限流插件。
8. 插件不得侵入业务控制器。

### 8.11 日志系统

#### 需求描述

系统必须集成生产可用日志能力，覆盖应用日志、请求日志、错误日志和 SQL 日志。

#### 功能要求

1. 必须集成 Monolog。
2. 必须支持 daily file、single file、stdout 等日志驱动。
3. 必须支持 app、error、request、sql 等日志通道。
4. 请求日志必须记录 method、uri、ip、user_agent、status、duration、request_id。
5. 异常日志必须记录异常类型、消息、文件、行号、堆栈。
6. 生产 Docker 环境必须支持输出到 stdout/stderr，便于容器日志收集。
7. 日志目录必须可配置。

### 8.12 数据库 ORM 层

#### 需求描述

系统必须集成轻量数据库访问能力，默认支持 Eloquent ORM，同时保留直接使用查询构造器或 PDO 的灵活性。

#### 功能要求

1. 必须支持 MySQL 连接配置。
2. 必须支持多数据库连接。
3. 必须支持读写分离配置。
4. 必须支持 Eloquent Model 基类。
5. 必须支持查询构造器。
6. 必须支持 SQL 日志开关。
7. 数据库连接配置必须来自 `.env` 或 Yaf 配置。
8. 数据库异常必须被全局异常处理器接管。

### 8.13 Redis 缓存组件

#### 需求描述

系统必须提供 Redis 访问和缓存辅助能力，用于缓存、Token 黑名单、限流、幂等锁等常见 API 场景。

#### 功能要求

1. 必须支持 Redis host、port、password、database、timeout 配置。
2. 必须提供 `get`、`set`、`delete`、`exists`、`ttl`、`expire` 等常用方法。
3. 必须支持设置过期时间。
4. 必须支持 Redis 不可用时记录错误日志。
5. 可选支持 Predis 和 phpredis 两种驱动。
6. 不要求在 PHP-FPM 模式下实现复杂连接池。

### 8.14 JWT 认证

#### 需求描述

系统必须提供标准 JWT 认证能力，用于 API Token 签发、解析和访问控制。

#### 功能要求

1. 必须支持 Token 签发。
2. 必须支持 Token 解析和校验。
3. 必须支持 Bearer Token 读取。
4. 必须支持过期时间配置。
5. 必须支持不同身份类型，例如 user、admin。
6. 必须支持 Token 黑名单能力，优先基于 Redis 实现。
7. 鉴权失败必须返回统一 JSON 错误。
8. 必须支持配置免认证路由，例如 `/health`、登录接口。

### 8.15 CORS 跨域

#### 需求描述

系统必须提供适合前后端分离 API 服务的跨域处理能力。

#### 功能要求

1. 必须支持配置允许来源。
2. 必须支持配置允许方法。
3. 必须支持配置允许 Header。
4. 必须支持 credentials 开关。
5. 必须自动处理 OPTIONS 预检请求。
6. 必须允许生产环境收敛为明确域名，不建议默认 `*`。

### 8.16 API 版本化

#### 需求描述

系统必须支持 API 版本化开发，降低版本迭代对旧接口的影响。

#### 功能要求

1. 必须支持 URL 版本前缀，例如 `/v1`、`/v2`。
2. 必须支持物理目录隔离，例如 `controllers/V1`、`controllers/V2`。
3. 不同版本控制器必须可独立演进。
4. 路由封装必须能识别版本号并映射到对应控制器。
5. 命令行生成器必须支持生成指定版本控制器。

### 8.17 命令行工具

#### 需求描述

系统必须提供统一命令行入口，提升开发效率，并支持环境检测、代码生成、迁移、缓存清理等常见任务。

#### 命令入口

推荐提供根目录命令：

```bash
php crab <command>
```

也可兼容：

```bash
php bin/crab <command>
```

#### V1.0 必须支持命令

| 命令 | 说明 |
| --- | --- |
| `php crab list` | 查看全部可用命令 |
| `php crab env:check` | 检测运行环境 |
| `php crab make:controller` | 创建控制器 |
| `php crab make:model` | 创建模型 |
| `php crab make:command` | 创建自定义 CLI 命令 |
| `php crab route:list` | 查看已注册路由 |
| `php crab config:show` | 查看当前环境配置摘要 |
| `php crab cache:clear` | 清理运行缓存 |

#### 控制器生成示例

```bash
php crab make:controller User --module=Api --version=V1
```

生成结果应包含：

- 控制器文件。
- 基础 CRUD action 模板。
- 命名空间或类名符合项目规范。
- 可选生成对应测试文件。

### 8.18 数据库迁移与填充

#### 需求描述

系统必须提供数据库结构版本管理能力，方便团队协作和持续交付。

#### 功能要求

1. 必须集成 Phinx 或等价迁移工具。
2. 必须读取 `.env` 数据库配置。
3. 必须提供迁移目录。
4. 必须支持创建迁移文件。
5. 必须支持执行迁移和回滚。
6. 可选支持 seed 数据填充。

### 8.19 测试与开发工具

#### 需求描述

系统必须提供基础测试能力和代码质量工具，保障脚手架自身和业务项目可持续维护。

#### 功能要求

1. 必须集成 PHPUnit。
2. 必须提供基础测试用例示例。
3. 必须支持 `.env.testing`。
4. 必须支持测试环境独立数据库配置。
5. 推荐集成 php-cs-fixer。
6. 推荐集成 phpstan。
7. 必须在 README 中说明测试命令。

### 8.20 Docker 部署

#### 需求描述

系统必须支持 Docker 化本地开发和生产部署参考配置。

#### 功能要求

1. 必须提供 Dockerfile。
2. 必须提供 docker-compose.yml。
3. docker-compose 至少包含 PHP-FPM、Nginx、MySQL、Redis。
4. PHP 镜像必须安装或启用 Yaf 扩展。
5. Nginx 必须配置 Yaf URL Rewrite，所有非静态请求转发到 `public/index.php`。
6. 必须支持挂载项目目录用于本地开发。
7. 必须提供生产构建建议，例如 composer install --no-dev、opcache、关闭 debug。
8. 必须支持健康检查。

### 8.21 健康检查

#### 需求描述

系统必须提供健康检查端点，用于负载均衡、容器编排和部署验证。

#### 功能要求

1. 必须提供 `/health` 接口。
2. `/health` 默认不需要鉴权。
3. 必须返回服务状态、时间戳、版本号。
4. 可选返回数据库和 Redis 状态。
5. 生产环境不得暴露敏感配置。

#### 响应示例

```json
{
  "status": "ok",
  "version": "1.0.0",
  "timestamp": 1710000000,
  "services": {
    "database": "ok",
    "redis": "ok"
  }
}
```

## 9. 非功能需求

### 9.1 性能

1. 框架封装不得显著增加 Yaf 原生请求生命周期开销。
2. Composer 包必须按需初始化，避免 Bootstrap 阶段加载过多无关服务。
3. 生产环境必须建议开启 opcache。
4. 路由封装必须最终落到 Yaf Router，不引入额外重型路由器。
5. 日志、数据库、Redis、JWT 等组件必须支持按配置启用。

### 9.2 可用性

1. 默认错误响应必须稳定，不因异常导致 HTML 错误页泄露给 API 调用方。
2. 生产配置必须提供安全默认值。
3. 环境检测必须能提前发现关键依赖缺失。
4. Docker 本地环境必须能一条命令启动主要依赖服务。

### 9.3 安全性

1. `.env`、密钥、数据库密码不得进入版本库。
2. JWT 密钥必须通过环境变量配置。
3. 生产环境必须关闭 debug。
4. CORS 生产配置不得默认允许任意来源。
5. 异常响应不得泄露堆栈、SQL、服务器路径等敏感信息。
6. 请求日志必须避免记录明文密码、Token 等敏感字段。

### 9.4 可维护性

1. 目录结构必须清晰，文档必须覆盖快速开始和常见配置。
2. 组件封装必须保持简单，避免深层继承和复杂魔法方法。
3. 命令行生成的代码必须符合项目规范。
4. 所有核心封装必须有示例或测试。

### 9.5 可部署性

1. 必须支持传统 PHP-FPM + Nginx 部署。
2. 必须支持 Docker 部署。
3. 必须支持 stdout/stderr 日志输出。
4. 必须支持运行时配置通过环境变量注入。

## 10. 技术约束

### 10.1 运行环境

| 项目 | 要求 |
| --- | --- |
| PHP | 7.0+ |
| Yaf | 必须安装 PHP Yaf 扩展 |
| Web Server | Nginx 优先，Apache 可通过文档说明 |
| 进程模型 | PHP-FPM |
| 包管理 | Composer |
| 数据库 | MySQL 5.7+ 或兼容版本 |
| 缓存 | Redis 4+ |
| 容器 | Docker、docker-compose |

### 10.2 PHP 版本兼容策略

为了支持 PHP 7.0+，脚手架必须避免使用 PHP 7.1+、7.4+、8.x 独有语法作为核心代码默认语法，例如：

- 不使用 typed property。
- 不使用 union type。
- 不使用 match 表达式。
- 不使用 arrow function。
- 不使用 nullsafe operator。

如需兼容 PHP 8.x，应通过测试矩阵验证，而不是在核心代码中依赖 PHP 8 语法。

### 10.3 Composer 依赖策略

1. `composer.json` 必须明确 `require` 和 `require-dev`。
2. 必须优先选择仍支持 PHP 7.0 的稳定版本。
3. 若某些包的新版本不支持 PHP 7.0，必须在文档中说明版本锁定原因。
4. 不得引入与 Yaf 生命周期冲突的全栈框架内核。

## 11. 建议目录结构

```text
crab-taoist/
├── application/
│   ├── Bootstrap.php
│   ├── controllers/
│   ├── modules/
│   │   └── Api/
│   │       └── controllers/
│   │           ├── V1/
│   │           └── V2/
│   ├── plugins/
│   ├── models/
│   └── library/
├── bin/
│   └── crab
├── conf/
│   ├── application.ini
│   ├── routes.ini
│   └── plugins.ini
├── config/
│   ├── database.php
│   ├── cache.php
│   ├── jwt.php
│   └── logging.php
├── database/
│   ├── migrations/
│   └── seeds/
├── docker/
│   ├── nginx/
│   └── php/
├── public/
│   └── index.php
├── runtime/
│   ├── cache/
│   └── logs/
├── tests/
├── vendor/
├── .env.example
├── .env.testing.example
├── composer.json
├── Dockerfile
├── docker-compose.yml
└── README.md
```

## 12. API 规范

### 12.1 响应规范

所有业务 API 默认返回 JSON：

```json
{
  "code": 200,
  "message": "success",
  "data": {},
  "request_id": "request-id"
}
```

分页接口建议返回：

```json
{
  "code": 200,
  "message": "success",
  "data": {
    "items": [],
    "pagination": {
      "page": 1,
      "page_size": 20,
      "total": 0
    }
  },
  "request_id": "request-id"
}
```

### 12.2 错误码规范

| code | 含义 |
| --- | --- |
| 200 | 成功 |
| 400 | 请求参数错误 |
| 401 | 未认证或 Token 无效 |
| 403 | 无权限 |
| 404 | 资源不存在 |
| 422 | 参数验证失败 |
| 429 | 请求过于频繁 |
| 500 | 服务器内部错误 |

业务项目可在此基础上扩展自己的业务错误码。

## 13. 文档需求

V1.0 必须提供以下文档：

1. `README.md`：项目介绍、快速开始、目录结构、常用命令。
2. `docs/PRD.md`：产品需求文档。
3. `docs/FeatureList.md`：功能清单。
4. `docs/QuickStart.md`：从安装到运行第一个 API。
5. `docs/Configuration.md`：环境变量和配置说明。
6. `docs/Routing.md`：路由配置和版本化说明。
7. `docs/CLI.md`：命令行工具说明。
8. `docs/Deployment.md`：传统部署和 Docker 部署说明。
9. `docs/Testing.md`：测试运行和测试环境配置。

## 14. 验收标准

### 14.1 基础运行验收

1. 执行 `composer install` 成功。
2. 复制 `.env.example` 为 `.env` 并配置后，项目可启动。
3. 访问默认首页或示例 API 返回 JSON。
4. 访问 `/health` 返回服务健康状态。
5. 生产环境关闭 debug 后，异常不暴露堆栈信息。

### 14.2 CLI 验收

1. `php crab list` 可查看命令列表。
2. `php crab env:check` 可检测 PHP、Yaf、Composer、目录权限、数据库、Redis。
3. `php crab make:controller User --module=Api --version=V1` 可生成控制器。
4. `php crab route:list` 可查看路由表。

### 14.3 API 能力验收

1. RESTful 路由可正确映射到 V1 控制器。
2. JSON 请求体可被控制器读取。
3. 统一响应格式稳定。
4. 参数验证失败返回标准 JSON 错误。
5. 未认证访问受保护接口返回 401。
6. CORS 预检请求可正确响应。

### 14.4 工程能力验收

1. 日志按配置写入文件或 stdout。
2. 数据库连接可用，并可执行基础查询。
3. Redis 连接可用，并可执行 set/get。
4. 数据库迁移命令可运行。
5. PHPUnit 示例测试可通过。
6. docker-compose 可启动完整本地开发环境。

## 15. 里程碑

### M1：基础骨架

- 完成项目目录、入口文件、Bootstrap、Composer 接入。
- 完成默认 Api V1 示例控制器。
- 完成 `.env.example` 和基础配置。

### M2：API 基础能力

- 完成路由封装。
- 完成统一请求、响应、异常处理。
- 完成参数验证。
- 完成 API 版本化目录和示例。

### M3：企业组件集成

- 完成日志系统。
- 完成数据库 ORM。
- 完成 Redis 缓存。
- 完成 JWT 鉴权。
- 完成 CORS 插件。
- 完成健康检查。

### M4：开发效率工具

- 完成 CLI 入口。
- 完成 env:check、make:controller、make:model、route:list 等命令。
- 完成迁移、测试、代码质量基础配置。

### M5：部署与文档

- 完成 Dockerfile 和 docker-compose。
- 完成 Nginx Rewrite 配置。
- 完成部署文档、快速开始文档、配置文档。
- 完成最终验收测试。

## 16. 风险与应对

| 风险 | 影响 | 应对 |
| --- | --- | --- |
| PHP 7.0 依赖生态老化 | 部分 Composer 包新版本无法安装 | 明确依赖版本矩阵，锁定兼容版本 |
| Yaf 扩展安装门槛 | 新用户本地环境启动失败 | 提供 Docker 镜像和 env:check |
| 过度封装导致性能下降 | 背离项目定位 | 保持 Yaf 原生机制，组件按需初始化 |
| 路由封装复杂化 | 学习成本升高 | 基于 INI 或简单数组配置，不引入复杂 DSL |
| 生产配置误用 | 安全信息泄露 | 默认生产关闭 debug，文档明确部署检查项 |
| Docker 与传统部署差异 | 环境不一致 | 同时提供 Docker 和 PHP-FPM + Nginx 部署说明 |

## 17. 成功指标

1. 新开发者可在 10 分钟内通过 Docker 启动项目并访问示例 API。
2. 开发者可在 1 分钟内生成一个新 API 控制器并完成路由访问。
3. 默认模板具备 API 项目常见生产基础能力，无需额外大规模集成。
4. 核心请求链路仍保持 Yaf 轻量高性能特征。
5. 文档足够支撑团队基于模板创建真实业务服务。

## 18. 附录：推荐默认 Composer 包

| 能力 | 包名 | 备注 |
| --- | --- | --- |
| 环境变量 | `vlucas/phpdotenv` | 注意 PHP 7.0 兼容版本 |
| 日志 | `monolog/monolog` | PHP 7.0 环境需选择兼容主版本 |
| 验证 | `illuminate/validation` 或 `respect/validation` | 优先选择轻量用法 |
| 数据库 | `illuminate/database` | 只使用 Capsule 与 Model，不引入 Laravel 内核 |
| Redis | `predis/predis` 或 `ext-redis` | 可配置驱动 |
| JWT | `firebase/php-jwt` | 需锁定兼容 PHP 7.0 的版本 |
| CLI | `symfony/console` | 用于 `php crab` 命令 |
| 迁移 | `robmorgan/phinx` | 数据库版本管理 |
| 测试 | `phpunit/phpunit` | PHP 7.0 需使用兼容版本 |
| 错误展示 | `filp/whoops` | 仅开发环境启用 |

## 19. 结论

Crab Taoist 的核心价值是把 Yaf 的高性能和 Composer 的工程生态结合起来，用轻量封装补齐企业 API 服务从开发、测试到部署的基础能力。V1.0 应聚焦脚手架本身的稳定性、可理解性和生产可用性，避免过早引入重型框架能力，让团队能够以较低成本快速启动并长期维护 Yaf API 项目。
