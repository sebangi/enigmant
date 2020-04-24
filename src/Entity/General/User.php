<?php

namespace App\Entity\General;

use App\Entity\Chene\ReservationJeu;
use App\Entity\General\ObtentionNiveau;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Repository\General\UserRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\Email;

/**
 * @ORM\Entity(repositoryClass="App\Repository\General\UserRepository")
 * @UniqueEntity("nom")
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
     * @ORM\OneToMany(targetEntity="App\Entity\General\ObtentionNiveau", mappedBy="user", cascade={"persist", "remove"})
     * @var Collection|ObtentionNiveau[] 
     */
    private $obtentionNiveaux;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Chene\ReservationJeu", mappedBy="user", orphanRemoval=true, cascade={"persist", "remove"})
     */
    private $reservations;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email(message = "'{{ value }}' n'est pas un email valide.")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prenom;

    /**
     * @ORM\Column(type="boolean", options={"default" : false})
     */
    private $masque = false;

    /**
     * @ORM\Column(type="boolean", options={"default" : true})
     */
    private $receptionInformationChasse = true;

    /**
     * @ORM\Column(type="boolean", options={"default" : true})
     */
    private $receptionInformationChene = true;    
    
    /**
     * @ORM\Column(type="boolean", options={"default" : true})
     */
    private $receptionInformationGenerale = true;
    
    /**
     * 
     */
    public function __construct() {
        $this->obtentionNiveaux = new ArrayCollection();
        $this->reservationJeux = new ArrayCollection();
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
        return null;
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
     * @return string|null
     */
    public function getGrade(?string $theme): ?string {
        if ($theme) {
            $niveaux = new ArrayCollection();
            foreach ($this->obtentionNiveaux->toArray() as $obt) {
                if ($obt->getNiveau()->getTheme()->getNom() == $theme)
                    $niveaux->add($obt);
            }

            if (!$niveaux->isEmpty()) {
                $iterator = $niveaux->getIterator();
                
                $iterator->uasort(function ($a, $b) {
                    return $a->getdate() <=> $b->getdate();
                });

                $niveauxTrie = new ArrayCollection(iterator_to_array($iterator));

                return $niveauxTrie->last()->getNiveau()->getGrade();
            } else {
                return null;
            }
        } else {
            return null;
        }        
    }
    
    /**
     * 
     * @param Niveau $niveau
     * @return bool
     */
    public function hasGrade(Niveau $niveau): bool {
        if ( $this->obtentionNiveaux->isEmpty() )
        {
            return false;
        }
        else
        {
            foreach ($this->obtentionNiveaux->toArray() as $obt) {
                if ( $obt->getNiveau()->getId() == $niveau->getId() )
                    return true;
            }
            
            return false;
        }        
    }    

    /**
     * @return Collection|ReservationJeu[]
     */
    public function getReservationJeux(): Collection
    {
        return $this->reservationJeux;
    }

    /**
     * 
     * @param ReservationJeu $reservationJeux
     * @return \App\Entity\General\User
     */
    public function addReservationJeux(ReservationJeu $reservationJeux): User
    {
        if (!$this->reservationJeux->contains($reservationJeux)) {
            $this->reservationJeux[] = $reservationJeux;
            $reservationJeux->setUser($this);
        }

        return $this;
    }

    /**
     * 
     * @param ReservationJeu $reservationJeux
     * @return \App\Entity\General\User
     */
    public function removeReservationJeux(ReservationJeu $reservationJeux): User
    {
        if ($this->reservationJeux->contains($reservationJeux)) {
            $this->reservationJeux->removeElement($reservationJeux);
            // set the owning side to null (unless already changed)
            if ($reservationJeux->getUser() === $this) {
                $reservationJeux->setUser(null);
            }
        }

        return $this;
    }

    /**
     * 
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * 
     * @param string|null $email
     * @return \App\Entity\General\User
     */
    public function setEmail(?string $email): User
    {
        $this->email = $email;

        return $this;
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
     * @return \App\Entity\General\User
     */
    public function setNom(string $nom): User
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * 
     * @return string|null
     */
    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    /**
     * 
     * @param string $prenom
     * @return \App\Entity\General\User
     */
    public function setPrenom(string $prenom): User
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * 
     * @return bool|null
     */
    public function getMasque(): ?bool
    {
        return $this->masque;
    }

    /**
     * 
     * @param bool $masque
     * @return \App\Entity\General\User
     */
    public function setMasque(bool $masque): User
    {
        $this->masque = $masque;

        return $this;
    }

    /**
     * 
     * @return bool|null
     */
    public function getReceptionInformationChasse(): ?bool
    {
        return $this->receptionInformationChasse;
    }

    /**
     * 
     * @param bool $receptionInformationChasse
     * @return \App\Entity\General\User
     */
    public function setReceptionInformationChasse(bool $receptionInformationChasse): User
    {
        $this->receptionInformationChasse = $receptionInformationChasse;

        return $this;
    }

    /**
     * 
     * @return bool|null
     */
    public function getReceptionInformationChene(): ?bool
    {
        return $this->receptionInformationChene;
    }

    /**
     * 
     * @param bool $receptionInformationChene
     * @return \App\Entity\General\User
     */
    public function setReceptionInformationChene(bool $receptionInformationChene): User
    {
        $this->receptionInformationChene = $receptionInformationChene;

        return $this;
    }

    /**
     * 
     * @return bool|null
     */
    public function getReceptionInformationGenerale(): ?bool
    {
        return $this->receptionInformationGenerale;
    }

    /**
     * 
     * @param bool $receptionInformationGenerale
     * @return \App\Entity\General\User
     */
    public function setReceptionInformationGenerale(bool $receptionInformationGenerale): User
    {
        $this->receptionInformationGenerale = $receptionInformationGenerale;

        return $this;
    }    
    
    
    
    public function serialize() {
        return serialize([
            $this->id,
            $this->username,
            $this->password
        ]);
    }

    public function unserialize($serialized) {
        list(
            $this->id,
            $this->username,
            $this->password
        ) = unserialize($serialized, ['allowed_classes' => false]);
    }
}
