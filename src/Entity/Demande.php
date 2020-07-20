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
     * @ORM\Column(type="integer", length=1, options={"default" : DemandeController::ETAT_PROCESS})
     */
    private $etatSecretaire = DemandeController::ETAT_PROCESS;

    /**
     * @ORM\Column(type="integer", length=1, options={"default" : DemandeController::ETAT_PROCESS})
     */
    private $etatDirecteurPd = DemandeController::ETAT_PROCESS;

    /**
     * @ORM\Column(type="integer", length=1, options={"default" : DemandeController::ETAT_PROCESS})
     */
    private $etatDirecteurGn = DemandeController::ETAT_PROCESS;

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
     * @ORM\ManyToOne(targetEntity=Diplome::class, inversedBy="demandes",cascade={"persist"})
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

    public function getEtatDirecteurPd(): ?int
    {
        return $this->etatDirecteurPd;
    }

    public function setEtatDirecteurPd(int $etatDirecteurPd): self
    {
        $this->etatDirecteurPd = $etatDirecteurPd;

        return $this;
    }

    public function getEtatDirecteurGn(): ?int
    {
        return $this->etatDirecteurGn;
    }

    public function setEtatDirecteurGn(int $etatDirecteurGn): self
    {
        $this->etatDirecteurGn = $etatDirecteurGn;

        return $this;
    }

    public function getEtatSecretaire(): ?int
    {
        return $this->etatSecretaire;
    }

    public function setEtatSecretaire(int $etatSecretaire): self
    {
        $this->etatSecretaire = $etatSecretaire;

        return $this;
    }
}
