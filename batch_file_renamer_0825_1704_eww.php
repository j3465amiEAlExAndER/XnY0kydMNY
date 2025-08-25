<?php
// 代码生成时间: 2025-08-25 17:04:37
require_once 'vendor/autoload.php';

use Symfony\Component\Finder\Finder;
# 改进用户体验
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
# 增强安全性

class BatchFileRenamer extends Command
{
    protected static $defaultName = 'app:rename-files';
# NOTE: 重要实现细节

    protected function configure()
    {
        $this
            ->setDescription('批量重命名指定目录下的文件。')
            ->addArgument('directory', InputArgument::REQUIRED, '文件所在目录')
            ->addOption('prefix', null, InputOption::VALUE_OPTIONAL, '文件名前缀', '')
            ->addOption('suffix', null, InputOption::VALUE_OPTIONAL, '文件名后缀', '')
            ->addOption('extension', null, InputOption::VALUE_OPTIONAL, '文件扩展名', 'txt');
# 改进用户体验
    }

    protected function execute(InputInterface $input, OutputInterface $output)
# NOTE: 重要实现细节
    {
        $directory = $input->getArgument('directory');
        $prefix = $input->getOption('prefix');
        $suffix = $input->getOption('suffix');
        $extension = $input->getOption('extension');

        // 创建Finder实例
        $finder = new Finder();
        $files = $finder->files()->in($directory)->name('*.'.$extension);

        $renameCount = 0;

        foreach ($files as $file) {
            /** @var SplFileInfo $file */
            $originalName = $file->getFilename();
            $newName = $prefix . $originalName . $suffix . '.' . $extension;
# 改进用户体验
            $newPath = $file->getPath() . '/' . $newName;

            try {
                // 重命名文件
                if ($file->isReadable() && !file_exists($newPath)) {
                    rename($file->getPathname(), $newPath);
                    $renameCount++;
                    $output->writeln("文件 {$originalName} 已重命名为 {$newName}");
                } else {
                    $output->writeln("文件 {$originalName} 无法重命名。");
                }
            } catch (Exception $e) {
                $output->writeln("错误: {$e->getMessage()}");
            }
        }

        $output->writeln("总共重命名了 {$renameCount} 个文件。");

        return Command::SUCCESS;
    }
}

// 创建Console Application
$application = new Application();

// 添加命令
$application->add(new BatchFileRenamer());

// 运行Console Application
$application->run();
