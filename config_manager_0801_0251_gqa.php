<?php
// 代码生成时间: 2025-08-01 02:51:56
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;

class ConfigManager
{
    private string $configFilePath;

    /**
     * Constructor
     *
     * @param string $configFilePath Path to the configuration file
     */
    public function __construct(string $configFilePath)
    {
        $this->configFilePath = $configFilePath;
    }

    /**
     * Load configuration from file
     *
     * @return array|false Returns the configuration array or false on error
     */
    public function loadConfig()
    {
        if (!file_exists($this->configFilePath)) {
            // Handle error: configuration file not found
            return false;
        }

        try {
            $fileSystem = new Filesystem();
            $yamlContent = $fileSystem->read($this->configFilePath);
            $config = Yaml::parse($yamlContent);

            return $config;
        } catch (ParseException $e) {
            // Handle error: unable to parse YAML
            return false;
        } catch (\Exception $e) {
            // Handle other general errors
            return false;
        }
    }

    /**
     * Save configuration to file
     *
     * @param array $config Configuration array to save
     * @return bool Returns true on success, false on error
     */
    public function saveConfig(array $config): bool
    {
        try {
            $yamlContent = Yaml::dump($config, 4, 2); // 4 spaces indentation, 2 spaces between items
            $fileSystem = new Filesystem();
            $fileSystem->dumpFile($this->configFilePath, $yamlContent);

            return true;
        } catch (\Exception $e) {
            // Handle error: unable to save configuration
            return false;
        }
    }
}
