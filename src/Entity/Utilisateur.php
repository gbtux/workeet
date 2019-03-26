<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UtilisateurRepository")
 */
class Utilisateur extends BaseUser
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $homeID;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHomeID(): ?string
    {
        return $this->homeID;
    }

    public function setHomeID(?string $homeID): self
    {
        $this->homeID = $homeID;

        return $this;
    }
}
