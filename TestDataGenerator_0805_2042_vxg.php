<?php
// 代码生成时间: 2025-08-05 20:42:50
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Faker\Factory;

class TestDataGenerator extends Command
{
    protected static $defaultName = 'app:test-data';

    /**
     * Configures the command.
     *
     * @return void
     */
    protected function configure()
    {
        $this
            ->setDescription('Generates test data for the application.')
            ->addArgument('count', InputArgument::OPTIONAL, 'Number of records to generate', 10)
            ->addOption(
                'type',
                null,
                InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                'Type of data to generate',
                ['users', 'products']
            );
    }

    /**
     * Executes the command.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $faker = Factory::create();
        $count = (int) $input->getArgument('count');
        $types = $input->getOption('type');

        try {
            foreach ($types as $type) {
                $this->generateData($type, $count, $output);
            }

            $output->writeln("<info>Test data generated successfully.</info>");

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $output->writeln("<error>Error generating test data: {$e->getMessage()}</error>");

            return Command::FAILURE;
        }
    }

    /**
     * Generates test data for the specified type.
     *
     * @param string $type
     * @param int    $count
     * @param OutputInterface $output
     *
     * @return void
     */
    private function generateData($type, $count, OutputInterface $output)
    {
        switch ($type) {
            case 'users':
                for ($i = 0; $i < $count; $i++) {
                    $user = [
                        'name' => $faker->name,
                        'email' => $faker->email,
                        'address' => $faker->address,
                    ];

                    // Store or process the user data
                    $this->processUser($user, $output);
                }
                break;

            case 'products':
                for ($i = 0; $i < $count; $i++) {
                    $product = [
                        'name' => $faker->word,
                        'price' => $faker->randomFloat(2, 0, 1000),
                        'description' => $faker->text,
                    ];

                    // Store or process the product data
                    $this->processProduct($product, $output);
                }
                break;

            default:
                $output->writeln("<error>Unknown data type: {$type}</error>");
                break;
        }
    }

    /**
     * Processes a user record.
     *
     * @param array  $user
     * @param OutputInterface $output
     *
     * @return void
     */
    private function processUser($user, OutputInterface $output)
    {
        // Implement user processing logic here
        $output->writeln("<info>Generated user: {$user['name']}</info>");
    }

    /**
     * Processes a product record.
     *
     * @param array  $product
     * @param OutputInterface $output
     *
     * @return void
     */
    private function processProduct($product, OutputInterface $output)
    {
        // Implement product processing logic here
        $output->writeln("<info>Generated product: {$product['name']}</info>");
    }
}
