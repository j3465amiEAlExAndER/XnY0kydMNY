<?php
// 代码生成时间: 2025-09-06 01:33:15
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpClient\HttpClient;

/**
 * Network Connection Checker
 * This class checks the connection to a given URL
 */
class NetworkConnectionChecker
{
    private HttpClient $client;

    public function __construct()
    {
        $this->client = HttpClient::create();
    }

    /**
     * Check if a URL is reachable
     *
     * @param string $url The URL to check
     * @return bool Returns true if the URL is reachable, false otherwise
     */
    public function checkUrl(string $url): bool
    {
        try {
            $response = $this->client->request('HEAD', $url);
            return $response->getStatusCode() < 400;
        } catch (Throwable $e) {
            // Log the exception
            error_log($e->getMessage());
            return false;
        }
    }
}

// Define the controller
/**
 * Network Connection Checker Controller
 */
class NetworkConnectionCheckerController
{
    private NetworkConnectionChecker $checker;

    public function __construct(NetworkConnectionChecker $checker)
    {
        $this->checker = $checker;
    }

    /**
     * @Route("/check", name="check_connection", methods="{GET}")
     */
    public function checkConnection(Request $request): Response
    {
        $url = $request->query->get('url');

        if (null === $url) {
            return new Response('No URL provided', Response::HTTP_BAD_REQUEST);
        }

        $reachable = $this->checker->checkUrl($url);

        $message = $reachable ? 'URL is reachable' : 'URL is not reachable';

        return new Response($message);
    }
}
