<?php
// 代码生成时间: 2025-09-24 18:20:59
// database_migration_tool.php

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Doctrine\DBAL\Tools\Console\Command as DoctrineCommand;
use Doctrine\DBAL\DriverManager;
use Doctrine\Migrations\Version\MigrationFactory;
use Doctrine\Migrations\Version\SqlMigrationFactory;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\Migrations\Configuration\Migration\MetadataStorageConfiguration;
use Doctrine\Migrations\Configuration\Migration\MetadataStorage\Database\TableMetadataStorageConfiguration;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Doctrine\Migrations\Extensions\Symfony\DependencyInjection\MigratorExtension;

require_once __DIR__ . '/vendor/autoload.php';

// 创建一个Doctrine数据库迁移配置
$metadataStorageConfig = new TableMetadataStorageConfiguration();
$metadataStorageConfig->setTableName('migration_versions');
$metadataStorageConfig->setTableDatabase('db_migration');

// 创建一个Doctrine Connection
$conn = DriverManager::getConnection(['url' => 'mysql://username:password@localhost:3306/db_migration'], new \Doctrine\DBAL\Logging\EchoSQLLogger());

// 创建一个迁移依赖工厂
$dependencyFactory = DependencyFactory::fromEntityManager(null, $conn);
$dependencyFactory->setMetadataStorageConfiguration($metadataStorageConfig);

// 创建迁移版本工厂
$versionFactory = new MigrationFactory();
$sqlMigrationFactory = new SqlMigrationFactory($conn);

// 创建迁移配置
$migratorConfig = $dependencyFactory->getMigrationConfiguration($conn);
$migratorConfig->registerMigrations(__DIR__ . '/migrations');

// 创建Symfony Console应用
$application = new Application();

// 添加Doctrine迁移命令
$application->add(new DoctrineCommand\GenerateCommand($versionFactory, $sqlMigrationFactory, $migratorConfig));
$application->add(new DoctrineCommand\ExecuteCommand($versionFactory, $sqlMigrationFactory, $migratorConfig));
$application->add(new DoctrineCommand\StatusCommand($versionFactory, $sqlMigrationFactory, $migratorConfig));
$application->add(new DoctrineCommand\MigrateCommand($versionFactory, $sqlMigrationFactory, $migratorConfig));
$application->add(new DoctrineCommand\LatestCommand($versionFactory, $sqlMigrationFactory, $migratorConfig));
$application->add(new DoctrineCommand\RollbackCommand($versionFactory, $sqlMigrationFactory, $migratorConfig));
$application->add(new DoctrineCommand\SyncMetadataCommand($versionFactory, $sqlMigrationFactory, $migratorConfig));
$application->add(new DoctrineCommand\UpToDateCommand($versionFactory, $sqlMigrationFactory, $migratorConfig));

// 运行应用
$application->run();

// 添加自定义迁移命令
class CustomMigrationCommand extends Command
{
    protected static $defaultName = 'app:custom-migration';

    protected function configure(): void
    {
        $this
            ->setDescription('Runs a custom migration command')
            ->setHelp('This command allows you to run a custom migration script...');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->note('Running custom migration...');

        try {
            // 执行自定义迁移逻辑
            // $this->runCustomMigrationLogic();

            $io->success('Custom migration completed successfully');

            return Command::SUCCESS;
        } catch (Exception $e) {
            $io->error('Error running custom migration: ' . $e->getMessage());

            return Command::FAILURE;
        }
    }
}

$application->add(new CustomMigrationCommand());