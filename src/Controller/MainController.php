<?php
/**
 * Created by PhpStorm.
 * User: smartnet
 * Date: 07.03.18
 * Time: 21:10
 */
declare(strict_types=1);


namespace App\Controller;


use Doctrine\DBAL\Driver\Connection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MainController extends Controller
{
    /**
     * @Route("/", name="sm_homepage")
     */
    public function index(Request $request, Connection $conn) {
        $session = $request->getSession();
        if (!$session->has('start')) {
            $session->set('start', true);
        }
        if (!$session->has('userstate')) {      //  Если состояние юзера не установлено (начальный вход)
            $session->set('userstate', 'a');          //  то юзер - аноним, в противном случае ничего не меняем
        }
        if (!$session->has('userrole')) {       // Если роль юзера не установлене (начальный вход)
            $session->set('userrole', 'unknown');     // то роль юзера - неизвестен
        }
        if (!$session->has('userID')) {         // Если ID юзера не установлен (начальный вход)
            $session->set('userID', 0);               // то ID юзера - 0
        }

        if (!$session->has('roleEmpl')) {       //  Если роль "Сотрудник" не установлена (начальный вход)
            $session->set('roleEmpl', 0);            //     то roleEmpl - 0 (нет)
        }

        if (!$session->has('roleManager')) {    //  Если роль "Руководитель" не установлена (начальный вход)
            $session->set('roleManager', 0);         //     то roleManager - 0 (нет)
        }

        if (!$session->has('roleAnalit')) {    //  Если роль "Аналитик" не установлена (начальный вход)
            $session->set('roleAnalit', 0);         //     то roleAnalit - 0 (нет)
        }

        if (!$session->has('roleAdmin')) {    //  Если роль "СисАдмин" не установлена (начальный вход)
            $session->set('roleAdmin', 0);         //     то roleAdmin - 0 (нет)
        }

        if ($session->get('userID') > 0) {
            $user = $conn->fetchAssoc('SELECT * FROM empl WHERE id = ?', [$session->get('userID')]);
            $session->set('roleEmpl', $user['role_empl']);
            $session->set('roleManager', $user['role_manager']);
            $session->set('roleAnalit', $user['role_analit']);
            $session->set('roleAdmin', $user['role_admin']);
        }
//        if (!$_SESSION['tMenu']) {
//            $_SESSION['tMenu'] = 'Start';
//        }

        $_SESSION['tMenu'] = 'Start';
        $_SESSION['lMenu'] = '';
        if ($session->get('userID') > 0) {
            return $this->render('smetric/index.html.twig', [ // Выводим шаблон default/index
                'Title'         =>  'SMetric: Начало',
                'user'          =>  $user,
                'UserState'     =>  $session->get('userstate'),
                'UserRole'      =>  $session->get('userrole'),
                'UserID'        =>  $session->get('userID'),
                'roleEmpl'      =>  $session->get('roleEmpl'),
                'roleManager'   =>  $session->get('roleManager'),
                'roleAnalit'    =>  $session->get('roleAnalit'),
                'roleAdmin'     =>  $session->get('roleAdmin')
            ]);
        } else {
            return $this->render('smetric/index.html.twig', [ // Выводим шаблон default/index
                'Title'         => 'SMetric: Начало',
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

    /**
     * @Route("/login", name="sm_login")
     */
    public function login(Request $request, Connection $conn) {

    }

    /**
     * @Route("/logout", name="sm_logout")
     */
    public function logout(Request $request) {

    }

    /**
     * @Route("/reg", name="sm_reg")
     */

    public function reg(Request $request) {

    }

    /**
     * @Route("/profile", name="sm_profile")
     */
    public function profile(Request $request, Connection $conn) {

        $_SESSION['lMenu'] = 'EmpProfile';
        $session = $request->getSession();
        //  Если (обработка запроса из формы профиля)
        if ($request->request->get('formName') == 'EmpProfile') {
            $user = $conn->fetchAssoc('SELECT * FROM empl WHERE id = ?', [$session->get('userID')]);
            if ($request->request->get('btnSubmit') == 'SaveProfile') { // Запрос на сохранение изменённых данных профиля
                //  Обновляем запись сотрудника в базе
                //      Проверяем статус договора сотрудник - в штате/совместитель
                if ($request->request->get('JobStatus') == 'Shtat') { // Если (в штате)
                    $jobstatus = 1;
                } elseif ($request->request->get('JobStatus') == 'Sovmestitel') {   // Если (совместитель)
                    $jobstatus = 0;
                }
                $empNewStatus   =   $request->request->get('empSciStatus');
                $empNewPosition =   $request->request->get('empSciPosition');
                $conn->update('empl',[
                    'empfamily'     =>  $request->request->get('empFamily'),
                    'empname'       =>  $request->request->get('empName'),
                    'empsoname'     =>  $request->request->get('empSoname'),
                    'empmainemail'  =>  $request->request->get('empEMail'),
                    'empsciposition'    =>  $empNewPosition,
                    'empscistatus'      =>  $empNewStatus,
                    'empwtel'       =>  $request->request->get('empWTel'),
                    'empmtel'       =>  $request->request->get('empMTel'),
                    'empmessenger'  =>  $request->request->get('empMessenger'),
                    'empjobstatus'  =>  $jobstatus,
                    'empbday'       =>  $request->request->get('empBDay'),
                ],[
                    'id'    =>   $session->get('userID')
                ]);
                return $this->render('@App/default/empl/empl.html.twig', [ // Возвращаемся на главную сотрудника
                    'Title'     => 'SMetric: Профиль...',
                    'user'      => $user,
                    'UserState'     =>  $session->get('userstate'),
                    'UserRole'      =>  $session->get('userrole'),
                    'UserID'        =>  $session->get('userID'),
                    'roleEmpl'      =>  $session->get('roleEmpl'),
                    'roleManager'   =>  $session->get('roleManager'),
                    'roleAnalit'    =>  $session->get('roleAnalit'),
                    'roleAdmin'     =>  $session->get('roleAdmin')
                ]);
            } elseif ($request->request->get('btnSubmit') == 'DontSaveProfile') {   // Запрос на выход из профиля без сохранения
                //          возвращаемся на стартовую страницу сотрудника
                return $this->render('@App/default/empl/empl.html.twig', [   // Выводим шаблон default/empl/empl
                    'Title'     => 'SMetric: Сотрудник...',
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
        } else {
            if ($session->get('userID') != 0) { // Пользователь имеет действующий ID (зарегистрированный)
                //          По ID из сеанса ищем в базе запись сотрудника
                //          Готовим переменную с информацией о сотруднике
                $user = $conn->fetchAssoc('SELECT * FROM empl WHERE id = ?', [$session->get('userID')]);
                $statuslist     = $conn->fetchAll('SELECT * FROM scistatus ORDER BY id DESC');
                $positionlist   = $conn->fetchAll('SELECT * FROM sciposition ORDER BY id DESC');
                //          Выводим форму профиля, заполненную информацией о сотруднике
                return $this->render('@App/default/profile.html.twig', [ // Выводим шаблон default/profile
                    'Title'         =>  'SMetric: Профиль...',
                    'user'          =>  $user,
                    'StatusList'    =>  $statuslist,
                    'PositionList'  =>  $positionlist,
                    'UserState'     =>  $session->get('userstate'),
                    'UserRole'      =>  $session->get('userrole'),
                    'UserID'        =>  $session->get('userID'),
                    'roleEmpl'      =>  $session->get('roleEmpl'),
                    'roleManager'   =>  $session->get('roleManager'),
                    'roleAnalit'    =>  $session->get('roleAnalit'),
                    'roleAdmin'     =>  $session->get('roleAdmin')
                ]);
            } elseif ($session->get('userID') == 0) {   // ID пользователя равен 0 (аноним)
                //          Переходим на главную страницы программы (вход/регистрация)
                return $this->render('smetric/index.html.twig', [ // Выводим шаблон default/index
                    'Title'     => 'SMetric: Начало',
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
    }

}