<?php

/**
 *
 */

namespace App\DataProvider;

use Faker\Factory;
use App\Entity\Business;
use App\Entity\Review;
use App\Entity\Reviews;
use App\Entity\User;

/**
 * Class BusinessEntityProvider
 * @package App\DataProvider
 */
class BusinessEntityProvider
{
    const BIG_NUMBER_BASE = 4294967295;
    
    const DATE_FORMAT = 'Y-m-d H:i:s';

    /**
     * @var \Faker\Generator
     */
    private $faker;


    /**
     * ShopEntityProvider constructor.
     */
    public function __construct()
    {
        $this->faker = Factory::create();
    }

    /**
     * @param int $amount
     * @return Business
     */
    public function buildWithReviewsAmount(int $amount, $sameUser = false)
    {
        $reviews = new Reviews();
        $sameUserNotAssigned = true;

        for ($x = 0; $x < $amount; $x++) {
            $reviewId = $x + self::BIG_NUMBER_BASE;
            
            if($sameUser && $sameUserNotAssigned) {
            
            }
            else{
                $reviews->add($this->buildReview($reviewId, $sameUser));
            }
        }

        $business = new Business(
            $this->faker->unique()->word(),
            $this->faker->unique()->userName(),
            $this->faker->name(),
            $amount,
            $this->faker->randomFloat(1, 1, 5),
            new \DateTimeImmutable($this->faker->dateTime()->format(self::DATE_FORMAT)),
            new \DateTimeImmutable($this->faker->dateTime()->format(self::DATE_FORMAT)),
            $reviews
        );


        return $business;
    }

    /**
     * @return Review
     */
    private function buildReview($reviewId = null, $sameUser = false)
    {
        if (is_null($reviewId)) {
            $reviewId = $this->faker->unique()->randomNumber + self::BIG_NUMBER_BASE;
        }
        
        if($sameUser) {
            $user = new User(  'fixedUserId', "FixedShared User");
        }
        else{
            $user = new User(
                $this->faker->unique()->word(),
                $this->faker->name()
            );
        }
        
        return new Review(
            $reviewId,
            $this->faker->text(),
            $this->faker->numberBetween(1, 5),
            $user,
            new \DateTimeImmutable($this->faker->dateTime()->format(self::DATE_FORMAT)),
            new \DateTimeImmutable($this->faker->dateTime()->format(self::DATE_FORMAT))
        );
    }
}
