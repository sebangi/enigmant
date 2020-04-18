<?php

namespace App\Entity\Chene;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Cocur\Slugify\Slugify;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Chene\CategorieBabioleRepository")
 * @UniqueEntity("nom")
 */
class CategorieBabiole
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
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     * @assert\Range(min=1)
     */
    private $num = 1;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Chene\Babiole", mappedBy="categorieBabiole")
     * @var ArrayCollection<Babiole> 
     */
    private $babioles;

    public function __construct()
    {
        $this->babioles = new ArrayCollection();
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
     * @return \App\Entity\Chene\CategorieBabiole
     */
    public function setNom(string $nom): CategorieBabiole
    {
        $this->nom = $nom;

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
     * @param string $description
     * @return \App\Entity\Chene\CategorieBabiole
     */
    public function setDescription(string $description): CategorieBabiole
    {
        $this->description = $description;

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
     * @return int|null
     */
    public function getNum(): ?int
    {
        return $this->num;
    }

    /**
     * 
     * @param int|null $num
     * @return \App\Entity\Chene\CategorieBabiole
     */
    public function setNum(?int $num): CategorieBabiole
    {
        $this->num = $num;

        return $this;
    }

    /**
     * @return Collection|Babiole[]
     */
    public function getBabioles(): Collection
    {
        return $this->babioles;
    }

    /**
     * 
     * @param \App\Entity\Chene\Babiole $babiole
     * @return CategorieBabiole
     */
    public function addBabiole(Babiole $babiole): CategorieBabiole
    {
        if (!$this->babioles->contains($babiole)) {
            $this->babioles[] = $babiole;
            $babiole->setCategorieBabiole($this);
        }

        return $this;
    }

    /**
     * 
     * @param \App\Entity\Chene\Babiole $babiole
     * @return CategorieBabiole
     */
    public function removeBabiole(Babiole $babiole): CategorieBabiole
    {
        if ($this->babioles->contains($babiole)) {
            $this->babioles->removeElement($babiole);
            // set the owning side to null (unless already changed)
            if ($babiole->getCategorieBabiole() === $this) {
                $babiole->setCategorieBabiole(null);
            }
        }

        return $this;
    }

}
