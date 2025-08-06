<?php
// 代码生成时间: 2025-08-06 23:18:59
require_once 'vendor/autoload.php';

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use ZipArchive;

class FileDecompressor {
    /**
     * The path to the file to be decompressed.
     *
     * @var string
     */
    private string $filePath;

    /**
     * The path where to decompress the file.
     *
     * @var string
     */
    private string $destinationPath;

    /**
     * Constructor.
     *
     * @param string $filePath
     * @param string $destinationPath
     */
    public function __construct(string $filePath, string $destinationPath) {
        $this->filePath = $filePath;
        $this->destinationPath = $destinationPath;
    }

    /**
     * Decompresses the file to the destination path.
     *
     * @return bool
     * @throws Exception
     */
    public function decompress(): bool {
        $fs = new Filesystem();
        if (!$fs->exists($this->filePath)) {
            throw new Exception("The file to decompress does not exist.");
        }

        $zip = new ZipArchive();
        if ($zip->open($this->filePath) === true) {
            $zip->extractTo($this->destinationPath);
            $zip->close();
            return true;
        } else {
            throw new Exception("Failed to open the zip file.");
        }
    }

    /**
     * Sets the file path.
     *
     * @param string $filePath
     */
    public function setFilePath(string $filePath): void {
        $this->filePath = $filePath;
    }

    /**
     * Sets the destination path.
     *
     * @param string $destinationPath
     */
    public function setDestinationPath(string $destinationPath): void {
        $this->destinationPath = $destinationPath;
    }
}

// Example usage
try {
    $decompressor = new FileDecompressor("path/to/archive.zip", "path/to/destination");
    if ($decompressor->decompress()) {
        echo "Decompression successful.\
";
    } else {
        echo "Decompression failed.\
";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\
";
}
