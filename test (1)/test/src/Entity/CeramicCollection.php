<?php

namespace App\Entity;

use App\Repository\CeramicCollectionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Oeuvre;

#[ORM\Entity(repositoryClass: CeramicCollectionRepository::class)]
class CeramicCollection
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $userId = null;

    #[ORM\Column(length: 255)]
    private ?string $nom_c = null;

    #[ORM\Column(length: 255)]
    private ?string $description_c = null;

    // Jointure OneToMany avec Oeuvre
    #[ORM\OneToMany(mappedBy: 'ceramicCollection', targetEntity: Oeuvre::class, cascade: ['persist', 'remove'])]
    private Collection $oeuvres;

    public function __construct()
    {
        $this->oeuvres = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomC(): ?string
    {
        return $this->nom_c;
    }

    public function setNomC(string $nom_c): static
    {
        $this->nom_c = $nom_c;
        return $this;
    }

    public function getDescriptionC(): ?string
    {
        return $this->description_c;
    }

    public function setDescriptionC(string $description_c): static
    {
        $this->description_c = $description_c;
        return $this;
    }

    /**
     * @return Collection<int, Oeuvre>
     */
    public function getOeuvres(): Collection
    {
        return $this->oeuvres;
    }

    public function addOeuvre(Oeuvre $oeuvre): self
    {
        if (!$this->oeuvres->contains($oeuvre)) {
            $this->oeuvres->add($oeuvre);
            $oeuvre->setCeramicCollection($this);
        }
        return $this;
    }

    public function removeOeuvre(Oeuvre $oeuvre): self
    {
        if ($this->oeuvres->removeElement($oeuvre)) {
            if ($oeuvre->getCeramicCollection() === $this) {
                $oeuvre->setCeramicCollection(null);
            }
        }
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
