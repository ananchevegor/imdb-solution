<?php

namespace App\Entity;

use App\Repository\TitleAkasRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TitleAkasRepository::class)]
#[ORM\Table(name: 'imdb_title_akas')]
class TitleAkas
{
    #[ORM\Id]
    #[ORM\Column(length: 16)]
    private ?string $title_id = null;

    #[ORM\Column]
    private ?int $ordering = null;

    #[ORM\Column(nullable: true)]
    private ?string $title = null;

    #[ORM\Column(length: 8, nullable: true)]
    private ?string $region = null;

    #[ORM\Column(length: 32, nullable: true)]
    private ?string $language = null;

    #[ORM\Column(nullable: true)]
    private ?string $types = null;

    #[ORM\Column(nullable: true)]
    private ?string $attributes = null;

    #[ORM\Column(nullable: true)]
    private ?int $is_original_title = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitleId(): ?string
    {
        return $this->title_id;
    }

    public function setTitleId(string $title_id): static
    {
        $this->title_id = $title_id;

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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion(?string $region): static
    {
        $this->region = $region;

        return $this;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(?string $language): static
    {
        $this->language = $language;

        return $this;
    }

    public function getTypes(): ?string
    {
        return $this->types;
    }

    public function setTypes(?string $types): static
    {
        $this->types = $types;

        return $this;
    }

    public function getAttributes(): ?string
    {
        return $this->attributes;
    }

    public function setAttributes(?string $attributes): static
    {
        $this->attributes = $attributes;

        return $this;
    }

    public function getIsOriginalTitle(): ?int
    {
        return $this->is_original_title;
    }

    public function setIsOriginalTitle(?int $is_original_title): static
    {
        $this->is_original_title = $is_original_title;

        return $this;
    }
}
