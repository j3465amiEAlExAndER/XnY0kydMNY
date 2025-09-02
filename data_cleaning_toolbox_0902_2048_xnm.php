<?php
// 代码生成时间: 2025-09-02 20:48:56
class DataCleaningToolbox {

    /**
     * 去除字符串中的前后空格
     *
     * @param string $str 输入的字符串
     * @return string 清理后的字符串
     */
    public function trimString($str) {
        return trim($str);
    }

    /**
     * 转换字符串为小写
     *
     * @param string $str 输入的字符串
     * @return string 小写转换后的字符串
     */
    public function toLowerCase($str) {
        return strtolower($str);
    }

    /**
     * 移除字符串中的HTML标签
     *
     * @param string $str 输入的字符串
     * @return string 清理后的字符串
     */
    public function removeHtmlTags($str) {
        return strip_tags($str);
    }

    /**
     * 替换字符串中的非法字符
     *
     * @param string $str 输入的字符串
     * @param string $illegalChars 非法字符列表
     * @param string $replacement 替换字符
     * @return string 替换后的字符串
     */
    public function replaceIllegalChars($str, $illegalChars, $replacement = '') {
        return preg_replace('/[' . preg_quote($illegalChars, '/') . ']/', $replacement, $str);
    }

    /**
     * 数据清洗和预处理
     *
     * @param array $data 输入的数据数组
     * @return array 清洗和预处理后的数据数组
     */
    public function processData($data) {
        if (!is_array($data)) {
            throw new InvalidArgumentException('Input data must be an array.');
        }

        $cleanedData = [];
        foreach ($data as $key => $value) {
            if (is_string($value)) {
                $cleanedData[$key] = $this->trimString($value)
                    . $this->toLowerCase($value)
                    . $this->removeHtmlTags($value)
                    . $this->replaceIllegalChars($value, '/[^a-zA-Z0-9]/');
            } else {
                $cleanedData[$key] = $value;
            }
        }

        return $cleanedData;
    }
}

// 使用示例
try {
    $dataToolbox = new DataCleaningToolbox();
    $rawData = [
        'name' => '  John Doe  ',
        'email' => '<EMAIL>John.Doe@example.com</EMAIL>',
        'age' => '30'
    ];

    $cleanedData = $dataToolbox->processData($rawData);
    print_r($cleanedData);
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
