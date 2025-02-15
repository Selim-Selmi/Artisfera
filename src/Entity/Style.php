<?php

namespace App\Entity;

use App\Repository\StyleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: StyleRepository::class)]
class Style
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le type de peinture est obligatoire.")]
    #[Assert\Length(
        min: 3,
        max: 30,
        minMessage: "Le type doit contenir au moins {{ limit }} caractères.",
        maxMessage: "Le type ne peut pas dépasser {{ limit }} caractères."
    )]
    #[Assert\Regex(
        pattern: "/^[a-zA-ZÀ-ÿ\s]+$/u",
        message: "Le type ne doit contenir que des lettres et des espaces."
    )]
    private ?string $type_p = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "La description est obligatoire.")]
    #[Assert\Length(
        min: 10,
        max: 255,
        minMessage: "La description doit contenir au moins {{ limit }} caractères.",
        maxMessage: "La description ne peut pas dépasser {{ limit }} caractères."
    )]
    private ?string $description = null;


    #[ORM\Column(length: 255)]
    private ?string $extab = null;

    /**
     * @var Collection<int, Peinture>
     */
    #[ORM\OneToMany(targetEntity: Peinture::class, mappedBy: 'type', cascade: ['persist', 'remove'])]

    private Collection $peintures;

    public function __construct()
    {
        $this->peintures = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypeP(): ?string
    {
        return $this->type_p;
    }

    public function setTypeP(string $type_p): static
    {
        $this->type_p = $type_p;

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

    public function getExtab(): ?string
    {
        return $this->extab;
    }

    public function setExtab(string $extab): static
    {
        $this->extab = $extab;

        return $this;
    }

    /**
     * @return Collection<int, Peinture>
     */
    public function getPeintures(): Collection
    {
        return $this->peintures;
    }

    public function addPeinture(Peinture $peinture): static
    {
        if (!$this->peintures->contains($peinture)) {
            $this->peintures->add($peinture);
            $peinture->setType($this);
        }

        return $this;
    }

    public function removePeinture(Peinture $peinture): static
    {
        if ($this->peintures->removeElement($peinture)) {
            // set the owning side to null (unless already changed)
            if ($peinture->getType() === $this) {
                $peinture->setType(null);
            }
        }

        return $this;
    }
}
