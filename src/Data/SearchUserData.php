<?php

namespace App\Data;

class SearchUserData
{
    /**
     * @ string
     */
    //par défaut : chaine vide
    public $q = '';

    /**
     * @var bool/null
     */
    public $active;

    /**
     * @var string
     */
    public  $role = "";

}