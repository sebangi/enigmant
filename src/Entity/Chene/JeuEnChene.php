<?php

namespace App\Entity\Chene;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Cocur\Slugify\Slugify;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use \App\Entity\Chene\Babiole;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Chene\JeuEnCheneRepository")
 * @UniqueEntity("intitule")
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
     */
    private $babioles;

    public function __construct()
    {
        $this->babioles = new ArrayCollection();
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
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): self
    {
        $this->commentaire = $commentaire;

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
        }

        return $this;
    }

    public function removeBabiole(Babiole $babiole): self
    {
        if ($this->babioles->contains($babiole)) {
            $this->babioles->removeElement($babiole);
        }

        return $this;
    }
}
