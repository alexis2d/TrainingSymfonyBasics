<?php

namespace App\Entity;

use App\Repository\ConferenceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ConferenceRepository::class)]
class Conference
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 10
    )]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 30
    )]
    private ?string $description = null;

    #[ORM\Column]
    #[Assert\NotNull]
    private ?bool $accessible = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 20
    )]
    private ?string $prerequisites = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\GreaterThanOrEqual('now')]
    private ?\DateTimeImmutable $startAt = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\GreaterThanOrEqual(propertyPath: 'startAt')]
    private ?\DateTimeImmutable $endAt = null;

    /**
     * @var Collection<int, Organization>
     */
    #[ORM\ManyToMany(targetEntity: Organization::class, inversedBy: 'conferences')]
    #[Assert\NotBlank]
    #[Assert\Valid]
    private Collection $organizations;

    /**
     * @var Collection<int, Volunteering>
     */
    #[ORM\OneToMany(targetEntity: Volunteering::class, mappedBy: 'conference')]
    #[Assert\NotBlank]
    private Collection $volunteerings;

    public function __construct()
    {
        $this->organizations = new ArrayCollection();
        $this->volunteerings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function isAccessible(): ?bool
    {
        return $this->accessible;
    }

    public function setAccessible(bool $accessible): static
    {
        $this->accessible = $accessible;

        return $this;
    }

    public function getPrerequisites(): ?string
    {
        return $this->prerequisites;
    }

    public function setPrerequisites(?string $prerequisites): static
    {
        $this->prerequisites = $prerequisites;

        return $this;
    }

    public function getStartAt(): ?\DateTimeImmutable
    {
        return $this->startAt;
    }

    public function setStartAt(\DateTimeImmutable $startAt): static
    {
        $this->startAt = $startAt;

        return $this;
    }

    public function getEndAt(): ?\DateTimeImmutable
    {
        return $this->endAt;
    }

    public function setEndAt(\DateTimeImmutable $endAt): static
    {
        $this->endAt = $endAt;

        return $this;
    }

    /**
     * @return Collection<int, Organization>
     */
    public function getOrganizations(): Collection
    {
        return $this->organizations;
    }

    public function addOrganization(Organization $organization): static
    {
        if (!$this->organizations->contains($organization)) {
            $this->organizations->add($organization);
        }

        return $this;
    }

    public function removeOrganization(Organization $organization): static
    {
        $this->organizations->removeElement($organization);

        return $this;
    }

    /**
     * @return Collection<int, Volunteering>
     */
    public function getVolunteerings(): Collection
    {
        return $this->volunteerings;
    }

    public function addVolunteering(Volunteering $volunteering): static
    {
        if (!$this->volunteerings->contains($volunteering)) {
            $this->volunteerings->add($volunteering);
            $volunteering->setConference($this);
        }

        return $this;
    }

    public function removeVolunteering(Volunteering $volunteering): static
    {
        if ($this->volunteerings->removeElement($volunteering)) {
            // set the owning side to null (unless already changed)
            if ($volunteering->getConference() === $this) {
                $volunteering->setConference(null);
            }
        }

        return $this;
    }
}
