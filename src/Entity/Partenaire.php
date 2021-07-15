<?php

namespace App\Entity;

use App\Repository\PartenaireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PartenaireRepository::class)
 */
class Partenaire
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
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=CodePromo::class, mappedBy="partenaire")
     */
    private $codePromos;
    /**
     * @ORM\OneToMany(targetEntity=BonPlan::class, mappedBy="partenaire")
     */
    private $bonplans;

    public function __construct()
    {

        $this->codePromos = new ArrayCollection();

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }




    /**
     * @return Collection|CodePromo[]
     */
    public function getCodePromos(): Collection
    {
        return $this->codePromos;
    }

    public function addCodePromos(CodePromo $categorie): self
    {
        if (!$this->codePromos->contains($categorie)) {
            $this->codePromos[] = $categorie;
            $categorie->setPartenaire($this);
        }

        return $this;
    }

    public function removeCodePromos(CodePromo $codePromos): self
    {
        if ($this->codePromos->removeElement($codePromos)) {
            // set the owning side to null (unless already changed)
            if ($codePromos->getPartenaire() === $this) {
                $codePromos->setPartenaire(null);
            }
        }

        return $this;
    }


    /**
     * @return Collection|BonPlan[]
     */
    public function getBonplans(): Collection
    {
        return $this->codePromos;
    }

    public function addBonplans(Bonplan $bonplan): self
    {
        if (!$this->bonplans->contains($bonplan)) {
            $this->codePromos[] = $bonplan;
            $bonplan->setPartenaire($this);
        }

        return $this;
    }

    public function removeBonplans(Bonplan $bonplan): self
    {
        if ($this->bonplans->removeElement($bonplan)) {
            // set the owning side to null (unless already changed)
            if ($bonplan->getPartenaire() === $this) {
                $bonplan->setPartenaire(null);
            }
        }

        return $this;
    }

}

