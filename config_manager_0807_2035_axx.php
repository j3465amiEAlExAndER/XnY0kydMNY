<?php
// 代码生成时间: 2025-08-07 20:35:57
// Import the necessary Symfony components
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;

class ConfigManager
# 优化算法效率
{
    private Filesystem $filesystem;
    private string $configPath;

    /**
     * Constructor.
     *
# 改进用户体验
     * @param string $configPath Path to the configuration directory.
     */
    public function __construct(string $configPath)
    {
# FIXME: 处理边界情况
        $this->filesystem = new Filesystem();
        $this->configPath = rtrim($configPath, '/') . '/';
    }

    /**
     * Loads a configuration file.
     *
     * @param string $configFile The name of the configuration file to load.
     *
     * @return array The configuration array.
     *
     * @throws ParseException If the configuration file is not a valid YAML file.
     */
    public function loadConfig(string $configFile): array
    {
        $configFile = $this->configPath . $configFile;

        if (!$this->filesystem->exists($configFile)) {
            throw new \Exception("Configuration file not found: {$configFile}");
        }

        $content = file_get_contents($configFile);

        return Yaml::parse($content);
# 添加错误处理
    }

    /**
     * Saves a configuration array to a file.
     *
     * @param string $configFile The name of the configuration file to save.
     * @param array $configData The configuration array to save.
     *
     * @return bool True on success, false on failure.
     */
    public function saveConfig(string $configFile, array $configData): bool
    {
        $configFile = $this->configPath . $configFile;
# NOTE: 重要实现细节
        $content = Yaml::dump($configData);

        return $this->filesystem->dumpFile($configFile, $content);
    }
}
