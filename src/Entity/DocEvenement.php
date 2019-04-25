<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use JMS\Serializer\Annotation\SerializedName;


/**
 * @ORM\Entity(repositoryClass="App\Repository\DocEvenementRepository")
 */
class DocEvenement
{

    public const TYPE_EVENEMENT_CREATION = "creation";
    public const TYPE_EVENEMENT_MODIFICATION = "modification";
    public const TYPE_EVENEMENT_PARTAGE = "partage";
    public const TYPE_EVENEMENT_TELECHARGEMENT = "telechargement";
    public const TYPE_EVENEMENT_SUPPRESSION = "suppression";

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
     * @SerializedName("typeEvent")
     */
    private $typeEvent;

    /**
     * @ORM\Column(type="datetime")
     * @Serializer\Groups({"simple"})
     * @SerializedName("dateEvent")
     */
    private $dateEvent;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Utilisateur", inversedBy="docEvenements")
     * @ORM\JoinColumn(nullable=true)
     * @Serializer\Groups({"simple"})
     */
    private $utilisateur;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Document", inversedBy="docEvenements")
     * @ORM\JoinColumn(nullable=false)
     */
    private $document;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Serializer\Groups({"simple"})
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $externalUser;
    
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

    public function getTypeEvent(): ?string
    {
        return $this->typeEvent;
    }

    public function setTypeEvent(string $typeEvent): self
    {
        $this->typeEvent = $typeEvent;

        return $this;
    }

    public function getDateEvent(): ?\DateTimeInterface
    {
        return $this->dateEvent;
    }

    public function setDateEvent(\DateTimeInterface $dateEvent): self
    {
        $this->dateEvent = $dateEvent;

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

    public function getDocument(): ?Document
    {
        return $this->document;
    }

    public function setDocument(?Document $document): self
    {
        $this->document = $document;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getExternalUser(): ?string
    {
        return $this->externalUser;
    }

    public function setExternalUser(?string $externalUser): self
    {
        $this->externalUser = $externalUser;

        return $this;
    }
}
