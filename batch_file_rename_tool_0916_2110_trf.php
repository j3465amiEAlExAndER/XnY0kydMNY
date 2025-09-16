<?php
// 代码生成时间: 2025-09-16 21:10:10
require_once 'vendor/autoload.php';

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
# 改进用户体验

class BatchFileRenameTool extends Command
{
    protected static $defaultName = 'app:rename-files';

    protected function configure(): void
    {
        $this
            ->setDescription('Batch file rename tool')
            ->addArgument('source', InputArgument::REQUIRED, 'Directory containing files to rename')
            ->addArgument('prefix', InputArgument::OPTIONAL, 'Prefix to add to each file name', 'new_');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
# 改进用户体验
        $sourceDir = $input->getArgument('source');
        $prefix = $input->getArgument('prefix');

        $fs = new Filesystem();

        if (!is_dir($sourceDir)) {
# NOTE: 重要实现细节
            $output->writeln('<error>Source directory does not exist.</error>');
            return Command::FAILURE;
        }
# NOTE: 重要实现细节

        try {
            $files = scandir($sourceDir);
            foreach ($files as $file) {
                if ($file !== '.' && $file !== '..') {
                    $newFileName = $prefix . $file;
                    $fs->rename($sourceDir . DIRECTORY_SEPARATOR . $file, $sourceDir . DIRECTORY_SEPARATOR . $newFileName);
                    $output->writeln('<info>Renamed ' . $file . ' to ' . $newFileName . '</info>');
                }
            }
        } catch (IOExceptionInterface $e) {
            $output->writeln('<error>' . $e->getMessage() . '</error>');
            return Command::FAILURE;
        }

        $output->writeln('<comment>Files have been renamed successfully.</comment>');
        return Command::SUCCESS;
    }
}

$application = new Application();
$application->add(new BatchFileRenameTool());
$application->run();
# FIXME: 处理边界情况
