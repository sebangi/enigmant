<?php

namespace App\Entity\General;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\General\NiveauRepository")
 */
class Niveau
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
     * @ORM\Column(type="integer")
     * @assert\Range(min=1)
     */
    private $num = 1;

    /**
     * @ORM\Column(type="boolean")
     */
    private $disponible;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $conditionTexte;
    
    /**
     * @ORM\Column(type="text")
     */
    private $raison;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $couleur;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\Theme", inversedBy="niveaux")
     */
    private $theme;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\General\ObtentionNiveau", mappedBy="niveau", cascade={"persist", "remove"})
     */
    private $obtentionNiveaux;

    /**
     * @ORM\Column(type="boolean", options={"default" : false})
     */
    private $nomCache = false;

    public function __construct()
    {
        $this->obtentionNiveaux = new ArrayCollection();
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
     * @return string|null
     */
    public function getGrade(): ?string
    {
        if ( $this->nom ) 
        {
            return $this->nom . " en " . $this->getTheme()->getNom();
        }
        else 
        {
            return null;            
        }
    }

    /**
     * 
     * @param string $nom
     * @return \App\Entity\General\Niveau
     */
    public function setNom(string $nom): Niveau
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * 
     * @return int|null
     */
    public function getNum(): ?int
    {
        return $this->num;
    }

    /**
     * 
     * @param int $num
     * @return \App\Entity\General\Niveau
     */
    public function setNum(int $num): Niveau
    {
        $this->num = $num;

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
     * @return \App\Entity\General\Niveau
     */
    public function setDisponible(bool $disponible): Niveau
    {
        $this->disponible = $disponible;

        return $this;
    }

    /**
     * 
     * @return string|null
     */
    public function getConditionTexte(): ?string
    {
        return $this->conditionTexte;
    }

    /**
     * 
     * @param string|null $conditionTexte
     * @return \App\Entity\General\Niveau
     */
    public function setConditionTexte(?string $conditionTexte): Niveau
    {
        $this->conditionTexte = $conditionTexte;

        return $this;
    }
    
    
    /**
     * 
     * @return string|null
     */
    public function getRaison(): ?string
    {
        return $this->raison;
    }

    /**
     * 
     * @param string $raison
     * @return \App\Entity\General\Niveau
     */
    public function setRaison(string $raison): Niveau
    {
        $this->raison = $raison;

        return $this;
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
     * @return \App\Entity\General\Niveau
     */
    public function setCouleur(?string $couleur): Niveau
    {
        $this->couleur = $couleur;

        return $this;
    }

    /**
     * 
     * @return \App\Entity\General\Theme|null
     */
    public function getTheme(): ?Theme
    {
        return $this->theme;
    }

    /**
     * 
     * @param \App\Entity\General\Theme|null $theme
     * @return \App\Entity\General\Niveau
     */
    public function setTheme(?Theme $theme): Niveau
    {
        $this->theme = $theme;

        return $this;
    }

    /**
     * @return Collection|ObtentionNiveau[]
     */
    public function getObtentionNiveaux(): Collection
    {
        return $this->obtentionNiveaux;
    }

    public function addObtentionNiveau(ObtentionNiveau $obtentionNiveau): self
    {
        if (!$this->obtentionNiveaux->contains($obtentionNiveau)) {
            $this->obtentionNiveaux[] = $obtentionNiveau;
            $obtentionNiveau->setNiveau($this);
        }

        return $this;
    }

    public function removeObtentionNiveau(ObtentionNiveau $obtentionNiveau): self
    {
        if ($this->obtentionNiveaux->contains($obtentionNiveau)) {
            $this->obtentionNiveaux->removeElement($obtentionNiveau);
            // set the owning side to null (unless already changed)
            if ($obtentionNiveau->getNiveau() === $this) {
                $obtentionNiveau->setNiveau(null);
            }
        }

        return $this;
    }
    
    public function getNomCache(): ?bool
    {
        return $this->nomCache;
    }

    public function setNomCache(bool $nomCache): self
    {
        $this->nomCache = $nomCache;

        return $this;
    }
}
