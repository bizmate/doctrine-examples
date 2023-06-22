<?php

/**
 *
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;

class Reviews extends ArrayCollection implements \JsonSerializable
{
    public function jsonSerialize(): mixed
    {
        return $this->getValues();
    }
}
