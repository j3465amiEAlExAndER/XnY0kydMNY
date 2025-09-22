<?php
// 代码生成时间: 2025-09-23 04:56:46
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class SystemPerformanceMonitoring extends Command
{
    // 配置命令名称
    protected static $defaultName = 'app:system-performance';

    protected function configure(): void
    {
        $this
            ->setDescription('Monitors system performance.')
            ->setHelp('This command allows you to monitor system performance...');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('System Performance Monitoring Tool');

        try {
            $this->monitorCpuUsage($io);
            $this->monitorMemoryUsage($io);
            $this->monitorDiskUsage($io);
        } catch (ProcessFailedException $e) {
            $io->error('An error occurred while trying to monitor system performance.');
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    private function monitorCpuUsage(SymfonyStyle $io): void
    {
        $process = new Process(['top', '-bn1']);
        $process->run();
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
        $io->section('CPU Usage');
        $io->writeln($process->getOutput());
    }

    private function monitorMemoryUsage(SymfonyStyle $io): void
    {
        $process = new Process(['free', '-h']);
        $process->run();
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
        $io->section('Memory Usage');
        $io->writeln($process->getOutput());
    }

    private function monitorDiskUsage(SymfonyStyle $io): void
    {
        $process = new Process(['df', '-h']);
        $process->run();
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
        $io->section('Disk Usage');
        $io->writeln($process->getOutput());
    }
}
