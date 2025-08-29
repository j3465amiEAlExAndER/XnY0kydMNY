<?php
// 代码生成时间: 2025-08-29 20:43:51
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

// PerformanceMonitorCommand 是一个基于 Symfony Console 的命令行工具，用于监控系统性能。
class PerformanceMonitorCommand extends Command
{
    protected static $defaultName = 'app:performance-monitor';

    protected function configure(): void
    {
        $this"
            ->setDescription('监控并报告系统性能指标。')
            ->setHelp('此命令将显示当前系统的CPU, 内存和磁盘使用情况。');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        try {
            // 显示欢迎信息
            $io->title('系统性能监控工具');

            // 获取CPU使用情况
            $cpuUsage = $this->getCpuUsage();
            $io->section('CPU使用情况');
            $io->text($cpuUsage);

            // 获取内存使用情况
            $memoryUsage = $this->getMemoryUsage();
            $io->section('内存使用情况');
            $io->text($memoryUsage);

            // 获取磁盘使用情况
            $diskUsage = $this->getDiskUsage();
            $io->section('磁盘使用情况');
            $io->text($diskUsage);

            $io->success('性能监控报告完成。');

            return Command::SUCCESS;
        } catch (Exception $e) {
            $io->error("监控失败: " . $e->getMessage());
            return Command::FAILURE;
        }
    }

    // 获取CPU使用率的方法
    private function getCpuUsage(): string
    {
        // 使用Linux的top命令来获取CPU使用率
        $process = new Process(['top', '-bn1', '-o', '%Cpu(s)', '-p', '1']);
        $process->run();
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
        return $process->getOutput();
    }

    // 获取内存使用情况的方法
    private function getMemoryUsage(): string
    {
        // 使用Linux的free命令来获取内存使用情况
        $process = new Process(['free', '-h']);
        $process->run();
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
        return $process->getOutput();
    }

    // 获取磁盘使用情况的方法
    private function getDiskUsage(): string
    {
        // 使用Linux的df命令来获取磁盘使用情况
        $process = new Process(['df', '-h']);
        $process->run();
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
        return $process->getOutput();
    }
}
