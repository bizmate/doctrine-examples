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
class Review implements \JsonSerializable
{
    use JsonSerialize;
    
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
     */
    public function __construct(
        string $id,
        string $text,
        int $rating,
        User $user
    ) {
        $this->id = $id;
        $this->text = $text;
        $this->rating = $rating;
        $this->user = $user;
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
