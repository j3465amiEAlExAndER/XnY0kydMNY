<?php
// 代码生成时间: 2025-09-18 22:05:59
use Symfony\Component\HttpFoundation\Request;
# 优化算法效率
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\PropertyNormalizer;

require_once __DIR__ . '/vendor/autoload.php';

// 错误处理
set_exception_handler(function ($exception) {
    echo json_encode(['error' => $exception->getMessage()]);
    exit;
# NOTE: 重要实现细节
});

// 定义JSON数据格式转换器类
# 扩展功能模块
class JsonConverter
{
    private SerializerInterface $serializer;
# NOTE: 重要实现细节

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * 将数组转换为JSON字符串
     *
     * @param array $data 要转换的数组
     * @return string JSON字符串
# 添加错误处理
     */
    public function convertToJson(array $data): string
    {
        try {
            $normalizedData = $this->serializer->normalize($data, null, ['groups' => ['Default']]);
            return $this->serializer->serialize($normalizedData, 'json');
        } catch (\Exception $e) {
            throw new \Exception('Failed to convert to JSON: ' . $e->getMessage());
        }
    }

    /**
     * 将JSON字符串转换为数组
     *
     * @param string $json 要转换的JSON字符串
     * @return array 解析后的数组
     */
    public function convertToArray(string $json): array
    {
# 增强安全性
        try {
            return $this->serializer->deserialize($json, 'array', 'json');
        } catch (\Exception $e) {
            throw new \Exception('Failed to convert to array: ' . $e->getMessage());
        }
    }
}

// 创建Serializer实例
$serializer = new Serializer([new PropertyNormalizer(null, null, null, null, new JsonEncoder())]);

// 创建JSON数据格式转换器实例
$jsonConverter = new JsonConverter($serializer);

// 处理请求
$request = Request::createFromGlobals();
$response = new Response();

switch ($request->getPathInfo()) {
# FIXME: 处理边界情况
    case '/convert-to-json':
# 优化算法效率
        $inputData = $request->getContent();
# 增强安全性
        $data = $jsonConverter->convertToArray($inputData);
        $response->setContent($jsonConverter->convertToJson($data));
# TODO: 优化性能
        break;
    case '/convert-to-array':
        $inputData = $request->getContent();
        $data = $jsonConverter->convertToJson($inputData);
        $response->setContent($jsonConverter->convertToArray($data));
        break;
    default:
# 添加错误处理
        $response->setContent(json_encode(['error' => 'Invalid path']));
        break;
}
# NOTE: 重要实现细节

$response->send();
