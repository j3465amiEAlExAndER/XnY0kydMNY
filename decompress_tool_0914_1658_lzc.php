<?php
// 代码生成时间: 2025-09-14 16:58:40
// 引入Symfony框架组件
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

/**
 * 压缩文件解压工具
 *
 * 这个类提供了一个简单的压缩文件解压工具，支持多种压缩文件格式。
 *
 * @author Your Name
 * @version 1.0
 */
class DecompressTool {

    /**
     * 解压文件到指定目录
     *
     * @param string $sourceFilePath 压缩文件的路径
     * @param string $destinationDirectory 目标解压目录
     *
     * @return bool 解压成功返回true，否则返回false
     */
    public function decompress(string $sourceFilePath, string $destinationDirectory): bool {
        // 检查文件是否存在
        if (!file_exists($sourceFilePath)) {
            throw new \Exception("The source file does not exist.");
        }

        // 检查目标目录是否存在，如果不存在则创建
        $filesystem = new Filesystem();
        if (!$filesystem->exists($destinationDirectory)) {
            $filesystem->mkdir($destinationDirectory);
        }

        // 根据文件扩展名确定压缩格式
        $extension = pathinfo($sourceFilePath, PATHINFO_EXTENSION);
        switch ($extension) {
            case 'zip':
                return $this->decompressZip($sourceFilePath, $destinationDirectory);
            case 'tar':
                return $this->decompressTar($sourceFilePath, $destinationDirectory);
            // 可以添加更多压缩格式的支持
            default:
                throw new \Exception("Unsupported file format: {$extension}");
        }
    }

    /**
     * 解压ZIP文件
     *
     * @param string $sourceFilePath ZIP文件的路径
     * @param string $destinationDirectory 目标解压目录
     *
     * @return bool 解压成功返回true，否则返回false
     */
    private function decompressZip(string $sourceFilePath, string $destinationDirectory): bool {
        $process = new Process(['unzip', $sourceFilePath, "-d{$destinationDirectory}"]);
        try {
            $process->mustRun();
        } catch (ProcessFailedException $e) {
            throw new \Exception("Failed to decompress ZIP file: {$e->getMessage()}");
        }

        return true;
    }

    /**
     * 解压TAR文件
     *
     * @param string $sourceFilePath TAR文件的路径
     * @param string $destinationDirectory 目标解压目录
     *
     * @return bool 解压成功返回true，否则返回false
     */
    private function decompressTar(string $sourceFilePath, string $destinationDirectory): bool {
        $process = new Process(['tar', '-xvf', $sourceFilePath, "-C{$destinationDirectory}"]);
        try {
            $process->mustRun();
        } catch (ProcessFailedException $e) {
            throw new \Exception("Failed to decompress TAR file: {$e->getMessage()}");
        }

        return true;
    }
}

// 示例用法：
try {
    $decompressTool = new DecompressTool();
    $sourceFilePath = 'path/to/your/compressed/file.zip';
    $destinationDirectory = 'path/to/your/destination/directory';

    if ($decompressTool->decompress($sourceFilePath, $destinationDirectory)) {
        echo 'File decompressed successfully.';
    } else {
        echo 'Failed to decompress file.';
    }
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
