<?php
// 代码生成时间: 2025-08-24 18:03:36
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * Excel表格自动生成器
 *
 * 该类用于创建一个Excel表格并写入数据
 */
class ExcelGenerator {

    /**
     * 创建一个新的Excel表格
     *
     * @param array $data 数据数组，其中每个元素代表一行
     * @param string $filename 要生成的Excel文件名
     * @return void
     */
    public function createExcelFile(array $data, string $filename): void {
        try {
            // 创建一个新的Spreadsheet实例
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // 设置文件名
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

            // 写入数据到Excel表格
            foreach ($data as $rowIndex => $rowData) {
                foreach ($rowData as $colIndex => $cellValue) {
                    $sheet->setCellValueByColumnAndRow($colIndex + 1, $rowIndex + 1, $cellValue);
                }
            }

            // 设置Excel文件名
            $writer->save($filename);

            echo "Excel文件已生成: {$filename}";

        } catch (Exception $e) {
            // 错误处理
            echo "发生错误: " . $e->getMessage();
        }
    }
}

// 示例用法
$data = [
    ['Name', 'Age', 'City'],
    ['John', 30, 'New York'],
    ['Jane', 25, 'Los Angeles']
];

$excelGenerator = new ExcelGenerator();
$excelGenerator->createExcelFile($data, 'example.xlsx');
