<?php

namespace App\Entity;

use App\Repository\TAdmissionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TAdmissionRepository::class)]
class TAdmission
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: TPreinscription::class, inversedBy: 'admissions')]
    private $preinscription;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $code;

    #[ORM\OneToMany(mappedBy: 'admission', targetEntity: TInscription::class)]
    private $inscriptions;

    public function __construct()
    {
        $this->inscriptions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPreinscription(): ?TPreinscription
    {
        return $this->preinscription;
    }

    public function setPreinscription(?TPreinscription $preinscription): self
    {
        $this->preinscription = $preinscription;

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
            $inscription->setAdmission($this);
        }

        return $this;
    }

    public function removeInscription(TInscription $inscription): self
    {
        if ($this->inscriptions->removeElement($inscription)) {
            // set the owning side to null (unless already changed)
            if ($inscription->getAdmission() === $this) {
                $inscription->setAdmission(null);
            }
        }

        return $this;
    }
}
