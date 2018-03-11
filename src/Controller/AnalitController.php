<?php
/**
 * Created by PhpStorm.
 * User: smartnet
 * Date: 04.03.18
 * Time: 3:40
 */

namespace App\Controller;

use Doctrine\DBAL\Driver\Connection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AnalitController extends Controller
{
    /**
     * @Route("/analit", name="sm_analit")
     */
    public function analit() {

    }

    /**
     * @Route("/analit/emplrs", name="sm_analit_emplrs")
     */
    public function analitEmplrs() {

    }

    /**
     * @Route("/analit/orgstruct", name="sm_analit_orgstruct")
     */
    public function analitOrgStruct() {

    }

    /**
     * @Route("/analit/reports", name="sm_analit_reports")
     */
    public function analitReports() {

    }

    /**
     * @Route("/analit/parameters", name="sm_analit_parameters")
     */
    public function analitParameters() {

    }
}