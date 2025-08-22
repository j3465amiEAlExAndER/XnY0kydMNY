<?php
// 代码生成时间: 2025-08-22 13:17:44
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class BatchFileRenamer extends Command
{
    protected static $defaultName = 'app:batch-file-rename';
    private $filesystem;

    public function __construct(Filesystem $filesystem)
# NOTE: 重要实现细节
    {
        $this->filesystem = $filesystem;
        parent::__construct();
    }

    protected function configure()
    {
# NOTE: 重要实现细节
        $this
            ->setDescription('批量重命名文件')
# FIXME: 处理边界情况
            ->addArgument('source', InputArgument::REQUIRED, '源目录')
# 扩展功能模块
            ->addArgument('destination', InputArgument::REQUIRED, '目标目录')
            ->addOption('prefix', 'p', InputOption::VALUE_REQUIRED, '文件名前缀', '')
            ->addOption('suffix', 's', InputOption::VALUE_REQUIRED, '文件名后缀', '');
# FIXME: 处理边界情况
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $source = $input->getArgument('source');
# 增强安全性
        $destination = $input->getArgument('destination');
        $prefix = $input->getOption('prefix');
        $suffix = $input->getOption('suffix');

        if (!$this->filesystem->exists($source)) {
            $io->error("源目录不存在: {$source}");
            return Command::FAILURE;
        }

        $renameCount = 0;
        $files = $this->filesystem->listFiles($source);
        foreach ($files as $file) {
            $newFileName = $prefix . basename($file) . $suffix;
            $newFilePath = $destination . '/' . $newFileName;

            try {
                $this->filesystem->rename($source . '/' . $file, $newFilePath);
                ++$renameCount;
# TODO: 优化性能
                $io->success("文件 {$file} 已重命名为 {$newFileName}");
            } catch (\Exception $e) {
# 改进用户体验
                $io->error("文件 {$file} 重命名失败: {$e->getMessage()}");
# 添加错误处理
            }
        }

        $io->success("共重命名 {$renameCount} 个文件");
# FIXME: 处理边界情况
        return Command::SUCCESS;
    }
}
# 改进用户体验
