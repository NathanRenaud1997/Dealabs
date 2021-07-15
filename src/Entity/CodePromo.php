<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CodePromoRepository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;

use Doctrine\ORM\Mapping as ORM;

/**
 *
 * @ORM\Entity(repositoryClass=CodePromoRepository::class)
 */
class CodePromo extends Deal
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *@Groups("hot")
     */
    private $id;


    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="codePromos")
     * @ORM\JoinColumn(nullable=false)

     */
    protected $userCrea;

    /**
     * @ORM\ManyToOne(targetEntity=Partenaire::class, inversedBy="codePromos")
     */
    protected $partenaire;

    /**
     * @ORM\ManyToMany(targetEntity=Categorie::class, inversedBy="codePromos")
     */
    private $categories;

    /**
     * @ORM\OneToMany(targetEntity=Commentaire::class, mappedBy="codePromo")
     */
    protected $commentaires;

    /**
     * @ORM\OneToMany(targetEntity=Liker::class, mappedBy="codePromo")
     */
    private $likers;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, mappedBy="CodePromoSaves")
     */
    private $UsersSaves;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, mappedBy="alertesCodePromo")
     */
    private $alertesUsers;

    public function __construct()
    {
        parent::__construct();
        $this->categories = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
        $this->likers = new ArrayCollection();
        $this->UsersSaves = new ArrayCollection();
        $this->alertes = new ArrayCollection();
        $this->alertesUsers = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }


    public function getUserCrea(): ?User
    {
        return $this->userCrea;
    }

    public function setUserCrea(?User $userCrea): self
    {
        $this->userCrea = $userCrea;

        return $this;
    }

    public function getPartenaire(): ?Partenaire
    {
        return $this->partenaire;
    }

    public function setPartenaire(?Partenaire $partenaire): self
    {
        $this->partenaire = $partenaire;

        return $this;
    }

    /**
     * @return Collection|Categorie[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategorie(Categorie $categoroe): self
    {
        if (!$this->categories->contains($categoroe)) {
            $this->categories[] = $categoroe;
        }

        return $this;
    }

    public function removeCategorie(Categorie $categorie): self
    {
        $this->categories->removeElement($categorie);

        return $this;
    }

    /**
     * @return Collection|Commentaire[]
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaire $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires[] = $commentaire;
            $commentaire->setCodePromo($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getCodePromo() === $this) {
                $commentaire->setCodePromo(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Liker[]
     */
    public function getLikers(): Collection
    {
        return $this->likers;
    }

    public function addLiker(Liker $liker): self
    {
        if (!$this->likers->contains($liker)) {
            $this->likers[] = $liker;
            $liker->setCodePromo($this);
        }

        return $this;
    }

    public function removeLiker(Liker $liker): self
    {
        if ($this->likers->removeElement($liker)) {
            // set the owning side to null (unless already changed)
            if ($liker->getCodePromo() === $this) {
                $liker->setCodePromo(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsersSaves(): Collection
    {
        return $this->UsersSaves;
    }

    public function addUsersSave(User $usersSave): self
    {
        if (!$this->UsersSaves->contains($usersSave)) {
            $this->UsersSaves[] = $usersSave;
            $usersSave->addCodePromoSave($this);
        }

        return $this;
    }

    public function removeUsersSave(User $usersSave): self
    {
        if ($this->UsersSaves->removeElement($usersSave)) {
            $usersSave->removeCodePromoSave($this);
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getAlertesUsers(): Collection
    {
        return $this->alertesUsers;
    }

    public function addAlertesUser(User $alertesUser): self
    {
        if (!$this->alertesUsers->contains($alertesUser)) {
            $this->alertesUsers[] = $alertesUser;
            $alertesUser->addAlertesCodePromo($this);
        }

        return $this;
    }

    public function removeAlertesUser(User $alertesUser): self
    {
        if ($this->alertesUsers->removeElement($alertesUser)) {
            $alertesUser->removeAlertesCodePromo($this);
        }

        return $this;
    }
}
