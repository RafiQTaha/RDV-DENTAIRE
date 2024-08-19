<?php

namespace App\Entity;

use App\Repository\TInscriptionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TInscriptionRepository::class)]
class TInscription
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: PStatut::class, inversedBy: 'inscriptions')]
    private $statut;

    #[ORM\ManyToOne(targetEntity: TAdmission::class, inversedBy: 'inscriptions')]
    private $admission;

    #[ORM\ManyToOne(targetEntity: AcAnnee::class, inversedBy: 'inscriptions')]
    private $annee;

    #[ORM\ManyToOne(targetEntity: AcPromotion::class, inversedBy: 'inscriptions')]
    private $promotion;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $code;

    #[ORM\ManyToOne(targetEntity: PGroupe::class, inversedBy: 'inscriptions')]
    private $groupe;

    /**
     * @var Collection<int, Rendezvous>
     */
    #[ORM\OneToMany(targetEntity: Rendezvous::class, mappedBy: 'inscription')]
    private Collection $rendezvouses;


    public function __construct()
    {
        // $this->periodeStages = new ArrayCollection();
        $this->rendezvouses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatut(): ?PStatut
    {
        return $this->statut;
    }

    public function setStatut(?PStatut $statut): self
    {
        $this->statut = $statut;

        return $this;
    }


    public function getAdmission(): ?TAdmission
    {
        return $this->admission;
    }

    public function setAdmission(?TAdmission $admission): self
    {
        $this->admission = $admission;

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

    public function getPromotion(): ?AcPromotion
    {
        return $this->promotion;
    }

    public function setPromotion(?AcPromotion $promotion): self
    {
        $this->promotion = $promotion;

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
    public function getGroupe(): ?PGroupe
    {
        return $this->groupe;
    }

    public function setGroupe(?PGroupe $groupe): self
    {
        $this->groupe = $groupe;

        return $this;
    }

    /**
     * @return Collection<int, Rendezvous>
     */
    public function getRendezvouses(): Collection
    {
        return $this->rendezvouses;
    }

    public function addRendezvouse(Rendezvous $rendezvouse): static
    {
        if (!$this->rendezvouses->contains($rendezvouse)) {
            $this->rendezvouses->add($rendezvouse);
            $rendezvouse->setInscription($this);
        }

        return $this;
    }

    public function removeRendezvouse(Rendezvous $rendezvouse): static
    {
        if ($this->rendezvouses->removeElement($rendezvouse)) {
            // set the owning side to null (unless already changed)
            if ($rendezvouse->getInscription() === $this) {
                $rendezvouse->setInscription(null);
            }
        }

        return $this;
    }
}
