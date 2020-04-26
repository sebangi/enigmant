<?php

namespace App\Entity\General;

use App\Entity\Chene\JeuEnChene;
use App\Entity\Chene\ReservationJeu;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\General\ConversationRepository")
 */
class Conversation
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
    private $Sujet;

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

    public function __construct()
    {
        $this->messages = new ArrayCollection();
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
    public function getSujet(): ?string
    {
        return $this->Sujet;
    }

    /**
     * 
     * @param string $Sujet
     * @return \App\Entity\General\Conversation
     */
    public function setSujet(string $Sujet): Conversation
    {
        $this->Sujet = $Sujet;

        return $this;
    }

    /**
     * 
     * @return ReservationJeu|null
     */
    public function getLienReservation(): ?ReservationJeu
    {
        return $this->lienReservation;
    }

    /**
     * 
     * @param ReservationJeu|null $lienReservation
     * @return \App\Entity\General\Conversation
     */
    public function setLienReservation(?ReservationJeu $lienReservation): Conversation
    {
        $this->lienReservation = $lienReservation;

        return $this;
    }

    /**
     * 
     * @return JeuEnChene|null
     */
    public function getLienJeuEnChene(): ?JeuEnChene
    {
        return $this->lienJeuEnChene;
    }

    /**
     * 
     * @param JeuEnChene|null $lienJeuEnChene
     * @return \App\Entity\General\Conversation
     */
    public function setLienJeuEnChene(?JeuEnChene $lienJeuEnChene): Conversation
    {
        $this->lienJeuEnChene = $lienJeuEnChene;

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
     * @return \App\Entity\General\Conversation
     */
    public function setUser(?User $user): Conversation
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setConversation($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->contains($message)) {
            $this->messages->removeElement($message);
            // set the owning side to null (unless already changed)
            if ($message->getConversation() === $this) {
                $message->setConversation(null);
            }
        }

        return $this;
    }
}