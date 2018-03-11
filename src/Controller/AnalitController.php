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
    public function analit(Request $request, Connection $conn) {
        $_SESSION['tMenu'] = 'Analit';
        $session = $request->getSession();
        $user = [];
        if ($session->get('userID') == 0) {
            $user['empfamily']  =   "Аноним";
            $user['empname']    =   "";
            $user['empsoname']  =   "";
        } else {
            $user = $conn->fetchAssoc('SELECT * FROM empl WHERE id = ?', [$session->get('userID')]);
        }
        return $this->render('smetric/analit/analit.html.twig', [   // Выводим шаблон default/analit/analit
            'Title'         => 'SMetric: Аналитик...',
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
     * @Route("/analit/emplrs", name="sm_analit_emplrs")
     */
    public function analitEmplrs(Request $request, Connection $conn) {
        $session = $request->getSession();
        $user = [];
        if ($session->get('userID') == 0) {
            $user['empfamily']  =   "Аноним";
            $user['empname']    =   "";
            $user['empsoname']  =   "";
        } else {
            $user = $conn->fetchAssoc('SELECT * FROM empl WHERE id = ?', [$session->get('userID')]);
        }
        return $this->render('smetric/analit/analit-emplrs.html.twig', [
            'Title'         => 'SMetric: Аналитик:Сотрудники',
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
     * @Route("/analit/orgstruct", name="sm_analit_orgstruct")
     */
    public function analitOrgStruct(Request $request, Connection $conn) {
        $session = $request->getSession();
        $user = [];
        if ($session->get('userID') == 0) {
            $user['empfamily']  =   "Аноним";
            $user['empname']    =   "";
            $user['empsoname']  =   "";
        } else {
            $user = $conn->fetchAssoc('SELECT * FROM empl WHERE id = ?', [$session->get('userID')]);
        }
        return $this->render('smetric/analit/analit-orgstruct.html.twig', [
            'Title'         => 'SMetric: Аналитик:Орг.Структура',
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
     * @Route("/analit/reports", name="sm_analit_reports")
     */
    public function analitReports(Request $request, Connection $conn) {
        $session = $request->getSession();
        $user = [];
        if ($session->get('userID') == 0) {
            $user['empfamily']  =   "Аноним";
            $user['empname']    =   "";
            $user['empsoname']  =   "";
        } else {
            $user = $conn->fetchAssoc('SELECT * FROM empl WHERE id = ?', [$session->get('userID')]);
        }
        return $this->render('smetric/analit/analit-reports.html.twig', [
            'Title'         => 'SMetric: Аналитик:Отчёты',
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
     * @Route("/analit/parameters", name="sm_analit_parameters")
     */
    public function analitParameters(Request $request, Connection $conn) {
        $session = $request->getSession();
        $user = [];
        if ($session->get('userID') == 0) {
            $user['empfamily']  =   "Аноним";
            $user['empname']    =   "";
            $user['empsoname']  =   "";
        } else {
            $user = $conn->fetchAssoc('SELECT * FROM empl WHERE id = ?', [$session->get('userID')]);
        }
        return $this->render('smetric/analit/analit-parameters.html.twig', [
            'Title'         => 'SMetric: Аналитик:Параметры',
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
}