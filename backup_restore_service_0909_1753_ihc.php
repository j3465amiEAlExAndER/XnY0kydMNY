<?php
// 代码生成时间: 2025-09-09 17:53:38
class BackupRestoreService 
{

    private $backupDir;
    private $sourceDir;
    private $logger;

    /**
     * Constructor
     *
     * @param string $backupDir Directory where backup files will be stored
     * @param string $sourceDir Directory that needs to be backed up
     * @param LoggerInterface $logger Logger instance for logging operations
     */
    public function __construct($backupDir, $sourceDir, LoggerInterface $logger) 
    {
        $this->backupDir = $backupDir;
        $this->sourceDir = $sourceDir;
        $this->logger = $logger;
    }

    /**
     * Backup data
     *
     * @param string $backupName Name of the backup file
     * @return bool True on success, False on failure
     */
    public function backupData($backupName) 
    {
        try {
            // Check if source directory exists
            if (!file_exists($this->sourceDir)) {
                throw new \Exception('Source directory does not exist.');
            }

            // Create backup directory if it does not exist
            if (!file_exists($this->backupDir)) {
                mkdir($this->backupDir, 0777, true);
            }

            // Create archive name with timestamp
            $archiveName = $backupName . '_' . date('YmdHis') . '.zip';
            $archivePath = $this->backupDir . '/' . $archiveName;

            // Create zip archive
            $zip = new ZipArchive();
            if ($zip->open($archivePath, ZipArchive::CREATE) === true) {
                $zip->addGlob($this->sourceDir . '/*', 0);
                $zip->close();
                $this->logger->info('Backup created successfully.');
                return true;
            } else {
                $this->logger->error('Failed to create zip archive.');
                return false;
            }
        } catch (\Exception $e) {
            $this->logger->error('Backup failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Restore data from a backup
     *
     * @param string $backupPath Path to the backup file
     * @return bool True on success, False on failure
     */
    public function restoreData($backupPath) 
    {
        try {
            // Check if backup file exists
            if (!file_exists($backupPath)) {
                throw new \Exception('Backup file does not exist.');
            }

            // Extract zip archive
            $zip = new ZipArchive();
            if ($zip->open($backupPath) === true) {
                $zip->extractTo($this->sourceDir);
                $zip->close();
                $this->logger->info('Restore completed successfully.');
                return true;
            } else {
                $this->logger->error('Failed to extract zip archive.');
                return false;
            }
        } catch (\Exception $e) {
            $this->logger->error('Restore failed: ' . $e->getMessage());
            return false;
        }
    }

}
