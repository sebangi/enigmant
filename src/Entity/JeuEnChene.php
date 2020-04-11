<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Cocur\Slugify\Slugify;

/**
 * @ORM\Entity(repositoryClass="App\Repository\JeuEnCheneRepository")
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
    private $Commentaire;

    /**
     * @ORM\Column(type="boolean", options={"default" : false})
     */
    private $disponible = false;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $difficulteObservation;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $difficulteRaisonnement;

    /**
     * @ORM\Column(type="integer")
     */
    private $tempsLocation;

    /**
     * @ORM\Column(type="integer")
     */
    private $prix;

    public function __construct()
    {
        
    }
        
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIntitule(): ?string
    {
        return $this->intitule;
    }
    
    public function setIntitule(string $intitule): self
    {
        $this->intitule = $intitule;

        return $this;
    }

    public function getSlug() : string {
        return ( new Slugify() )->slugify($this->intitule); 
    }

    public function getCommentaire(): ?string
    {
        return $this->Commentaire;
    }

    public function setCommentaire(?string $Commentaire): self
    {
        $this->Commentaire = $Commentaire;

        return $this;
    }

    public function getDisponible(): ?bool
    {
        return $this->disponible;
    }

    public function setDisponible(bool $disponible): self
    {
        $this->disponible = $disponible;

        return $this;
    }

    public function getDifficulteObservation(): ?int
    {
        return $this->difficulteObservation;
    }

    public function setDifficulteObservation(?int $difficulteObservation): self
    {
        $this->difficulteObservation = $difficulteObservation;

        return $this;
    }

    public function getDifficulteRaisonnement(): ?int
    {
        return $this->difficulteRaisonnement;
    }

    public function setDifficulteRaisonnement(?int $difficulteRaisonnement): self
    {
        $this->difficulteRaisonnement = $difficulteRaisonnement;

        return $this;
    }

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

    public function setTempsLocation(int $tempsLocation): self
    {
        $this->tempsLocation = $tempsLocation;

        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function getPrixFormate() : string {
        if ( $this->prix > 1 )
            return $this->prix . " babioles";
        else
            return $this->prix . " babiole";
    }

    public function setPrix(int $prix): self
    {
        $this->prix = $prix;

        return $this;
    }
}
