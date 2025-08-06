<?php
// 代码生成时间: 2025-08-06 08:21:43
use Symfony\Component\Yaml\Yaml;
use Doctrine\DBAL\DriverManager;
use Doctrine\Migrations\Configuration\Configuration;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\Migrations\MigrationsVersion;

// DatabaseMigrationTool 类
class DatabaseMigrationTool
{
    private string $migrationsDir;
    private string $dbPath;
    private Configuration $configuration;

    public function __construct(string $migrationsDir, string $dbPath)
    {
        $this->migrationsDir = $migrationsDir;
        $this->dbPath = $dbPath;
        $this->configuration = $this->initConfiguration();
    }

    // 初始化配置
    private function initConfiguration(): Configuration
    {
        $configYml = Yaml::parseFile($this->dbPath);
        $conn = DriverManager::getConnection($configYml['doctrine']);

        $configuration = new Configuration($conn);
        $configuration->setMigrationsNamespace('App\Migrations');
        $configuration->setMigrationsDirectory($this->migrationsDir);

        return $configuration;
    }

    // 执行迁移
    public function migrate(): void
    {
        try {
            $dependencyFactory = DependencyFactory::fromConfiguration($this->configuration);
            $version = new MigrationsVersion($dependencyFactory);
            $version->migrateLatest();
        } catch (Exception $e) {
            // 错误处理
            error_log($e->getMessage());
            throw $e;
        }
    }
}

// 使用示例
try {
    $migrationsDir = __DIR__ . '/migrations';
    $dbPath = __DIR__ . '/config/packages/doctrine.yaml';

    $migrationTool = new DatabaseMigrationTool($migrationsDir, $dbPath);
    $migrationTool->migrate();
} catch (Exception $e) {
    // 错误处理
    error_log($e->getMessage());
    exit(1);
}
