<?php

namespace App\Entity;

use App\Repository\OeuvreRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\CeramicCollection;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: OeuvreRepository::class)]
class Oeuvre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $userId = null;

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
    private ?string $dimensions = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le créateur est obligatoire.")]
    #[Assert\Regex(pattern: "/^[a-zA-Z\s]+$/", message: "Le créateur ne doit contenir que des lettres.")]
    private ?string $createur = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "La catégorie est obligatoire.")]
    #[Assert\Regex(pattern: "/^[a-zA-Z\s]+$/", message: "La catégorie ne doit contenir que des lettres.")]
    private ?string $categorie = null;

    // Jointure ManyToOne avec CeramicCollection
    #[ORM\ManyToOne(targetEntity: CeramicCollection::class, inversedBy: 'oeuvres')]
    #[ORM\JoinColumn(nullable: false)]
    private ?CeramicCollection $ceramicCollection = null;

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

    public function getDimensions(): ?string
    {
        return $this->dimensions;
    }

    public function setDimensions(string $dimensions): static
    {
        $this->dimensions = $dimensions;
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

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(string $categorie): static
    {
        $this->categorie = $categorie;
        return $this;
    }

    public function getCeramicCollection(): ?CeramicCollection
    {
        return $this->ceramicCollection;
    }

    public function setCeramicCollection(?CeramicCollection $ceramicCollection): self
    {
        $this->ceramicCollection = $ceramicCollection;
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
