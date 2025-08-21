<?php
// 代码生成时间: 2025-08-21 18:14:41
class JsonDataConverter
{

    /**
     * 将JSON字符串转换为PHP数组
     *
     * @param string $json JSON字符串
     * @return array|null 返回PHP数组，如果转换失败则返回null
     */
    public function convertJsonToArray(string $json)
    {
        try {
            // 尝试将JSON字符串解码为PHP数组
            $data = json_decode($json, true);
            // 检查解码是否成功
            if (is_null($data)) {
                throw new Exception('Failed to decode JSON to array.');
            }
            return $data;
        } catch (Exception $e) {
            // 捕获并记录错误
            error_log($e->getMessage());
            return null;
        }
    }

    /**
     * 将JSON字符串转换为PHP对象
     *
     * @param string $json JSON字符串
     * @return object|null 返回PHP对象，如果转换失败则返回null
     */
    public function convertJsonToObject(string $json)
    {
        try {
            // 尝试将JSON字符串解码为PHP对象
            $data = json_decode($json);
            // 检查解码是否成功
            if (is_null($data)) {
                throw new Exception('Failed to decode JSON to object.');
            }
            return $data;
        } catch (Exception $e) {
            // 捕获并记录错误
            error_log($e->getMessage());
            return null;
        }
    }

    /**
     * 将PHP数组或对象转换为JSON字符串
     *
     * @param mixed $data PHP数组或对象
     * @return string|null 返回JSON字符串，如果转换失败则返回null
     */
    public function convertDataToJson($data): ?string
    {
        try {
            // 尝试将PHP数据编码为JSON字符串
            $json = json_encode($data);
            // 检查编码是否成功
            if ($json === false) {
                throw new Exception('Failed to encode data to JSON.');
            }
            return $json;
        } catch (Exception $e) {
            // 捕获并记录错误
            error_log($e->getMessage());
            return null;
        }
    }

}

// 使用示例
// $converter = new JsonDataConverter();
// $jsonString = '{"key": "value"}';
// $array = $converter->convertJsonToArray($jsonString);
// if ($array !== null) {
//     print_r($array);
// }
// $object = $converter->convertJsonToObject($jsonString);
// if ($object !== null) {
//     print_r($object);
// }
// $jsonFromData = $converter->convertDataToJson($array);
// if ($jsonFromData !== null) {
//     echo $jsonFromData;
// }
