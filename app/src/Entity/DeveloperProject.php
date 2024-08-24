<?php

namespace App\Entity;

use App\Repository\DeveloperProjectRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DeveloperProjectRepository::class)]
class DeveloperProject
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $developer_id = null;

    #[ORM\Column]
    private ?int $project_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDeveloperId(): ?int
    {
        return $this->developer_id;
    }

    public function setDeveloperId(int $developer_id): static
    {
        $this->developer_id = $developer_id;

        return $this;
    }

    public function getProjectId(): ?int
    {
        return $this->project_id;
    }

    public function setProjectId(int $project_id): static
    {
        $this->project_id = $project_id;

        return $this;
    }
}
