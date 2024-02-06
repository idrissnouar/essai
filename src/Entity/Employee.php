<?php

namespace App\Entity;

use App\Repository\EmployeeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EmployeeRepository::class)]
class Employee
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $lastName = null;

    #[ORM\Column()]
    #[Assert\GreaterThan('-150 years')]
    private ?\DateTime $birthDate = null;

    #[ORM\Column()]
    private ?\DateTime $created = null;

    #[ORM\Column()]
    private ?\DateTime $updated = null;

    #[ORM\OneToMany(targetEntity: Job::class, mappedBy: 'employee')]
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

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }
    public function getBirthDate(): ?\DateTime
    {
        return $this->birthDate;
    }

    public function setBirthDate(?\DateTime $birthDate): static
    {
        $this->birthDate = $birthDate;

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
