<?php

namespace App\Entity\General;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Repository\General\UserRepository;

/**
 * @ORM\Entity(repositoryClass="App\Repository\General\UserRepository")
 */
class User implements UserInterface {

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\General\ObtentionNiveau", mappedBy="user")
     */
    private $obtentionNiveaux;

    /**
     * 
     */
    public function __construct() {
        $this->obtentionNiveaux = new ArrayCollection();
    }

    /**
     * 
     * @return int|null
     */
    public function getId(): ?int {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string {
        return (string) $this->username;
    }

    /**
     * 
     * @param string $username
     * @return \App\Entity\General\User
     */
    public function setUsername(string $username): User {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * 
     * @param array $roles
     * @return \App\Entity\General\User
     */
    public function setRoles(array $roles): User {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string {
        return (string) $this->password;
    }

    /**
     * 
     * @param string $password
     * @return \App\Entity\General\User
     */
    public function setPassword(string $password): User {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt() {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials() {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection|ObtentionNiveau[]
     */
    public function getObtentionNiveaux(): Collection {
        return $this->obtentionNiveaux;
    }

    /**
     * 
     * @param \App\Entity\General\ObtentionNiveau $obtentionNiveau
     * @return \App\Entity\General\User
     */
    public function addObtentionNiveau(ObtentionNiveau $obtentionNiveau): User {
        if (!$this->obtentionNiveaux->contains($obtentionNiveau)) {
            $this->obtentionNiveaux[] = $obtentionNiveau;
            $obtentionNiveau->setUser($this);
        }

        return $this;
    }

    /**
     * 
     * @param \App\Entity\General\ObtentionNiveau $obtentionNiveau
     * @return \App\Entity\General\User
     */
    public function removeObtentionNiveau(ObtentionNiveau $obtentionNiveau): User {
        if ($this->obtentionNiveaux->contains($obtentionNiveau)) {
            $this->obtentionNiveaux->removeElement($obtentionNiveau);
            // set the owning side to null (unless already changed)
            if ($obtentionNiveau->getUser() === $this) {
                $obtentionNiveau->setUser(null);
            }
        }

        return $this;
    }

    /**
     * 
     * @param string|null $theme
     * @return string
     */
    public function getGrade(?string $theme): string {
        if ($theme) {
            $niveaux = new ArrayCollection();
            foreach ($this->obtentionNiveaux->toArray() as $obt) {
                if ($obt->getNiveau()->getTheme()->getNom() == strtolower($theme))
                    $niveaux->add($obt);
            }

            if (!$niveaux->isEmpty()) {
                $iterator = $niveaux->getIterator();
                $niveauxTrie = new ArrayCollection();

                $iterator->uasort(function ($a, $b) {
                    return $a->getdate() <=> $b->getdate();
                });

                $niveauxTrie = new ArrayCollection(iterator_to_array($iterator));

                return $niveauxTrie->last()->getNiveau()->getGrade() . " " . $this->username;
            } else {
                return $this->username;
            }
        } else {
            return $this->username;
        }        
    }

}
