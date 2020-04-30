<?php

namespace App\Entity\General;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\General\ThemeRepository")
 */
class Theme
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
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\General\Niveau", mappedBy="theme")
     */
    private $niveaux;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $route;

    /**
     * @ORM\Column(type="boolean", options={"default" : true})
     */
    private $disponible = true;

    public function __construct()
    {
        $this->niveaux = new ArrayCollection();
    }

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
     * @return \App\Entity\Theme
     */
    public function setNom(string $nom): Theme
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
     * @return \App\Entity\General\Theme
     */
    public function setNum(int $num): Theme
    {
        $this->num = $num;

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
     * @return \App\Entity\Theme
     */
    public function setDescription(?string $description): Theme
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|Niveau[]
     */
    public function getNiveaux(): Collection
    {
        return $this->niveaux;
    }

    /**
     * 
     * @param \App\Entity\General\Niveau $niveau
     * @return \App\Entity\General\Theme
     */
    public function addNiveau(Niveau $niveau): Theme
    {
        if (!$this->niveaux->contains($niveau)) {
            $this->niveaux[] = $niveau;
            $niveau->setTheme($this);
        }

        return $this;
    }

    /**
     * 
     * @param \App\Entity\General\Niveau $niveau
     * @return \App\Entity\General\Theme
     */
    public function removeNiveau(Niveau $niveau): Theme
    {
        if ($this->niveaux->contains($niveau)) {
            $this->niveaux->removeElement($niveau);
            // set the owning side to null (unless already changed)
            if ($niveau->getTheme() === $this) {
                $niveau->setTheme(null);
            }
        }

        return $this;
    }

    /**
     * 
     * @return string|null
     */
    public function getRoute(): ?string
    {
        return $this->route;
    }

    /**
     * 
     * @param string $route
     * @return \App\Entity\General\Theme
     */
    public function setRoute(string $route): Theme
    {
        $this->route = $route;

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
     * @return \App\Entity\General\Theme
     */
    public function setDisponible(bool $disponible): Theme
    {
        $this->disponible = $disponible;

        return $this;
    }
}
