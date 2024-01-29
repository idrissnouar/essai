<?php
namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
#[ORM\Id]
#[ORM\GeneratedValue]
#[ORM\Column(type: 'integer')]
private int $id;

#[ORM\Column(type: 'string', length: 255, unique: true)]
#[Assert\NotBlank()]
#[Assert\Length(max: 255)]
#[Assert\Email()]
private ?string $email;

#[ORM\Column(type: 'json')]
private array $roles = [];

#[ORM\Column(type: 'string')]
#[Assert\NotBlank()]
#[Assert\Length(min: 6, max: 40)]
private string $password;

#[ORM\Column(type: 'string', length: 100, unique: true)]
#[Assert\NotBlank(groups: ['add'])]
#[Assert\Length(min: 4, max: 100, groups: ['add'])]
private string $pseudo;

public function getId(): ?int
{
return $this->id;
}

public function getEmail(): ?string
{
return $this->email;
}

public function setEmail(string $email): self
{
$this->email = $email;

return $this;
}

public function getPseudo(): ?string
{
return $this->pseudo;
}

public function setPseudo(string $pseudo): self
{
$this->pseudo = $pseudo;

return $this;
}

/**
* The public representation of the user (e.g. a username, an email address, etc.)
*
* @see UserInterface
*/
public function getUserIdentifier(): string
{
return (string) $this->email;
}

/**
* @see UserInterface
*/
public function getRoles(): array
{
$roles = $this->roles;
// guarantee every user at least has ROLE_USER
$roles[] = 'ROLE_USER';

return array_unique($roles);
}

public function setRoles(array $roles): self
{
$this->roles = $roles;

return $this;
}

/**
* @see PasswordAuthenticatedUserInterface
*/
public function getPassword(): string
{
return $this->password;
}

public function setPassword(string $password): self
{
$this->password = $password;

return $this;
}

/**
* Returning a salt is only needed if you are not using a modern
* hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
*
* @see UserInterface
*/
public function getSalt(): ?string
{
return null;
}

/**
* @see UserInterface
*/
public function eraseCredentials(): void
{
// If you store any temporary, sensitive data on the user, clear it here
// $this->plainPassword = null;
}
}