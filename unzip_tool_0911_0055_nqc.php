<?php
// 代码生成时间: 2025-09-11 00:55:43
 * easy to maintain and expand.
 */

require_once 'vendor/autoload.php';

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use ZipArchive;

class UnzipTool {
    private string $zipFilePath;
    private string $destinationPath;

    public function __construct(string $zipFilePath, string $destinationPath) {
        $this->zipFilePath = $zipFilePath;
        $this->destinationPath = $destinationPath;
    }

    /**
     * Unzips the file to the specified destination.
     *
     * @return bool Returns true on success, false on failure.
     */
    public function unzip(): bool {
        // Check if the file exists
        if (!file_exists($this->zipFilePath)) {
            throw new \Exception('Zip file does not exist');
        }

        // Create the destination directory if it doesn't exist
        $filesystem = new Filesystem();
        if (!$filesystem->exists($this->destinationPath)) {
            $filesystem->mkdir($this->destinationPath);
        }

        // Open the zip file
        $zip = new ZipArchive();
        if ($zip->open($this->zipFilePath) === true) {
            // Extract the files to the destination path
            if ($zip->extractTo($this->destinationPath)) {
                $zip->close();
                return true;
            } else {
                $zip->close();
                throw new \Exception('Failed to extract zip file');
            }
        } else {
            throw new \Exception('Failed to open zip file');
        }
    }
}

// Example usage:
try {
    $zipFilePath = 'path/to/your/file.zip';
    $destinationPath = 'path/to/destination/directory';
    $unzipper = new UnzipTool($zipFilePath, $destinationPath);
    if ($unzipper->unzip()) {
        echo "Unzip operation was successful.";
    } else {
        echo "Unzip operation failed.";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
