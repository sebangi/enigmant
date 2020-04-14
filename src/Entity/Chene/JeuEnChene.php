<?php

namespace App\Entity\Chene;

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
 * @UniqueEntity("intitule")
 * @Vich\Uploadable
 */
class JeuEnChene
{
    const codeLocation = 
    [
        0 => 'une semaine',
        1 => 'deux semaines',
        2 => 'un mois'
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
    private $intitule;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $commentaire;

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
     */
    private $difficulteRaisonnement = 5;

    /**
     * @ORM\Column(type="integer")
     */
    private $tempsLocation;

    /**
     * @ORM\Column(type="integer", options={"default" : 1})
     */
    private $prix = 1;

    /**
     * @ORM\ManyToMany(targetEntity="\App\Entity\Chene\Babiole", inversedBy="jeuEnChenes")
     * @var ArrayCollection<Babiole> $babioles
     */
    private $babioles;
    
    /**
     * @Vich\UploadableField(mapping="badge_image", fileNameProperty="badgeImageName")     
     * @assert\Image(mimeTypes="image/png") 
     * @var File|null
     */
    private $badgeImageFile;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string|null
     */
    private $badgeImageName;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @param \DateTimeInterface
     */
    private $majDate;

    public function __construct()
    {
        $this->babioles = new ArrayCollection();
    }
    
    /**
     * 
     * @return null|File
     */
    public function getBadgeImageFile(): ?File 
    {
        return $this->badgeImageFile;
    }

    /**
     * @param null|File $badgeImageFile
     * @return JeuEnChene
     */
    public function setBadgeImageFile(?File $badgeImageFile) : JeuEnChene 
    {
        $this->badgeImageFile = $badgeImageFile;
        
        if ($this->badgeImageFile instanceof UploadedFile) {
            $this->majDate = new \DateTime('now');
        }
        
        return $this;
    }
    
     /**
     * 
     * @return null|string
     */
    public function getBadgeImageName() : ?string 
    {
        return $this->badgeImageName;
    }
    
    /**
     * 
     * @param null|string $badgeImageName
     * @return JeuEnChene
     */
    public function setBadgeImageName(?string $badgeImageName) : JeuEnChene
    {
        $this->badgeImageName = $badgeImageName;
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
     * @return string|null
     */
    public function getIntitule(): ?string
    {
        return $this->intitule;
    }
    
    
    /**
     * 
     * @param string $intitule
     * @return \App\Entity\Chene\JeuEnChene
     */
    public function setIntitule(string $intitule): JeuEnChene 
    {
        $this->intitule = $intitule;

        return $this;
    }

    /**
     * 
     * @return string
     */
    public function getSlug() : string {
        return ( new Slugify() )->slugify($this->intitule); 
    }

    /**
     * 
     * @return string|null
     */
    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    /**
     * 
     * @param string|null $commentaire
     * @return \App\Entity\Chene\JeuEnChene 
     */
    public function setCommentaire(?string $commentaire): JeuEnChene
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    /**
     * 
     * @return bool|null
     */
    public function getDisponible(): ?bool
    {
        return $this->disponible;
    }

    /**
     * 
     * @param bool $disponible
     * @return JeuEnChene
     */
    public function setDisponible(bool $disponible): JeuEnChene
    {
        $this->disponible = $disponible;

        return $this;
    }

    /**
     * 
     * @return int|null
     */
    public function getDifficulteObservation(): ?int
    {
        return $this->difficulteObservation;
    }

    /**
     * 
     * @param int|null $difficulteObservation
     * @return JeuEnChene
     */
    public function setDifficulteObservation(?int $difficulteObservation): JeuEnChene
    {
        $this->difficulteObservation = $difficulteObservation;

        return $this;
    }

    /**
     * 
     * @return int|null 
     */
    public function getDifficulteRaisonnement(): ?int
    {
        return $this->difficulteRaisonnement;
    }

    /**
     * 
     * @param int|null $difficulteRaisonnement
     * @return JeuEnChene
     */
    public function setDifficulteRaisonnement(?int $difficulteRaisonnement): JeuEnChene
    {
        $this->difficulteRaisonnement = $difficulteRaisonnement;

        return $this;
    }

    /**
     * 
     * @return int|null
     */
    public function getTempsLocation(): ?int
    {
        return $this->tempsLocation;
    }
    
    /**
     * @return string
     */
    public function getTempsLocationString() : string {
        return self::codeLocation[ $this->tempsLocation];
    }

    /**
     * 
     * @param int $tempsLocation
     * @return \App\Entity\Chene\JeuEnChene
     */
    public function setTempsLocation(int $tempsLocation): JeuEnChene
    {
        $this->tempsLocation = $tempsLocation;

        return $this;
    }

    /**
     * 
     * @return int|null
     */
    public function getPrix(): ?int
    {
        return $this->prix;
    }

    /**
     * 
     * @return string
     */
    public function getPrixFormate() : string {
        if ( $this->prix > 1 )
            return $this->prix . " babioles";
        else
            return $this->prix . " babiole";
    }

    /**
     * 
     * @param int $prix
     * @return \App\Entity\Chene\JeuEnChene
     */
    public function setPrix(int $prix): JeuEnChene
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * @return Babiole[]
     */
    public function getBabioles(): Collection
    {
        return $this->babioles;
    }

    /**
     * 
     * @param Babiole $babiole
     * @return \App\Entity\Chene\JeuEnChene
     */
    public function addBabiole(Babiole $babiole): JeuEnChene
    {
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
    public function removeBabiole(Babiole $babiole): JeuEnChene
    {
        if ($this->babioles->contains($babiole)) {
            $this->babioles->removeElement($babiole);
        }

        return $this;
    }

    /**
     * 
     * @return \DateTimeInterface|null
     */
    public function getMajDate(): ?\DateTimeInterface
    {
        return $this->majDate;
    }

    /**
     * 
     * @param \DateTimeInterface $majDate
     * @return \App\Entity\Chene\JeuEnChene
     */
    public function setMajDate(\DateTimeInterface $majDate): JeuEnChene
    {
        $this->majDate = $majDate;

        return $this;
    }
}
