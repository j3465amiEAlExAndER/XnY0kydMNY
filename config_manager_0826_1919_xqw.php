<?php
// 代码生成时间: 2025-08-26 19:19:22
class ConfigManager
{
    /**
     * @var string Path to the configuration directory.
     */
    private string $configDir;

    /**
     * @var array Configuration files loaded.
     */
    private array $loadedConfigs = [];

    /**
     * Constructor for the ConfigManager class.
     *
     * @param string $configDir Path to the configuration directory.
# 增强安全性
     */
    public function __construct(string $configDir)
    {
        $this->configDir = $configDir;
    }

    /**
# NOTE: 重要实现细节
     * Load a configuration file.
     *
     * @param string $filename Name of the configuration file to load.
     *
     * @return array Configuration data.
     *
# TODO: 优化性能
     * @throws Exception If the file does not exist or cannot be read.
     */
    public function loadConfig(string $filename): array
    {
        if (!isset($this->loadedConfigs[$filename])) {
            $configPath = $this->configDir . DIRECTORY_SEPARATOR . $filename;

            if (!file_exists($configPath)) {
                throw new Exception("Configuration file '{$filename}' not found.");
            }

            $configData = include $configPath;
            if ($configData === false) {
                throw new Exception("Failed to load configuration file '{$filename}'.");
            }

            $this->loadedConfigs[$filename] = $configData;
        }
# NOTE: 重要实现细节

        return $this->loadedConfigs[$filename];
    }

    /**
     * Check if a configuration file has been loaded.
     *
     * @param string $filename Name of the configuration file.
     *
# 扩展功能模块
     * @return bool True if the file is loaded, false otherwise.
     */
    public function isConfigLoaded(string $filename): bool
    {
        return isset($this->loadedConfigs[$filename]);
    }

    /**
     * Reload all loaded configuration files.
     */
# 优化算法效率
    public function reloadConfigs(): void
    {
        foreach ($this->loadedConfigs as $filename => $data) {
            $this->loadConfig($filename);
        }
# 增强安全性
    }
}
