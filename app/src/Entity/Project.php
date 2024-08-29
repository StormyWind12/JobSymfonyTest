<?php

namespace App\Entity;

use App\Repository\ProjectRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
class Project
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank(message: 'Имя проекта не должно быть пустым.')]
    #[ORM\Column(length: 255)]
    private ?string $name_project = null;

    #[Assert\NotBlank(message: 'Имя клиента не должно быть пустым.')]
    #[ORM\Column(length: 255)]
    private ?string $client = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameProject(): ?string
    {
        return $this->name_project;
    }

    public function setNameProject(string $name_project): static
    {
        $this->name_project = $name_project;

        return $this;
    }

    public function getClient(): ?string
    {
        return $this->client;
    }

    public function setClient(string $client): static
    {
        $this->client = $client;

        return $this;
    }
}
