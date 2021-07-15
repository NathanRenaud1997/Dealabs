<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ApiResource()
 *
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mail;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="array")
     */
    private $roles = [];

    /**
     * @ORM\OneToMany(targetEntity=BonPlan::class, mappedBy="userCrea")
     */
    private $bonPlans;

    /**
     * @ORM\OneToMany(targetEntity=CodePromo::class, mappedBy="userCrea")
     */
    private $codePromos;

    /**
     * @ORM\OneToMany(targetEntity=Commentaire::class, mappedBy="user")
     */
    private $commentaires;

    /**
     * @ORM\OneToMany(targetEntity=Liker::class, mappedBy="user")
     */
    private $likers;

    /**
     * @ORM\ManyToMany(targetEntity=BonPlan::class, inversedBy="UsersSaves")
     * @ORM\JoinTable(name="saved_bonplan",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="bon_plan_id ", referencedColumnName="id")}
     *      )
     */
    private $BonPlanSave;

    /**
     * @ORM\ManyToMany(targetEntity=CodePromo::class, inversedBy="UsersSaves")
     *  @ORM\JoinTable(name="saved_codepromo",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="code_promo_id ", referencedColumnName="id")}
     *      )
     */
    private $CodePromoSaves;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateCreation;

    /**
     * @ORM\ManyToMany(targetEntity=Badge::class, inversedBy="users")
     */
    private $badges;

    /**
     * @ORM\OneToMany(targetEntity=Alerte::class, mappedBy="User")
     */
    private $alertes;

    /**
     * @ORM\ManyToMany(targetEntity=BonPlan::class, inversedBy="alertesUsers")
     */
    private $alertesBonPlan;

    /**
     * @ORM\ManyToMany(targetEntity=CodePromo::class, inversedBy="alertesUsers")
     */
    private $alertesCodePromo;

    public function __construct()
    {
        $this->bonPlans = new ArrayCollection();
        $this->codePromos = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
        $this->likers = new ArrayCollection();
        $this->BonPlanSave = new ArrayCollection();
        $this->CodePromoSaves = new ArrayCollection();
        $this->badges = new ArrayCollection();
        $this->alertes = new ArrayCollection();
        $this->alertesBonPlan = new ArrayCollection();
        $this->alertesCodePromo = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }


    public function getRoles()
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }
    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    /**
     * @return Collection|BonPlan[]
     */
    public function getBonPlans(): Collection
    {
        return $this->bonPlans;
    }

    public function addBonPlan(BonPlan $bonPlan): self
    {
        if (!$this->bonPlans->contains($bonPlan)) {
            $this->bonPlans[] = $bonPlan;
            $bonPlan->setUserCrea($this);
        }

        return $this;
    }


    public function removeBonPlan(BonPlan $bonPlan): self
    {
        if ($this->bonPlans->removeElement($bonPlan)) {
            // set the owning side to null (unless already changed)
            if ($bonPlan->getUserCrea() === $this) {
                $bonPlan->setUserCrea(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|CodePromo[]
     */
    public function getCodePromos(): Collection
    {
        return $this->codePromos;
    }

    public function addCodePromo(CodePromo $codePromo): self
    {
        if (!$this->codePromos->contains($codePromo)) {
            $this->codePromos[] = $codePromo;
            $codePromo->setUserCrea($this);
        }

        return $this;
    }

    public function removeCodePromo(CodePromo $codePromo): self
    {
        if ($this->codePromos->removeElement($codePromo)) {
            // set the owning side to null (unless already changed)
            if ($codePromo->getUserCrea() === $this) {
                $codePromo->setUserCrea(null);
            }
        }

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
            $commentaire->setUser($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getUser() === $this) {
                $commentaire->setUser(null);
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
            $liker->addUser($this);
        }

        return $this;
    }

    public function removeLiker(Liker $liker): self
    {
        if ($this->likers->removeElement($liker)) {
            $liker->removeUser($this);
        }

        return $this;
    }

    /**
     * @return Collection|BonPlan[]
     */
    public function getBonPlanSave(): Collection
    {
        return $this->BonPlanSave;
    }

    public function saveBonPlan(BonPlan $bonPlanSave): self
    {
        if (!$this->BonPlanSave->contains($bonPlanSave)) {
            $this->BonPlanSave[] = $bonPlanSave;
        }

        return $this;
    }

    public function removeBonPlanSave(BonPlan $bonPlanSave): self
    {
        $this->BonPlanSave->removeElement($bonPlanSave);

        return $this;
    }

    /**
     * @return Collection|CodePromo[]
     */
    public function getCodePromoSaves(): Collection
    {
        return $this->CodePromoSaves;
    }

    public function SaveCodePromo(CodePromo $codePromoSave): self
    {
        if (!$this->CodePromoSaves->contains($codePromoSave)) {
            $this->CodePromoSaves[] = $codePromoSave;
        }

        return $this;
    }

    public function removeCodePromoSave(CodePromo $codePromoSave): self
    {
        $this->CodePromoSaves->removeElement($codePromoSave);

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

    /**
     * @return Collection|Badge[]
     */
    public function getBadges(): Collection
    {
        return $this->badges;
    }

    public function addBadge(Badge $badge): self
    {
        if (!$this->badges->contains($badge)) {
            $this->badges[] = $badge;
        }

        return $this;
    }

    public function removeBadge(Badge $badge): self
    {
        $this->badges->removeElement($badge);

        return $this;
    }

    /**
     * @return Collection|Alerte[]
     */
    public function getAlertes(): Collection
    {
        return $this->alertes;
    }

    public function addAlerte(Alerte $alerte): self
    {
        if (!$this->alertes->contains($alerte)) {
            $this->alertes[] = $alerte;
            $alerte->setUser($this);
        }

        return $this;
    }

    public function removeAlerte(Alerte $alerte): self
    {
        if ($this->alertes->removeElement($alerte)) {
            // set the owning side to null (unless already changed)
            if ($alerte->getUser() === $this) {
                $alerte->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|BonPlan[]
     */
    public function getAlertesBonPlan(): Collection
    {
        return $this->alertesBonPlan;
    }

    public function addAlertesBonPlan(BonPlan $alertesBonPlan): self
    {
        if (!$this->alertesBonPlan->contains($alertesBonPlan)) {
            $this->alertesBonPlan[] = $alertesBonPlan;
        }

        return $this;
    }

    public function removeAlertesBonPlan(BonPlan $alertesBonPlan): self
    {
        $this->alertesBonPlan->removeElement($alertesBonPlan);

        return $this;
    }

    /**
     * @return Collection|CodePromo[]
     */
    public function getAlertesCodePromo(): Collection
    {
        return $this->alertesCodePromo;
    }

    public function addAlertesCodePromo(CodePromo $alertesCodePromo): self
    {
        if (!$this->alertesCodePromo->contains($alertesCodePromo)) {
            $this->alertesCodePromo[] = $alertesCodePromo;
        }

        return $this;
    }

    public function removeAlertesCodePromo(CodePromo $alertesCodePromo): self
    {
        $this->alertesCodePromo->removeElement($alertesCodePromo);

        return $this;
    }
}
