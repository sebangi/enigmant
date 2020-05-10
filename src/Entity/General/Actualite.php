<?php

namespace App\Entity\General;

use App\Entity\Chene\Babiole;
use App\Entity\Chene\CollectionChene;
use App\Entity\Chene\JeuEnChene;
use App\Repository\General\ActualiteRepository;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ActualiteRepository::class)
 * @Vich\Uploadable
 */
class Actualite
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
    private $titre;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity=Theme::class, inversedBy="actualites")
     */
    private $theme;

    /**
     * @ORM\ManyToOne(targetEntity=JeuEnChene::class)
     */
    private $jeuEnChene;

    /**
     * @ORM\ManyToOne(targetEntity=CollectionChene::class)
     */
    private $collectionChene;

    /**
     * @ORM\ManyToOne(targetEntity=Babiole::class)
     */
    private $babiole;

    /**
     * @ORM\Column(type="text")
     */
    private $texte;

    /**
     * @ORM\Column(type="boolean", options={"default" : 0})
     */
    private $prioritaire = false;
    
    /**
     * @Vich\UploadableField(mapping="actualite_image", fileNameProperty="imageName")     
     * @assert\Image(mimeTypes="image/jpeg") 
     * @var File|null
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string|null
     */
    private $imageName;
    
    /**
     * 
     */
    public function __construct()
    {
        $this->date = new \DateTime('now');
    }
        
    /**
     * 
     * @return null|File
     */
    public function getImageFile(): ?File {
        return $this->imageFile;
    }

    /**
     * @param null|File $imageFile
     * @return Actualite
     */
    public function setImageFile(?File $imageFile): Actualite {
        $this->imageFile = $imageFile;

        return $this;
    }
    
    /**
     * 
     * @return null|string
     */
    public function getImageName(): ?string {
        return $this->imageName;
    }

    /**
     * 
     * @param null|string $imageName
     * @return Actualite
     */
    public function setImageName(?string $imageName): Actualite {
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
    public function getTitre(): ?string
    {
        return $this->titre;
    }

    /**
     * 
     * @param string $titre
     * @return \App\Entity\General\Actualite
     */
    public function setTitre(string $titre): Actualite
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * 
     * @return \DateTimeInterface|null
     */
    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    /**
     * 
     * @param \DateTimeInterface $date
     * @return \App\Entity\General\Actualite
     */
    public function setDate(\DateTimeInterface $date): Actualite
    {
        $this->date = $date;

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
     * @return \App\Entity\General\Actualite
     */
    public function setTheme(?Theme $theme): Actualite
    {
        $this->theme = $theme;

        return $this;
    }

    /**
     * 
     * @return JeuEnChene|null
     */
    public function getJeuEnChene(): ?JeuEnChene
    {
        return $this->jeuEnChene;
    }

    /**
     * 
     * @param JeuEnChene|null $jeuEnChene
     * @return \App\Entity\General\Actualite
     */
    public function setJeuEnChene(?JeuEnChene $jeuEnChene): Actualite
    {
        $this->jeuEnChene = $jeuEnChene;

        return $this;
    }

    /**
     * 
     * @return CollectionChene|null
     */
    public function getCollectionChene(): ?CollectionChene
    {
        return $this->collectionChene;
    }

    /**
     * 
     * @param CollectionChene|null $collectionChene
     * @return \App\Entity\General\Actualite
     */
    public function setCollectionChene(?CollectionChene $collectionChene): Actualite
    {
        $this->collectionChene = $collectionChene;

        return $this;
    }

    /**
     * 
     * @return Babiole|null
     */
    public function getBabiole(): ?Babiole
    {
        return $this->babiole;
    }

    /**
     * 
     * @param Babiole|null $babiole
     * @return \App\Entity\General\Actualite
     */
    public function setBabiole(?Babiole $babiole): Actualite
    {
        $this->babiole = $babiole;

        return $this;
    }

    /**
     * 
     * @return string|null
     */
    public function getTexte(): ?string
    {
        return $this->texte;
    }

    /**
     * 
     * @param string $texte
     * @return \App\Entity\General\Actualite
     */
    public function setTexte(string $texte): Actualite
    {
        $this->texte = $texte;

        return $this;
    }

    /**
     * 
     * @return bool|null
     */
    public function getPrioritaire(): ?bool
    {
        return $this->prioritaire;
    }

    /**
     * 
     * @param bool $prioritaire
     * @return \App\Entity\General\Actualite
     */
    public function setPrioritaire(bool $prioritaire): Actualite
    {
        $this->prioritaire = $prioritaire;

        return $this;
    }
}
