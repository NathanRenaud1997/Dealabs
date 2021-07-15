<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\BonPlanRepository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 *
 * @ORM\Entity(repositoryClass=BonPlanRepository::class)
 */
class BonPlan extends Deal
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("hot")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $prix;

    /**
     * @ORM\Column(type="float")
     */
    private $prix_habituel;

    /**
     * @ORM\Column(type="float")
     */
    private $fraisDePort;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isFree;

    /**
     * @ORM\ManyToOne(targetEntity=Partenaire::class, inversedBy="bonplans")
     */
    protected $partenaire;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="bonPlans")
     * @ORM\JoinColumn(nullable=false)
     */
    private $userCrea;

    /**
     * @ORM\OneToMany(targetEntity=Commentaire::class, mappedBy="bonPlan")
     */
    protected $commentaires;

    /**
     * @ORM\ManyToMany(targetEntity=Categorie::class, inversedBy="bonPlans")
     * @ORM\JoinTable(name="bon_plan_categorie")
     */
    private $categories;

    /**
     * @ORM\OneToMany(targetEntity=Liker::class, mappedBy="bonPlan")
     */
    private $likers;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, mappedBy="BonPlanSave")
     */
    private $UsersSaves;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, mappedBy="alertesBonPlan")
     */
    private $alertesUsers;

    public function __construct()
    {
        parent::__construct();
        $this->commentaires = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->likers = new ArrayCollection();
        $this->UsersSaves = new ArrayCollection();
        $this->alertes = new ArrayCollection();
        $this->alertesUsers = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getPrixHabituel(): ?float
    {
        return $this->prix_habituel;
    }

    public function setPrixHabituel(float $prix_habituel): self
    {
        $this->prix_habituel = $prix_habituel;

        return $this;
    }

    public function getFraisDePort(): ?float
    {
        return $this->fraisDePort;
    }

    public function setFraisDePort(float $fraisDePort): self
    {
        $this->fraisDePort = $fraisDePort;

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

    public function getIsFree(): ?bool
    {
        return $this->isFree;
    }

    public function setIsFree(bool $isFree): self
    {
        $this->isFree = $isFree;

        return $this;
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

    /**
     * @return Collection|commentaire[]
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(commentaire $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires[] = $commentaire;
            $commentaire->setBonPlan($this);
        }

        return $this;
    }

    public function removeCommentaire(commentaire $commentaire): self
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getBonPlan() === $this) {
                $commentaire->setBonPlan(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Categorie[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategorie(Categorie $categorie): self
    {
        if (!$this->categories->contains($categorie)) {
            $this->categories[] = $categorie;
        }

        return $this;
    }

    public function removeCategorie(Categorie $categorie): self
    {
        $this->categories->removeElement($categorie);

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
            $liker->setBonPlan($this);
        }

        return $this;
    }

    public function removeLiker(Liker $liker): self
    {
        if ($this->likers->removeElement($liker)) {
            // set the owning side to null (unless already changed)
            if ($liker->getBonPlan() === $this) {
                $liker->setBonPlan(null);
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
            $usersSave->addBonPlanSave($this);
        }

        return $this;
    }

    public function removeUsersSave(User $usersSave): self
    {
        if ($this->UsersSaves->removeElement($usersSave)) {
            $usersSave->removeBonPlanSave($this);
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
            $alertesUser->addAlertesBonPlan($this);
        }

        return $this;
    }

    public function removeAlertesUser(User $alertesUser): self
    {
        if ($this->alertesUsers->removeElement($alertesUser)) {
            $alertesUser->removeAlertesBonPlan($this);
        }

        return $this;
    }
}
