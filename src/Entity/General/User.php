<?php

namespace App\Entity\General;

use App\Entity\Chene\Babiole;
use App\Entity\Chene\ReservationJeu;
use App\Entity\Chene\JeuEnChene;
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
 * @UniqueEntity(fields={"username"}, message="L'identifiant {{ value }} existe déjà. Choisissez-en un autre.")
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
     * @Assert\Regex( 
     * pattern = "/^[a-zA-Z0-9]{3,20}$/i", 
     * htmlPattern = "^[a-zA-Z0-9]{3,20}$", 
     * message="Votre identifiant doit contenir seulement des lettres et des chiffres et doit contenir entre 3 et 20 caractères"
     * )
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min = 10, max = 20, minMessage = "Il faut 10 chiffres", maxMessage = "Votre numéro de téléphone est trop long")
     * @Assert\Regex(
     * pattern="/^0[0-9]*$/i", 
     * htmlPattern="^0[0-9]*$", 
     * message="Ce n'est pas un numéro de téléphone valide") 
     */
    private $telephone;

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
     * @ORM\Column(type="boolean", options={"default" : true})
     */
    private $visible = true;

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
     * @ORM\Column(type="boolean", options={"default" : true})
     */
    private $receptionInformationNouveau = true;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\General\Conversation", mappedBy="user", orphanRemoval=true)
     */
    private $conversations;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Chene\Babiole", mappedBy="user")
     */
    private $babioles;

    /**
     * @ORM\OneToMany(targetEntity=Grade::class, mappedBy="user", orphanRemoval=true)
     */
    private $grades;

    /**
     * 
     */
    public function __construct() {
        $this->obtentionNiveaux = new ArrayCollection();
        $this->reservationJeux = new ArrayCollection();
        $this->conversations = new ArrayCollection();
        $this->babioles = new ArrayCollection();
        $this->grades = new ArrayCollection();
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
     * 
     * @return bool
     */
    public function estAdmin(): bool {
        return in_array("ROLE_ADMIN", $this->roles);
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
     * Retourne le plus haut grade acquis d'un thème donné sous forme de chaine
     * @param string|null $theme
     * @return string|null
     */
    public function getGradeString(?string $theme): ?string {
        if ($theme) {
            $niveaux = new ArrayCollection();
            foreach ($this->obtentionNiveaux->toArray() as $obt) {
                if ($obt->getNiveau()->getTheme()->getNom() == $theme)
                    $niveaux->add($obt);
            }

            if (!$niveaux->isEmpty()) {
                $iterator = $niveaux->getIterator();

                $iterator->uasort(function ($a, $b) {
                    return $a->getNiveau()->getNum() <=> $b->getNiveau()->getNum();
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
     * A REVOIR CAR ON PEUT MAINTENANT UTILISER LA TABLE GRADE
     * Retourne le plus haut grade acquis d'un thème donné sous forme de chaine
     * @param string|null $theme
     * @return ObtentionNiveau|null
     */
    public function getGrade(?string $theme): ?ObtentionNiveau {
        if ($theme) {
            $niveaux = new ArrayCollection();
            foreach ($this->obtentionNiveaux->toArray() as $obt) {
                if ($obt->getNiveau()->getTheme()->getNom() == $theme)
                    $niveaux->add($obt);
            }

            if (!$niveaux->isEmpty()) {
                $iterator = $niveaux->getIterator();

                $iterator->uasort(function ($a, $b) {
                    return $a->getNiveau()->getNum() <=> $b->getNiveau()->getNum();
                });

                $niveauxTrie = new ArrayCollection(iterator_to_array($iterator));

                return $niveauxTrie->last();
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    /**
     * 
     * @return bool
     */
    public function hasGradeNonVu(): bool {
        if ($this->obtentionNiveaux->isEmpty()) {
            return false;
        } else {
            foreach ($this->obtentionNiveaux->toArray() as $obt) {
                if (!$obt->getVu())
                    return true;
            }

            return false;
        }
    }

    /**
     * 
     * @param Niveau $niveau
     * @return bool
     */
    public function hasGrade(Niveau $niveau): bool {
        if ($this->obtentionNiveaux->isEmpty()) {
            return false;
        } else {
            foreach ($this->obtentionNiveaux->toArray() as $obt) {
                if ($obt->getNiveau()->getId() == $niveau->getId())
                    return true;
            }

            return false;
        }
    }

    /**
     * 
     * @return ObtentionNiveaux|null
     */
    public function GetObtentionGrade($niveau_id): ?ObtentionNiveau {
        if ($this->obtentionNiveaux->isEmpty()) {
            return null;
        } else {
            foreach ($this->obtentionNiveaux->toArray() as $obt) {
                if ($obt->getNiveau()->getId() == $niveau_id)
                    return $obt;
            }

            return null;
        }
    }

    /**
     * 
     * @return bool
     */
    public function hasMessageNonVu(bool $est_admin): bool {
        foreach ($this->conversations->toArray() as $conv) {

            foreach ($conv->getMessages()->toArray() as $mess) {
                if ($est_admin) {
                    if (!$mess->getVuGourou())
                        return true;
                } else {
                    if (!$mess->getVu())
                        return true;
                }
            }
        }

        return false;
    }

    /**
     * @return Collection|ReservationJeu[]
     */
    public function getReservationJeux(): Collection {
        return $this->reservationJeux;
    }

    /**
     * 
     * @param ReservationJeu $reservationJeux
     * @return \App\Entity\General\User
     */
    public function addReservationJeux(ReservationJeu $reservationJeux): User {
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
    public function removeReservationJeux(ReservationJeu $reservationJeux): User {
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
     * @param \App\Entity\Chene\JeuEnChene $jeu
     * @return bool
     */
    public function aReussiJeu(JeuEnChene $jeu): bool {
        foreach ($this->reservations->toArray() as $res) {
            if ($res->getJeu() == $jeu &&
                    $res->getReussi())
                return true;
        }

        return false;
    }

    /**
     * 
     * @return string|null
     */
    public function getEmail(): ?string {
        return $this->email;
    }

    /**
     * 
     * @param string|null $email
     * @return \App\Entity\General\User
     */
    public function setEmail(?string $email): User {
        $this->email = $email;

        return $this;
    }

    /**
     * 
     * @return string|null
     */
    public function getNom(): ?string {
        return $this->nom;
    }

    /**
     * 
     * @param string $nom
     * @return \App\Entity\General\User
     */
    public function setNom(string $nom): User {
        $this->nom = $nom;

        return $this;
    }

    /**
     * 
     * @return string|null
     */
    public function getPrenom(): ?string {
        return $this->prenom;
    }

    /**
     * 
     * @param string $prenom
     * @return \App\Entity\General\User
     */
    public function setPrenom(string $prenom): User {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * 
     * @return bool|null
     */
    public function getVisible(): ?bool {
        return $this->visible;
    }

    /**
     * 
     * @param bool $visible
     * @return \App\Entity\General\User
     */
    public function setVisible(bool $visible): User {
        $this->visible = $visible;

        return $this;
    }

    /**
     * 
     * @return bool|null
     */
    public function getReceptionInformationChasse(): ?bool {
        return $this->receptionInformationChasse;
    }

    /**
     * 
     * @param bool $receptionInformationChasse
     * @return \App\Entity\General\User
     */
    public function setReceptionInformationChasse(bool $receptionInformationChasse): User {
        $this->receptionInformationChasse = $receptionInformationChasse;

        return $this;
    }

    /**
     * 
     * @return bool|null
     */
    public function getReceptionInformationChene(): ?bool {
        return $this->receptionInformationChene;
    }

    /**
     * 
     * @param bool $receptionInformationChene
     * @return \App\Entity\General\User
     */
    public function setReceptionInformationChene(bool $receptionInformationChene): User {
        $this->receptionInformationChene = $receptionInformationChene;

        return $this;
    }

    /**
     * 
     * @return bool|null
     */
    public function getReceptionInformationGenerale(): ?bool {
        return $this->receptionInformationGenerale;
    }

    /**
     * 
     * @param bool $receptionInformationGenerale
     * @return \App\Entity\General\User
     */
    public function setReceptionInformationGenerale(bool $receptionInformationGenerale): User {
        $this->receptionInformationGenerale = $receptionInformationGenerale;

        return $this;
    }
    
    /**
     * 
     * @return type
     */
    public function getReservations() {
        return $this->reservations;
    }

    /**
     * 
     * @return type
     */
    public function getReceptionInformationNouveau() {
        return $this->receptionInformationNouveau;
    }

    /**
     * 
     * @param type $reservations
     * @return \App\Entity\General\User
     */
    public function setReservations($reservations): User {
        $this->reservations = $reservations;
        return $this;
    }

    /**
     * 
     * @param type $receptionInformationNouveau
     * @return \App\Entity\General\User
     */
    public function setReceptionInformationNouveau($receptionInformationNouveau): User {
        $this->receptionInformationNouveau = $receptionInformationNouveau;
        return $this;
    }

    
    public function serialize() {
        return serialize([
            $this->id,
            $this->username,
            $this->password
        ]);
    }

    /**
     * 
     * @param type $serialized
     */
    public function unserialize($serialized) {
        list(
                $this->id,
                $this->username,
                $this->password
                ) = unserialize($serialized, ['allowed_classes' => false]);
    }

    /**
     * @return Collection|Conversation[]
     */
    public function getConversations(): Collection {
        return $this->conversations;
    }

    /**
     * 
     * @param \App\Entity\General\Conversation $conversation
     * @return \App\Entity\General\User
     */
    public function addConversation(Conversation $conversation): User {
        if (!$this->conversations->contains($conversation)) {
            $this->conversations[] = $conversation;
            $conversation->setUser($this);
        }

        return $this;
    }

    /**
     * 
     * @param \App\Entity\General\Conversation $conversation
     * @return \App\Entity\General\User
     */
    public function removeConversation(Conversation $conversation): User {
        if ($this->conversations->contains($conversation)) {
            $this->conversations->removeElement($conversation);
            // set the owning side to null (unless already changed)
            if ($conversation->getUser() === $this) {
                $conversation->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Babiole[]
     */
    public function getBabioles(): Collection {
        return $this->babioles;
    }

    /**
     * 
     * @param Babiole $babiole
     * @return \App\Entity\General\User
     */
    public function addBabiole(Babiole $babiole): User {
        if (!$this->babioles->contains($babiole)) {
            $this->babioles[] = $babiole;
            $babiole->setUser($this);
        }

        return $this;
    }

    /**
     * 
     * @param Babiole $babiole
     * @return \App\Entity\General\User
     */
    public function removeBabiole(Babiole $babiole): User {
        if ($this->babioles->contains($babiole)) {
            $this->babioles->removeElement($babiole);
            // set the owning side to null (unless already changed)
            if ($babiole->getUser() === $this) {
                $babiole->setUser(null);
            }
        }

        return $this;
    }

    /**
     * 
     * @return string|null
     */
    public function getTelephone(): ?string {
        return $this->telephone;
    }

    /**
     * 
     * @return string|null
     */
    public function getTelephoneFormate(): ?string {
        if (strlen($this->telephone) == 10) {
            $a1 = substr($this->telephone, 0, 2);
            $a2 = substr($this->telephone, 2, 2);
            $a3 = substr($this->telephone, 4, 2);
            $a4 = substr($this->telephone, 6, 2);
            $a5 = substr($this->telephone, 8, 2);

            return $a1 . ' ' . $a2 . ' ' . $a3 . ' ' . $a4 . ' ' . $a5;
        } else
            return $this->telephone;
    }

    /**
     * 
     * @param string $telephone
     * @return \App\Entity\General\User
     */
    public function setTelephone(string $telephone): User {
        $this->telephone = $telephone;

        return $this;
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
            $grade->setUser($this);
        }

        return $this;
    }

    public function removeGrade(Grade $grade): self
    {
        if ($this->grades->contains($grade)) {
            $this->grades->removeElement($grade);
            // set the owning side to null (unless already changed)
            if ($grade->getUser() === $this) {
                $grade->setUser(null);
            }
        }

        return $this;
    }

}
