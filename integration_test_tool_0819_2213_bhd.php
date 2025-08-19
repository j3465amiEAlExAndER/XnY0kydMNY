<?php
// 代码生成时间: 2025-08-19 22:13:59
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

// IntegrationTestTool 是一个基于 Symfony 框架的集成测试工具
class IntegrationTestTool extends WebTestCase
{
    protected static function createKernel(array $options = []): Kernel
    {
        // 创建一个新的 Symfony 内核实例，用于测试
        $class = self::createKernelClass();
        return new $class($options);
    }

    protected static function createKernelClass(): string
    {
        // 定义内核类名
        return 'App\Bundle\FrameworkBundle\AppKernel';
    }

    public function testExample(): void
    {
        // 创建客户端实例
        $client = static::createClient();

        // 发送 GET 请求到示例路由
        $crawler = $client->request('GET', '/hello');

        // 检查响应状态码
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Failed to get a 200 response");

        // 检查页面内容
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Hello World")')->count(), "Page does not contain 'Hello World'");
    }
}
