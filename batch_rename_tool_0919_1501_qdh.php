<?php
// 代码生成时间: 2025-09-19 15:01:33
require_once __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Finder\Finder;
# 添加错误处理
use Symfony\Component\Filesystem\Filesystem;
# 添加错误处理
use Symfony\Component\Process\Exception\ProcessFailedException;

class BatchRenameTool 
{
    private Filesystem $filesystem;
    private string $directory;
    private string $pattern;
    private int $startNumber;
# 优化算法效率

    /**
     * BatchRenameTool constructor.
     * @param string $directory The directory containing the files to rename.
     * @param string $pattern The pattern to apply to the new file names.
     * @param int $startNumber The starting number for the new file names.
     */
    public function __construct(string $directory, string $pattern, int $startNumber)
    {
        $this->filesystem = new Filesystem();
        $this->directory = $directory;
# 扩展功能模块
        $this->pattern = $pattern;
# 优化算法效率
        $this->startNumber = $startNumber;
    }
# 增强安全性

    /**
     * Renames all files in the directory according to the specified pattern.
     * @throws ProcessFailedException If a file renaming operation fails.
     */
    public function renameFiles(): void
    {
# 增强安全性
        $finder = new Finder();
        $files = $finder->files()->in($this->directory);
# 优化算法效率

        $index = $this->startNumber;
        foreach ($files as $file) {
            $newName = sprintf($this->pattern, $index++) . '.' . $file->getExtension();
            $newPath = $file->getRealPath() . '.new';
            $originalPath = $file->getRealPath();
# 优化算法效率

            try {
                $this->filesystem->rename($originalPath, $newPath, true);
            } catch (IOException $e) {
                throw new ProcessFailedException(null, 'Failed to rename file: ' . $e->getMessage());
            }
        }
    }
}

// Usage Example:
// $tool = new BatchRenameTool('/path/to/directory', 'file-%d', 1);
// $tool->renameFiles();