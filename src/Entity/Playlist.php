<?php

namespace App\Entity;

use App\Repository\PlaylistRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PlaylistRepository::class)]
class Playlist
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le titre de la playlist est requis.")]
    #[Assert\Length(
        max: 255,
        maxMessage: "Le titre ne peut pas dépasser {{ limit }} caractères."
    )]
    private ?string $titreP = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "L'ID de l'utilisateur est requis.")]
    private ?int $userId = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message: "La date de création est requise.")]
    #[Assert\Type("\DateTimeInterface", message: "La date de création doit être une date valide.")]
    private ?\DateTimeInterface $dateCreation = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "La description est requise.")]
    #[Assert\Length(
        max: 255,
        maxMessage: "La description ne peut pas dépasser {{ limit }} caractères."
    )]
    private ?string $description = null;

    /**
     * @var Collection<int, Musique>
     */
    #[ORM\ManyToMany(targetEntity: Musique::class, mappedBy: 'playlists')]
    private Collection $musiques;

    public function __construct()
    {
        $this->musiques = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitreP(): ?string
    {
        return $this->titreP;
    }

    public function setTitreP(string $titreP): static
    {
        $this->titreP = $titreP;

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

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): static
    {
        $this->dateCreation = $dateCreation;

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

/**
 * @return Collection<int, Musique>
 */
public function getMusiques(): Collection
{
    return $this->musiques;
}

public function addMusique(Musique $musique): static
{
    if (!$this->musiques->contains($musique)) {
        $this->musiques->add($musique);
        $musique->addPlaylist($this);
    }

    return $this;
}

public function removeMusique(Musique $musique): static
{
    if ($this->musiques->removeElement($musique)) {
        $musique->removePlaylist($this);
    }

    return $this;
}

}
