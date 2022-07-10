<?php

namespace App\Entity;

use App\Repository\HeadingRepository;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TranslatableInterface;
use Knp\DoctrineBehaviors\Model\Translatable\TranslatableTrait;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: HeadingRepository::class)]
#[UniqueEntity(fields: 'textID')]
class Heading implements TranslatableInterface
{

    use TranslatableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 50, unique: true)]
    private $textID;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTextID(): ?string
    {
        return $this->textID;
    }

    public function setTextID(string $textID): self
    {
        $this->textID = $textID;

        return $this;
    }

}
