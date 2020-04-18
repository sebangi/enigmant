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
 * @ORM\Entity(repositoryClass="App\Repository\Chene\CollectionCheneRepository")
 * @UniqueEntity("nom")
 * @Vich\Uploadable
 */
class CollectionChene
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     */
    private $nom;

    /**
     * @ORM\Column(type="integer")
     * @assert\Range(min=1)
     */
    private $num;
    
    /**
     * @Vich\UploadableField(mapping="image", fileNameProperty="imageName")     
     * @assert\Image(mimeTypes="image/jpg") 
     * @var File|null
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string|null
     */
    private $imageName;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Chene\JeuEnChene", mappedBy="collectionChene")
     * @var ArrayCollection<JeuEnChene> 
     */
    private $jeuEnChenes;

    public function __construct()
    {
        $this->jeuEnChenes = new ArrayCollection();
    }
    
    /**
     * 
     * @return \App\Entity\Chene\File|null
     */
    public function getImageFile(): ?File {
        return $this->imageFile;
    }

    /**
     * 
     * @return string|null
     */
    public function getImageName(): ?string  {
        return $this->imageName;
    }

    /**
     * 
     * @param \App\Entity\Chene\File $imageFile
     * @return \App\Entity\Chene\CollectionChene
     */
    public function setImageFile(File $imageFile): CollectionChene {
        $this->imageFile = $imageFile;
        return $this;
    }

    /**
     * 
     * @param type $imageName
     * @return \App\Entity\Chene\CollectionChene
     */
    public function setImageName($imageName): CollectionChene {
        $this->imageName = $imageName;
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
    public function getNom(): ?string
    {
        return $this->nom;
    }

    /**
     * 
     * @param string $nom
     * @return \App\Entity\Chene\CollectionChene
     */
    public function setNom(string $nom): CollectionChene
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
     * @return \App\Entity\Chene\CollectionChene
     */
    public function setNum(int $num): CollectionChene
    {
        $this->num = $num;

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
     * @return Collection|jeuEnChene[]
     */
    public function getJeuEnChenes(): Collection
    {
        return $this->jeuEnChenes;
    }

    /**
     * 
     * @param \App\Entity\Chene\jeuEnChene $jeuEnChene
     * @return \App\Entity\Chene\CollectionChene
     */
    public function addJeuEnChene(jeuEnChene $jeuEnChene): CollectionChene
    {
        if (!$this->jeuEnChenes->contains($jeuEnChene)) {
            $this->jeuEnChenes[] = $jeuEnChene;
            $jeuEnChene->setCollectionChene($this);
        }

        return $this;
    }

    /**
     * 
     * @param \App\Entity\Chene\jeuEnChene $jeuEnChene
     * @return \App\Entity\Chene\CollectionChene
     */
    public function removeJeuEnChene(jeuEnChene $jeuEnChene): CollectionChene
    {
        if ($this->jeuEnChenes->contains($jeuEnChene)) {
            $this->jeuEnChenes->removeElement($jeuEnChene);
            // set the owning side to null (unless already changed)
            if ($jeuEnChene->getCollectionChene() === $this) {
                $jeuEnChene->setCollectionChene(null);
            }
        }

        return $this;
    }
    

}
