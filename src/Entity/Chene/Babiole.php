<?php

namespace App\Entity\Chene;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Chene\BabioleRepository")
 */
class Babiole
{
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
     * @ORM\Column(type="integer", options={"default" : 1})
     */
    private $valeur = 1;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $commentaireGourou;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Chene\JeuEnChene", mappedBy="babioles")
     */
    private $jeuEnChenes;

    public function __construct()
    {
        $this->jeuEnChenes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getValeur(): ?int
    {
        return $this->valeur;
    }

    public function setValeur(int $valeur): self
    {
        $this->valeur = $valeur;

        return $this;
    }

    public function getCommentaireGourou(): ?string
    {
        return $this->commentaireGourou;
    }

    public function setCommentaireGourou(string $commentaireGourou): self
    {
        $this->commentaireGourou = $commentaireGourou;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|JeuEnChene[]
     */
    public function getJeuEnChenes(): Collection
    {
        return $this->jeuEnChenes;
    }

    public function addJeuEnChene(JeuEnChene $jeuEnChene): self
    {
        if (!$this->jeuEnChenes->contains($jeuEnChene)) {
            $this->jeuEnChenes[] = $jeuEnChene;
            $jeuEnChene->addBabiole($this);
        }

        return $this;
    }

    public function removeJeuEnChene(JeuEnChene $jeuEnChene): self
    {
        if ($this->jeuEnChenes->contains($jeuEnChene)) {
            $this->jeuEnChenes->removeElement($jeuEnChene);
            $jeuEnChene->removeBabiole($this);
        }

        return $this;
    }
    
    /**
     * 
     * @return string
     */
    public function getSlug() : string {
        return ( new Slugify() )->slugify($this->nom); 
    }
}
