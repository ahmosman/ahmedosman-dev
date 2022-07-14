<?php

namespace App\Entity;

use App\Repository\TimelineCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
use Knp\DoctrineBehaviors\Contract\Entity\TranslatableInterface;
use Knp\DoctrineBehaviors\Model\Translatable\TranslatableTrait;

#[ORM\Entity(repositoryClass: TimelineCategoryRepository::class)]
class TimelineCategory implements TranslatableInterface
{
    use TranslatableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\OneToMany(mappedBy: 'timelineCategory', targetEntity: Timeline::class)]
    private $timelines;

    public function __construct()
    {
        $this->timelines = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Timeline>
     */
    public function getTimelines(): Collection
    {
        return $this->timelines;
    }

    public function addTimeline(Timeline $timeline): self
    {
        if (!$this->timelines->contains($timeline)) {
            $this->timelines[] = $timeline;
            $timeline->setTimelineCategory($this);
        }

        return $this;
    }

    public function removeTimeline(Timeline $timeline): self
    {
        if ($this->timelines->removeElement($timeline)) {
            // set the owning side to null (unless already changed)
            if ($timeline->getTimelineCategory() === $this) {
                $timeline->setTimelineCategory(null);
            }
        }

        return $this;
    }

    #[Pure] public function __toString(): string
    {
        return $this->getId();
    }

}
