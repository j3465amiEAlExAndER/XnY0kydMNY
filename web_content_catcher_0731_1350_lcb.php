<?php
// 代码生成时间: 2025-07-31 13:50:32
// web_content_catcher.php

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\Service\ResetInterface;
use Symfony\Contracts\Service\ResetInterface;

class WebContentCatcher {

    // 使用Symfony HttpClient进行HTTP请求
    private $httpClient;

    public function __construct() {
        // 创建HttpClient实例
        $this->httpClient = HttpClient::create();
    }

    // 抓取网页内容的函数
    public function fetchContent(string $url): string {
        try {
            // 发送GET请求到指定URL
            $response = $this->httpClient->request('GET', $url);
            
            // 获取响应正文
            $content = $response->getContent();
            
            // 返回网页内容
            return $content;
        } catch (Exception $e) {
            // 错误处理
            // 这里可以记录错误日志或者抛出异常
            return "Error fetching content: " . $e->getMessage();
        }
    }

    // 此方法用于重置服务，如果HttpClient实例实现了ResetInterface
    public function reset() {
        if ($this->httpClient instanceof ResetInterface) {
            $this->httpClient->reset();
        }
    }
}

// 使用示例
$webContentCatcher = new WebContentCatcher();
$url = "http://example.com";
try {
    $content = $webContentCatcher->fetchContent($url);
    echo $content;
} catch (Exception $e) {
    // 异常处理
    echo "An error occurred: " . $e->getMessage();
}
