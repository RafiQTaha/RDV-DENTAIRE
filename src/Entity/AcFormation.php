<?php

namespace App\Entity;

use App\Repository\AcFormationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AcFormationRepository::class)]
class AcFormation
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: AcEtablissement::class, inversedBy: 'acFormations')]
    private $etablissement;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $code;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $designation;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $abreviation;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $active = 1;

    #[ORM\OneToMany(mappedBy: 'formation', targetEntity: AcPromotion::class)]
    private $acPromotions;

    #[ORM\OneToMany(mappedBy: 'formation', targetEntity: AcAnnee::class)]
    private $acAnnees;

    public function __construct()
    {
        $this->acPromotions = new ArrayCollection();
        $this->acAnnees = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getEtablissement(): ?AcEtablissement
    {
        return $this->etablissement;
    }

    public function setEtablissement(?AcEtablissement $etablissement): self
    {
        $this->etablissement = $etablissement;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getDesignation(): ?string
    {
        return $this->designation;
    }

    public function setDesignation(?string $designation): self
    {
        $this->designation = $designation;

        return $this;
    }

    public function getAbreviation(): ?string
    {
        return $this->abreviation;
    }

    public function setAbreviation(?string $abreviation): self
    {
        $this->abreviation = $abreviation;

        return $this;
    }

    public function getActive(): ?int
    {
        return $this->active;
    }

    public function setActive(?int $active): self
    {
        $this->active = $active;

        return $this;
    }

    /**
     * @return Collection|AcPromotion[]
     */
    public function getAcPromotions(): Collection
    {
        return $this->acPromotions;
    }

    public function addAcPromotion(AcPromotion $acPromotion): self
    {
        if (!$this->acPromotions->contains($acPromotion)) {
            $this->acPromotions[] = $acPromotion;
            $acPromotion->setFormation($this);
        }

        return $this;
    }

    public function removeAcPromotion(AcPromotion $acPromotion): self
    {
        if ($this->acPromotions->removeElement($acPromotion)) {
            // set the owning side to null (unless already changed)
            if ($acPromotion->getFormation() === $this) {
                $acPromotion->setFormation(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|AcAnnee[]
     */
    public function getAcAnnees(): Collection
    {
        return $this->acAnnees;
    }

    public function addAcAnnee(AcAnnee $acAnnee): self
    {
        if (!$this->acAnnees->contains($acAnnee)) {
            $this->acAnnees[] = $acAnnee;
            $acAnnee->setFormation($this);
        }

        return $this;
    }

    public function removeAcAnnee(AcAnnee $acAnnee): self
    {
        if ($this->acAnnees->removeElement($acAnnee)) {
            // set the owning side to null (unless already changed)
            if ($acAnnee->getFormation() === $this) {
                $acAnnee->setFormation(null);
            }
        }

        return $this;
    }
}
