<?php
/**
 * Created by PhpStorm.
 * User: smartnet
 * Date: 07.03.18
 * Time: 21:10
 */
declare(strict_types=1);


namespace App\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class MainController extends Controller
{
    /**
     * @Route("/", name="index")
     */
    public function index() {
        return $this->render('smetric/m2.html.twig');
    }
}