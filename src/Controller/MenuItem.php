<?php
declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: smartnet
 * Date: 23.03.2017
 * Time: 19:57
 */

namespace App\Controller;

class MenuItem
{
    public $iText   =   '';
    public $iURL    =   '';
    public $iState  =   '';
    public $iStatus =   '';

    public function __construct($iText="", $iURL="", $iState="", $iStatus="")
    {
        $this->iText    =   $iText;
        $this->iURL     =   $iURL;
        $this->iState   =   $iState;
        $this->iStatus  =   $iStatus;
    }
}