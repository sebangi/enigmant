<?php

namespace App\Entity\General;

use App\Repository\General\GradeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GradeRepository::class)
 */
class Grade
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", options={"default" : 0})
     */
    private $num = 0;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="grades")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Theme::class, inversedBy="grades")
     * @ORM\JoinColumn(nullable=false)
     */
    private $theme;

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
     * @return int|null
     */
    public function getNum(): ?int
    {
        return $this->num;
    }

    /**
     * 
     * @param int $num
     * @return \App\Entity\General\Grade
     */
    public function setNum(int $num): Grade
    {
        $this->num = $num;

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
     * @return \App\Entity\General\Grade
     */
    public function setUser(?User $user): Grade
    {
        $this->user = $user;

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
     * @return \App\Entity\General\Grade
     */
    public function setTheme(?Theme $theme): Grade
    {
        $this->theme = $theme;

        return $this;
    }
}
