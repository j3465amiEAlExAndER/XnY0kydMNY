<?php
// 代码生成时间: 2025-08-28 01:47:59
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class ProcessManager
{
    /**
     * Runs a command in a new process and returns the output.
     *
     * @param string $command The command to execute.
     *
     * @return string The output of the command.
     *
     * @throws ProcessFailedException
     */
    public function runCommand(string $command): string
    {
        // Create a new process
        $process = new Process($command);

        // Run the process
        $process->run();

        // Check if the process was successful
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        // Return the output of the process
        return $process->getOutput();
    }

    /**
     * Starts a process and keeps it running in the background.
     *
     * @param string $command The command to execute.
     *
     * @return void
     */
    public function startBackgroundProcess(string $command): void
    {
        // Create a new process with the command
        $process = new Process($command);

        // Set the process to run in the background and start it
        $process->start();
    }
}
