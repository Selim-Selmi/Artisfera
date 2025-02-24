<?php

namespace App\Entity;

use App\Repository\MusiqueRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MusiqueRepository::class)]
class Musique
{
    
     #[ORM\Id]
        #[ORM\GeneratedValue]
        #[ORM\Column]
        private ?int $id = null;
    
        #[ORM\Column(length: 255)]
        #[Assert\NotBlank(message: "Le titre est requis.")]
        #[Assert\Length(
            max: 255,
            maxMessage: "Le titre ne peut pas dépasser {{ limit }} caractères."
        )]
        private ?string $titre = null;
    
        
        #[ORM\Column]
        private ?int $artistId = null;
    
        #[ORM\Column(length: 255)]
        #[Assert\NotBlank(message: "Le genre est requis.")]
        #[Assert\Length(
            max: 255,
            maxMessage: "Le genre ne peut pas dépasser {{ limit }} caractères."
        )]
        private ?string $genre = null;
    
        #[ORM\Column(type: Types::DATE_MUTABLE)]
        #[Assert\NotBlank(message: "La date de sortie est requise.")]
        #[Assert\Type("\DateTimeInterface", message: "La date de sortie doit être une date valide.")]
        private ?\DateTimeInterface $dateSortie = null;
    
        #[ORM\Column(length: 255)]
        // #[Assert\NotBlank(message: "Le chemin du fichier est requis.")]
        private ?string $cheminFichier = null;
    
        #[ORM\Column(length: 255)]
        #[Assert\NotBlank(message: "La description est requise.")]
        #[Assert\Length(
            max: 255,
            maxMessage: "La description ne peut pas dépasser {{ limit }} caractères."
        )]
        private ?string $description = null;
    
        #[ORM\Column(length: 255, nullable: true)]
        private ?string $photo = null;
    
        #[ORM\Column(length: 255)]
        #[Assert\NotBlank(message: "Le Nom de l'artiste est requise.")]
        #[Assert\Length(
            max: 255,
            maxMessage: "Le artist Name ne peut pas dépasser {{ limit }} caractères."
        )]
        private ?string $artistName = null;
     
    
    /**
     * @var Collection<int, Playlist>
     */
    #[ORM\ManyToMany(targetEntity: Playlist::class, inversedBy: 'musiques')]
    private Collection $playlists;

    
    public function __construct()
    {
        $this->playlists = new ArrayCollection();
    }

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

    public function getArtistId(): ?int
    {
        return $this->artistId;
    }

    public function setArtistId(int $artistId): static
    {
        $this->artistId = $artistId;

        return $this;
    }

    public function getGenre(): ?string
    {
        return $this->genre;
    }

    public function setGenre(string $genre): static
    {
        $this->genre = $genre;

        return $this;
    }

    public function getDateSortie(): ?\DateTimeInterface
    {
        return $this->dateSortie;
    }

    public function setDateSortie(\DateTimeInterface $dateSortie): static
    {
        $this->dateSortie = $dateSortie;

        return $this;
    }

    public function getCheminFichier(): ?string
    {
        return $this->cheminFichier;
    }

    public function setCheminFichier(string $cheminFichier): static
    {
        $this->cheminFichier = $cheminFichier;

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

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): static
    {
        $this->photo = $photo;

        return $this;
    }

/**
 * @return Collection<int, Playlist>
 */
public function getPlaylists(): Collection
{
    return $this->playlists;
}

public function addPlaylist(Playlist $playlist): static
{
    if (!$this->playlists->contains($playlist)) {
        $this->playlists->add($playlist);
    }

    return $this;
}

public function removePlaylist(Playlist $playlist): static
{
    $this->playlists->removeElement($playlist);

    return $this;
}

public function getArtistName(): ?string
{
    return $this->artistName;
}

public function setArtistName(string $artistName): static
{
    $this->artistName = $artistName;

    return $this;
}
}
