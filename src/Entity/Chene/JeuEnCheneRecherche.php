<?php

namespace App\Entity\Chene;
use Symfony\Component\Validator\Constraints as Assert;

class JeuEnCheneRecherche {

    /**
     *
     * @var int|null 
     * @assert\Range(min=0)
     */
    private $maxPrix;
    
    /**
     *
     * @var int|null 
     * @assert\Range(min=0, max=10)
     */
    private $minDifficulteRaisonnement;    
    
    function __construct()
    {
        
    }

    /**
     * @return int|null
     */
    public function getMaxPrix() : ?int
    {
        return $this->maxPrix;
    }

    /**
     * @param int|null maxPrix
     * @return JeuEnCheneRecherche
     */
    public function setMaxPrix($maxPrix) : JeuEnCheneRecherche 
    {
        $this->maxPrix = $maxPrix;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getMinDifficulteRaisonnement(): ?int 
    {
        return $this->minDifficulteRaisonnement;
    }

    /**
     *
     * @param int|null minDifficulteRaisonnement
     * @return JeuEnCheneRecherche
     */
    public function setMinDifficulteRaisonnement($minDifficulteRaisonnement) : JeuEnCheneRecherche
    {
        $this->minDifficulteRaisonnement = $minDifficulteRaisonnement;
        return $this;
    }
    
}