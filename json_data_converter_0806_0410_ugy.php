<?php
// 代码生成时间: 2025-08-06 04:10:19
// JsonDataConverter.php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Annotation\Route;

// 定义JsonDataConverter类
class JsonDataConverter
{
    private SerializerInterface $serializer;

    // 构造函数
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    // 将JSON数据转换为PHP数组
    public function convertJsonToArray(string $jsonData): array
    {
        try {
            $data = $this->serializer->decode($jsonData, 'json');
        } catch (Exception $e) {
            // 错误处理
            throw new \Exception('Failed to decode JSON data: ' . $e->getMessage());
        }

        return $data;
    }

    // 将PHP数组转换为JSON数据
    public function convertArrayToJson(array $dataArray): string
    {
        try {
            $json = $this->serializer->encode($dataArray, 'json');
        } catch (Exception $e) {
            // 错误处理
            throw new \Exception('Failed to encode array to JSON: ' . $e->getMessage());
        }

        return $json;
    }
}

// 创建一个简单的控制器来处理请求
class JsonDataConverterController
{
    private JsonDataConverter $jsonDataConverter;

    public function __construct(JsonDataConverter $jsonDataConverter)
    {
        $this->jsonDataConverter = $jsonDataConverter;
    }

    // 定义路由和方法来处理转换请求
    #[Route('/json-convert', methods: ['POST', 'GET'], name: 'json_convert')]
    public function convert(Request $request): Response
    {
        $requestData = $request->getContent();

        if ($request->isMethod('POST')) {
            try {
                // POST请求，将JSON转换为数组
                $array = $this->jsonDataConverter->convertJsonToArray($requestData);
                $response = new Response(\$this->jsonDataConverter->convertArrayToJson($array));
            } catch (Exception $e) {
                $response = new Response($e->getMessage(), Response::HTTP_BAD_REQUEST);
            }
        } elseif ($request->isMethod('GET')) {
            try {
                // GET请求，将数组转换为JSON
                $array = $request->query->all();
                $response = new Response(\$this->jsonDataConverter->convertArrayToJson($array));
            } catch (Exception $e) {
                $response = new Response($e->getMessage(), Response::HTTP_BAD_REQUEST);
            }
        } else {
            $response = new Response('Invalid request method', Response::HTTP_METHOD_NOT_ALLOWED);
        }

        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}
