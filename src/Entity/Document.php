<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use JMS\Serializer\Annotation\SerializedName;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DocumentRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Document
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @Serializer\Groups({"simple"})
     * @SerializedName("id")
     */
    private $hashedId;


    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Groups({"simple"})
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $path;

    /**
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"simple"})
     */
    private $size;

    /**
     * @ORM\Column(type="datetime")
     * @Serializer\Groups({"simple"})
     * @SerializedName("dateCreation")
     */
    private $dateCreation;

    /**
     * @ORM\Column(type="datetime")
     * @Serializer\Groups({"simple"})
     * @SerializedName("dateModification")
     */
    private $dateModification;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     * @Serializer\Groups({"simple"})
     */
    private $extension;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Repertoire", inversedBy="documents")
     * @ORM\JoinColumn(nullable=false)
     */
    private $repertoire;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Utilisateur", inversedBy="documents")
     * @ORM\JoinColumn(nullable=false)
     * @Serializer\Groups({"simple"})
     */
    private $author;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Partage", mappedBy="document")
     * @Serializer\Groups({"simple"})
     */
    private $partages;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\DocEvenement", mappedBy="document")
     * @Serializer\Groups({"simple"})
     */
    private $docEvenements;

    /**
     * @ORM\Column(type="boolean")
     * @Serializer\Groups({"simple"})
     */
    private $public = false;

    /**
     * @ORM\Column(type="simple_array", nullable=true)
     * @Serializer\Groups({"simple"})
     * @SerializedName("partagesExternes")
     */
    private $partagesExternes = [];

    public function __construct()
    {
        $this->partages = new ArrayCollection();
        $this->docEvenements = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->nom;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setHashedId($hashedId)
    {
        $this->hashedId = $hashedId;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getSize(): ?int
    {
        return $this->size;
    }

    public function setSize(int $size): self
    {
        $this->size = $size;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    public function getRepertoire(): ?Repertoire
    {
        return $this->repertoire;
    }

    public function setRepertoire(?Repertoire $repertoire): self
    {
        $this->repertoire = $repertoire;

        return $this;
    }

    public function getExtension(): ?string
    {
        return $this->extension;
    }

    public function setExtension(?string $extension): self
    {
        $this->extension = $extension;

        return $this;
    }

    public function getAuthor(): ?Utilisateur
    {
        return $this->author;
    }

    public function setAuthor(?Utilisateur $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection|Partage[]
     */
    public function getPartages(): Collection
    {
        return $this->partages;
    }

    public function addPartage(Partage $partage): self
    {
        if (!$this->partages->contains($partage)) {
            $this->partages[] = $partage;
            $partage->setDocument($this);
        }

        return $this;
    }

    public function removePartage(Partage $partage): self
    {
        if ($this->partages->contains($partage)) {
            $this->partages->removeElement($partage);
            // set the owning side to null (unless already changed)
            if ($partage->getDocument() === $this) {
                $partage->setDocument(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|DocEvenement[]
     */
    public function getDocEvenements(): Collection
    {
        return $this->docEvenements;
    }

    public function addDocEvenement(DocEvenement $docEvenement): self
    {
        if (!$this->docEvenements->contains($docEvenement)) {
            $this->docEvenements[] = $docEvenement;
            $docEvenement->setDocument($this);
        }

        return $this;
    }

    public function removeDocEvenement(DocEvenement $docEvenement): self
    {
        if ($this->docEvenements->contains($docEvenement)) {
            $this->docEvenements->removeElement($docEvenement);
            // set the owning side to null (unless already changed)
            if ($docEvenement->getDocument() === $this) {
                $docEvenement->setDocument(null);
            }
        }

        return $this;
    }

    public function getDateModification(): ?\DateTimeInterface
    {
        return $this->dateModification;
    }

    public function setDateModification(\DateTimeInterface $dateModification): self
    {
        $this->dateModification = $dateModification;

        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function updateDateModification()
    {
        $this->dateModification = new \DateTime();
    }

    public function getPublic(): ?bool
    {
        return $this->public;
    }

    public function setPublic(bool $public): self
    {
        $this->public = $public;

        return $this;
    }

    public function getPartagesExternes(): ?array
    {
        return $this->partagesExternes;
    }

    public function setPartagesExternes(?array $partagesExternes): self
    {
        $this->partagesExternes = $partagesExternes;

        return $this;
    }
}
