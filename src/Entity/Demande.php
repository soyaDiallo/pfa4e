<?php

namespace App\Entity;

use App\Repository\DemandeRepository;
use App\Controller\DemandeController;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * @ORM\Entity(repositoryClass=DemandeRepository::class)
 */
class Demande
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", length=1, options={"default" : DemandeController::ETAT_NOT_VALIDE})
     */
    private $etat = DemandeController::ETAT_NOT_VALIDE;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateValidationSecretaire;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateValidationDP;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateValidationDE;

    /**
     * @ORM\ManyToOne(targetEntity=Diplome::class, inversedBy="demandes")
     * @JoinColumn(name="diplome_id", referencedColumnName="id")
     */
    private $diplome;

    /**
     * @ORM\ManyToOne(targetEntity=Secretaire::class, inversedBy="demandes")
     */
    private $secretaire;

    /**
     * @ORM\ManyToOne(targetEntity=Entreprise::class, inversedBy="demandes")
     */
    private $entreprise;

    /**
     * @ORM\ManyToOne(targetEntity=Laureat::class, inversedBy="demandes")
     */
    private $laureat;

    /**
     * @ORM\ManyToOne(targetEntity=Etablissement::class, inversedBy="demandes")
     * @JoinColumn(name="etablissement_id", referencedColumnName="id")
     */
    private $etablissement;

    /**
     * @ORM\ManyToOne(targetEntity=DirecteurPedagogique::class, inversedBy="demandes")
     */
    private $directeurPedagogique;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEtat(): ?int
    {
        return $this->etat;
    }

    public function setEtat(int $etat): self
    {
        $this->etat = $etat;
        
        return $this;
    }

    public function getDateValidationSecretaire(): ?\DateTimeInterface
    {
        return $this->dateValidationSecretaire;
    }

    public function setDateValidationSecretaire(\DateTimeInterface $dateValidationSecretaire): self
    {
        $this->dateValidationSecretaire = $dateValidationSecretaire;

        return $this;
    }

    public function getDateValidationDP(): ?\DateTimeInterface
    {
        return $this->dateValidationDP;
    }

    public function setDateValidationDP(\DateTimeInterface $dateValidationDP): self
    {
        $this->dateValidationDP = $dateValidationDP;

        return $this;
    }

    public function getDateValidationDE(): ?\DateTimeInterface
    {
        return $this->dateValidationDE;
    }

    public function setDateValidationDE(\DateTimeInterface $dateValidationDE): self
    {
        $this->dateValidationDE = $dateValidationDE;

        return $this;
    }

    public function getDiplome(): ?Diplome
    {
        return $this->diplome;
    }

    public function setDiplome(?Diplome $diplome): self
    {
        $this->diplome = $diplome;

        return $this;
    }

    public function getSecretaire(): ?Secretaire
    {
        return $this->secretaire;
    }

    public function setSecretaire(?Secretaire $secretaire): self
    {
        $this->secretaire = $secretaire;

        return $this;
    }

    public function getEntreprise(): ?Entreprise
    {
        return $this->entreprise;
    }

    public function setEntreprise(?Entreprise $entreprise): self
    {
        $this->entreprise = $entreprise;

        return $this;
    }

    public function getLaureat(): ?Laureat
    {
        return $this->laureat;
    }

    public function setLaureat(?Laureat $laureat): self
    {
        $this->laureat = $laureat;

        return $this;
    }

    public function getEtablissement(): ?Etablissement
    {
        return $this->etablissement;
    }

    public function setEtablissement(?Etablissement $etablissement): self
    {
        $this->etablissement = $etablissement;

        return $this;
    }

    public function getDirecteurPedagogique(): ?DirecteurPedagogique
    {
        return $this->directeurPedagogique;
    }

    public function setDirecteurPedagogique(?DirecteurPedagogique $directeurPedagogique): self
    {
        $this->directeurPedagogique = $directeurPedagogique;

        return $this;
    }
}
