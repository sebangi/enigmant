<?php

namespace App\Entity\General;
use App\Entity\General\User;
use Symfony\Component\Validator\Constraints as Assert;

class ObtentionNiveauRecherche {

    /**
     * @var User|null     
     */
    private $user;
        
    function __construct()
    {
        
    }
    
    public function getUser() {
        return $this->user;
    }

    public function setUser(User $user) {
        $this->user = $user;
        return $this;
    }    
    
}