<?php
// 代码生成时间: 2025-08-06 13:54:11
// web_content_scraper.php
// 使用Symfony框架创建的网页内容抓取工具

require_once 'vendor/autoload.php';

use Symfony\Component\HttpClient\Psr18Client;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

class WebContentScraper
{
    private HttpClientInterface $client;

    public function __construct()
    {
        // 使用Symfony的HttpClient客户端
        $this->client = new Psr18Client();
    }

    /**
     * 抓取网页内容
     *
     * @param string $url 要抓取的网页URL
     * @return string|null 返回网页的HTML内容，如果发生错误则返回null
     */
    public function scrapeContent(string $url): ?string
    {
        try {
            // 发起GET请求
            $response = $this->client->request('GET', $url);

            // 检查响应状态码
            if ($response->getStatusCode() === 200) {
                // 返回网页的HTML内容
                return $response->getContent();
            } else {
                // 响应状态码不是200，记录错误并返回null
                error_log('Failed to scrape content: HTTP status code ' . $response->getStatusCode());
                return null;
            }
        } catch (\Exception $e) {
            // 请求过程中发生异常，记录异常信息并返回null
            error_log('An error occurred while scraping content: ' . $e->getMessage());
            return null;
        }
    }
}

// 示例用法
$scraper = new WebContentScraper();
$url = 'https://example.com';
$content = $scraper->scrapeContent($url);

if ($content !== null) {
    echo 'Scraped content: ' . $content;
} else {
    echo 'Failed to scrape content.';
}
