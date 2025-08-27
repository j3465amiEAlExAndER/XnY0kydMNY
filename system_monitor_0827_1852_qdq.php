<?php
// 代码生成时间: 2025-08-27 18:52:33
 * It includes error handling, documentation, and best practices for maintainability and scalability.
 */

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class SystemMonitorController
{
    private $kernel;

    public function __construct($kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @Route("/monitor", name="system_monitor")
     */
    public function monitorAction(): Response
    {
        try {
            // Get system information
            $systemInfo = $this->getSystemInfo();

            // Get CPU load
            $cpuLoad = $this->getCpuLoad();

            // Get memory usage
            $memoryUsage = $this->getMemoryUsage();

            // Get disk usage
            $diskUsage = $this->getDiskUsage();

            // Compile the results into a single array
            $results = [
                'system_info' => $systemInfo,
                'cpu_load' => $cpuLoad,
                'memory_usage' => $memoryUsage,
                'disk_usage' => $diskUsage
            ];

            // Return the results as a JSON response
            return new Response(json_encode($results), Response::HTTP_OK, ['Content-Type' => 'application/json']);

        } catch (Exception $e) {
            // Handle any exceptions that occur during the monitoring process
            return new Response(json_encode(['error' => $e->getMessage()]), Response::HTTP_INTERNAL_SERVER_ERROR, ['Content-Type' => 'application/json']);
        }
    }

    private function getSystemInfo(): array
    {
        // Fetch system information using the 'uname' command
        $process = new Process(['uname', '-a']);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        return ['system_info' => trim($process->getOutput())];
    }

    private function getCpuLoad(): array
    {
        // Fetch CPU load using the 'uptime' command
        $process = new Process(['uptime', '-s']);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        return ['cpu_load' => trim($process->getOutput())];
    }

    private function getMemoryUsage(): array
    {
        // Fetch memory usage using the 'free' command
        $process = new Process(['free', '-m']);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        return ['memory_usage' => trim($process->getOutput())];
    }

    private function getDiskUsage(): array
    {
        // Fetch disk usage using the 'df' command
        $process = new Process(['df', '-h']);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        return ['disk_usage' => trim($process->getOutput())];
    }
}
