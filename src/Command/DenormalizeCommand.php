<?php

/**
 * This command exist for the sole point of implementing a serializer from JSON to Business
 */

namespace App\Command;


use App\DataProvider\BusinessFixturesProvider;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DenormalizeCommand extends Command
{
    protected static $defaultName = 'app:business:denormalize';
    

    public function __construct(private LoggerInterface $logger, private BusinessFixturesProvider $businessFixturesProvider)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setHelp('Pass an id to fetch a business')->setDescription('Denormilize a business JSON with Serializer.');
        /*$this
            // ...
            //->addArgument(self::BUSINESS_ID, InputArgument::REQUIRED, 'pass a ' . self::BUSINESS_ID)
        ;*/
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $businessFixture = $this->businessFixturesProvider->getBusiness('aut');
            $this->logger->info(
                __METHOD__ . " Fixture class: " . get_class($businessFixture) . " JSON encoded: " . json_encode($businessFixture)
            );
        } catch (\Exception $e) {
            $this->logger->critical(
                __METHOD__ . " exception : " . get_class($e) . " thrown with msg: " . $e->getMessage() .
                " trace: " .  $e->getTraceAsString()
            );

            return 1;
        }
        
        return 0;
    }
}
