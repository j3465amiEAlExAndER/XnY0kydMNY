<?php
// 代码生成时间: 2025-08-22 22:43:20
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use League\Flysystem\Filesystem as Flysystem;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Adapter\AwsS3;
use League\Flysystem\AwsS3V3\AwsS3Adapter;
use Exception;

// 文件备份和同步工具
class FileBackupSyncTool {
    private $sourcePath;
    private $destinationPath;
    private $filesystem;
    private $adapter;
    private $options;

    public function __construct($sourcePath, $destinationPath, $options = []) {
        $this->sourcePath = $sourcePath;
        $this->destinationPath = $destinationPath;
        $this->options = $options;
        $this->filesystem = new Filesystem();
        $this->adapter = $this->createAdapter($options);
        $this->filesystem->mountFilesystem('backup', $this->adapter);
    }

    // 创建适配器
    private function createAdapter($options) {
        if (isset($options['adapter']) && $options['adapter'] === 's3') {
            $client = new Aws\S3\S3Client($options);
            return new AwsS3Adapter($client, $options['bucket'], isset($options['prefix']) ? $options['prefix'] : '');
        } else {
            return new Local($options['path']);
        }
    }

    // 备份文件
    public function backupFiles() {
        try {
            $finder = new Finder();
            $files = $finder->files()->in($this->sourcePath);

            foreach ($files as $file) {
                $relativePath = $file->getRelativePathname();
                $this->filesystem->copy($this->sourcePath . '/' . $relativePath, 'backup://' . $relativePath);
            }

            echo "Files backed up successfully.\
";
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage() . "\
";
        }
    }

    // 同步文件
    public function syncFiles() {
        try {
            $finder = new Finder();
            $sourceFiles = $finder->files()->in($this->sourcePath);
            $destinationFiles = $finder->files()->in($this->destinationPath);

            // 删除目标路径中多余的文件
            foreach ($destinationFiles as $file) {
                $relativePath = $file->getRelativePathname();
                if (!$sourceFiles->in($this->sourcePath . '/' . $relativePath)->hasResults()) {
                    $this->filesystem->delete($this->destinationPath . '/' . $relativePath);
                }
            }

            // 同步源路径中的文件
            foreach ($sourceFiles as $file) {
                $relativePath = $file->getRelativePathname();
                $this->filesystem->copy($this->sourcePath . '/' . $relativePath, $this->destinationPath . '/' . $relativePath);
            }

            echo "Files synced successfully.\
";
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage() . "\
";
        }
    }
}

// 用法示例
$sourcePath = "/path/to/source";
$destinationPath = "/path/to/destination";
$options = [
    'adapter' => 's3',
    'key' => 'your_aws_key',
    'secret' => 'your_aws_secret',
    'region' => 'your_aws_region',
    'bucket' => 'your_s3_bucket',
    'prefix' => 'backup/',
    'path' => '/path/to/local/backup'
];

$tool = new FileBackupSyncTool($sourcePath, $destinationPath, $options);

// 备份文件
$tool->backupFiles();

// 同步文件
$tool->syncFiles();

?>