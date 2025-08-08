<?php
// 代码生成时间: 2025-08-08 11:30:04
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class FileBackupSyncTool {

    /**
     * @var Filesystem
# 扩展功能模块
     */
    private $filesystem;

    /**
     * @var string
     */
    private $sourceDir;
# 增强安全性

    /**
     * @var string
     */
    private $targetDir;
# 添加错误处理

    /**
     * Constructor
     *
     * @param string $sourceDir The source directory to backup/sync from
     * @param string $targetDir The target directory to backup/sync to
     */
    public function __construct($sourceDir, $targetDir) {
        $this->filesystem = new Filesystem();
        $this->sourceDir = $sourceDir;
# 优化算法效率
        $this->targetDir = $targetDir;
    }

    /**
     * Backup files from the source directory to the target directory
     *
     * @param array $fileExtensions Optional array of file extensions to include
     */
    public function backupFiles($fileExtensions = []) {
        // Find all files in the source directory
        $finder = new Finder();
        $files = $finder->files()->in($this->sourceDir);
# TODO: 优化性能

        // Filter files by extension if provided
        if (!empty($fileExtensions)) {
            $files = $files->name('/\.' . implode('|\.', $fileExtensions) . '$/');
        }

        foreach ($files as $file) {
            $sourcePath = $file->getPathname();
            $relativePath = $file->getRelativePathname();
            $targetPath = $this->targetDir . DIRECTORY_SEPARATOR . $relativePath;
# 添加错误处理

            // Create the target directory if it doesn't exist
            $this->filesystem->mkdir(dirname($targetPath));

            // Copy the file to the target directory
            $this->filesystem->copy($sourcePath, $targetPath);
        }
    }

    /**
     * Synchronize files between the source and target directories
     *
     * @param bool $deleteOnSource If true, delete files from the source that are not present in the target
     */
    public function syncFiles($deleteOnSource = false) {
# 改进用户体验
        $sourceFiles = $this->getFiles($this->sourceDir);
        $targetFiles = $this->getFiles($this->targetDir);

        foreach ($sourceFiles as $file) {
            if (!in_array($file, $targetFiles)) {
                $sourcePath = $this->sourceDir . DIRECTORY_SEPARATOR . $file;
# 优化算法效率
                $targetPath = $this->targetDir . DIRECTORY_SEPARATOR . $file;
# NOTE: 重要实现细节

                // Create the target directory if it doesn't exist
                $this->filesystem->mkdir(dirname($targetPath));
# 添加错误处理

                // Copy the file to the target directory
                $this->filesystem->copy($sourcePath, $targetPath);
            }
        }

        foreach ($targetFiles as $file) {
            if (!in_array($file, $sourceFiles)) {
                $this->filesystem->remove($this->targetDir . DIRECTORY_SEPARATOR . $file);
            }
        }

        if ($deleteOnSource) {
            foreach ($sourceFiles as $file) {
                if (!in_array($file, $targetFiles)) {
                    $this->filesystem->remove($this->sourceDir . DIRECTORY_SEPARATOR . $file);
                }
# FIXME: 处理边界情况
            }
        }
# 增强安全性
    }

    /**
     * Get a list of files in a directory, including subdirectories
     *
     * @param string $dir The directory to scan
     * @return array An array of file names
     */
    private function getFiles($dir) {
        $files = [];
        $iterator = new \RecursiveIteratorIterator(
# 添加错误处理
            new \RecursiveDirectoryIterator($dir),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($iterator as $file) {
            if (!$file->isDir()) {
                $files[] = $file->getFilename();
            }
        }

        return $files;
    }
}
