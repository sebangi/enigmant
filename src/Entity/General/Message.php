<?php

namespace App\Entity\General;

use Doctrine\ORM\Mapping as ORM;
use Cocur\Slugify\Slugify;

/**
 * @ORM\Entity(repositoryClass="App\Repository\General\MessageRepository")
 */
class Message
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $texte;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date;

    /**
     * @ORM\Column(type="boolean", options={"default" : false})
     */
    private $vu = false;

    /**
     * @ORM\Column(type="boolean", options={"default" : false})
     */
    private $vuGourou = false;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\Conversation", inversedBy="messages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $conversation;

    /**
     * @ORM\Column(type="boolean", options={"default" : false})
     */
    private $messageGourou = false;

    public function __construct()
    {
        $this->date = new \DateTime('now');
    }
    
     /**
     * 
     * @return string
     */
    public function getSlug() : string {
        return ( new Slugify() )->slugify($this->texte); 
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
    public function getTexte(): ?string
    {
        return $this->texte;
    }

    /**
     * 
     * @param string $texte
     * @return \App\Entity\General\Message
     */
    public function setTexte(string $texte): Message
    {
        $this->texte = $texte;

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
     * @return string|null
     */
    public function getDateString(): ?string
    {
        setlocale(LC_TIME, 'fr_FR.UTF8', 'fr.UTF8', 'fr_FR.UTF-8', 'fr.UTF-8');
        return strftime("le %e %B %G à %Hh%M", $this->date->getTimestamp() );
    }

    /**
     * 
     * @param \DateTimeInterface|null $date
     * @return \App\Entity\General\Message
     */
    public function setDate(?\DateTimeInterface $date): Message
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
     * @return \App\Entity\General\Message
     */
    public function setVu(bool $vu): Message
    {
        $this->vu = $vu;

        return $this;
    }

    /**
     * 
     * @return bool|null
     */
    public function getVuGourou(): ?bool
    {
        return $this->vuGourou;
    }

    /**
     * 
     * @param bool $vuGourou
     * @return \App\Entity\General\Message
     */
    public function setVuGourou(bool $vuGourou): Message
    {
        $this->vuGourou = $vuGourou;

        return $this;
    }

    /**
     * 
     * @return \App\Entity\General\Conversation|null
     */
    public function getConversation(): ?Conversation
    {
        return $this->conversation;
    }

    /**
     * 
     * @param \App\Entity\General\Conversation|null $conversation
     * @return \App\Entity\General\Message
     */
    public function setConversation(?Conversation $conversation): Message
    {
        $this->conversation = $conversation;

        return $this;
    }

    public function getMessageGourou(): ?bool
    {
        return $this->messageGourou;
    }

    public function setMessageGourou(bool $messageGourou): self
    {
        $this->messageGourou = $messageGourou;

        return $this;
    }
}
