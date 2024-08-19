<?php

namespace App\Entity;

use App\Repository\AcPromotionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AcPromotionRepository::class)]
class AcPromotion
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: AcFormation::class, inversedBy: 'acPromotions')]
    private $formation;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $code;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $designation;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $ordre;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $active = 1;

    #[ORM\OneToMany(mappedBy: 'promotion', targetEntity: AcSemestre::class)]
    private $semestres;

    #[ORM\OneToMany(mappedBy: 'promotion', targetEntity: TInscription::class)]
    private $inscriptions;

    /**
     * @var Collection<int, Act>
     */
    #[ORM\OneToMany(targetEntity: Act::class, mappedBy: 'promotion')]
    private Collection $acts;

    public function __construct()
    {
        $this->semestres = new ArrayCollection();
        $this->inscriptions = new ArrayCollection();
        $this->acts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
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

    public function getOrdre(): ?int
    {
        return $this->ordre;
    }

    public function setOrdre(?int $ordre): self
    {
        $this->ordre = $ordre;

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
     * @return Collection|AcSemestre[]
     */
    public function getSemestres(): Collection
    {
        return $this->semestres;
    }

    public function addSemestre(AcSemestre $semestre): self
    {
        if (!$this->semestres->contains($semestre)) {
            $this->semestres[] = $semestre;
            $semestre->setPromotion($this);
        }

        return $this;
    }

    public function removeSemestre(AcSemestre $semestre): self
    {
        if ($this->semestres->removeElement($semestre)) {
            // set the owning side to null (unless already changed)
            if ($semestre->getPromotion() === $this) {
                $semestre->setPromotion(null);
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
            $inscription->setPromotion($this);
        }

        return $this;
    }

    public function removeInscription(TInscription $inscription): self
    {
        if ($this->inscriptions->removeElement($inscription)) {
            // set the owning side to null (unless already changed)
            if ($inscription->getPromotion() === $this) {
                $inscription->setPromotion(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Act>
     */
    public function getActs(): Collection
    {
        return $this->acts;
    }

    public function addAct(Act $act): static
    {
        if (!$this->acts->contains($act)) {
            $this->acts->add($act);
            $act->setPromotion($this);
        }

        return $this;
    }

    public function removeAct(Act $act): static
    {
        if ($this->acts->removeElement($act)) {
            // set the owning side to null (unless already changed)
            if ($act->getPromotion() === $this) {
                $act->setPromotion(null);
            }
        }

        return $this;
    }
}
