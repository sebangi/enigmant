<?php

namespace App\Entity\Chene;

use App\Entity\General\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use \App\Entity\Chene\TypeBabiole;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @assert\Range(min=0)
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

    /**
     * @ORM\ManyToOne(targetEntity="\App\Entity\Chene\TypeBabiole", inversedBy="babioles")
     */
    private $typeBabiole;
        
    /**
     * @ORM\ManyToOne(targetEntity="\App\Entity\Chene\CategorieBabiole", inversedBy="babioles")
     */
    private $categorieBabiole;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\User", inversedBy="babioles")
     */
    private $user;

    public function __construct()
    {
        $this->jeuEnChenes = new ArrayCollection();
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
    public function getNom(): ?string
    {
        return $this->nom;
    }

    /**
     * 
     * @param string $nom
     * @return \App\Entity\Chene\Babiole
     */
    public function setNom(string $nom): Babiole
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * 
     * @return int|null
     */
    public function getValeur(): ?int
    {
        return $this->valeur;
    }

    /**
     * 
     * @param int $valeur
     * @return \App\Entity\Chene\Babiole
     */
    public function setValeur(int $valeur): Babiole
    {
        $this->valeur = $valeur;

        return $this;
    }

    /**
     * 
     * @return string|null
     */
    public function getCommentaireGourou(): ?string
    {
        return $this->commentaireGourou;
    }

    /**
     * 
     * @param string $commentaireGourou
     * @return \App\Entity\Chene\Babiole
     */
    public function setCommentaireGourou(string $commentaireGourou): Babiole
    {
        $this->commentaireGourou = $commentaireGourou;

        return $this;
    }

    /**
     * 
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * 
     * @param string|null $description
     * @return \App\Entity\Chene\Babiole
     */
    public function setDescription(?string $description): Babiole
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

    /**
     * 
     * @param \App\Entity\Chene\JeuEnChene $jeuEnChene
     * @return \App\Entity\Chene\Babiole
     */
    public function addJeuEnChene(JeuEnChene $jeuEnChene): Babiole
    {
        if (!$this->jeuEnChenes->contains($jeuEnChene)) {
            $this->jeuEnChenes[] = $jeuEnChene;
            $jeuEnChene->addBabiole($this);
        }

        return $this;
    }

    /**
     * 
     * @param \App\Entity\Chene\JeuEnChene $jeuEnChene
     * @return \App\Entity\Chene\Babiole
     */
    public function removeJeuEnChene(JeuEnChene $jeuEnChene): Babiole
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

    /**
     * 
     * @return TypeBabiole|null
     */
    public function getTypeBabiole(): ?TypeBabiole
    {
        return $this->typeBabiole;
    }

    /**
     * 
     * @param TypeBabiole|null $typeBabiole
     * @return \App\Entity\Chene\Babiole
     */
    public function setTypeBabiole(?TypeBabiole $typeBabiole): Babiole
    {
        $this->typeBabiole = $typeBabiole;

        return $this;
    }    
    
    /**
     * 
     * @return CategorieBabiole|null
     */
    public function getCategorieBabiole(): ?CategorieBabiole
    {
        return $this->categorieBabiole;
    }

    /**
     * 
     * @param \App\Entity\Chene\CategorieBabiole|null $categorieBabiole
     * @return \App\Entity\Chene\Babiole
     */
    public function setCategorieBabiole(?CategorieBabiole $categorieBabiole): Babiole
    {
        $this->categorieBabiole = $categorieBabiole;

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
     * @return \App\Entity\Chene\Babiole
     */
    public function setUser(?User $user): Babiole
    {
        $this->user = $user;

        return $this;
    }
}
