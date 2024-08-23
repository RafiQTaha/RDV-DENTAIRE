<?php

namespace App\Entity;

use App\Repository\RendezvousRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RendezvousRepository::class)]
class Rendezvous
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'rendezvouses')]
    private ?TInscription $inscription = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $prenom = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $created = null;

    /**
     * @var Collection<int, Act>
     */
    #[ORM\ManyToMany(targetEntity: Act::class, inversedBy: 'rendezvouses')]
    private Collection $Actes;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Code = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $cin = null;

    #[ORM\Column(nullable: true)]
    private ?bool $Annuler = false;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $annulated = null;

    public function __construct()
    {
        $this->Actes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInscription(): ?TInscription
    {
        return $this->inscription;
    }

    public function setInscription(?TInscription $inscription): static
    {
        $this->inscription = $inscription;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(?\DateTimeInterface $created): static
    {
        $this->created = $created;

        return $this;
    }

    /**
     * @return Collection<int, Act>
     */
    public function getActes(): Collection
    {
        return $this->Actes;
    }

    public function addActe(Act $acte): static
    {
        if (!$this->Actes->contains($acte)) {
            $this->Actes->add($acte);
        }

        return $this;
    }

    public function removeActe(Act $acte): static
    {
        $this->Actes->removeElement($acte);

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->Code;
    }

    public function setCode(?string $Code): static
    {
        $this->Code = $Code;

        return $this;
    }

    public function getCin(): ?string
    {
        return $this->cin;
    }

    public function setCin(?string $cin): static
    {
        $this->cin = $cin;

        return $this;
    }

    public function isAnnuler(): ?bool
    {
        return $this->Annuler;
    }

    public function setAnnuler(?bool $Annuler): static
    {
        $this->Annuler = $Annuler;

        return $this;
    }

    public function getAnnulated(): ?\DateTimeInterface
    {
        return $this->annulated;
    }

    public function setAnnulated(?\DateTimeInterface $annulated): static
    {
        $this->annulated = $annulated;

        return $this;
    }
}
