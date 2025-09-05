<?php
// 代码生成时间: 2025-09-06 05:12:49
// FolderStructureOrganizer.php
//
// This class is designed to organize a given folder structure by
// sorting files and subdirectories in a specified manner.
//
// @author Your Name
// @version 1.0

require_once 'vendor/autoload.php';

use Symfony\Component\Finder\Finder;

class FolderStructureOrganizer
{
    /**
     * The path to the directory to organize.
     *
     * @var string
     */
    private string $directoryPath;

    /**
     * Constructor to initialize the directory path.
     *
     * @param string $directoryPath The path to the directory.
     */
    public function __construct(string $directoryPath)
    {
        if (!is_dir($directoryPath)) {
            throw new InvalidArgumentException("The provided path is not a valid directory.");
        }

        $this->directoryPath = $directoryPath;
    }

    /**
     * Organizes the directory by sorting files and subdirectories.
     *
     * @return void
     */
    public function organize(): void
    {
        try {
            $finder = new Finder();
            // Find all files and directories within the given directory.
            $filesAndDirs = $finder->in($this->directoryPath);

            // Sort files and directories into separate arrays.
            $files = [];
            $directories = [];
            foreach ($filesAndDirs as $fileInfo) {
                if ($fileInfo->isDir()) {
                    $directories[] = $fileInfo->getRealPath();
                } else {
                    $files[] = $fileInfo->getRealPath();
                }
            }

            // Sort files and directories alphabetically.
            sort($files);
            sort($directories);

            // Create a new directory structure.
            $this->createNewStructure($directories, $files);

        } catch (Exception $e) {
            // Handle any exceptions that may occur during the organizing process.
            error_log("Error organizing directory: " . $e->getMessage());
        }
    }

    /**
     * Creates a new directory structure based on the sorted files and directories.
     *
     * @param array $directories The sorted list of directories.
     * @param array $files The sorted list of files.
     *
     * @return void
     */
    private function createNewStructure(array $directories, array $files): void
    {
        // Create a new directory structure based on the sorted lists.
        foreach ($directories as $dir) {
            // Move each directory to the beginning of the directory path.
            $this->moveItem($dir, $this->directoryPath . '/' . basename($dir));
        }

        foreach ($files as $file) {
            // Move each file to the end of the directory path.
            $this->moveItem($file, $this->directoryPath . '/' . basename($file));
        }
    }

    /**
     * Moves an item to a new location.
     *
     * @param string $source The source path of the item.
     * @param string $destination The destination path of the item.
     *
     * @return void
     */
    private function moveItem(string $source, string $destination): void
    {
        if (!rename($source, $destination)) {
            error_log("Failed to move item from $source to $destination");
        }
    }
}

// Usage example:
// $organizer = new FolderStructureOrganizer('/path/to/your/directory');
// $organizer->organize();
