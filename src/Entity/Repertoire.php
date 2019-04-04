<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use JMS\Serializer\Annotation\SerializedName;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RepertoireRepository")
 */
class Repertoire
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Groups({"simple"})
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Groups({"simple"})
     */
    private $hash;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Document", mappedBy="repertoire")
     * @ORM\OrderBy({"dateCreation" = "DESC"})
     * @Serializer\Groups({"simple"})
     */
    private $documents;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Repertoire", mappedBy="repertoireParent")
     * @Serializer\Groups({"simple"})
     * @SerializedName("sousRepertoires")
     */
    private $sousRepertoires;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Repertoire", inversedBy="sousRepertoires")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    private $repertoireParent;

    public function __construct()
    {
        $this->documents = new ArrayCollection();
        $this->sousRepertoires = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection|Document[]
     */
    public function getDocuments(): Collection
    {
        return $this->documents;
    }

    public function addDocument(Document $document): self
    {
        if (!$this->documents->contains($document)) {
            $this->documents[] = $document;
            $document->setRepertoire($this);
        }

        return $this;
    }

    public function removeDocument(Document $document): self
    {
        if ($this->documents->contains($document)) {
            $this->documents->removeElement($document);
            // set the owning side to null (unless already changed)
            if ($document->getRepertoire() === $this) {
                $document->setRepertoire(null);
            }
        }

        return $this;
    }

    public function getHash(): ?string
    {
        return $this->hash;
    }

    public function setHash(string $hash): self
    {
        $this->hash = $hash;

        return $this;
    }

    /**
     * @return Collection|Repertoire[]
     */
    public function getSousRepertoires(): Collection
    {
        return $this->sousRepertoires;
    }

    public function addSousRepertoire(Repertoire $sousRepertoire): self
    {
        if (!$this->sousRepertoires->contains($sousRepertoire)) {
            $this->sousRepertoires[] = $sousRepertoire;
            $sousRepertoire->setRepertoireParent($this);
        }

        return $this;
    }

    public function removeSousRepertoire(Repertoire $sousRepertoire): self
    {
        if ($this->sousRepertoires->contains($sousRepertoire)) {
            $this->sousRepertoires->removeElement($sousRepertoire);
            // set the owning side to null (unless already changed)
            if ($sousRepertoire->getRepertoireParent() === $this) {
                $sousRepertoire->setRepertoireParent(null);
            }
        }

        return $this;
    }

    public function getRepertoireParent(): ?self
    {
        return $this->repertoireParent;
    }

    public function setRepertoireParent(?self $repertoireParent): self
    {
        $this->repertoireParent = $repertoireParent;

        return $this;
    }

}
