<?php

/**
 *
 */

namespace App\Infrastructure;

use App\Entity\Business;
use Exception;

interface BusinessRepositoryInterface
{
    /**
     * @param Business $business
     * @return bool
     */
    public function save(Business $business): bool;

    /**
     * @param Business $business
     * @return bool
     */
    public function remove(Business $business): bool;

    /**
     * @param string $businessId
     * @param int $reviewsAmount
     * @return Business
     * @throws Exception
     */
    public function getByAliasWithReviewsAmount(string $businessId, int $reviewsAmount): Business;
}
