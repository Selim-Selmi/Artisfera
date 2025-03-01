<?php

namespace App\Entity;

use App\Repository\CommentaireRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity(repositoryClass: CommentaireRepository::class)]
class Commentaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $contenu = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $date = null;

    //jointure avec user
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'commentaires')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(targetEntity: Oeuvre::class, inversedBy: 'commentaires')]
#[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]  // Ajout de onDelete="CASCADE"
private ?Oeuvre $oeuvre = null;


    public function getOeuvre(): ?Oeuvre
    {
        return $this->oeuvre;
    }
    public function setOeuvre(?Oeuvre $oeuvre): static
    {
        $this->oeuvre = $oeuvre;
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): static
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }
    
    public function setDate(\DateTimeImmutable $date): static
    {
        $this->date = $date;
        return $this;
    }
    
    // Getters and Setters
  public function getUser(): ?User
  {
      return $this->user;
  }

  public function setUser(?User $user): static
  {
      $this->user = $user;
      return $this;
  }
  public function __construct()
{
    $this->date = new \DateTimeImmutable(); // Sets the current date & time automatically
}

}
