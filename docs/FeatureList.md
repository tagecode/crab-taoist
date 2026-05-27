# 项目

基于 Yaf 框架 + Composer 打造一套企业级、生产可用的 API 快速开发脚手架

## 概要

基于 Yaf 框架 + Composer 打造一套企业级、生产可用的 API 快速开发脚手架，核心是**不破坏 Yaf 的 C 扩展高性能特性，同时用 Composer 生态补足开发效率**。下面是模板应实现的主要功能清单，每一项都遵循“轻封装、重整合、开箱即用”的原则。

---

### 1. 项目骨架与目录结构

- 严格遵循 Yaf 的模块化目录规范（`application/modules/`），并预设 Api 模块及版本子目录（如 `V1/controllers`）。
- 整合 `public/` 入口、`conf/`、`vendor/`、`bin/`、`tests/` 等标准目录，兼容 Composer 的 vendor 结构。
- 利用 Yaf 的 `Bootstrap` 机制初始化 Composer 自动加载、环境变量等，保证原生加载性能。

### 2. Composer 自动加载与 Yaf 整合

- 在 `Bootstrap` 中手动加载 `vendor/autoload.php`，使 Yaf 的类加载规则与 PSR-4 共存。
- 保留 Yaf 自身的控制器、模型、插件自动加载（命名规则：`{Module}_Controller_{Name}` 等），不重写核心加载逻辑，确保性能无损。

### 3. 多环境配置管理

- 使用 `vlucas/phpdotenv` 加载 `.env` 文件，分离开发、测试、生产环境变量。
- 在 Yaf 的 `conf/application.ini` 中使用常量引用，结合 `ap.environment` 分段加载不同配置，实现数据库、缓存、日志等配置的环境切换。

### 4. RESTful 路由与版本控制

- 充分利用 Yaf 的路由器，通过配置文件定义简洁的 RESTful 风格路由（支持 GET/POST/PUT/DELETE 映射）。
- 路由可自动匹配到模块（如 `v1` 到 `Api_V1` 子模块），实现 API 版本化。
- 支持路由组、正则与变量绑定，将 `/v1/user/:id` 映射到 `Api_V1_UserController::getAction()`，减少重复代码。

### 5. 统一请求与响应封装

- 基于 Yaf 的 `Yaf_Request_Http` 和 `Yaf_Response_Http` 做轻量级扩展，提供输入获取器（`$request->json()`、`$request->getParam()` 过滤）。
- 封装 `JsonResponse` 辅助方法，统一返回 `{"code":200,"message":"success","data":...}`，支持自定义 HTTP 状态码与业务码。
- 不改变 Yaf 响应机制，仅通过助手函数在控制器中快速输出 JSON。

### 6. 参数验证组件

- 集成 `illuminate/validation` 或 `respect/validation`，封装一个简单验证器服务。
- 在控制器中通过门面式方法（如 `Validator::make($request->all(), $rules)`）调用，验证失败自动抛出业务异常，由全局异常处理器返回统一 JSON 错误。

### 7. 全局异常处理（JSON 错误页面）

- 利用 Yaf 的异常捕获机制（`application.dispatcher.catchException = On`）和自定义 `ErrorController`。
- 设置异常时 `setRequest` 分发到专门的 Error 控制器，该控制器仅输出 JSON 格式错误，区分调试模式（显示详细堆栈）和生产模式（安全错误信息）。
- 可通过 `Whoops` 在开发环境美化错误输出，生产环境切换 JSON 响应。

### 8. Yaf 插件系统（中间件式切面）

- 实现多个 `Yaf_Plugin_Abstract` 插件，按 `routerStartup`、`routerShutdown`、`dispatchLoopStartup`、`preDispatch`、`postDispatch`、`dispatchLoopShutdown` 等钩子插入处理逻辑。
- 模板内置常用插件：
  - **CORS 插件**：统一处理跨域头。
  - **JWT 鉴权插件**：在路由后、分发前校验令牌。
  - **请求日志插件**：记录每次请求的方法、URI、参数、耗时。
  - **限流插件**（可选）：基于 Redis 实现简单令牌桶。
- 插件可通过配置文件开关和参数调节，不侵入业务代码。

### 9. 日志系统集成

- 使用 `monolog/monolog`，在 Bootstrap 中初始化全局 Logger，支持按通道（app、sql、error）和按天分割。
- 为 Yaf 注册一个 dispatch 前后插件，自动记录请求入口日志和响应状态，异常时自动记录错误栈。
- 配置驱动（.env 中 `LOG_CHANNEL=daily`），生产环境可输出到文件/stdout，方便 ELK 或容器收集。

### 10. 数据库 ORM 层

- 集成 `illuminate/database`（Eloquent ORM），通过 Capsule 管理器在 Bootstrap 中初始化连接。
- 数据库配置从 Yaf 配置或 .env 读取，支持多数据库连接、读写分离。
- 提供基础 Model 基类，同时保留直接使用查询构造器的灵活性，不强制复杂抽象。

### 11. Redis 缓存组件

- 使用 `predis/predis` 或 `phpredis` 扩展封装统一的 Cache 类（`Cache::set/get/delete`）。
- 支持连接池（可选，通常 PHP-FPM 连接池不实用，但可提供单例连接管理），配合插件或服务容器获取实例。
- 用于 JWT Token 黑名单、幂等性锁、频率限制等场景。

### 12. JWT 认证方案

- 基于 `firebase/php-jwt` 实现 Token 生成、刷新、校验。
- 结合 Yaf 插件（如 `AuthPlugin`）从 Header 获取 Bearer Token，解析后注入请求对象或用户上下文。
- 支持多身份（用户/管理员）签发不同 payload，配置密钥与过期时间。

### 13. CORS 跨域支持

- 独立的 CORS 插件，读取 `.env` 中的 `CORS_ALLOW_ORIGINS` 等配置。
- 处理 OPTIONS 预检请求，自动添加 Access-Control-* 头，确保前后端分离开发便捷。

### 14. API 版本化目录结构

- 通过 `modules/Api/controllers/V1/`、`modules/Api/controllers/V2/` 物理隔离版本。
- 路由映射自动匹配版本号前缀，使得同一模块下不同版本的代码完全解耦，利于长期维护。

### 15. 命令行支持（可选但推荐）

- 集成 `symfony/console`，在 `bin/cli` 中启动 Console 应用，可创建定时任务、队列 Worker、数据种子等命令。
- 利用 Yaf 的 CLI 模式，让命令也能访问 Bootstrap 初始化的所有服务（数据库、缓存等）。

### 16. 数据库迁移与填充（可选）

- 集成 `phinx/phinx`，提供数据库迁移管理，配置文件自动读取 `.env` 数据库信息。
- 在脚手架中预设迁移目录和基础迁移命令，便于团队快速搭建表结构。

### 17. 单元测试与开发工具

- 集成 `phpunit/phpunit`，配置基于 Yaf 请求的简单测试用例。
- 提供 `.env.testing` 与测试专用数据库，确保不污染开发/生产数据。
- 配置 `php-cs-fixer` 和 `phpstan` 作为开发依赖，保证代码风格一致性。

### 18. Docker 化部署与基础 Nginx 配置

- 提供 `Dockerfile` 和 `docker-compose.yml`（含 PHP-FPM、Nginx、MySQL、Redis），支持本地一行命令启动。
- Nginx 配置示例包含 Yaf 的 URL 重写规则，确保所有请求指向入口文件。
- 生产环境推荐配置（如 opcache、Composer 生产模式启动）也一并给出。

### 19. 健康检查与信息端点

- 内置一个不受认证拦截的 `HealthController`，返回服务时间戳、版本号、数据库连接状态等，方便 K8s 或负载均衡探测。
- 利用 Yaf 的静态路由直接映射到健康检查动作，性能无损耗。

---

### 模板的轻量级与高性能体现

- 所有封装基于 Yaf 原生钩子（Bootstrap、Plugin），避免重复加载或重型框架内核。
- 不使用全栈框架式的 Service Container、Repository 等深层抽象，而是提供简单的静态助手或工厂，按需加载。
- Composer 包选择均以“按需实例化”为原则，不影响常驻内存下的 Yaf 并发能力。
- 配置、路由、插件都遵循 Yaf 的 INI 配置文件风格，学习成本低，不引入复杂 DSL。

这份功能清单可以直接作为脚手架开发的蓝图，覆盖了一个企业级 API 服务所需的核心能力，同时完全释放了 Yaf C 扩展的性能优势。
