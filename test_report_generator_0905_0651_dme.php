<?php
// 代码生成时间: 2025-09-05 06:51:10
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Yaml\Yaml;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

// TestReportGeneratorController负责生成测试报告
class TestReportGeneratorController extends AbstractController
{
    // @Route("/generate-report", name="generate_report", methods={"POST"})
    public function generateReport(Request $request): Response
    {
        // 尝试获取请求体中的数据
        try {
            $requestData = $request->getContent();
            $requestData = json_decode($requestData, true);
        } catch (\Exception $e) {
            // 如果请求体格式错误，返回错误信息
            return $this->json(['error' => 'Invalid request body'], Response::HTTP_BAD_REQUEST);
        }

        // 检查必要的参数
        if (!isset($requestData['testResults'])) {
            return $this->json(['error' => 'Missing test results in request'], Response::HTTP_BAD_REQUEST);
        }

        // 生成测试报告
        $reportData = $this->generateReportData($requestData['testResults']);

        // 将测试报告保存到文件
        $this->saveReport($reportData, 'test_report.yml');

        // 返回成功信息和报告文件名
        return $this->json(['message' => 'Report generated successfully', 'fileName' => 'test_report.yml'], Response::HTTP_OK);
    }

    // 生成报告数据
    private function generateReportData(array $testResults): array
    {
        // 计算测试结果的总和和总数
        $totalTests = count($testResults);
        $totalFailures = array_reduce($testResults, function ($carry, $test) {
            return $carry + ($test['status'] === 'failed' ? 1 : 0);
        }, 0);

        // 构建报告数据
        $reportData = [
            'totalTests' => $totalTests,
            'totalFailures' => $totalFailures,
            'testResults' => $testResults,
        ];

        return $reportData;
    }

    // 保存报告到文件
    private function saveReport(array $reportData, string $fileName): void
    {
        // 使用YAML格式保存报告数据
        $yaml = Yaml::dump($reportData, 4);
        file_put_contents($fileName, $yaml);
    }
}
