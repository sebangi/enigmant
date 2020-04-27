<?php

namespace App\Entity\General;

use App\Entity\Chene\JeuEnChene;
use App\Entity\Chene\ReservationJeu;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Cocur\Slugify\Slugify;

/**
 * @ORM\Entity(repositoryClass="App\Repository\General\ConversationRepository")
 */
class Conversation {

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $sujet;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Chene\ReservationJeu", inversedBy="conversation", cascade={"persist", "remove"})
     */
    private $lienReservation;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Chene\JeuEnChene", inversedBy="conversations")
     */
    private $lienJeuEnChene;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\User", inversedBy="conversations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\General\Message", mappedBy="conversation", orphanRemoval=true)
     */
    private $messages;

    /**
     * @ORM\Column(type="boolean", options={"default" : false})
     */
    private $creeParGourou = false;

    public function __construct() {
        $this->messages = new ArrayCollection();
    }

    /**
     * 
     * @return string
     */
    public function getSlug(): string {
        return ( new Slugify())->slugify($this->sujet);
    }

    /**
     * 
     * @return int|null
     */
    public function getId(): ?int {
        return $this->id;
    }

    /**
     * 
     * @return string|null
     */
    public function getSujet(): ?string {
        return $this->sujet;
    }

    /**
     * 
     * @param string $sujet
     * @return \App\Entity\General\Conversation
     */
    public function setSujet(string $sujet): Conversation {
        $this->sujet = $sujet;

        return $this;
    }

    /**
     * 
     * @return ReservationJeu|null
     */
    public function getLienReservation(): ?ReservationJeu {
        return $this->lienReservation;
    }

    /**
     * 
     * @param ReservationJeu|null $lienReservation
     * @return \App\Entity\General\Conversation
     */
    public function setLienReservation(?ReservationJeu $lienReservation): Conversation {
        $this->lienReservation = $lienReservation;

        return $this;
    }

    /**
     * 
     * @return JeuEnChene|null
     */
    public function getLienJeuEnChene(): ?JeuEnChene {
        return $this->lienJeuEnChene;
    }

    /**
     * 
     * @param JeuEnChene|null $lienJeuEnChene
     * @return \App\Entity\General\Conversation
     */
    public function setLienJeuEnChene(?JeuEnChene $lienJeuEnChene): Conversation {
        $this->lienJeuEnChene = $lienJeuEnChene;

        return $this;
    }

    /**
     * 
     * @return \App\Entity\General\User|null
     */
    public function getUser(): ?User {
        return $this->user;
    }

    /**
     * 
     * @param \App\Entity\General\User|null $user
     * @return \App\Entity\General\Conversation
     */
    public function setUser(?User $user): Conversation {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getMessages(): Collection {
        return $this->messages;
    }

    /**
     * 
     * @param \App\Entity\General\Message $message
     * @return \App\Entity\General\Conversation
     */
    public function addMessage(Message $message): Conversation {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setConversation($this);
        }

        return $this;
    }

    /**
     * 
     * @param \App\Entity\General\Message $message
     * @return \App\Entity\General\Conversation
     */
    public function removeMessage(Message $message): Conversation {
        if ($this->messages->contains($message)) {
            $this->messages->removeElement($message);
            // set the owning side to null (unless already changed)
            if ($message->getConversation() === $this) {
                $message->setConversation(null);
            }
        }

        return $this;
    }

    /**
     * 
     * @return int
     */
    public function getNbMessagesNonLus(bool $est_admin): int {
        if ($this->messages->isEmpty()) {
            return 0;
        } else {
            $c = 0;
            foreach ($this->messages->toArray() as $mess) {
                if ( $est_admin )
                {
                    if (! $mess->getVuGourou())
                    {
                        $c = $c + 1;
                    }
                }
                else
                {
                    if (! $mess->getVu())
                    {
                        $c = $c + 1;
                    }
                }
            }

            return $c;
        }
    }

    /**
     * 
     * @return string
     */
    public function getAncreNonVu(bool $est_admin): string {
        if ($this->messages->isEmpty()) {
            return "";
        } 
        else {
            $iterator = $this->messages->getIterator();

            $iterator->uasort(function ($a, $b) {
                return $a->getDate() <=> $b->getDate();
                });

            $messagesTries = new ArrayCollection(iterator_to_array($iterator));
            
            foreach ($messagesTries->toArray() as $mess) {
                if ( $est_admin )
                {
                    if ( ! $mess->getVuGourou() )
                    {
                        return "#message-".$mess->getId();
                    }
                }
                else 
                {
                    if ( ! $mess->getVu() )
                    {
                        return "#message-" . $mess->getId();
                    }
                }
            }
            
            // tous vu
            return "#nouveau-message";
        }
    }

    /**
     * 
     * @return bool|null
     */
    public function getCreeParGourou(): ?bool
    {
        return $this->creeParGourou;
    }

    /**
     * 
     * @param bool $creeParGourou
     * @return \App\Entity\General\Conversation
     */
    public function setCreeParGourou(bool $creeParGourou): Conversation
    {
        $this->creeParGourou = $creeParGourou;

        return $this;
    }

}
