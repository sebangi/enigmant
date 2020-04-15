<?php

namespace App\Entity\Chene;

use Symfony\Component\Validator\Constraints as Assert;

class BabioleRecherche {

    /**
     *
     * @var int|null 
     * @assert\Range(min=0)
     */
    private $maxValeur;
    
       
    function __construct()
    {
        
    }

    /**
     * 
     * @return int|null
     */
    public function getMaxValeur() : ?int {
        return $this->maxValeur;
    }

    /**
     * 
     * @param type $maxValeur
     * @return \App\Entity\Chene\BabioleRecherche
     */
    public function setMaxValeur($maxValeur) : BabioleRecherche {
        $this->maxValeur = $maxValeur;
        return $this;
    }

}