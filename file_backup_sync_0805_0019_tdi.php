<?php
// 代码生成时间: 2025-08-05 00:19:46
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

/**
 * 文件备份和同步工具
 *
 * 该工具用于将指定目录下的文件备份并同步到目标目录。
 */
class FileBackupSync {

    private $sourceDir;
    private $targetDir;
    private $filesystem;

    public function __construct($sourceDir, $targetDir) {
        $this->sourceDir = $sourceDir;
        $this->targetDir = $targetDir;
        $this->filesystem = new Filesystem();
    }

    /**
     * 执行文件备份和同步操作
     *
     * @return void
     */
    public function backupAndSync() {
        try {
            // 确保目标目录存在
            $this->filesystem->mkdir($this->targetDir);

            // 查找源目录下的所有文件
            $finder = new Finder();
            $files = $finder->files()->in($this->sourceDir);

            foreach ($files as $file) {
                // 构建目标文件路径
                $targetFilePath = $this->targetDir . '/' . $file->getRelativePathname();

                // 复制文件
                $this->filesystem->copy($file->getRealPath(), $targetFilePath, true);
            }

            echo '文件备份和同步完成！' . PHP_EOL;

        } catch (\Exception $e) {
            // 错误处理
            echo '文件备份和同步失败：' . $e->getMessage() . PHP_EOL;
        }
    }
}

// 示例用法
$sourceDir = '/path/to/source';
$targetDir = '/path/to/target';

$fileBackupSync = new FileBackupSync($sourceDir, $targetDir);
$fileBackupSync->backupAndSync();