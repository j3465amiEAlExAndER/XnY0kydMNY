<?php
// 代码生成时间: 2025-09-07 14:19:23
// 引入Symfony依赖
use Symfony\Component\Finder\Finder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class LogParser extends Command
{
    protected static $defaultName = 'app:parse-log';

    protected function configure()
    {
        $this
            // 配置命令
            ->setDescription('Parses log files and extracts relevant information')
            ->addArgument('log-file', InputArgument::REQUIRED, 'Path to the log file')
            ->addOption('output', 'o', InputOption::VALUE_OPTIONAL, 'Output file path', 'parsed_log.txt');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        // 获取命令行参数
        $logFilePath = $input->getArgument('log-file');
        $outputFilePath = $input->getOption('output');

        // 检查日志文件是否存在
        if (!file_exists($logFilePath)) {
            $io->error("Log file not found: {$logFilePath}");
            return Command::FAILURE;
        }

        // 解析日志文件
        try {
            $parsedData = $this->parseLogFile($logFilePath);

            // 将解析结果写入输出文件
            file_put_contents($outputFilePath, $parsedData);

            $io->success("Log file parsed successfully. Output saved to {$outputFilePath}");

            return Command::SUCCESS;
        } catch (Exception $e) {
            $io->error("Failed to parse log file: {$e->getMessage()}");
            return Command::FAILURE;
        }
    }

    /**
     * 解析日志文件并提取相关信息
     *
     * @param string $logFilePath 日志文件路径
     * @return string 解析后的日志内容
     * @throws Exception
     */
    protected function parseLogFile($logFilePath)
    {
        // 读取日志文件内容
        $logContent = file_get_contents($logFilePath);

        // 使用正则表达式解析日志内容
        // 假设日志格式为: [timestamp] log level: message
        $pattern = '/\[(.*?)\] (DEBUG|INFO|WARNING|ERROR): (.*?)\
/';

        $parsedLines = [];
        preg_match_all($pattern, $logContent, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $parsedLines[] = "Timestamp: {$match[1]}, Level: {$match[2]}, Message: {$match[3]}";
        }

        return implode("\
", $parsedLines);
    }
}
