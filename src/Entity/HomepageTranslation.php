<?php

namespace App\Entity;

use App\Repository\HomepageRepository;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TranslationInterface;
use Knp\DoctrineBehaviors\Model\Translatable\TranslatableTrait;
use Knp\DoctrineBehaviors\Model\Translatable\TranslationTrait;

#[ORM\Entity(repositoryClass: HomepageRepository::class)]
class HomepageTranslation implements TranslationInterface
{
    use TranslationTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 50)]
    private $heading;

    #[ORM\Column(type: 'string', length: 255)]
    private $subheading;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHeading(): ?string
    {
        return $this->heading;
    }

    public function setHeading(string $heading): self
    {
        $this->heading = $heading;

        return $this;
    }

    public function getSubheading(): ?string
    {
        return $this->subheading;
    }

    public function setSubheading(string $subheading): self
    {
        $this->subheading = $subheading;

        return $this;
    }
}
