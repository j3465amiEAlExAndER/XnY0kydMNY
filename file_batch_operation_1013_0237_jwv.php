<?php
// 代码生成时间: 2025-10-13 02:37:23
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class FileBatchOperation {

    /**
     * @var Filesystem $fs
     */
    private $fs;

    public function __construct() {
        $this->fs = new Filesystem();
    }

    /**
     * 复制文件
     *
     * @param string $sourceDir 源目录
     * @param string $destDir 目标目录
     * @return void
     */
    public function copyFiles($sourceDir, $destDir) {
        $files = Finder::create()->files()->in($sourceDir);
        foreach ($files as $file) {
            $this->fs->copy((string) $file, $destDir . '/' . $file->getFilename(), true);
        }
    }

    /**
     * 移动文件
     *
     * @param string $sourceDir 源目录
     * @param string $destDir 目标目录
     * @return void
     */
    public function moveFiles($sourceDir, $destDir) {
        $files = Finder::create()->files()->in($sourceDir);
        foreach ($files as $file) {
            $this->fs->move((string) $file, $destDir . '/' . $file->getFilename(), true);
        }
    }

    /**
     * 删除文件
     *
     * @param string $dir 目录
     * @return void
     */
    public function deleteFiles($dir) {
        $files = Finder::create()->files()->in($dir);
        foreach ($files as $file) {
            $this->fs->remove((string) $file);
        }
    }

}

// 使用示例
$operation = new FileBatchOperation();
try {
    // 复制文件
    $operation->copyFiles('/path/to/source', '/path/to/destination');
    // 移动文件
    $operation->moveFiles('/path/to/source', '/path/to/destination');
    // 删除文件
    $operation->deleteFiles('/path/to/delete');
} catch (Exception $e) {
    // 错误处理
    echo 'Error: ' . $e->getMessage();
}
