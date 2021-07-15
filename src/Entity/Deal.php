<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\DealRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Controller\DealController;
use App\Controller\DealSaveController;


/**
 * @ORM\MappedSuperclass()
 * @ApiResource(collectionOperations={
 *     "get"={
"method"="GET",
 *         "path"="/deals",
 *         "controller"=DealController::class,
 *     "normalization_context"={"groups"={"hot"}},
 *     },
 *     "getDealsSaved"={
"method"="GET",
 *         "path"="/deals/saved/{id}",
 *         "controller"=DealSaveController::class,
 *     "normalization_context"={"groups"={"hot"}},
 *     }
 * })
 */

class Deal
{
    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("hot")
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $url;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $codePromo;

    /**

     * @ORM\Column(type="string", length=255)
     */
    private $apercu;
    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateCreation;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $note;

    public function __construct()
    {
        $this->alertes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }



    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

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

    public function getCodePromo(): ?string
    {
        return $this->codePromo;
    }

    public function setCodePromo(?string $codePromo): self
    {
        $this->codePromo = $codePromo;

        return $this;
    }

    public function getApercu(): ?string
    {
        return $this->apercu;
    }

    public function setApercu(string $apercu): self
    {
        $this->apercu = $apercu;

        return $this;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(?int $note): self
    {
        $this->note = $note;

        return $this;
    }
}
