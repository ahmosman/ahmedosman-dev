<?php

namespace App\Entity;

use Knp\DoctrineBehaviors\Contract\Entity\TranslatableInterface;
use Knp\DoctrineBehaviors\Model\Translatable\TranslatableTrait;
use Doctrine\Common\Collections\Collection;

abstract class AbstractTranslatableCategoryEntity implements TranslatableInterface
{
    use TranslatableTrait;
    abstract public function getTranslatableCollection(): Collection;
}