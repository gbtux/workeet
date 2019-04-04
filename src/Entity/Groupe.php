<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\Group as BaseGroup;
use JMS\Serializer\Annotation as Serializer;
use JMS\Serializer\Annotation\SerializedName;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GroupeRepository")
 */
class Groupe extends BaseGroup
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @var string
     * @Serializer\Groups({"simple"})
     * @SerializedName("id")
     */
    private $hashedId;

    /**
     * @var string
     * @Serializer\Groups({"simple"})
     */
    protected $name;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Partage", mappedBy="groupe")
     */
    private $partages;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Utilisateur", mappedBy="groups")
     */
    private $users;


    public function __construct($name = "")
    {
        parent::__construct($name);
        $this->partages = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
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
            $partage->setGroupe($this);
        }

        return $this;
    }

    public function removePartage(Partage $partage): self
    {
        if ($this->partages->contains($partage)) {
            $this->partages->removeElement($partage);
            // set the owning side to null (unless already changed)
            if ($partage->getGroupe() === $this) {
                $partage->setGroupe(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Utilisateur[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(Utilisateur $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
        }

        return $this;
    }

    public function removeUser(Utilisateur $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
        }

        return $this;
    }
}
