<?php

namespace App\Entity;

use App\Repository\ProjectSlideRepository;
use App\Service\UploaderHelper;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TranslatableInterface;
use Knp\DoctrineBehaviors\Model\Translatable\TranslatableTrait;

#[ORM\Entity(repositoryClass: ProjectSlideRepository::class)]
class ProjectSlide implements TranslatableInterface
{
    use TranslatableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $orderValue;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $imageFilename;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrderValue(): ?int
    {
        return $this->orderValue;
    }

    public function setOrderValue(?int $orderValue): self
    {
        $this->orderValue = $orderValue;

        return $this;
    }

    public function getImagePath()
    {
        return UploaderHelper::PROJECT_IMAGE . '/' . $this->getImageFilename();
    }

    public function getImageFilename(): ?string
    {
        return $this->imageFilename;
    }

    public function setImageFilename(?string $imageFilename): self
    {
        $this->imageFilename = $imageFilename;

        return $this;
    }
}
