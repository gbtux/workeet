<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use JMS\Serializer\Annotation as Serializer;
use JMS\Serializer\Annotation\SerializedName;

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
     * @var string
     * @Serializer\Groups({"simple"})
     * @SerializedName("id")
     */
    private $hashedId;

    /**
     * @var string
     * @Serializer\Groups({"simple"})
     */
    protected $username;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $homeID;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Document", mappedBy="author")
     */
    private $documents;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Groupe", inversedBy="users")
     * @ORM\JoinTable(name="utilisateurs_groupes",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
     * )
     */
    protected $groups;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Partage", mappedBy="utilisateur")
     */
    private $partages;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\DocEvenement", mappedBy="utilisateur")
     */
    private $docEvenements;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Contact", mappedBy="creator")
     */
    private $contacts;

    public function __construct()
    {
        parent::__construct();
        $this->documents = new ArrayCollection();
        $this->groups = new ArrayCollection();
        $this->partages = new ArrayCollection();
        $this->docEvenements = new ArrayCollection();
        $this->contacts = new ArrayCollection();
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

    public function getHomeID(): ?string
    {
        return $this->homeID;
    }

    public function setHomeID(?string $homeID): self
    {
        $this->homeID = $homeID;

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
            $document->setAuthor($this);
        }

        return $this;
    }

    public function removeDocument(Document $document): self
    {
        if ($this->documents->contains($document)) {
            $this->documents->removeElement($document);
            // set the owning side to null (unless already changed)
            if ($document->getAuthor() === $this) {
                $document->setAuthor(null);
            }
        }

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
            $partage->setUtilisateur($this);
        }

        return $this;
    }

    public function removePartage(Partage $partage): self
    {
        if ($this->partages->contains($partage)) {
            $this->partages->removeElement($partage);
            // set the owning side to null (unless already changed)
            if ($partage->getUtilisateur() === $this) {
                $partage->setUtilisateur(null);
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
            $docEvenement->setUtilisateur($this);
        }

        return $this;
    }

    public function removeDocEvenement(DocEvenement $docEvenement): self
    {
        if ($this->docEvenements->contains($docEvenement)) {
            $this->docEvenements->removeElement($docEvenement);
            // set the owning side to null (unless already changed)
            if ($docEvenement->getUtilisateur() === $this) {
                $docEvenement->setUtilisateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Contact[]
     */
    public function getContacts(): Collection
    {
        return $this->contacts;
    }

    public function addContact(Contact $contact): self
    {
        if (!$this->contacts->contains($contact)) {
            $this->contacts[] = $contact;
            $contact->setCreator($this);
        }

        return $this;
    }

    public function removeContact(Contact $contact): self
    {
        if ($this->contacts->contains($contact)) {
            $this->contacts->removeElement($contact);
            // set the owning side to null (unless already changed)
            if ($contact->getCreator() === $this) {
                $contact->setCreator(null);
            }
        }

        return $this;
    }

}
