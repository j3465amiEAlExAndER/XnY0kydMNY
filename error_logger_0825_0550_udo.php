<?php
// 代码生成时间: 2025-08-25 05:50:03
// ErrorLogger.php
// 错误日志收集器类，使用Symfony框架

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Log\Logger;

class ErrorLogger implements DebugLoggerInterface
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Handles an exception.
     *
     * @param \Exception $exception
     * @param Request $request
     *
     * @return bool
     */
    public function logException($exception, Request $request): bool
    {
        // Check for specific exception types or error levels
        if ($exception instanceof HttpException) {
            $logLevel = LogLevel::ERROR;
        } else {
            $logLevel = LogLevel::CRITICAL;
        }

        // Log the exception with the appropriate log level
        $this->logger->log($logLevel, $exception->getMessage(), ['exception' => $exception]);

        return false; // Let Symfony handle the exception
    }

    /**
     * Logs a debug message.
     *
     * @param string $message
     *
     * @return void
     */
    public function debug($message): void
    {
        $this->logger->debug($message);
    }

    /**
     * Logs an information message.
     *
     * @param string $message
     *
     * @return void
     */
    public function info($message): void
    {
        $this->logger->info($message);
    }

    /**
     * Logs a warning message.
     *
     * @param string $message
     *
     * @return void
     */
    public function warning($message): void
    {
        $this->logger->warning($message);
    }

    /**
     * Logs an error message.
     *
     * @param string $message
     *
     * @return void
     */
    public function error($message): void
    {
        $this->logger->error($message);
    }

    /**
     * Logs a critical message.
     *
     * @param string $message
     *
     * @return void
     */
    public function critical($message): void
    {
        $this->logger->critical($message);
    }
}
