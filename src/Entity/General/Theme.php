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

    /**
     * @ORM\OneToMany(targetEntity=Actualite::class, mappedBy="theme")
     */
    private $actualites;

    /**
     * @ORM\OneToMany(targetEntity=Grade::class, mappedBy="theme", orphanRemoval=true)
     */
    private $grades;

    public function __construct()
    {
        $this->niveaux = new ArrayCollection();
        $this->actualites = new ArrayCollection();
        $this->grades = new ArrayCollection();
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

    /**
     * @return Collection|Actualite[]
     */
    public function getActualites(): Collection
    {
        return $this->actualites;
    }

    /**
     * 
     * @param \App\Entity\General\Actualite $actualite
     * @return \App\Entity\General\Theme
     */
    public function addActualite(Actualite $actualite): Theme
    {
        if (!$this->actualites->contains($actualite)) {
            $this->actualites[] = $actualite;
            $actualite->setTheme($this);
        }

        return $this;
    }

    /**
     * 
     * @param \App\Entity\General\Actualite $actualite
     * @return \App\Entity\General\Theme
     */
    public function removeActualite(Actualite $actualite): Theme
    {
        if ($this->actualites->contains($actualite)) {
            $this->actualites->removeElement($actualite);
            // set the owning side to null (unless already changed)
            if ($actualite->getTheme() === $this) {
                $actualite->setTheme(null);
            }
        }

        return $this;
    }
    
    /**
     * @return null|Grade[]
     */
    public function getGradesActuel($numNiveau): ?array
    {
        $result = [];
        
        foreach ($this->grades->toArray() as $gra) {
            if ($gra->getNum() == $numNiveau) {
                $result[] = $gra;
            }
        }
        
        return $result;
    }

    /**
     * @return Collection|Grade[]
     */
    public function getGrades(): Collection
    {
        return $this->grades;
    }

    public function addGrade(Grade $grade): self
    {
        if (!$this->grades->contains($grade)) {
            $this->grades[] = $grade;
            $grade->setTheme($this);
        }

        return $this;
    }

    public function removeGrade(Grade $grade): self
    {
        if ($this->grades->contains($grade)) {
            $this->grades->removeElement($grade);
            // set the owning side to null (unless already changed)
            if ($grade->getTheme() === $this) {
                $grade->setTheme(null);
            }
        }

        return $this;
    }
}
