<?php

namespace App\Entity\Chene;

use App\Entity\General\Conversation;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Cocur\Slugify\Slugify;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Chene\JeuEnCheneRepository")
 * @UniqueEntity("nom")
 * @Vich\Uploadable
 */
class JeuEnChene {

    const codeLocation = [
                0 => 'une semaine',
                1 => 'deux semaines',
                2 => 'un mois'
    ];
    
    const codeNiveauDifficulte = [
                0 => 'facile',
                1 => 'moyen',
                2 => 'difficile'
    ];

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="boolean", options={"default" : false})
     */
    private $disponible = false;

    /**
     * @ORM\Column(type="integer", nullable=true, options={"default" : 5})
     * @assert\Range(min=0, max=10)
     */
    private $difficulteObservation = 5;

    /**
     * @ORM\Column(type="integer", nullable=true, options={"default" : 5})
     * @assert\Range(min=0, max=10)
     */
    private $difficulteRaisonnement = 5;

    /**
     * @ORM\Column(type="integer")
     */
    private $niveauDifficulte;
    
    /**
     * @ORM\Column(type="integer")
     */
    private $tempsLocation;
    
    /**
     * @ORM\Column(type="integer", options={"default" : 1})
     */
    private $prix = 1;

    /**
     * @ORM\ManyToMany(targetEntity="\App\Entity\Chene\Babiole", inversedBy="jeuEnChenes", cascade={"persist"})
     * @var ArrayCollection<Babiole> 
     */
    private $babioles;

    /**
     * @Vich\UploadableField(mapping="jeu_en_chene_image", fileNameProperty="imageName")     
     * @assert\Image(mimeTypes="image/jpeg") 
     * @var File|null
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string|null
     */
    private $imageName;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @param \DateTimeInterface
     */
    private $majDate;

    /**
     * @ORM\Column(type="integer", options={"default" : 1})
     */
    private $num = 1;

    /**
     * @ORM\Column(type="integer", nullable=true, options={"default" : 1})
     */
    private $nombreEtapes = 1;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Chene\CollectionChene", inversedBy="jeuEnChenes")
     * @var CollectionChene     
     */
    private $collectionChene;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Chene\ReservationJeu", mappedBy="jeu", orphanRemoval=true, cascade={"persist", "remove"})
     */
    private $reservations;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\General\Conversation", mappedBy="lienJeuEnChene")
     */
    private $conversations;

    /**
     * @ORM\Column(type="boolean", options={"default" : 0})
     */
    private $construit = false;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $couleur;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $commentairesGourou;


    public function __construct() {
        $this->babioles = new ArrayCollection();
        $this->reservations = new ArrayCollection();
        $this->conversations = new ArrayCollection();
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
     * @return string|null
     */
    public function getCouleur(): ?string
    {
        return $this->couleur;
    }

    /**
     * 
     * @param string|null $couleur
     * @return \App\Entity\Chene\JeuEnChene
     */
    public function setCouleur(?string $couleur): JeuEnChene
    {
        $this->couleur = $couleur;

        return $this;
    }

    /**
     * 
     * @return string|null
     */
    public function getNom(): ?string {
        return $this->nom;
    }

    /**
     * 
     * @return string|null
     */
    public function getNomEtCollection(): ?string {
        if ($this->collectionChene) {
            return $this->nom . " [" . $this->collectionChene->getNom() . "]";
        } else {
            return $this->nom;
        }
    }

    /**
     * 
     * @param string $nom
     * @return \App\Entity\Chene\JeuEnChene
     */
    public function setNom(string $nom): JeuEnChene {
        $this->nom = $nom;

        return $this;
    }

    /**
     * 
     * @return string
     */
    public function getSlug(): string {
        return ( new Slugify())->slugify($this->nom);
    }

    /**
     * 
     * @return string|null
     */
    public function getDescription(): ?string {
        return $this->description;
    }

    /**
     * 
     * @param string|null $description
     * @return \App\Entity\Chene\JeuEnChene 
     */
    public function setDescription(?string $description): JeuEnChene {
        $this->description = $description;

        return $this;
    }

    /**
     * 
     * @return bool|null
     */
    public function getDisponible(): ?bool {
        return $this->disponible;
    }

    /**
     * 
     * @param bool $disponible
     * @return JeuEnChene
     */
    public function setDisponible(bool $disponible): JeuEnChene {
        $this->disponible = $disponible;

        return $this;
    }

    /**
     * 
     * @return int|null
     */
    public function getDifficulteObservation(): ?int {
        return $this->difficulteObservation;
    }

    /**
     * 
     * @param int|null $difficulteObservation
     * @return JeuEnChene
     */
    public function setDifficulteObservation(?int $difficulteObservation): JeuEnChene {
        $this->difficulteObservation = $difficulteObservation;

        return $this;
    }

    /**
     * 
     * @return int|null 
     */
    public function getDifficulteRaisonnement(): ?int {
        return $this->difficulteRaisonnement;
    }

    /**
     * 
     * @param int|null $difficulteRaisonnement
     * @return JeuEnChene
     */
    public function setDifficulteRaisonnement(?int $difficulteRaisonnement): JeuEnChene {
        $this->difficulteRaisonnement = $difficulteRaisonnement;

        return $this;
    }

    /**
     * 
     * @return int|null
     */
    public function getTempsLocation(): ?int {
        return $this->tempsLocation;
    }

    /**
     * @return string
     */
    public function getTempsLocationString(): string {
        return self::codeLocation[$this->tempsLocation];
    }

    /**
     * 
     * @param int $tempsLocation
     * @return \App\Entity\Chene\JeuEnChene
     */
    public function setTempsLocation(int $tempsLocation): JeuEnChene {
        $this->tempsLocation = $tempsLocation;

        return $this;
    }      
    
    /**
     * 
     * @return int|null
     */
    public function getNiveauDifficulte(): ?int {
        return $this->niveauDifficulte;
    }

    /**
     * @return string
     */
    public function getNiveauDifficulteString(): string {
        return self::codeNiveauDifficulte[$this->niveauDifficulte];
    }

    /**
     * 
     * @param int $niveauDifficulte
     * @return \App\Entity\Chene\JeuEnChene
     */
    public function setNiveauDifficulte(int $niveauDifficulte): JeuEnChene {
        $this->niveauDifficulte = $niveauDifficulte;

        return $this;
    }

    /**
     * 
     * @return int|null
     */
    public function getPrix(): ?int {
        return $this->prix;
    }

    /**
     * 
     * @return string
     */
    public function getPrixFormate(): string {
        if ($this->prix > 1)
            return $this->prix . " babioles";
        else
            return $this->prix . " babiole";
    }

    /**
     * 
     * @param int $prix
     * @return \App\Entity\Chene\JeuEnChene
     */
    public function setPrix(int $prix): JeuEnChene {
        $this->prix = $prix;

        return $this;
    }

    /**
     * @return Babiole[]
     */
    public function getBabioles(): Collection {
        return $this->babioles;
    }

    /**
     * 
     * @param Babiole $babiole
     * @return \App\Entity\Chene\JeuEnChene
     */
    public function addBabiole(Babiole $babiole): JeuEnChene {
        if (!$this->babioles->contains($babiole)) {
            $this->babioles[] = $babiole;
        }

        return $this;
    }

    /**
     * 
     * @param Babiole $babiole
     * @return \App\Entity\Chene\JeuEnChene
     */
    public function removeBabiole(Babiole $babiole): JeuEnChene {
        if ($this->babioles->contains($babiole)) {
            $this->babioles->removeElement($babiole);
        }

        return $this;
    }

    /**
     * 
     * @return \DateTimeInterface|null
     */
    public function getMajDate(): ?\DateTimeInterface {
        return $this->majDate;
    }

    /**
     * 
     * @param \DateTimeInterface $majDate
     * @return \App\Entity\Chene\JeuEnChene
     */
    public function setMajDate(\DateTimeInterface $majDate): JeuEnChene {
        $this->majDate = $majDate;

        return $this;
    }

    /**
     * 
     * @return int|null
     */
    public function getNum(): ?int {
        return $this->num;
    }

    /**
     * 
     * @param int $num
     * @return \self
     */
    public function setNum(int $num): self {
        $this->num = $num;

        return $this;
    }

    /**
     * 
     * @return int|null
     */
    public function getNombreEtapes(): ?int {
        return $this->nombreEtapes;
    }

    /**
     * 
     * @param int|null $nombreEtapes
     * @return \App\Entity\Chene\JeuEnChene
     */
    public function setNombreEtapes(?int $nombreEtapes): JeuEnChene {
        $this->nombreEtapes = $nombreEtapes;

        return $this;
    }

    /**
     * 
     * @return \App\Entity\Chene\CollectionChene|null
     */
    public function getCollectionChene(): ?CollectionChene {
        return $this->collectionChene;
    }

    /**
     * 
     * @param \App\Entity\Chene\CollectionChene|null $collectionChene
     * @return \App\Entity\Chene\JeuEnChene
     */
    public function setCollectionChene(?CollectionChene $collectionChene): JeuEnChene {
        $this->collectionChene = $collectionChene;

        return $this;
    }

    /**
     * 
     * @return null|File
     */
    public function getImageFile(): ?File {
        return $this->imageFile;
    }

    /**
     * @param null|File $imageFile
     * @return JeuEnChene
     */
    public function setImageFile(?File $imageFile): JeuEnChene {
        $this->imageFile = $imageFile;

        if ($this->imageFile instanceof UploadedFile) {
            $this->majDate = new \DateTime('now');
        }

        return $this;
    }

    /**
     * 
     * @return null|string
     */
    public function getImageName(): ?string {
        return $this->imageName;
    }

    /**
     * 
     * @param null|string $imageName
     * @return JeuEnChene
     */
    public function setImageName(?string $imageName): JeuEnChene {
        $this->imageName = $imageName;
        return $this;
    }

    /**
     * @return Collection|ReservationJeu[]
     */
    public function getReservations(): Collection {
        return $this->reservations;
    }

    /**
     * 
     * @param \App\Entity\Chene\ReservationJeu $reservation
     * @return \App\Entity\Chene\JeuEnChene
     */
    public function addReservation(ReservationJeu $reservation): JeuEnChene {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations[] = $reservation;
            $reservation->setJeu($this);
        }

        return $this;
    }

    /**
     * 
     * @param \App\Entity\Chene\ReservationJeu $reservation
     * @return \App\Entity\Chene\JeuEnChene
     */
    public function removeReservation(ReservationJeu $reservation): JeuEnChene {
        if ($this->reservations->contains($reservation)) {
            $this->reservations->removeElement($reservation);
            // set the owning side to null (unless already changed)
            if ($reservation->getJeu() === $this) {
                $reservation->setJeu(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Conversation[]
     */
    public function getConversations(): Collection {
        return $this->conversations;
    }

    /**
     * 
     * @param Conversation $conversation
     * @return \App\Entity\Chene\JeuEnChene
     */
    public function addConversation(Conversation $conversation): JeuEnChene {
        if (!$this->conversations->contains($conversation)) {
            $this->conversations[] = $conversation;
            $conversation->setLienJeuEnChene($this);
        }

        return $this;
    }

    /**
     * 
     * @param Conversation $conversation
     * @return \App\Entity\Chene\JeuEnChene
     */
    public function removeConversation(Conversation $conversation): JeuEnChene {
        if ($this->conversations->contains($conversation)) {
            $this->conversations->removeElement($conversation);
            // set the owning side to null (unless already changed)
            if ($conversation->getLienJeuEnChene() === $this) {
                $conversation->setLienJeuEnChene(null);
            }
        }

        return $this;
    }

    /**
     * 
     * @return bool|null
     */
    public function getConstruit(): ?bool {
        return $this->construit;
    }

    /**
     * 
     * @param bool $construit
     * @return \App\Entity\Chene\JeuEnChene
     */
    public function setConstruit(bool $construit): JeuEnChene {
        $this->construit = $construit;

        return $this;
    }

    /**
     * 
     * @return string|null
     */
    public function getCommentairesGourou(): ?string {
        return $this->commentairesGourou;
    }

    /**
     * 
     * @param string|null $commentairesGourou
     * @return \App\Entity\Chene\JeuEnChene
     */
    public function setCommentairesGourou(?string $commentairesGourou): JeuEnChene {
        $this->commentairesGourou = $commentairesGourou;

        return $this;
    }

    /**
     * 
     * @param type $id_user
     * @return bool
     */
    public function estGratuit($id_user): bool {
        foreach ($this->babioles->toArray() as $bab) {
            if ($bab->getUser()) {
                if ($bab->getUser()->getId() == $id_user) {
                    return true;
                }
            }
        }

        return false;
    }


}
