<?php
// 代码生成时间: 2025-10-01 01:39:27
require_once 'vendor/autoload.php';

use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class TempFileCleanerCommand extends Command
{
    protected static $defaultName = 'app:temp-file-cleaner';
    protected static $defaultDescription = 'Cleans up temporary files in a specified directory';

    protected function configure()
    {
        $this
            ->setDescription(self::$defaultDescription)
            ->addArgument('directory', InputArgument::REQUIRED, 'The directory to clean up temporary files from')
            ->addOption('dry-run', null, InputOption::VALUE_NONE, 'List files that would be deleted instead of actually deleting them')
            ->addOption('age', null, InputOption::VALUE_REQUIRED, 'Only delete files older than this number of days', 7)
            ->addOption('suffix', null, InputOption::VALUE_REQUIRED, 'Only delete files with this suffix', '.tmp');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $directory = $input->getArgument('directory');
        $dryRun = $input->getOption('dry-run');
        $age = $input->getOption('age');
        $suffix = $input->getOption('suffix');

        // Check if the directory exists
        if (!is_dir($directory)) {
            $io->error("The specified directory does not exist: {$directory}");
            return Command::FAILURE;
        }

        // Find temporary files in the directory
        $finder = new Finder();
        $finder->files()
            ->in($directory)
            ->name('/' . preg_quote($suffix, '/') . '$/')
            ->date('since ' . $age . ' days ago');

        $files = iterator_to_array($finder);

        if (empty($files)) {
            $io->success('No files to delete.');
            return Command::SUCCESS;
        }

        // List files that would be deleted (dry run)
        if ($dryRun) {
            foreach ($files as $file) {
                $io->note($file->getRealPath());
            }
            return Command::SUCCESS;
        }

        // Delete files
        foreach ($files as $file) {
            if ($file instanceof SplFileInfo && $file->isFile()) {
                if (!unlink($file->getRealPath())) {
                    $io->error("Failed to delete file: {$file->getRealPath()}");
                    continue;
                }
                $io->success("Deleted file: {$file->getRealPath()}");
            }
        }

        return Command::SUCCESS;
    }
}

// Register the command with the console application
// Assuming you have a console application setup with Symfony Console component
// $application->add(new TempFileCleanerCommand());

 ?>