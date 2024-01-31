<?php

namespace App\Data;

use App\Entity\Category;

class SearchData
{
    /**
     * @ string
     */
    //par défaut : chaine vide
    public $q = '';

    /**
     * @var Category[]
     */
    public $categories = [];

    /**
     * @var \DateTime|null
     */
    public $dateDebut;

    /**
     * @var \DateTime|null
     */
    public $dateFin;

    /**
     * @var boolean
     */
    //par défaut choix à false
    public $choix1 = false;

    /**
     * @var boolean
     */
    public $choix2 = false;

    /**
     * @var boolean
     */
    public $choix3 = false;

}