<?php

namespace App\Entity;

use App\Repository\AcAnneeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AcAnneeRepository::class)]
class AcAnnee
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: AcFormation::class, inversedBy: 'acAnnees')]
    private $formation;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $code;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $designation;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $active;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $validation_academique;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $cloture_academique;

    #[ORM\OneToMany(mappedBy: 'annee', targetEntity: TPreinscription::class)]
    private $preinscriptions;

    #[ORM\OneToMany(mappedBy: 'annee', targetEntity: TInscription::class)]
    private $inscriptions;


    public function __construct()
    {
        $this->preinscriptions = new ArrayCollection();
        $this->inscriptions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFormation(): ?AcFormation
    {
        return $this->formation;
    }

    public function setFormation(?AcFormation $formation): self
    {
        $this->formation = $formation;

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

    public function getActive(): ?int
    {
        return $this->active;
    }

    public function setActive(?int $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getValidationAcademique(): ?string
    {
        return $this->validation_academique;
    }

    public function setValidationAcademique(?string $validation_academique): self
    {
        $this->validation_academique = $validation_academique;

        return $this;
    }

    public function getClotureAcademique(): ?string
    {
        return $this->cloture_academique;
    }

    public function setClotureAcademique(?string $cloture_academique): self
    {
        $this->cloture_academique = $cloture_academique;

        return $this;
    }

    /**
     * @return Collection|TPreinscription[]
     */
    public function getPreinscriptions(): Collection
    {
        return $this->preinscriptions;
    }

    public function addPreinscription(TPreinscription $preinscription): self
    {
        if (!$this->preinscriptions->contains($preinscription)) {
            $this->preinscriptions[] = $preinscription;
            $preinscription->setAnnee($this);
        }

        return $this;
    }

    public function removePreinscription(TPreinscription $preinscription): self
    {
        if ($this->preinscriptions->removeElement($preinscription)) {
            // set the owning side to null (unless already changed)
            if ($preinscription->getAnnee() === $this) {
                $preinscription->setAnnee(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|TInscription[]
     */
    public function getInscriptions(): Collection
    {
        return $this->inscriptions;
    }

    public function addInscription(TInscription $inscription): self
    {
        if (!$this->inscriptions->contains($inscription)) {
            $this->inscriptions[] = $inscription;
            $inscription->setAnnee($this);
        }

        return $this;
    }

    public function removeInscription(TInscription $inscription): self
    {
        if ($this->inscriptions->removeElement($inscription)) {
            // set the owning side to null (unless already changed)
            if ($inscription->getAnnee() === $this) {
                $inscription->setAnnee(null);
            }
        }

        return $this;
    }
}
