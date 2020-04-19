<?php

namespace App\Entity\General;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\General\ObtentionNiveauRepository")
 */
class ObtentionNiveau
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     * @param \DateInterface
     */
    private $date;

    /**
     * @ORM\Column(type="boolean", options={"default" : false})
     */
    private $vu = false;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\Niveau", inversedBy="obtentionNiveaux")
     */
    private $niveau;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\User", inversedBy="obtentionNiveaux")
     */
    private $user;

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
     * @return \DateTimeInterface|null
     */
    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    /**
     * 
     * @param \DateTimeInterface $date
     * @return \App\Entity\General\ObtentionNiveau
     */
    public function setDate(\DateTimeInterface $date): ObtentionNiveau
    {
        $this->date = $date;

        return $this;
    }

    /**
     * 
     * @return bool|null
     */
    public function getVu(): ?bool
    {
        return $this->vu;
    }

    /**
     * 
     * @param bool $vu
     * @return \App\Entity\General\ObtentionNiveau
     */
    public function setVu(bool $vu): ObtentionNiveau
    {
        $this->vu = $vu;

        return $this;
    }

    /**
     * 
     * @return \App\Entity\General\Niveau|null
     */
    public function getNiveau(): ?Niveau
    {
        return $this->niveau;
    }

    /**
     * 
     * @param \App\Entity\General\Niveau|null $niveau
     * @return \App\Entity\General\ObtentionNiveau
     */
    public function setNiveau(?Niveau $niveau): ObtentionNiveau
    {
        $this->niveau = $niveau;

        return $this;
    }

    /**
     * 
     * @return \App\Entity\General\User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * 
     * @param \App\Entity\General\User|null $user
     * @return \App\Entity\General\ObtentionNiveau
     */
    public function setUser(?User $user): ObtentionNiveau
    {
        $this->user = $user;

        return $this;
    }
}
