<?php
// 代码生成时间: 2025-08-03 04:08:01
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Firewall\LogoutListener;
use Symfony\Component\Security\Http\SecurityEvents;
use Symfony\Component\Security\Http\Event\LogoutEvent;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

// 安全审计日志服务类
class SecurityAuditLogger {
    private $logger;

    public function __construct(Logger $logger) {
        $this->logger = $logger;
    }

    // 记录用户登录事件
    public function logLogin(Request $request): void {
        $this->logger->info(
            'User logged in',
            ['username' => $request->user->getUsername(), 'ip' => $request->getClientIp()]
        );
    }

    // 记录用户登出事件
    public function logLogout(LogoutEvent $event): void {
        $this->logger->info(
            'User logged out',
            ['username' => $event->getUsername(), 'ip' => $event->getRequest()->getClientIp()]
        );
    }
}

// 安全审计日志的依赖注入和服务配置
// 需要在Symfony的服务容器中注册
// services.yaml
// services:
//     App\SecurityAuditLogger:
//         arguments: ['@logger']

// 事件监听器，监听用户登出事件
class SecurityLogoutListener extends LogoutListener {
    private $auditLogger;

    public function __construct(EventDispatcherInterface $eventDispatcher, SecurityAuditLogger $auditLogger) {
        parent::__construct($eventDispatcher);
        $this->auditLogger = $auditLogger;
    }

    protected function onLogoutSuccess(Request $request): void {
        parent::onLogoutSuccess($request);
        $this->auditLogger->logLogout($this->getLogoutEvent($request));
    }
}

// 配置日志处理器和Logger实例
// config/packages/prod/monolog.yaml
// monolog:
//     handlers:
//         main:
//             type: stream
//             path: '%kernel.logs_dir%/%kernel.environment%.log'
//             level: debug
//             channels: ['security']

// 控制器中使用安全审计日志服务
// src/Controller/SecurityController.php
// use App\SecurityAuditLogger;
// use Symfony\Component\HttpFoundation\Request;

// class SecurityController extends AbstractController {
//     private $auditLogger;

//     public function __construct(SecurityAuditLogger $auditLogger) {
//         $this->auditLogger = $auditLogger;
//     }

//     public function login(Request $request): Response {
//         // 登录逻辑...
//         $this->auditLogger->logLogin($request);
//         return new Response('Logged in successfully');
//     }
// }
