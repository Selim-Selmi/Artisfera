<?php

namespace App\Entity;

use App\Repository\PeintureRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PeintureRepository::class)]
class Peinture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le titre est obligatoire.")]
    #[Assert\Length(
        min: 3,
        max: 255,
        minMessage: "Le titre doit contenir au moins {{ limit }} caractères.",
        maxMessage: "Le titre ne peut pas dépasser {{ limit }} caractères."
    )]

    #[Assert\Regex(
        pattern: "/^[a-zA-ZÀ-ÿ\s]+$/u",
        message: "Le titre ne doit contenir que des lettres et des espaces."
    )]
    private ?string $titre = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotNull(message: "La date de création est obligatoire.")]
    #[Assert\LessThanOrEqual("today", message: "La date de création ne peut pas être dans le futur.")]
    private ?\DateTimeInterface $date_cr = null;

    #[ORM\Column(length: 255)]
    //#[Assert\NotNull(message: "Le tableau est obligatoire.")]
    private ?string $tableau = null;

    #[ORM\ManyToOne(inversedBy: 'peintures')]
    private ?Style $type = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDateCr(): ?\DateTimeInterface
    {
        return $this->date_cr;
    }

    public function setDateCr(\DateTimeInterface $date_cr): static
    {
        $this->date_cr = $date_cr;

        return $this;
    }

    public function getTableau(): ?string
    {
        return $this->tableau;
    }

    public function setTableau(string $tableau): static
    {
        $this->tableau = $tableau;

        return $this;
    }

    public function getType(): ?Style
    {
        return $this->type;
    }

    public function setType(?Style $type): static
    {
        $this->type = $type;

        return $this;
    }
}
