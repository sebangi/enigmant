<?php

namespace App\Entity\Chene;

use App\Entity\General\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Chene\ReservationJeuRepository")
 */
class ReservationJeu
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $dateDemande;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateRetrait;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateFinPrevue;
    
     /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateRendu;
   
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $avisPublic;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $avisPriveDifficulte;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $avisPriveTechnique;

    /**
     * @ORM\Column(type="boolean", options={"default" : false})
     */
    private $reussi = false;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $tempsJeuEstime;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\User", inversedBy="reservations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Chene\JeuEnChene", inversedBy="reservations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $jeu;

    /**
     * 
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * 
     * @return \DateTimeInterface|null
     */
    public function getDateDemande(): ?\DateTimeInterface
    {
        return $this->dateDemande;
    }

    /**
     * 
     * @param \DateTimeInterface $dateDemande
     * @return \App\Entity\Chene\ReservationJeu
     */
    public function setDateDemande(\DateTimeInterface $dateDemande): ReservationJeu
    {
        $this->dateDemande = $dateDemande;

        return $this;
    }

    /**
     * 
     * @return \DateTimeInterface|null
     */
    public function getDateRetrait(): ?\DateTimeInterface
    {
        return $this->dateRetrait;
    }

    /**
     * 
     * @param \DateTimeInterface $dateRetrait
     * @return \App\Entity\Chene\ReservationJeu
     */
    public function setDateRetrait(\DateTimeInterface $dateRetrait): ReservationJeu
    {
        $this->dateRetrait = $dateRetrait;

        return $this;
    }

    /**
     * 
     * @return \DateTimeInterface|null
     */
    public function getDateFinPrevue(): ?\DateTimeInterface
    {
        return $this->dateFinPrevue;
    }

    /**
     * 
     * @param \DateTimeInterface|null $dateFinPrevue
     * @return \App\Entity\Chene\ReservationJeu
     */
    public function setDateFinPrevue(?\DateTimeInterface $dateFinPrevue): ReservationJeu
    {
        $this->dateFinPrevue = $dateFinPrevue;

        return $this;
    }
        
    /**
     * 
     * @return \DateTimeInterface|null
     */
    public function getDateRendu() : ?\DateTimeInterface{
        return $this->dateRendu;
    }

    /**
     * 
     * @param type $dateRendu
     * @return \App\Entity\Chene\ReservationJeu
     */
    public function setDateRendu($dateRendu): ReservationJeu {
        $this->dateRendu = $dateRendu;
        return $this;
    }

    /**
     * 
     * @return string|null
     */
    public function getAvisPublic(): ?string
    {
        return $this->avisPublic;
    }

    /**
     * 
     * @param string|null $avisPublic
     * @return \App\Entity\Chene\ReservationJeu
     */
    public function setAvisPublic(?string $avisPublic): ReservationJeu
    {
        $this->avisPublic = $avisPublic;

        return $this;
    }

    /**
     * 
     * @return string|null
     */
    public function getAvisPriveDifficulte(): ?string
    {
        return $this->avisPriveDifficulte;
    }

    /**
     * 
     * @param string $avisPriveDifficulte
     * @return \App\Entity\Chene\ReservationJeu
     */
    public function setAvisPriveDifficulte(string $avisPriveDifficulte): ReservationJeu
    {
        $this->avisPriveDifficulte = $avisPriveDifficulte;

        return $this;
    }

    /**
     * 
     * @return string|null
     */
    public function getAvisPriveTechnique(): ?string
    {
        return $this->avisPriveTechnique;
    }

    /**
     * 
     * @param string|null $avisPriveTechnique
     * @return \App\Entity\Chene\ReservationJeu
     */
    public function setAvisPriveTechnique(?string $avisPriveTechnique): ReservationJeu
    {
        $this->avisPriveTechnique = $avisPriveTechnique;

        return $this;
    }

    /**
     * 
     * @return bool|null
     */
    public function getReussi(): ?bool
    {
        return $this->reussi;
    }

    /**
     * 
     * @param bool $reussi
     * @return \App\Entity\Chene\ReservationJeu
     */
    public function setReussi(bool $reussi): ReservationJeu
    {
        $this->reussi = $reussi;

        return $this;
    }

    /**
     * 
     * @return int|null
     */
    public function getTempsJeuEstime(): ?int
    {
        return $this->tempsJeuEstime;
    }

    /**
     * 
     * @param int|null $tempsJeuEstime
     * @return \App\Entity\Chene\ReservationJeu
     */
    public function setTempsJeuEstime(?int $tempsJeuEstime): ReservationJeu
    {
        $this->tempsJeuEstime = $tempsJeuEstime;

        return $this;
    }

    /**
     * 
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * 
     * @param User|null $user
     * @return \App\Entity\Chene\ReservationJeu
     */
    public function setUser(?User $user): ReservationJeu
    {
        $this->user = $user;

        return $this;
    }

    /**
     * 
     * @return \App\Entity\Chene\JeuEnChene|null
     */
    public function getJeu(): ?JeuEnChene
    {
        return $this->jeu;
    }

    /**
     * 
     * @param \App\Entity\Chene\JeuEnChene|null $jeu
     * @return \App\Entity\Chene\ReservationJeu
     */
    public function setJeu(?JeuEnChene $jeu): ReservationJeu
    {
        $this->jeu = $jeu;

        return $this;
    }
}
