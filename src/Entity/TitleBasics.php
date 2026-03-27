<?php

namespace App\Entity;

use App\Repository\TitleBasicsRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\TitleRating;

#[ORM\Entity(repositoryClass: TitleBasicsRepository::class)]
#[ORM\Table(name: 'imdb_title_basics')]
class TitleBasics
{

    #[ORM\Id]
    #[ORM\Column(length: 45)]
    private ?string $tconst = null;

    #[ORM\OneToOne(mappedBy: 'titleBasic', targetEntity: TitleRating::class)]
    private ?TitleRating $rating = null;

    #[ORM\Column(length: 255, name: 'primary_title')]
    private ?string $primaryTitle = null;

    #[ORM\Column(name: 'start_year', nullable: true)]
    private ?int $startYear = null;

    #[ORM\Column(name: 'end_year', nullable: true)]
    private ?int $endYear = null;

    #[ORM\Column(name: 'runtime_minutes', nullable: true)]
    private ?int $runtimeMinutes = null;

    #[ORM\Column(name: 'genres', nullable: true)]
    private ?string $genres = null;

    #[ORM\Column(name: 'title_type', nullable: true)]
    private ?string $titleType = null;

    #[ORM\Column(name: 'original_title', type: 'string', length: 255, nullable: true)]
    private ?string $originalTitle = null;

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

    public function getRating(): ?TitleRating
    {
        return $this->rating;
    }

    public function setRating(?TitleRating $rating): static
    {
        $this->rating = $rating;

        return $this;
    }

    public function getPrimaryTitle(): ?string
    {
        return $this->primaryTitle;
    }

    public function setPrimaryTitle(string $primaryTitle): static
    {
        $this->primaryTitle = $primaryTitle;

        return $this;
    }

    public function getStartYear(): ?int
    {
        return $this->startYear;
    }

    public function setStartYear(?int $startYear): static
    {
        $this->startYear = $startYear;

        return $this;
    }

    public function getTitleType(): ?string
    {
        return $this->titleType;
    } 

    public function setTitleType(?string $titleType): static
    {
        $this->titleType = $titleType;

        return $this;
    }

    public function getEndYear(): ?int
    {
        return $this->endYear;
    }

    public function setEndYear(?int $endYear): static
    {
        $this->endYear = $endYear;

        return $this;
    }

    public function getRuntimeMinutes(): ?int
    {
        return $this->runtimeMinutes;
    }

    public function setRuntimeMinutes(?int $runtimeMinutes): static
    {
        $this->runtimeMinutes = $runtimeMinutes;

        return $this;
    }

    public function getGenres(): ?string
    {
        return $this->genres;
    }

    public function setGenres(?string $genres): static
    {
        $this->genres = $genres;

        return $this;
    }

    public function getOriginalTitle(): ?string
    {
        return $this->originalTitle;
    }   

    public function setOriginalTitle(?string $originalTitle): static
    {
        $this->originalTitle = $originalTitle;

        return $this;
    }
}
