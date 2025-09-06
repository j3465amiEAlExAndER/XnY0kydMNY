<?php
// 代码生成时间: 2025-09-07 03:20:47
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Yaml\Yaml;

// FolderStructureOrganizer 类定义了一个文件夹结构整理器
class FolderStructureOrganizer {

    private string $sourceFolder;
    private string $targetFolder;
    private string $structureFile;
    private array $filePermissions = [
        'file' => 0644,
        'dir' => 0755,
    ];

    // 构造函数
    public function __construct(string $sourceFolder, string $targetFolder, string $structureFile) {
        $this->sourceFolder = $sourceFolder;
        $this->targetFolder = $targetFolder;
        $this->structureFile = $structureFile;
    }

    // 执行整理功能
    public function organize() {
        try {
            // 读取结构文件并解析为数组
            $structure = Yaml::parseFile($this->structureFile);

            // 创建目标文件夹
            if (!is_dir($this->targetFolder)) {
                mkdir($this->targetFolder, $this->filePermissions['dir'], true);
            }

            // 遍历源文件夹并整理文件到目标文件夹
            foreach ($structure as $path => $files) {
                $targetPath = rtrim($this->targetFolder, '/') . '/' . ltrim($path, '/');
                if (!is_dir($targetPath)) {
                    mkdir($targetPath, $this->filePermissions['dir'], true);
                }

                foreach ($files as $file) {
                    $sourceFilePath = rtrim($this->sourceFolder, '/') . '/' . ltrim($file, '/');
                    $targetFilePath = $targetPath . '/' . basename($file);

                    if (is_file($sourceFilePath)) {
                        copy($sourceFilePath, $targetFilePath);
                        chmod($targetFilePath, $this->filePermissions['file']);
                    } else {
                        throw new \Exception("File not found: {$sourceFilePath}");
                    }
                }
            }
        } catch (\Exception $e) {
            // 错误处理
            echo "Error: " . $e->getMessage();
        }
    }
}

// 使用示例
// $organizer = new FolderStructureOrganizer('/path/to/source', '/path/to/target', '/path/to/structure.yml');
// $organizer->organize();
