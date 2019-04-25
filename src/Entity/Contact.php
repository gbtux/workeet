<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use JMS\Serializer\Annotation\SerializedName;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ContactRepository")
 */
class Contact
{

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"simple"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="civility", nullable=true)
     * @Serializer\Groups({"simple"})
     */
    private $civility;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Serializer\Groups({"simple"})
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Groups({"simple"})
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Serializer\Groups({"simple"})
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Serializer\Groups({"simple"})
     */
    private $mobile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Serializer\Groups({"simple"})
     */
    private $telephone;

    /**
     * @var string
     *
     * @ORM\Column(name="facebook", nullable=true)
     * @Serializer\Groups({"simple"})
     */
    private $facebook;

    /**
     * @var string
     *
     * @ORM\Column(name="twitter", nullable=true)
     * @Serializer\Groups({"simple"})
     */
    private $twitter;

    /**
     * @var string
     *
     * @ORM\Column(name="linkedin", nullable=true)
     * @Serializer\Groups({"simple"})
     */
    private $linkedin;

    /**
     * @var string
     *
     * @ORM\Column(name="skype", nullable=true)
     * @Serializer\Groups({"simple"})
     */
    private $skype;

    /**
     * @var string
     * @ORM\Column(name="adresse", nullable=true)
     * @Serializer\Groups({"simple"})
     */
    private $adresse;

    /**
     * @var string
     * @ORM\Column(name="code_postal", nullable=true)
     * @Serializer\Groups({"simple"})
     * @SerializedName("codePostal")
     */
    private $codePostal;

    /**
     * @var string
     * @ORM\Column(name="ville", nullable=true)
     * @Serializer\Groups({"simple"})
     */
    private $ville;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Utilisateur", cascade={"persist"})
     */
    private $utilisateur;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Utilisateur", inversedBy="contacts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $creator;

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): self
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCivility(): ?string
    {
        return $this->civility;
    }

    public function setCivility(?string $civility): self
    {
        $this->civility = $civility;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): self
    {
        $this->prenom = $prenom;

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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getMobile(): ?string
    {
        return $this->mobile;
    }

    public function setMobile(?string $mobile): self
    {
        $this->mobile = $mobile;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getFacebook(): ?string
    {
        return $this->facebook;
    }

    public function setFacebook(?string $facebook): self
    {
        $this->facebook = $facebook;

        return $this;
    }

    public function getTwitter(): ?string
    {
        return $this->twitter;
    }

    public function setTwitter(?string $twitter): self
    {
        $this->twitter = $twitter;

        return $this;
    }

    public function getLinkedin(): ?string
    {
        return $this->linkedin;
    }

    public function setLinkedin(?string $linkedin): self
    {
        $this->linkedin = $linkedin;

        return $this;
    }

    public function getSkype(): ?string
    {
        return $this->skype;
    }

    public function setSkype(?string $skype): self
    {
        $this->skype = $skype;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getCodePostal(): ?string
    {
        return $this->codePostal;
    }

    public function setCodePostal(?string $codePostal): self
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(?string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getCreator(): ?Utilisateur
    {
        return $this->creator;
    }

    public function setCreator(?Utilisateur $creator): self
    {
        $this->creator = $creator;

        return $this;
    }


}
