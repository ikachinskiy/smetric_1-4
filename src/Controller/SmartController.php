<?php
/**
 * Created by PhpStorm.
 * User: smartnet
 * Date: 04.03.18
 * Time: 3:40
 */

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class SmartController extends Controller
{
    /**
     * @Route("/", name="index")
     */

    public function index() {
        return $this->render('smartpro/first.html.twig');
    }

    /**
     * @Route("/base", name="base")
     */

    public function base() {
        return $this->render('smartpro/second.html.twig');
    }
}