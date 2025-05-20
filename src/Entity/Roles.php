<?php

namespace App\Entity;

use App\Repository\RolesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: RolesRepository::class)]
class Roles
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\ManyToMany(targetEntity: Permisos::class, inversedBy: 'roles')]
    #[ORM\JoinTable(name: 'roles_permissions')]
    private Collection $permissions;

    #[ORM\ManyToMany(targetEntity: Usuarios::class, mappedBy: 'roles')]
    private Collection $usuarios;

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

    public function getPermissions(): ?Collection
    {
        return $this->permissions;
    }

    public function setPermissions(?Permisos $permiss): static
    {
        $this->permiss = $permiss;
        return $this;
    }

}
