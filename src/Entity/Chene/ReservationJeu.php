<?php

namespace App\Entity\Chene;

use App\Entity\General\Conversation;
use App\Entity\General\User;
use Cocur\Slugify\Slugify;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Chene\ReservationJeuRepository")
 */
class ReservationJeu {

    const codePossessionBabiole = [
        0 => 'a babiole',
        1 => 'a peut être babiole',
        2 => 'pas assez de babiole',
        3 => 'aucune babiole'
    ];
    const codeEtat = [
        0 => 'En cours de préparation',
        1 => 'Prêt pour le retrait',
        2 => 'Jeu retiré. En jeu !',
        3 => 'En attente de retour',
        4 => 'Prêt pour le retour',
        5 => 'Location terminée',
        6 => 'Avis donnés',
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
     * @ORM\Column(type="boolean", options={"default" : false})
     */
    private $avisDonne = false;
    
    /**
     * @ORM\Column(type="integer", options={"default" : -1})
     */
    private $note = -1;
    
    
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
     * @ORM\Column(type="integer", nullable=true)
     */
    private $possessionBabiole;

    /**
     * @ORM\Column(type="integer", options={"default" : 0})
     */
    private $etat = 0;

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
     * @ORM\Column(type="boolean", options={"default" : false})
     */
    private $retourRDV = false;

    /**
     * @ORM\Column(type="boolean", options={"default" : false})
     */
    private $retourDomicile = false;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $lieuRetourRDV;

    /**
     *
     * @var bool|null 
     */
    private $contactOk = false;

    /**
     * @ORM\OneToMany(targetEntity=Babiole::class, mappedBy="reservationJeu")
     */
    private $babioles;

    public function __construct() {
        $this->dateRetrait = null;
        $this->babioles = new ArrayCollection();
    }

    /**
     * 
     * @return type
     */
    public function getNote() {
        return $this->note;
    }

    /**
     * 
     * @param int|null $note
     * @return \App\Entity\Chene\ReservationJeu
     */
    public function setNote(?int $note) : ReservationJeu {
        $this->note = $note;
        return $this;
    }
            
    /**
     * 
     * @return string
     */
    public function getSlug(): string {
        return ( new Slugify())->slugify($this->getIntitule());
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
    public function getEtat(): ?int {
        return $this->etat;
    }

    /**
     * @return string
     */
    public function getEtatString(): string {
        return self::codeEtat[$this->etat];
    }
    
    /**
     * @return string
     */
    public function getEtatStringLong(): string {
        return "[". $this->etat . "/6" . "] " . self::codeEtat[$this->etat];
    }

    /**
     * @return string
     */
    public function getStatus(): string {
        if ($this->etat == 6)
            return self::codeEtat[5];
        else
            return self::codeEtat[$this->etat];
    }

    /**
     * 
     * @param int $etat
     * @return \App\Entity\Chene\ReservationJeu
     */
    public function setEtat(int $etat): ReservationJeu {
        $this->etat = $etat;

        return $this;
    }

    /**
     * 
     * @return int|null
     */
    public function getId(): ?int {
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
    public function setAAucuneBabiole($aAucuneBabiole): ReservationJeu {
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
    public function setAPeutEtreBabiole($aPeutEtreBabiole): ReservationJeu {
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
    public function setABabiole($aBabiole): ReservationJeu {
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
    public function setAPasAssezBabiole($aPasAssezBabiole): ReservationJeu {
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
    public function setContactOk($contactOk): ReservationJeu {
        $this->contactOk = $contactOk;
        return $this;
    }

    /**
     * 
     * @return \DateTimeInterface|null
     */
    public function getDateDemande(): ?\DateTimeInterface {
        return $this->dateDemande;
    }

    /**
     * 
     * @param \DateTimeInterface $dateDemande
     * @return \App\Entity\Chene\ReservationJeu
     */
    public function setDateDemande(\DateTimeInterface $dateDemande): ReservationJeu {
        $this->dateDemande = $dateDemande;

        return $this;
    }

    /**
     * 
     * @return \DateTimeInterface|null
     */
    public function getDateRetrait(): ?\DateTimeInterface {
        return $this->dateRetrait;
    }

    /**
     * 
     * @param \DateTimeInterface $dateRetrait
     * @return \App\Entity\Chene\ReservationJeu
     */
    public function setDateRetrait(\DateTimeInterface $dateRetrait): ReservationJeu {
        $this->dateRetrait = $dateRetrait;

        $date2 = clone $dateRetrait;
        if ($this->jeu->getTempsLocation() == 0)
            $date2->modify('+7 day');
        else if ($this->jeu->getTempsLocation() == 1)
            $date2->modify('+14 day');
        else
            $date2->modify('+1 month');

        $this->setDateFinPrevue($date2);

        return $this;
    }

    /**
     * 
     * @return \DateTimeInterface|null
     */
    public function getDateFinPrevue(): ?\DateTimeInterface {
        return $this->dateFinPrevue;
    }

    /**
     * 
     * @param \DateTimeInterface|null $dateFinPrevue
     * @return \App\Entity\Chene\ReservationJeu
     */
    public function setDateFinPrevue(?\DateTimeInterface $dateFinPrevue): ReservationJeu {
        $this->dateFinPrevue = $dateFinPrevue;

        return $this;
    }

    /**
     * 
     * @return \DateTimeInterface|null
     */
    public function getDateRendu(): ?\DateTimeInterface {
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
    public function getIntitule(): ?string {
        setlocale(LC_TIME, 'fr_FR.UTF8', 'fr.UTF8', 'fr_FR.UTF-8', 'fr.UTF-8');
        return "Location de " . $this->jeu->getNom() . " effectuée le " . strftime("%A %e %B %G", $this->dateDemande->getTimestamp());
    }

    /**
     * 
     * @return string|null
     */
    public function getAvisPublic(): ?string {
        return $this->avisPublic;
    }

    /**
     * 
     * @param string|null $avisPublic
     * @return \App\Entity\Chene\ReservationJeu
     */
    public function setAvisPublic(?string $avisPublic): ReservationJeu {
        $this->avisPublic = $avisPublic;

        return $this;
    }

    /**
     * 
     * @return string|null
     */
    public function getAvisPriveDifficulte(): ?string {
        return $this->avisPriveDifficulte;
    }

    /**
     * 
     * @param string|null $avisPriveDifficulte
     * @return \App\Entity\Chene\ReservationJeu
     */
    public function setAvisPriveDifficulte(?string $avisPriveDifficulte): ReservationJeu {
        $this->avisPriveDifficulte = $avisPriveDifficulte;

        return $this;
    }

    /**
     * 
     * @return string|null
     */
    public function getAvisPriveTechnique(): ?string {
        return $this->avisPriveTechnique;
    }

    /**
     * 
     * @param string|null $avisPriveTechnique
     * @return \App\Entity\Chene\ReservationJeu
     */
    public function setAvisPriveTechnique(?string $avisPriveTechnique): ReservationJeu {
        $this->avisPriveTechnique = $avisPriveTechnique;

        return $this;
    }

    /**
     * 
     * @return type
     */
    public function getAvisDonne() {
        return $this->avisDonne;
    }

    /**
     * 
     * @param type $avisDonne
     * @return \App\Entity\Chene\ReservationJeu
     */
    public function setAvisDonne($avisDonne) : ReservationJeu {
        $this->avisDonne = $avisDonne;
        return $this;
    }
        
    /**
     * 
     * @return bool|null
     */
    public function getReussi(): ?bool {
        return $this->reussi;
    }

    /**
     * 
     * @param bool $reussi
     * @return \App\Entity\Chene\ReservationJeu
     */
    public function setReussi(bool $reussi): ReservationJeu {
        $this->reussi = $reussi;

        return $this;
    }

    /**
     * 
     * @return User|null
     */
    public function getUser(): ?User {
        return $this->user;
    }

    /**
     * 
     * @param User|null $user
     * @return \App\Entity\Chene\ReservationJeu
     */
    public function setUser(?User $user): ReservationJeu {
        $this->user = $user;

        return $this;
    }

    /**
     * 
     * @return \App\Entity\Chene\JeuEnChene|null
     */
    public function getJeu(): ?JeuEnChene {
        return $this->jeu;
    }

    /**
     * 
     * @param \App\Entity\Chene\JeuEnChene|null $jeu
     * @return \App\Entity\Chene\ReservationJeu
     */
    public function setJeu(?JeuEnChene $jeu): ReservationJeu {
        $this->jeu = $jeu;

        return $this;
    }

    public function getConversation(): ?Conversation {
        return $this->conversation;
    }

    public function setConversation(?Conversation $conversation): self {
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
    public function getRetraitRDV(): ?bool {
        return $this->retraitRDV;
    }

    /**
     * 
     * @param bool $retraitRDV
     * @return \App\Entity\Chene\ReservationJeu
     */
    public function setRetraitRDV(bool $retraitRDV): ReservationJeu {
        $this->retraitRDV = $retraitRDV;

        return $this;
    }

    /**
     * 
     * @return bool|null
     */
    public function getRetraitDomicile(): ?bool {
        return $this->retraitDomicile;
    }

    /**
     * 
     * @param bool $retraitDomicile
     * @return \App\Entity\Chene\ReservationJeu
     */
    public function setRetraitDomicile(bool $retraitDomicile): ReservationJeu {
        $this->retraitDomicile = $retraitDomicile;

        return $this;
    }

    /**
     * 
     * @return string|null
     */
    public function getLieuRDV(): ?string {
        return $this->lieuRDV;
    }

    /**
     * 
     * @param string|null $lieuRDV
     * @return \App\Entity\Chene\ReservationJeu
     */
    public function setLieuRDV(?string $lieuRDV): ReservationJeu {
        $this->lieuRDV = $lieuRDV;

        return $this;
    }

    /**
     * 
     * @return type
     */
    public function getRetourRDV() {
        return $this->retourRDV;
    }

    /**
     * 
     * @return type
     */
    public function getRetourDomicile() {
        return $this->retourDomicile;
    }

    /**
     * 
     * @return type
     */
    public function getLieuRetourRDV() {
        return $this->lieuRetourRDV;
    }

    /**
     * 
     * @param type $retourRDV
     * @return \App\Entity\Chene\ReservationJeu
     */
    public function setRetourRDV($retourRDV): ReservationJeu {
        $this->retourRDV = $retourRDV;
        return $this;
    }

    /**
     * 
     * @param type $retourDomicile
     * @return \App\Entity\Chene\ReservationJeu
     */
    public function setRetourDomicile($retourDomicile): ReservationJeu {
        $this->retourDomicile = $retourDomicile;
        return $this;
    }

    /**
     * 
     * @param type $lieuRetourRDV
     * @return \App\Entity\Chene\ReservationJeu
     */
    public function setLieuRetourRDV($lieuRetourRDV): ReservationJeu {
        $this->lieuRetourRDV = $lieuRetourRDV;
        return $this;
    }

    /**
     * @return Collection|Babiole[]
     */
    public function getBabioles(): Collection
    {
        return $this->babioles;
    }

    public function addBabiole(Babiole $babiole): self
    {
        if (!$this->babioles->contains($babiole)) {
            $this->babioles[] = $babiole;
            $babiole->setReservationJeu($this);
        }

        return $this;
    }

    public function removeBabiole(Babiole $babiole): self
    {
        if ($this->babioles->contains($babiole)) {
            $this->babioles->removeElement($babiole);
            // set the owning side to null (unless already changed)
            if ($babiole->getReservationJeu() === $this) {
                $babiole->setReservationJeu(null);
            }
        }

        return $this;
    }

}
