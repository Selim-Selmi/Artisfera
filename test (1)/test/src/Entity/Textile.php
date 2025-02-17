<?php

namespace App\Entity;

use App\Repository\TextileRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TextileRepository::class)]
class Textile
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le nom est obligatoire.")]
    #[Assert\Regex(pattern: "/^[a-zA-Z\s]+$/", message: "Le nom ne doit contenir que des lettres.")]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le type est obligatoire.")]
    #[Assert\Regex(pattern: "/^[a-zA-Z\s]+$/", message: "Le type ne doit contenir que des lettres.")]
    private ?string $type = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "La description est obligatoire.")]
    #[Assert\Length(min: 10, minMessage: "La description doit contenir au moins 10 caractères.")]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "La matière est obligatoire.")]
    #[Assert\Regex(pattern: "/^[a-zA-Z\s]+$/", message: "La matière ne doit contenir que des lettres.")]
    private ?string $matiere = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "La couleur est obligatoire.")]
    #[Assert\Regex(pattern: "/^[a-zA-Z\s]+$/", message: "La couleur ne doit contenir que des lettres.")]
    private ?string $couleur = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Les dimensions sont obligatoires.")]
    #[Assert\Regex(pattern: "/^\d+x\d+x\d+$/", message: "Le format des dimensions doit être hxlxp (ex: 30x20x15).")]
    private ?string $dimension = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le créateur est obligatoire.")]
    #[Assert\Regex(pattern: "/^[a-zA-Z\s]+$/", message: "Le créateur ne doit contenir que des lettres.")]
    private ?string $createur = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[ORM\Column(length: 255)]
    private ?string $technique = null;
    #[Assert\NotBlank(message: "La technique est obligatoire.")]
    #[Assert\Regex(pattern: "/^[a-zA-Z\s]+$/", message: "La technique ne doit contenir que des lettres.")]

    #[ORM\ManyToOne(targetEntity: CollectionT::class, inversedBy: 'textiles')]
    #[ORM\JoinColumn(name: "collection_id", referencedColumnName: "id", nullable: true)]
    private ?CollectionT $collection = null;

    public function getCollection(): ?CollectionT
    {
        return $this->collection;
    }

    public function setCollection(?CollectionT $collection): static
    {
        $this->collection = $collection;
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

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

    public function getMatiere(): ?string
    {
        return $this->matiere;
    }

    public function setMatiere(string $matiere): static
    {
        $this->matiere = $matiere;

        return $this;
    }

    public function getCouleur(): ?string
    {
        return $this->couleur;
    }

    public function setCouleur(string $couleur): static
    {
        $this->couleur = $couleur;

        return $this;
    }

    public function getDimension(): ?string
    {
        return $this->dimension;
    }

    public function setDimension(string $dimension): static
    {
        $this->dimension = $dimension;

        return $this;
    }

    public function getCreateur(): ?string
    {
        return $this->createur;
    }

    public function setCreateur(string $createur): static
    {
        $this->createur = $createur;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getTechnique(): ?string
    {
        return $this->technique;
    }

    public function setTechnique(string $technique): static
    {
        $this->technique = $technique;

        return $this;
    }
    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): static
    {
        $this->userId = $userId;
        return $this;
    }
}
