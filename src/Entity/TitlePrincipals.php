<?php

namespace App\Entity;

use App\Repository\TitlePrincipalsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TitlePrincipalsRepository::class)]
#[ORM\Table(name: 'imdb_title_principals')]
class TitlePrincipals
{
    #[ORM\Id]
    #[ORM\Column(length: 16)]
    private ?string $tconst = null;

    #[ORM\Column]
    private ?int $ordering = null;

    #[ORM\Column(nullable: true)]
    private ?string $nconst = null;

    #[ORM\Column(nullable: true)]
    private ?string $category = null;

    #[ORM\Column(nullable: true)]
    private ?string $job = null;

    #[ORM\Column(nullable: true)]
    private ?string $characters = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTconsts(): ?string
    {
        return $this->tconsts;
    }

    public function setTconsts(string $tconsts): static
    {
        $this->tconsts = $tconsts;

        return $this;
    }

    public function getOrdering(): ?int
    {
        return $this->ordering;
    }

    public function setOrdering(int $ordering): static
    {
        $this->ordering = $ordering;

        return $this;
    }

    public function getNconst(): ?string
    {
        return $this->nconst;
    }

    public function setNconst(?string $nconst): static
    {
        $this->nconst = $nconst;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(?string $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getJob(): ?string
    {
        return $this->job;
    }

    public function setJob(?string $job): static
    {
        $this->job = $job;

        return $this;
    }

    public function getCharacters(): ?string
    {
        return $this->characters;
    }

    public function setCharacters(?string $characters): static
    {
        $this->characters = $characters;

        return $this;
    }
}
