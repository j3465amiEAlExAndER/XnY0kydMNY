<?php
// 代码生成时间: 2025-09-02 08:11:29
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

// 文件解压工具类
class FileDecompressionTool {

    private Filesystem $filesystem;
    private string $tempDirectory;

    public function __construct(Filesystem $filesystem, string $tempDirectory) {
        $this->filesystem = $filesystem;
        $this->tempDirectory = $tempDirectory;
    }

    // 解压文件方法
    public function decompress(string $source, string $destination): bool {
# 添加错误处理
        try {
            // 检查源文件是否存在
            if (!$this->filesystem->exists($source)) {
                throw new \Exception("Source file not found");
            }

            // 创建目标目录
            $this->filesystem->mkdir($destination);

            // 根据文件类型选择不同的解压命令
            switch ($this->getFileExtension($source)) {
# 改进用户体验
                case 'zip':
                    $this->decompressZip($source, $destination);
                    break;
                case 'tar':
                case 'tar.gz':
                case 'tgz':
                    $this->decompressTar($source, $destination);
                    break;
                default:
                    throw new \Exception("Unsupported file type");
            }

            return true;
        } catch (\Exception $e) {
            // 错误处理
            error_log($e->getMessage());
# NOTE: 重要实现细节
            return false;
        }
    }

    private function getFileExtension(string $filename): string {
# 增强安全性
        return pathinfo($filename, PATHINFO_EXTENSION);
    }

    private function decompressZip(string $source, string $destination): void {
        $command = sprintf('unzip -o "%s" -d "%s"', escapeshellarg($source), escapeshellarg($destination));
        $this->executeCommand($command);
# FIXME: 处理边界情况
    }

    private function decompressTar(string $source, string $destination): void {
        $command = sprintf('tar -xzf "%s" -C "%s"', escapeshellarg($source), escapeshellarg($destination));
        $this->executeCommand($command);
    }
# FIXME: 处理边界情况

    private function executeCommand(string $command): void {
        $process = new Process($command);
        $process->run();

        // 检查命令是否成功执行
        if (!$process->isSuccessful()) {
# TODO: 优化性能
            throw new ProcessFailedException($process);
        }
    }
# NOTE: 重要实现细节
}

// 使用示例
// 创建文件系统对象和临时目录
$filesystem = new Filesystem();
$tempDirectory = sys_get_temp_dir();

// 创建解压工具对象
$decompressionTool = new FileDecompressionTool($filesystem, $tempDirectory);

// 解压文件
$sourceFile = 'path/to/source.zip';
$destinationDirectory = 'path/to/destination';
$success = $decompressionTool->decompress($sourceFile, $destinationDirectory);

// 检查解压是否成功
if ($success) {
    echo "File decompressed successfully\
";
} else {
    echo "Failed to decompress file\
";
}
