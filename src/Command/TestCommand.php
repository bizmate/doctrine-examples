<?php

/**
 * 
 */

namespace App\Command;

use App\DataProvider\BusinessEntityProvider;
use App\DataProvider\BusinessFixturesProvider;
use App\Entity\Business;
use App\Entity\Reviews;
use App\Infrastructure\BusinessRepositoryInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TestCommand extends Command
{
    const BUSINESS_ID = 'business-id';

    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:business:test';

    public function __construct(
        private LoggerInterface $logger, private BusinessRepositoryInterface $businessRepository,
        private BusinessFixturesProvider $businessFixturesProvider
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setHelp('Pass an id to fetch a business')->setDescription('Test a business relation.');
        /*$this
            // ...
            //->addArgument(self::BUSINESS_ID, InputArgument::REQUIRED, 'pass a ' . self::BUSINESS_ID)
        ;*/
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $businessFixture = $this->businessFixturesProvider->getBusiness('aut');
            $this->logger->debug(
                __METHOD__ . ' class ' . get_class($businessFixture) . " fixture:" .
                json_encode($businessFixture)
            );
            $this->businessRepository->save($businessFixture);
            $this->logger->info(__METHOD__ . " saved business with alias: " . $businessFixture->getAlias() .
                " and reviews count: " . $businessFixture->getReviewCount()
            );
            
            $testBusiness = $this->businessRepository->getByAliasWithReviewsAmount($businessFixture->getAlias(), 4);
            $this->logger->info(__METHOD__ . " fetched business with alias: " . $testBusiness->getAlias() .
                 " and reviews count: " . $testBusiness->getReviewCount()
            );
            $testBusinessReviews = $testBusiness->getReviews();
            
            $modifiedTestBusinessReviewsArray = $testBusinessReviews->slice(2);
    
            $modifiedTestBusinessReviews = new Reviews();
    
            foreach($modifiedTestBusinessReviewsArray as $modifiedTestBusinessReview) {
                $modifiedTestBusinessReviews->add($modifiedTestBusinessReview);
            }
    
            $this->logger->info(
                __METHOD__ . " modified reviews have " . $modifiedTestBusinessReviews->count()
                . " reviews"
            );
    
            $modifiedTestBusiness = new Business(
                $testBusiness->getId(), $testBusiness->getAlias(), $testBusiness->getName().'_ChangeName',
                $testBusiness->getReviewCount() + 1, $testBusiness->getRating(), $modifiedTestBusinessReviews
            );
    
            $this->logger->info(
                __METHOD__ . " modified business has review count " . $modifiedTestBusiness->getReviewCount() . " ."
            );
    
            $updatedBusiness = $this->businessRepository->save($modifiedTestBusiness);
    
            $this->logger->info(
                __METHOD__ . " tried updating the business and result was " . $updatedBusiness . " ."
            );
            
        } catch (\Exception $e) {
            $this->logger->critical(
                __METHOD__ . " exception : " . get_class($e) . " thrown with msg: " . $e->getMessage() . " trace: " .
                $e->getTraceAsString()
            );

            return 1;
        }


        $fetched = $updatedBusiness ? 'Yes' : 'No';

        $output->writeln(
            'Reviews for businessId/Alias : ' . $testBusiness->getAlias() . ' . Fetched? ' . $fetched
        );
        
        return 0;
    }
}
