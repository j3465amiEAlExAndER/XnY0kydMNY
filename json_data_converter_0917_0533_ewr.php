<?php
// 代码生成时间: 2025-09-17 05:33:38
class JsonDataConverter {

    /**
     * 将JSON字符串转换为PHP数组
     *
# NOTE: 重要实现细节
     * @param string $jsonString JSON字符串
     * @return array 转换后的数组
     * @throws InvalidArgumentException 如果JSON字符串无效
     */
    public function convertToJsonArray(string $jsonString): array {
        try {
            // 将JSON字符串解码为PHP数组
            $array = json_decode($jsonString, true);
            if (is_null($array)) {
                throw new InvalidArgumentException('Invalid JSON string');
            }
            return $array;
        } catch (JsonException $e) {
            throw new InvalidArgumentException('Failed to convert JSON string: ' . $e->getMessage());
        }
    }

    /**
     * 将JSON字符串转换为PHP对象
     *
     * @param string $jsonString JSON字符串
     * @return object 转换后的对象
     * @throws InvalidArgumentException 如果JSON字符串无效
     */
    public function convertToJsonObject(string $jsonString): object {
# NOTE: 重要实现细节
        try {
            // 将JSON字符串解码为PHP对象
            $object = json_decode($jsonString);
            if (is_null($object)) {
                throw new InvalidArgumentException('Invalid JSON string');
            }
            return $object;
        } catch (JsonException $e) {
            throw new InvalidArgumentException('Failed to convert JSON string: ' . $e->getMessage());
# 优化算法效率
        }
    }

    /**
     * 将PHP数组转换为JSON字符串
     *
     * @param array $array PHP数组
     * @param int $options JSON编码选项
     * @return string 转换后的JSON字符串
# FIXME: 处理边界情况
     * @throws InvalidArgumentException 如果数组无效
# 优化算法效率
     */
    public function convertArrayToJson(array $array, int $options = JSON_THROW_ON_ERROR): string {
# 扩展功能模块
        try {
            // 将PHP数组编码为JSON字符串
            return json_encode($array, $options);
        } catch (JsonException $e) {
            throw new InvalidArgumentException('Failed to convert array to JSON: ' . $e->getMessage());
        }
    }

    /**
     * 将PHP对象转换为JSON字符串
     *
     * @param object $object PHP对象
     * @param int $options JSON编码选项
     * @return string 转换后的JSON字符串
     * @throws InvalidArgumentException 如果对象无效
     */
    public function convertObjectToJson(object $object, int $options = JSON_THROW_ON_ERROR): string {
        try {
            // 将PHP对象编码为JSON字符串
            return json_encode($object, $options);
# 增强安全性
        } catch (JsonException $e) {
            throw new InvalidArgumentException('Failed to convert object to JSON: ' . $e->getMessage());
# NOTE: 重要实现细节
        }
    }
}
