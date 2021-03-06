<?php

namespace App\Entity;

use App\Repository\ProjectRepository;
use App\Service\UploaderHelper;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
use Knp\DoctrineBehaviors\Contract\Entity\TranslatableInterface;
use Knp\DoctrineBehaviors\Model\Translatable\TranslatableTrait;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
class Project implements TranslatableInterface
{
    use TranslatableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $imageFilename;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $githubLink;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $webLink;

    #[ORM\OneToMany(mappedBy: 'project', targetEntity: ProjectSlide::class)]
    #[ORM\OrderBy(['orderValue' => 'ASC'])]
    private $projectSlides;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $orderValue;

    public function __construct()
    {
        $this->projectSlides = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getGithubLink(): ?string
    {
        return $this->githubLink;
    }

    public function setGithubLink(?string $githubLink): self
    {
        $this->githubLink = $githubLink;

        return $this;
    }

    public function getWebLink(): ?string
    {
        return $this->webLink;
    }

    public function setWebLink(?string $webLink): self
    {
        $this->webLink = $webLink;

        return $this;
    }


    /**
     * @return Collection<int, ProjectSlide>
     */
    public function getProjectSlides(): Collection
    {
        return $this->projectSlides;
    }

    public function addProjectSlide(ProjectSlide $projectSlide): self
    {
        if (!$this->projectSlides->contains($projectSlide)) {
            $this->projectSlides[] = $projectSlide;
            $projectSlide->setProject($this);
        }

        return $this;
    }

    public function removeProjectSlide(ProjectSlide $projectSlide): self
    {
        if ($this->projectSlides->removeElement($projectSlide)) {
            // set the owning side to null (unless already changed)
            if ($projectSlide->getProject() === $this) {
                $projectSlide->setProject(null);
            }
        }

        return $this;
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

    #[Pure] public function __toString(): string
    {
        return $this->translate('pl')->getTitle();
    }

}
