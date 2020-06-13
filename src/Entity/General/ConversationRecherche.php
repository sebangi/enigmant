<?php

namespace App\Entity\General;
use App\Entity\General\User;
use App\Entity\General\Theme;
use Symfony\Component\Validator\Constraints as Assert;

class ConversationRecherche {

    /**
     * @var User|null     
     */
    private $user;
    
    /**
     * @var Theme|null     
     */
    private $theme;
        
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
    
    public function getTheme() {
        return $this->theme;
    }

    public function setTheme(Theme $theme) {
        $this->theme = $theme;
        return $this;
    }


}