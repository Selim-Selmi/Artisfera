<?php 

namespace App\Entity;

use App\Repository\WorkshopsRepository;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;

#[ORM\Entity(repositoryClass: WorkshopsRepository::class)]
#[Vich\Uploadable]
class Workshops
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $video = null;

    // Cette propriété va contenir le fichier vidéo téléchargé
    /**
     * @Vich\UploadableField(mapping="workshop_videos", fileNameProperty="videoName")
     * @var File|null
     */
    private ?File $videoFile = null;

    // Cette propriété va stocker le nom du fichier vidéo après l'upload
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $videoName = null;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getVideo(): ?string
    {
        return $this->video;
    }

    public function setVideo(string $video): static
    {
        $this->video = $video;

        return $this;
    }

    // Getter et Setter pour $videoFile
    public function getVideoFile(): ?File
    {
        return $this->videoFile;
    }

    public function setVideoFile(?File $videoFile = null): static
    {
        $this->videoFile = $videoFile;

        // Rafraîchissement de la date de mise à jour lorsque le fichier vidéo change
        if ($videoFile) {
            $this->updatedAt = new \DateTimeImmutable();
        }

        return $this;
    }

    // Getter et Setter pour $videoName
    public function getVideoName(): ?string
    {
        return $this->videoName;
    }

    public function setVideoName(?string $videoName): static
    {
        $this->videoName = $videoName;

        return $this;
    }
}
