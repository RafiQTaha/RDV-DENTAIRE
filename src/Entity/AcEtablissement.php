<?php

namespace App\Entity;

use App\Repository\AcEtablissementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AcEtablissementRepository::class)]
class AcEtablissement
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $code;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $designation;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $abreviation;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $active;

    #[ORM\OneToMany(mappedBy: 'etablissement', targetEntity: AcFormation::class)]
    private $acFormations;

    public function __construct()
    {
        $this->acFormations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
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
     * @return Collection|AcFormation[]
     */
    public function getAcFormations(): Collection
    {
        return $this->acFormations;
    }

    public function addAcFormation(AcFormation $acFormation): self
    {
        if (!$this->acFormations->contains($acFormation)) {
            $this->acFormations[] = $acFormation;
            $acFormation->setEtablissement($this);
        }

        return $this;
    }

    public function removeAcFormation(AcFormation $acFormation): self
    {
        if ($this->acFormations->removeElement($acFormation)) {
            // set the owning side to null (unless already changed)
            if ($acFormation->getEtablissement() === $this) {
                $acFormation->setEtablissement(null);
            }
        }

        return $this;
    }

}
