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
     * @var \App\Entity\Category|null
     */
    public $categories;


    /**
     * @var \DateTime|null
     */
    public $dateDebut;

    /**
     * @var \DateTime|null
     */
    public $dateFin;

    /**
     * @var int
     */
    //par défaut choix à 1 (toutes)
    public $activite = 1;

}