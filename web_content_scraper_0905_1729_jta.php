<?php
// 代码生成时间: 2025-09-05 17:29:52
// web_content_scraper.php

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use DOMDocument;
use DOMXPath;
use Exception;

class WebContentScraper {
    /**
     * @var string URL of the webpage to scrape
     */
    private string $url;

    /**
     * @var HttpClient Symfony HTTP client
     */
    private HttpClient $client;

    public function __construct(string $url) {
        $this->url = $url;
        $this->client = HttpClient::create();
    }

    /**
     * Scrapes the webpage content and returns the HTML
     *
     * @return string
     * @throws Exception
     */
    public function scrapeHtml(): string {
        try {
            $response = $this->client->request('GET', $this->url);
            $response->getStatusCode(); // Check for successful response
            return $response->getContent();
        } catch (ClientExceptionInterface $e) {
            throw new Exception('Failed to send request: ' . $e->getMessage(), 0, $e);
        } catch (TransportExceptionInterface $e) {
            throw new Exception('Network error: ' . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Extracts content based on XPath from the scraped HTML
     *
     * @param string $xpath The XPath query to extract content
     * @return array An array of content extracted from the webpage
     * @throws Exception
     */
    public function extractContentByXPath(string $xpath): array {
        $html = $this->scrapeHtml();
        $dom = new DOMDocument();
        @$dom->loadHTML($html); // Suppress errors due to invalid HTML
        $xpath = new DOMXPath($dom);

        try {
            $nodes = $xpath->query($xpath);
            $content = [];
            foreach ($nodes as $node) {
                $content[] = $node->nodeValue;
            }
            return $content;
        } catch (Exception $e) {
            throw new Exception('Failed to extract content: ' . $e->getMessage(), 0, $e);
        }
    }
}

// Example usage:
try {
    $scraper = new WebContentScraper('https://example.com');
    $content = $scraper->extractContentByXPath('//p'); // Extract all paragraph texts
    echo '<pre>' . implode("\
", $content) . '</pre>';
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}