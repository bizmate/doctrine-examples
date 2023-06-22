<?php

/**
 *
 */

namespace App\Entity;

/**
 * Class Business
 * @package App\Entity
 *
 * This structure is based on the Business response to business resource detail.
 */

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Class Shop
 * @package App\Entities
 */
class Business implements \JsonSerializable
{
    use JsonSerialize;
    /**
     * @var string
     */
    private string $id;
    /**
     * @var string
     */
    private string $alias;
    /**
     * @var string
     */
    private string $name;
    /**
     * @var int
     */
    private int $reviewCount;
    /**
     * @var float
     */
    private float $rating;
    /**
     * @var DateTimeImmutable
     */
    private DateTimeImmutable $createDate;
    /**
     * @var DateTimeImmutable
     */
    private DateTimeImmutable $updateDate;
    /**
     * Notice: Reviews is an ArrayCollection. If i typehint this as Reviews then Doctrine will not be able to pass
     * PersistentCollection and will throw an error. As such we hint it as the Collection interface
     *
     * @var Doctrine\Common\Collections\Collection<Review>
     */
    private Collection $reviews;
    
    /**
     * @param string $id
     * @param string $alias
     * @param string $name
     * @param int $reviewCount
     * @param float $rating
     * @param DateTimeImmutable $createDate
     * @param DateTimeImmutable $updateDate
     * @param Reviews|null $reviews
     */
    public function __construct(
        string $id,
        string $alias,
        string $name,
        int $reviewCount,
        float $rating,
        DateTimeImmutable $createDate,
        DateTimeImmutable $updateDate,
        Reviews $reviews = null
    ) {
        $this->id = $id;
        $this->alias = $alias;
        $this->name = $name;
        $this->reviewCount = $reviewCount;
        $this->rating = $rating;
        $this->createDate = $createDate;
        $this->updateDate = $updateDate;
        $this->reviews = $reviews ?? new Reviews();
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getAlias(): string
    {
        return $this->alias;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getReviewCount(): int
    {
        return $this->reviewCount;
    }

    /**
     * @return float
     */
    public function getRating(): float
    {
        return $this->rating;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getCreateDate(): DateTimeImmutable
    {
        return $this->createDate;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getUpdateDate(): DateTimeImmutable
    {
        return $this->updateDate;
    }

    /**
     * @return Collection
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    /**
     * @param Reviews $reviews
     * @return $this
     */
    public function setReviews(Reviews $reviews)
    {
        $this->reviews = $reviews;

        return $this;
    }
}
