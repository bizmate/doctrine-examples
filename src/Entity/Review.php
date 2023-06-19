<?php

/**
 *
 */

namespace App\Entity;

use DateTimeImmutable as DateTimeImmutableAlias;

/**
 * Class Review
 * @package App\Entities
 */
class Review
{
    /**
     * @var string
     */
    private string $id;
    /**
     * @var string
     */
    private string $text;
    /**
     * @var int
     */
    private int $rating;
    /**
     * @var User
     */
    private User $user;
    /**
     * @var DateTimeImmutableAlias
     */
    private $createTimestamp;
    /**
     * @var DateTimeImmutableAlias
     */
    private $updateTimestamp;

    /**
     * Forced by doctrine
     *
     * @var Business
     */
    private $business;

    /**
     * @param string $id
     * @param string $text
     * @param int $rating
     * @param User $user
     * @param DateTimeImmutableAlias $createTimestamp
     * @param DateTimeImmutableAlias $updateTimestamp
     */
    public function __construct(
        string $id,
        string $text,
        int $rating,
        User $user,
        DateTimeImmutableAlias $createTimestamp,
        DateTimeImmutableAlias $updateTimestamp
    ) {
        $this->id = $id;
        $this->text = $text;
        $this->rating = $rating;
        $this->user = $user;
        $this->createTimestamp = $createTimestamp;
        $this->updateTimestamp = $updateTimestamp;
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
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @return int
     */
    public function getRating(): int
    {
        return $this->rating;
    }

    /**
     * @return Business
     */
    public function getBusiness(): Business
    {
        return $this->business;
    }

    /**
     * @return DateTimeImmutableAlias
     */
    public function getCreateTimestamp(): DateTimeImmutableAlias
    {
        return $this->createTimestamp;
    }

    /**
     * @return DateTimeImmutableAlias
     */
    public function getUpdateTimestamp(): DateTimeImmutableAlias
    {
        return $this->updateTimestamp;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return void
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }
}
