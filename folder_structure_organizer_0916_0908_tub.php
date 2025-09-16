<?php
// 代码生成时间: 2025-09-16 09:08:10
class FolderStructureOrganizer
{

    /**
     * @var string The root directory to start organizing.
     */
    private $rootDirectory;

    /**
     * Constructor for the FolderStructureOrganizer class.
     *
     * @param string $rootDirectory The root directory to start organizing.
     */
    public function __construct($rootDirectory)
    {
        $this->rootDirectory = $rootDirectory;
    }

    /**
     * Scans the directory and organizes the files.
     *
     * @return void
     */
    public function organize()
    {
        if (!is_dir($this->rootDirectory)) {
            throw new InvalidArgumentException("The provided root directory is not a valid directory.");
        }

        $files = $this->scanDirectory($this->rootDirectory);
        $this->sortFiles($files);
    }

    /**
     * Scans the directory recursively and returns an array of files.
     *
     * @param string $directory The directory to scan.
     * @return array An array of files.
     */
    private function scanDirectory($directory)
    {
        $files = [];
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($directory),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($iterator as $file) {
            if (!$file->isDir()) {
                $files[] = $file->getPathname();
            }
        }

        return $files;
    }

    /**
     * Sorts the files into separate folders based on their extensions.
     *
     * @param array $files An array of files.
     * @return void
     */
    private function sortFiles($files)
    {
        foreach ($files as $file) {
            $extension = pathinfo($file, PATHINFO_EXTENSION);
            $newDirectory = $this->rootDirectory . '/' . $extension;

            if (!is_dir($newDirectory)) {
                mkdir($newDirectory, 0777, true);
            }

            rename($file, $newDirectory . '/' . basename($file));
        }
    }

}

// Example usage:
try {
    $organizer = new FolderStructureOrganizer('/path/to/your/directory');
    $organizer->organize();
    echo "Folder structure organized successfully.
";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "
";
}