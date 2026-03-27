<?php

namespace App\Entity;

use App\Repository\TitleCrewRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TitleCrewRepository::class)]
#[ORM\Table(name: 'imdb_title_crew')]
class TitleCrew
{
    #[ORM\Id]
    #[ORM\Column(length: 16)]
    private ?string $tconst = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $directors = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $writers = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTconst(): ?string
    {
        return $this->tconst;
    }

    public function setTconst(string $tconst): static
    {
        $this->tconst = $tconst;

        return $this;
    }

    public function getDirectors(): ?string
    {
        return $this->directors;
    }

    public function setDirectors(?string $directors): static
    {
        $this->directors = $directors;

        return $this;
    }

    public function getWriters(): ?string
    {
        return $this->writers;
    }

    public function setWriters(?string $writers): static
    {
        $this->writers = $writers;

        return $this;
    }
}
