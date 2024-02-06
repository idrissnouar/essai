<?php

namespace App\Entity;

use App\Repository\CompanyRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: CompanyRepository::class)]
class Company
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $name = null;

    #[ORM\Column()]
    private ?\DateTime $created = null;

    #[ORM\Column()]
    private ?\DateTime $updated = null;

    #[ORM\OneToMany(targetEntity: Job::class, mappedBy: 'company')]
    private Collection $jobs;

    public function __construct()
    {
        $this->jobs = new ArrayCollection();
    }

    /**
     * @return Collection<int, Job>
     */
    public function getJobs(): Collection
    {
        return $this->jobs;
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

    public function setCreated(?\DateTime $created): static
    {
        $this->created = $created;

        return $this;
    }

    public function setUpdated(?\DateTime $updated): static
    {
        $this->updated = $updated;

        return $this;
    }
}
