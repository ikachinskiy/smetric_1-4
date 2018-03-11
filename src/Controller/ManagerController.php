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

class ManagerController extends Controller
{
    /**
     * @Route("/manager", name="sm_manager")
     */
    public function manager(Request $request, Connection $conn) {
        $_SESSION['tMenu'] = 'Manager';
        $session = $request->getSession();
        $user = [];
        if ($session->get('userID') == 0) {
            $user['empfamily']  =   "Аноним";
            $user['empname']    =   "";
            $user['empsoname']  =   "";
        } else {
            $user = $conn->fetchAssoc('SELECT * FROM empl WHERE id = ?', [$session->get('userID')]);
        }
        return $this->render('smetric/manager/manager.html.twig', [   // Выводим шаблон default/manager/manager
            'Title'         => 'SMetric: Руководитель...',
            'user'      => $user,
            'UserState'     =>  $session->get('userstate'),
            'UserRole'      =>  $session->get('userrole'),
            'UserID'        =>  $session->get('userID'),
            'roleEmpl'      =>  $session->get('roleEmpl'),
            'roleManager'   =>  $session->get('roleManager'),
            'roleAnalit'    =>  $session->get('roleAnalit'),
            'roleAdmin'     =>  $session->get('roleAdmin')
        ]);
    }

    /**
     * @Route("/manager/emplrs", name="sm_manager_emplrs")
     */
    public function managerEmplrs() {

    }
}