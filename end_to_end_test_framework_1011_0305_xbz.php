<?php
// 代码生成时间: 2025-10-11 03:05:23
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\BrowserKit\Client;

// 端到端测试框架
class EndToEndTestFramework extends WebTestCase
{
    /**
     * 创建一个新的测试客户端
     *
     * @return Client
     */
    private function createClient(): Client
    {
        return static::createClient();
    }

    /**
     * 测试首页是否正常返回
     *
     * @dataProvider urlProvider
     */
    public function testHomePage($url): void
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', $url);

        // 检查响应状态码
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode(), '首页应该返回200状态码');

        // 检查页面标题
        $this->assertStringContainsString('首页标题', $crawler->filter('title')->text(), '页面标题应该包含预期文本');
    }

    /**
     * 提供测试的URL
     *
     * @return array
     */
    public function urlProvider(): array
    {
        return [
            ['/'],
            ['/home'],
            ['/about'],
        ];
    }

    /**
     * 测试登录功能是否正常工作
     *
     * @dataProvider loginProvider
     */
    public function testLogin($credentials): void
    {
        $client = $this->createClient();
        $crawler = $client->request('POST', '/login', $credentials);

        // 检查是否成功登录
        $this->assertEquals(Response::HTTP_FOUND, $client->getResponse()->getStatusCode(), '登录应该重定向');

        // 检查是否跳转到预期页面
        $crawler = $client->followRedirect();
        $this->assertStringContainsString('Dashboard', $crawler->filter('title')->text(), '登录后应该跳转到Dashboard页面');
    }

    /**
     * 提供登录测试的用户凭据
     *
     * @return array
     */
    public function loginProvider(): array
    {
        return [
            ['username' => 'admin', 'password' => 'password'],
            ['username' => 'user', 'password' => 'password'],
        ];
    }
}
