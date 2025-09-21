<?php
// 代码生成时间: 2025-09-22 02:27:28
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use League\Flysystem\Filesystem as FlyFilesystem;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Adapter\AwsS3;
use Aws\S3\S3Client;
use League\Flysystem\AwsS3v3\AwsS3Adapter;

/**
 * FileSyncTool
 *
 * A tool for file backup and synchronization using Symfony and Flysystem.
 */
class FileSyncTool {
    private $sourcePath;
    private $destinationPath;
    private $filesystem;
    private $s3;
    private $adapter;

    /**
     * Constructor
     *
     * @param string $sourcePath The source directory path
     * @param string $destinationPath The destination directory path
     */
    public function __construct($sourcePath, $destinationPath) {
        $this->sourcePath = $sourcePath;
        $this->destinationPath = $destinationPath;
        $this->filesystem = new Filesystem();
        
        // Initialize AWS S3 client
        $this->s3 = new S3Client(
            array(
                'version' => 'latest',
                'region'  => 'your-region',
                'credentials' => array(
                    'key'    => 'your-key',
                    'secret' => 'your-secret',
                ),
            )
        );
    }

    /**
     * Sync files from source to destination
     *
     * @return void
     */
    public function syncFiles() {
        try {
            $finder = new Finder();
            $finder->in($this->sourcePath)->depth(0);
            foreach ($finder as $file) {
                $file->getRealPath();
                $relativePath = $file->getRelativePathname();
                $destination = $this->destinationPath . '/' . $relativePath;
                $this->filesystem->copy($file->getRealPath(), $destination);
            }
        } catch (Exception $e) {
            // Handle error
            echo 'Error syncing files: ' . $e->getMessage();
        }
    }

    /**
     * Backup files to AWS S3
     *
     * @param string $bucketName The AWS S3 bucket name
     * @return void
     */
    public function backupToS3($bucketName) {
        try {
            $this->adapter = new AwsS3Adapter($this->s3, $bucketName);
            $flyFilesystem = new FlyFilesystem($this->adapter);
            $finder = new Finder();
            $finder->in($this->sourcePath)->depth(0);
            foreach ($finder as $file) {
                $file->getRealPath();
                $relativePath = $file->getRelativePathname();
                $flyFilesystem->write($relativePath, $file->getContents());
            }
        } catch (Exception $e) {
            // Handle error
            echo 'Error backing up files: ' . $e->getMessage();
        }
    }
}
