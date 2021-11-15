<?php

namespace App\Data;

use App\Entity\Site;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\VarDumper\Cloner\Data;

class SearchData
{
    public $q = '';

    public $site = [];

    public $debut;

    public $fin;

    public $isOrga = false;

    public $isInscrit = false;

    public $isNotInscrit = false;

    public $isFinished = false;
}