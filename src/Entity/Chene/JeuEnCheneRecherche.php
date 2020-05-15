<?php

namespace App\Entity\Chene;
use App\Entity\Chene\CollectionChene;
use App\Entity\Repository\CollectionCheneRepository;
use Symfony\Component\Validator\Constraints as Assert;

class JeuEnCheneRecherche {

    /**
     *
     * @var bool|null 
     */
    private $disponible;   
    
    /**
     * @var CollectionChene|null     
     */
    private $collection;
    
    /**
     *
     * @var int|null 
     * @assert\Range(min=0)
     */
    private $maxPrix;
    
    /**
     *
     * @var int|null 
     * @assert\Range(min=0)
     */
    private $minPrix;
    
    /**
     *
     * @var int|null 
     * @assert\Range(min=0)
     */
    private $maxEtape;
    
    /**
     *
     * @var int|null 
     * @assert\Range(min=0)
     */
    private $minEtape;
    
    /**
     *
     * @var int|null 
     * @assert\Range(min=0, max=10)
     */
    private $minDifficulteRaisonnement;    

    /**
     *
     * @var int|null 
     * @assert\Range(min=0, max=10)
     */
    private $maxDifficulteRaisonnement;    


    /**
     *
     * @var int|null 
     * @assert\Range(min=0, max=10)
     */
    private $minDifficulteObservation;    

    /**
     *
     * @var int|null 
     * @assert\Range(min=0, max=10)
     */
    private $maxDifficulteObservation;    

    
    function __construct()
    {
        
    }

    public function getMaxEtape() {
        return $this->maxEtape;
    }

    public function getMinEtape() {
        return $this->minEtape;
    }

    public function setMaxEtape($maxEtape) {
        $this->maxEtape = $maxEtape;
        return $this;
    }

    public function setMinEtape($minEtape) {
        $this->minEtape = $minEtape;
        return $this;
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
    
    /**
     * 
     * @return type
     */
    public function getDisponible() {
        return $this->disponible;
    }


    /**
     * 
     * @return type
     */
    public function getMinPrix() {
        return $this->minPrix;
    }

    /**
     * 
     * @return type
     */
    public function getMaxDifficulteRaisonnement() {
        return $this->maxDifficulteRaisonnement;
    }

    /**
     * 
     * @return type
     */
    public function getMinDifficulteObservation() {
        return $this->minDifficulteObservation;
    }

    /**
     * 
     * @return type
     */
    public function getMaxDifficulteObservation() {
        return $this->maxDifficulteObservation;
    }

    /**
     * 
     * @param type $disponible
     * @return \App\Entity\Chene\JeuEnCheneRecherche
     */
    public function setDisponible($disponible) : JeuEnCheneRecherche {
        $this->disponible = $disponible;
        return $this;
    }

    /**
     * 
     * @return CollectionChene|null
     */
    public function getCollection(): ?CollectionChene {
        return $this->collection;
    }

    /**
     * 
     * @param CollectionChene $collection
     * @return \App\Entity\Chene\JeuEnCheneRecherche
     */
    public function setCollection(CollectionChene $collection) : JeuEnCheneRecherche {
        $this->collection = $collection;
        return $this;
    }

    
    /**
     * 
     * @param type $minPrix
     * @return \App\Entity\Chene\JeuEnCheneRecherche
     */
    public function setMinPrix($minPrix) : JeuEnCheneRecherche {
        $this->minPrix = $minPrix;
        return $this;
    }

    /**
     * 
     * @param type $maxDifficulteRaisonnement
     * @return \App\Entity\Chene\JeuEnCheneRecherche
     */
    public function setMaxDifficulteRaisonnement($maxDifficulteRaisonnement) : JeuEnCheneRecherche {
        $this->maxDifficulteRaisonnement = $maxDifficulteRaisonnement;
        return $this;
    }

    /**
     * 
     * @param type $minDifficulteObservation
     * @return \App\Entity\Chene\JeuEnCheneRecherche
     */
    public function setMinDifficulteObservation($minDifficulteObservation) : JeuEnCheneRecherche {
        $this->minDifficulteObservation = $minDifficulteObservation;
        return $this;
    }

    /**
     * 
     * @param type $maxDifficulteObservation
     * @return \App\Entity\Chene\JeuEnCheneRecherche
     */
    public function setMaxDifficulteObservation($maxDifficulteObservation) : JeuEnCheneRecherche {
        $this->maxDifficulteObservation = $maxDifficulteObservation;
        return $this;
    }

    
    
}