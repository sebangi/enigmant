<?php

namespace App\Entity\Chene;

use App\Entity\General\Conversation;
use App\Entity\General\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Chene\ReservationJeuRepository")
 */
class ReservationJeu
{
    const codePossessionBabiole = [
                0 => 'a babiole',
                1 => 'a peut être babiole',
                2 => 'pas assez de babiole',
                3 => 'aucune babiole'
    ];
    
    
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateDemande;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateRetrait;
    
    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateFinPrevue;
    
     /**
     * @ORM\Column(type="datetime", nullable=true)
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
     * @ORM\OneToOne(targetEntity="App\Entity\General\Conversation", mappedBy="lienReservation", cascade={"persist", "remove"})
     */
    private $conversation;

    /**
     * @ORM\Column(type="boolean", options={"default" : false})
     */
    private $retraitRDV = false;

    /**
     * @ORM\Column(type="boolean", options={"default" : false})
     */
    private $retraitDomicile = false;
    
    /**
     * @ORM\Column(type="integer")
     */
    private $possessionBabiole;
    
    /**
     */
    private $aBabiole = false;
    
    /**
     */
    private $aPasAssezBabiole = false;
        
    /**
     */
    private $aPeutEtreBabiole = false;
    
    /**
     */
    private $aAucuneBabiole = false;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $lieuRDV;
    
    /**
     *
     * @var bool|null 
     */
    private $contactOk = false; 
    
    
    
    public function __construct() {
        $this->dateRetrait = null;
    }    
    
    /**
     * 
     * @return int|null
     */
    public function getPossessionBabiole(): ?int {
        return $this->possessionBabiole;
    }

    /**
     * @return string
     */
    public function getPossessionBabioleString(): string {
        return self::codePossessionBabiole[$this->possessionBabiole];
    }

    /**
     * 
     * @param int $possessionBabiole
     * @return \App\Entity\Chene\ReservationJeu
     */
    public function setPossessionBabiole(int $possessionBabiole): ReservationJeu {
        $this->possessionBabiole = $possessionBabiole;

        return $this;
    }
    
    
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
     * @return type
     */
    public function getAAucuneBabiole() {
        return $this->aAucuneBabiole;
    }

    /**
     * 
     * @param type $aAucuneBabiole
     * @return \App\Entity\Chene\ReservationJeu
     */
    public function setAAucuneBabiole($aAucuneBabiole) : ReservationJeu
    {
        $this->aAucuneBabiole = $aAucuneBabiole;
        return $this;
    }

        
    /**
     * 
     * @return type
     */
    public function getAPeutEtreBabiole() {
        return $this->aPeutEtreBabiole;
    }

    /**
     * 
     * @param type $aPeutEtreBabiole
     * @return \App\Entity\Chene\ReservationJeu
     */
    public function setAPeutEtreBabiole($aPeutEtreBabiole) :ReservationJeu
    {
        $this->aPeutEtreBabiole = $aPeutEtreBabiole;
        return $this;
    }   
    
    /**
     * 
     * @return type
     */
    public function getABabiole() {
        return $this->aBabiole;
    }

    /**
     * 
     * @param type $aBabiole
     * @return \App\Entity\Chene\ReservationJeu
     */
    public function setABabiole($aBabiole) : ReservationJeu 
    {
        $this->aBabiole = $aBabiole;
        return $this;
    }
    
    /**
     * 
     * @return type
     */
    public function getAPasAssezBabiole() {
        return $this->aPasAssezBabiole;
    }

    /**
     * 
     * @param type $aPasAssezBabiole
     * @return \App\Entity\Chene\ReservationJeu
     */
    public function setAPasAssezBabiole($aPasAssezBabiole) : ReservationJeu 
    {
        $this->aPasAssezBabiole = $aPasAssezBabiole;
        return $this;
    }

        
    /**
     * 
     * @return type
     */
    public function getContactOk() {
        return $this->contactOk;
    }

    /**
     * 
     * @param type $contactOk
     * @return \App\Entity\Chene\ReservationJeu
     */
    public function setContactOk($contactOk) : ReservationJeu {
        $this->contactOk = $contactOk;
        return $this;
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
    public function getIntitule(): ?string
    {
        setlocale(LC_TIME, 'fr_FR.UTF8', 'fr.UTF8', 'fr_FR.UTF-8', 'fr.UTF-8');
        return "Location de " .$this->jeu->getNom() . " effectuée le " . strftime("%A %e %B %G", $this->dateDemande->getTimestamp() );
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

    public function getConversation(): ?Conversation
    {
        return $this->conversation;
    }

    public function setConversation(?Conversation $conversation): self
    {
        $this->conversation = $conversation;

        // set (or unset) the owning side of the relation if necessary
        $newLienReservation = null === $conversation ? null : $this;
        if ($conversation->getLienReservation() !== $newLienReservation) {
            $conversation->setLienReservation($newLienReservation);
        }

        return $this;
    }

    /**
     * 
     * @return bool|null
     */
    public function getRetraitRDV(): ?bool
    {
        return $this->retraitRDV;
    }

    /**
     * 
     * @param bool $retraitRDV
     * @return \App\Entity\Chene\ReservationJeu
     */
    public function setRetraitRDV(bool $retraitRDV): ReservationJeu
    {
        $this->retraitRDV = $retraitRDV;

        return $this;
    }

    /**
     * 
     * @return bool|null
     */
    public function getRetraitDomicile(): ?bool
    {
        return $this->retraitDomicile;
    }

    /**
     * 
     * @param bool $retraitDomicile
     * @return \App\Entity\Chene\ReservationJeu
     */
    public function setRetraitDomicile(bool $retraitDomicile): ReservationJeu
    {
        $this->retraitDomicile = $retraitDomicile;

        return $this;
    }

    public function getLieuRDV(): ?string
    {
        return $this->lieuRDV;
    }

    public function setLieuRDV(?string $lieuRDV): self
    {
        $this->lieuRDV = $lieuRDV;

        return $this;
    }
}
