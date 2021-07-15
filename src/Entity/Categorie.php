<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategorieRepository::class)
 */
class Categorie
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
    private $label;

    /**
     * @ORM\ManyToMany(targetEntity=CodePromo::class, mappedBy="categories")
     */
    private $codePromos;

    /**
     * @ORM\ManyToMany(targetEntity=BonPlan::class, mappedBy="categories")
     */
    private $bonPlans;

    public function __construct()
    {

        $this->bonPlans = new ArrayCollection();
        $this->codePromos = new ArrayCollection();

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
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
            $bonPlan->addCategory($this);
        }

        return $this;
    }

    public function removeBonPlan(BonPlan $bonPlan): self
    {
        if ($this->bonPlans->removeElement($bonPlan)) {
            $bonPlan->removeCategory($this);
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
            $codePromo->addCategoroe($this);
        }

        return $this;
    }

    public function removeCodePromo(CodePromo $codePromo): self
    {
        if ($this->codePromos->removeElement($codePromo)) {
            $codePromo->removeCategoroe($this);
        }

        return $this;
    }
}
