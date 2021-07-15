<?php

namespace App\Entity;

use App\Repository\AlerteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AlerteRepository::class)
 */
class Alerte
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
    private $keyword;

    /**
     * @ORM\Column(type="integer")
     */
    private $minimum_temperature;

    /**
     * @ORM\Column(type="boolean")
     */
    private $notify_by_email;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="alertes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function __construct()
    {
        $this->deals = new ArrayCollection();
        $this->bonPlanAlertes = new ArrayCollection();
        $this->codePromoAlertes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getKeyword(): ?string
    {
        return $this->keyword;
    }

    public function setKeyword(string $keyword): self
    {
        $this->keyword = $keyword;

        return $this;
    }

    public function getMinimumTemperature(): ?int
    {
        return $this->minimum_temperature;
    }

    public function setMinimumTemperature(int $minimum_temperature): self
    {
        $this->minimum_temperature = $minimum_temperature;

        return $this;
    }

    public function getNotifyByEmail(): ?bool
    {
        return $this->notify_by_email;
    }

    public function setNotifyByEmail(bool $notify_by_email): self
    {
        $this->notify_by_email = $notify_by_email;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
