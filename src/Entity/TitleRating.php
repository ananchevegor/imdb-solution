<?php

namespace App\Entity;

use App\Repository\TitleRatingRepository;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: TitleRatingRepository::class)]
#[ORM\Table(name: 'imdb_title_ratings')]
class TitleRating
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 255)]
    private ?string $tconst = null;

    #[ORM\OneToOne(inversedBy: 'rating', targetEntity: TitleBasics::class)]
    #[ORM\JoinColumn(name: 'tconst', referencedColumnName: 'tconst')]
    private ?TitleBasics $titleBasic = null;

    #[ORM\Column(type: 'float', name: 'averagerating')]
    private ?float $averageRating = null;

    #[ORM\Column(type: 'integer', name: 'numvotes')]
    private ?int $numVotes = null;

    public function getTconst(): ?string
    {
        return $this->tconst;
    }

    public function setTconst(string $tconst): self
    {
        $this->tconst = $tconst;

        return $this;
    }

    public function getAverageRating(): ?float
    {
        return $this->averageRating;
    }

    public function setAverageRating(float $averageRating): self
    {
        $this->averageRating = $averageRating;

        return $this;
    }

    public function getNumVotes(): ?int
    {
        return $this->numVotes;
    }

    public function setNumVotes(int $numVotes): self
    {
        $this->numVotes = $numVotes;

        return $this;
    }
}