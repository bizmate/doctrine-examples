<?php

/**
 *
 */

namespace App\Infrastructure\Persistence\Doctrine;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception\DriverException;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Business;
use App\Entity\Reviews;
use App\Infrastructure\BusinessRepositoryInterface;
use Psr\Log\LoggerInterface;


/**
 * Class ShopRepository
 * @package App\Infrastructure\Persistence\Doctrine
 */
class BusinessRepository extends ServiceEntityRepository implements BusinessRepositoryInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;
    
    public function __construct(ManagerRegistry $registry, LoggerInterface $logger)
    {
        parent::__construct($registry, Business::class);
        $this->logger = $logger;
    }
    /**
     * @param Business $business
     * @return bool
     */
    public function save(Business $business): bool
    {
        try{
            $businessFound = $this->findOneBy( array('alias'=>$business->getAlias()));
            
            if (!$businessFound instanceof Business) {
                $this->getEntityManager()->persist($business);
            }
            
            $this->getEntityManager()->flush();
        }
        catch (\Exception $e) {
            $querySql = '';
            if($e instanceof DriverException){
                $querySql = $e->getQuery()->getSQL() . " params: " . json_encode($e->getQuery()->getParams());
            }
            $this->logger->critical(
                __METHOD__ . " exception class:" . get_class($e) . " when saving businessId: " . $business->getAlias() . 
                " with error: " . $e->getMessage() . " sql: " . $querySql . " trace: " . $e->getTraceAsString());
            
            return false;
        }
        
        return true;
    }

    /**
     * @param Business $business
     * @return bool
     */
    public function remove(Business $business): bool
    {
        $businessFound = $this->findOneBy( array('alias'=>$business->getAlias()));
        if ($businessFound instanceof Business) {
            $this->getEntityManager()->remove($businessFound);
            $this->getEntityManager()->flush();
        }
        else{
            return false;
        }

        return true;
    }
    
    /**
     * @param string $businessId
     * @param int|null $amount
     * @return Business
     * @throws \Exception
     */
    public function getByAliasWithReviewsAmount(string $businessId, int $amount = null): Business
    {
        $business = $this->findOneBy( array('alias'=>$businessId) );

        if ($business instanceof Business) {
            
            if ($amount && $business->getReviewCount() > $amount) {
                $reviews = $business->getReviews();
                $reviewsArray = $reviews->slice(0, $amount);

                $business->setReviews(new Reviews($reviewsArray));
            }

            return $business;
        }

        throw new \Exception();
    }
}
