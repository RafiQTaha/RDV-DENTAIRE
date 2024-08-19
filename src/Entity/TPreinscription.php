<?php

namespace App\Entity;

use App\Repository\TPreinscriptionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TPreinscriptionRepository::class)]
class TPreinscription
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: TEtudiant::class, inversedBy: 'preinscriptions')]
    #[ORM\JoinColumn(nullable: false)]
    private $etudiant;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $code;

    #[ORM\Column(type: 'smallint', nullable: true)]
    private $inscriptionValide;

    #[ORM\Column(type: 'smallint', nullable: true)]
    private $active = null;

    #[ORM\OneToMany(mappedBy: 'preinscription', targetEntity: TAdmission::class)]
    private $admissions;

    #[ORM\ManyToOne(targetEntity: AcAnnee::class, inversedBy: 'preinscriptions')]
    private $annee;


    public function __construct()
    {
        $this->admissions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEtudiant(): ?TEtudiant
    {
        return $this->etudiant;
    }

    public function setEtudiant(?TEtudiant $etudiant): self
    {
        $this->etudiant = $etudiant;

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

    public function getInscriptionValide(): ?int
    {
        return $this->inscriptionValide;
    }

    public function setInscriptionValide(?int $inscriptionValide): self
    {
        $this->inscriptionValide = $inscriptionValide;

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
     * @return Collection|TAdmission[]
     */
    public function getAdmissions(): Collection
    {
        return $this->admissions;
    }

    public function addAdmission(TAdmission $admission): self
    {
        if (!$this->admissions->contains($admission)) {
            $this->admissions[] = $admission;
            $admission->setPreinscription($this);
        }

        return $this;
    }

    public function removeAdmission(TAdmission $admission): self
    {
        if ($this->admissions->removeElement($admission)) {
            // set the owning side to null (unless already changed)
            if ($admission->getPreinscription() === $this) {
                $admission->setPreinscription(null);
            }
        }

        return $this;
    }

    public function getAnnee(): ?AcAnnee
    {
        return $this->annee;
    }

    public function setAnnee(?AcAnnee $annee): self
    {
        $this->annee = $annee;

        return $this;
    }
}
