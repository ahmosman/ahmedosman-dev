<?php

namespace App\Entity;

use App\Repository\TimelineRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TranslatableInterface;
use Knp\DoctrineBehaviors\Model\Translatable\TranslatableTrait;

#[ORM\Entity(repositoryClass: TimelineRepository::class)]
class Timeline implements TranslatableInterface
{
    use TranslatableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'date', nullable: true)]
    private $date;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $link;

    #[ORM\ManyToOne(targetEntity: TimelineCategory::class, inversedBy: 'timelines')]
    private $timelineCategory;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): self
    {
        $this->link = $link;

        return $this;
    }

    public function getTimelineCategory(): ?TimelineCategory
    {
        return $this->timelineCategory;
    }

    public function setTimelineCategory(?TimelineCategory $timelineCategory): self
    {
        $this->timelineCategory = $timelineCategory;

        return $this;
    }
}
