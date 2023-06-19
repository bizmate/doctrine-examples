<?php

/**
 *
 */

namespace App\Entity;


/**
 * Class BusinessId
 * @package App\Entity
 */
class BusinessId
{
    /**
     * @var int
     */
    private $id;

    /**
     * BusinessId constructor.
     * @param $id
     */
    public function __construct(string $id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }
}
