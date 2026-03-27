<?php

namespace App\Entity;

use App\Repository\NameBasicsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NameBasicsRepository::class)]
#[ORM\Table(name: 'imdb_name_basics')]
class NameBasics
{
    #[ORM\Id]

    #[ORM\Column(length: 16)]
    private ?string $nconst = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $primary_name = null;

    #[ORM\Column(nullable: true)]
    private ?int $birth_year = null;

    #[ORM\Column(nullable: true)]
    private ?int $death_year = null;

    #[ORM\Column(nullable: true)]
    private ?string $primary_profession = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $known_for_titles = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNconst(): ?string
    {
        return $this->nconst;
    }

    public function setNconst(string $nconst): static
    {
        $this->nconst = $nconst;

        return $this;
    }

    public function getPrimaryName(): ?string
    {
        return $this->primary_name;
    }

    public function setPrimaryName(?string $primary_name): static
    {
        $this->primary_name = $primary_name;

        return $this;
    }

    public function getBirthYear(): ?int
    {
        return $this->birth_year;
    }

    public function setBirthYear(?int $birth_year): static
    {
        $this->birth_year = $birth_year;

        return $this;
    }

    public function getDeathYear(): ?int
    {
        return $this->death_year;
    }

    public function setDeathYear(?int $death_year): static
    {
        $this->death_year = $death_year;

        return $this;
    }

    public function getPrimaryProfession(): ?string
    {
        return $this->primary_profession;
    }

    public function setPrimaryProfession(?string $primary_profession): static
    {
        $this->primary_profession = $primary_profession;

        return $this;
    }

    public function getKnownForTitles(): ?string
    {
        return $this->known_for_titles;
    }

    public function setKnownForTitles(?string $known_for_titles): static
    {
        $this->known_for_titles = $known_for_titles;

        return $this;
    }
}
