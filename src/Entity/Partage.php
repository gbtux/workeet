<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use JMS\Serializer\Annotation\SerializedName;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PartageRepository")
 */
class Partage
{
    public const TYPE_PARTAGE_LECTURE = "read";
    public const TYPE_PARTAGE_ECRITURE = "write";

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
     * @ORM\ManyToOne(targetEntity="App\Entity\Document", inversedBy="partages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $document;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Groups({"simple"})
     * @SerializedName("typePartage")
     */
    private $typePartage;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Utilisateur", inversedBy="partages")
     * @Serializer\Groups({"simple"})
     */
    private $utilisateur;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Groupe", inversedBy="partages")
     * @Serializer\Groups({"simple"})
     */
    private $groupe;

    public function __toString()
    {
        return (string) $this->id;
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

    public function getDocument(): ?Document
    {
        return $this->document;
    }

    public function setDocument(?Document $document): self
    {
        $this->document = $document;

        return $this;
    }

    public function getTypePartage(): ?string
    {
        return $this->typePartage;
    }

    public function setTypePartage(string $typePartage): self
    {
        $this->typePartage = $typePartage;

        return $this;
    }

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): self
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    public function getGroupe(): ?Groupe
    {
        return $this->groupe;
    }

    public function setGroupe(?Groupe $groupe): self
    {
        $this->groupe = $groupe;

        return $this;
    }
}
