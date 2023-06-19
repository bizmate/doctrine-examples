<?php

/**
 * 
 */

namespace App\Command;

use App\DataProvider\BusinessEntityProvider;
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
    
    private $businessRepository;

    /**
     * @var LoggerInterface
     */
    private $logger;
    
    /**
     * @var BusinessEntityProvider
     */
    private $businessEntityProvider;

    public function __construct(
        LoggerInterface $logger, BusinessRepositoryInterface $businessRepository, BusinessEntityProvider $businessEntityProvider)
    {
        $this->logger = $logger;
        $this->businessRepository = $businessRepository;
        $this->businessEntityProvider = $businessEntityProvider;

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
            $business = $this->businessEntityProvider->buildWithReviewsAmount(4);
            $this->businessRepository->save($business);
            $this->logger->info(__METHOD__ . " saved business with alias: " . $business->getAlias() .
                " and reviews count: " . $business->getReviewCount()
            );
            
            $testBusiness = $this->businessRepository->getByAliasWithReviewsAmount($business->getAlias(), 4);
            $this->logger->info(__METHOD__ . " fetched business with alias: " . $business->getAlias() .
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
                $testBusiness->getReviewCount() + 1, $testBusiness->getRating(),
                $testBusiness->getCreateDate(), $testBusiness->getUpdateDate(), $modifiedTestBusinessReviews
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
