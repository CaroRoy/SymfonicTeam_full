<?php

namespace App\Data;

use DateTimeInterface;
use App\Entity\Category;

class SearchData {
    /**
     * @var int
     */
    public $page = 1;

    /**
     * @var string
     */
    public $q = '';

    /**
     * @var string
     */
    public $instrument = '';

    /**
     * @var string
     */
    public $typeOfMusic = '';

    /**
     * @var string
     */
    public $postalCode = '';

    /**
     * @var \DateTimeInterface
     */
    public $dateMin = null;

        /**
     * @var \DateTimeInterface
     */
    public $dateMax = null;

}