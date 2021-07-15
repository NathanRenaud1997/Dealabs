<?php

namespace App\Entity;

use App\Repository\LikerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LikerRepository::class)
 */
class Liker
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="likers")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=BonPlan::class, inversedBy="likers")
     */
    private $bonPlan;

    /**
     * @ORM\ManyToOne(targetEntity=CodePromo::class, inversedBy="likers")
     */
    private $codePromo;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $plusOuMoins;

    public function __construct()
    {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return User
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getBonPlan(): ?BonPlan
    {
        return $this->bonPlan;
    }

    public function setBonPlan(?BonPlan $bonPlan): self
    {
        $this->bonPlan = $bonPlan;

        return $this;
    }

    public function getCodePromo(): ?codePromo
    {
        return $this->codePromo;
    }

    public function setCodePromo(?CodePromo $codePromo): self
    {
        $this->codePromo = $codePromo;

        return $this;
    }

    public function getPlusOuMoins(): ?int
    {
        return $this->plusOuMoins;
    }

    public function setPlusOuMoins(?int $plusOuMoins): self
    {
        $this->plusOuMoins = $plusOuMoins;

        return $this;
    }
}
