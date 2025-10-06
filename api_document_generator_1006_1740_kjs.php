<?php
// 代码生成时间: 2025-10-06 17:40:42
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Router;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Yaml\Yaml;

class ApiDocumentGenerator {

    private $router;
    private $routes;

    public function __construct($routingFile = 'config/routes.yaml') {
        // 加载路由文件
        $loader = new YamlFileLoader($routingFile);
        $this->routes = $loader->load($routingFile);
        $this->router = new Router($loader, $routingFile);
    }

    public function generateDocumentation() {
        try {
            // 获取所有路由
            $routes = $this->router->getRouteCollection()->all();
            $documentation = [];

            foreach ($routes as $name => $route) {
                $methods = $route->getMethods();
                $path = $route->getPath();
                $controller = $route->getDefault('_controller');
                $documentation[$name] = [
                    'path' => $path,
                    'methods' => implode(', ', $methods),
                    'controller' => $controller,
                ];
            }

            // 将路由信息转换为 YAML 格式的文档
            return Yaml::dump($documentation, 4);
        } catch (Exception $e) {
            // 错误处理
            return 'Error: ' . $e->getMessage();
        }
    }

    public function displayDocumentation() {
        $documentation = $this->generateDocumentation();
        $response = new Response($documentation, 200, ['Content-Type' => 'text/yaml']);
        $response->send();
    }
}

// 确保路由文件 config/routes.yaml 存在并配置正确
// 路由配置示例：
/*
api_doc:
  path: /api/doc
  methods: [GET]
  defaults:
    _controller: ApiDocumentGenerator::displayDocumentation
*/
