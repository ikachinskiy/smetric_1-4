<?php
/**
 * Created by PhpStorm.
 * User: smartnet
 * Date: 04.03.18
 * Time: 3:40
 */
declare(strict_types=1);

namespace App\Controller;

use Doctrine\DBAL\Driver\Connection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\SMetric\Empl;

class AdminController extends Controller
{
    /**
     * @Route("/admin", name="sm_admin")
     */
    public function admin(Request $request, Connection $conn) {
        $_SESSION['tMenu'] = 'Admin';
        $session = $request->getSession();
        $user = [];
        if ($session->get('userID') == 0) {
            $user['empfamily']  =   "Аноним";
            $user['empname']    =   "";
            $user['empsoname']  =   "";
        } else {
            $user = $conn->fetchAssoc('SELECT * FROM empl WHERE id = ?', [$session->get('userID')]);
        }
        $emp = new Empl();
        return $this->render('smetric/admin/admin.html.twig', [   // Выводим шаблон default/admin/admin
            'Title'         => 'SMetric: Админ...',
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
     * @Route("/admin/orgstruct", name="sm_admin_orgstruct")
     */
    public function adminOrgStruct(Request $request, Connection $conn) {
        $session = $request->getSession();
        $user = [];
        if ($session->get('userID') == 0) {
            $user['empfamily']  =   "Аноним";
            $user['empname']    =   "";
            $user['empsoname']  =   "";
        } else {
            $user = $conn->fetchAssoc('SELECT * FROM empl WHERE id = ?', [$session->get('userID')]);
        }
        return $this->render('smetric/admin/admin-orgstruct.html.twig', [
            'Title'         => 'SMetric: Админ:ОргСтруктура',
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
     * @Route("/admin/emplrs", name="sm_admin_emplrs")
     */
    public function adminEmplrs(Request $request, Connection $conn) {
        $session = $request->getSession();
        $user = [];
        if ($session->get('userID') == 0) {
            $user['empfamily']  =   "Аноним";
            $user['empname']    =   "";
            $user['empsoname']  =   "";
        } else {
            $user = $conn->fetchAssoc('SELECT * FROM empl WHERE id = ?', [$session->get('userID')]);
        }

        // Выборка текущего списка сотрудников
        $empls = $conn->fetchAll('SELECT * FROM empl ORDER BY empl.empfamily');

        $session->set('userrole', 'admin');
        return $this->render('smetric/admin/admin-emplrs.html.twig', [
            'Title'         => 'SMetric: Админ:Сотрудники',
            'user'      => $user,
            'empls'     => $empls,
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
     * @Route("/admin/emplrs/new", name="sm_admin_emplrs_new")
     */
    public function adminEmplrsNew(Request $request, Connection $conn) {
        $session    =   $request->getSession();
        $errMess    =   "";
        $user   =   [];
        $emp    =   [];
        $emp['family']  =   '';
        $emp['name']    =   '';
        $emp['soname']  =   '';
        $emp['email']   =   '';
        $emp['pass1']   =   '';
        $emp['pass2']   =   '';
        if ($session->get('userID') == 0) {
            $user['empfamily']  =   "Аноним";
            $user['empname']    =   "";
            $user['empsoname']  =   "";
        } else {
            $user = $conn->fetchAssoc('SELECT * FROM empl WHERE id = ?', [$session->get('userID')]);
            $scistatus = $conn->fetchAll('SELECT * FROM scistatus ORDER BY id DESC');
            $sciposition = $conn->fetchAll('SELECT * FROM sciposition ORDER BY id DESC');
        }
        //  Если запрошена запись в базу нового сотрудника
        if (($request->request->get('formName') == 'AdminEmpNew') &&
            ($request->request->get('btnSubmit') == 'SaveNewEmp')) {
            $emp['family']      =   $request->request->get('empFamily');
            $emp['name']        =   $request->request->get('empName');
            $emp['soname']      =   $request->request->get('empSoname');
            $emp['email']       =   $request->request->get('empEMail');
            $emp['pass1']       =   $request->request->get('empPass1');
            $emp['pass2']       =   $request->request->get('empPass2');
            $emp['sciposition'] =   $request->request->get('empSciPosition');
            $emp['scistatus']   =   $request->request->get('empSciStatus');
            $emp['wtel']        =   $request->request->get('empWTel');
            $emp['mtel']        =   $request->request->get('empMTel');
            $emp['messenger']   =   $request->request->get('empMessenger');
            if ($request->request->get('emplJobStatus') == 'Staff')
                $emp['jobstatus'] = 1;
            else
                $emp['jobstatus'] = 0;
            if ($request->request->get('empBDay') == null) { // Если Сотруднику не был задан "день рождения"
                $emp['empBDay'] = '1111-11-11';                  // По умолчанию ставится - 11-11-1111
            } else {
                $emp['empBDay'] = $request->request->get('empBDay'); // Иначе считывается из ввода
            }

            if ($request->request->get('grEmpl') == 'on') {     // Установка параметра группы "Сотрудник"
                $roleEmpl = 1;
            } else {
                $roleEmpl = 0;
            }

            if ($request->request->get('grManager') == 'on') {     // Установка параметра группы "Сотрудник"
                $roleManager = 1;
            } else {
                $roleManager = 0;
            }

            if ($request->request->get('grAnalit') == 'on') {     // Установка параметра группы "Сотрудник"
                $roleAnalit = 1;
            } else {
                $roleAnalit = 0;
            }

            if ($request->request->get('grAdmin') == 'on') {     // Установка параметра группы "Сотрудник"
                $roleAdmin = 1;
            } else {
                $roleAdmin = 0;
            }


            $errState   =   0;  //TODO реализовать проверку корректности данных сотрудника в Админ/НовыйСотр
            //      Проверка введённых данных корректность
            //      1 Фамилия - не пусто
            //      2 Имя - не пусто
            //      3 Отчество - не пусто
            //      4 Почта - не пусто
            //      5 Пароль - не пусто и равны
            if ($errState == 0) {
                //      Если все данные корректны
                //          Запись в базу нового сотрудника
                //          Генерация хэша пароля
                $emp['password'] = password_hash($emp['pass1'], PASSWORD_DEFAULT);
                $conn->insert('empl', [
                    'empfamily'         =>  $emp['family'],
                    'empname'           =>  $emp['name'],
                    'empsoname'         =>  $emp['soname'],
                    'empmainemail'      =>  $emp['email'],
                    'emppassword'       =>  $emp['password'],
                    'empbday'           =>  $emp['empBDay'],
                    'empsciposition'    =>  $emp['sciposition'],
                    'empscistatus'      =>  $emp['scistatus'],
                    'empwtel'           =>  $emp['wtel'],
                    'empmtel'           =>  $emp['mtel'],
                    'empmessenger'      =>  $emp['messenger'],
                    'empjobstatus'      =>  $emp['jobstatus'],
                    'role_empl'         =>  $roleEmpl,
                    'role_manager'      =>  $roleManager,
                    'role_analit'       =>  $roleAnalit,
                    'role_admin'        =>  $roleAdmin
                ]);
                //          Выборка текущего списка сотрудников
                $empls = $conn->fetchAll('SELECT * FROM empl ORDER BY empl.empfamily');
                //          Возвращение к отображению списка сотрудников
                return $this->render('smetric/admin/admin-emplrs.html.twig', [
                    'Title'         => 'SMetric: Админ:Сотрудники-Новый',
                    'user'      =>  $user,
                    'errMess'   =>  $errMess,
                    'empls'     =>  $empls,
                    'UserState'     =>  $session->get('userstate'),
                    'UserRole'      =>  $session->get('userrole'),
                    'UserID'        =>  $session->get('userID'),
                    'roleEmpl'      =>  $session->get('roleEmpl'),
                    'roleManager'   =>  $session->get('roleManager'),
                    'roleAnalit'    =>  $session->get('roleAnalit'),
                    'roleAdmin'     =>  $session->get('roleAdmin')
                ]);
            } else {
                $errMess = 'Ошибка в ведённых данных!';
                //          Отображение формы регистрации с диагностикой
                return $this->render('smetric/admin/admin-emplrs-new.html.twig', [
                    'Title'         => 'SMetric: Админ:Сотрудники-Новый',
                    'user'      =>  $user,
                    'errMess'   =>  $errMess,
                    'emp'       =>  $emp,
                    'UserState'     =>  $session->get('userstate'),
                    'UserRole'      =>  $session->get('userrole'),
                    'UserID'        =>  $session->get('userID'),
                    'roleEmpl'      =>  $session->get('roleEmpl'),
                    'roleManager'   =>  $session->get('roleManager'),
                    'roleAnalit'    =>  $session->get('roleAnalit'),
                    'roleAdmin'     =>  $session->get('roleAdmin')
                ]);
            }
        } elseif (($request->request->get('formName') == 'AdminEmpNew') &&
            ($request->request->get('btnSubmit') == 'DontSaveNewEmp')) {
            // Возврат к отображению списка сотрудников
            // Выборка текущего списка сотрудников
            $empls = $conn->fetchAll('SELECT * FROM empl ORDER BY empl.empfamily');
            return $this->render('smetric/admin/admin-emplrs.html.twig', [
                'Title'         => 'SMetric: Админ:Сотрудники-Новый',
                'user'      =>  $user,
                'errMess'   =>  $errMess,
                'empls'     =>  $empls,
                'UserState'     =>  $session->get('userstate'),
                'UserRole'      =>  $session->get('userrole'),
                'UserID'        =>  $session->get('userID'),
                'roleEmpl'      =>  $session->get('roleEmpl'),
                'roleManager'   =>  $session->get('roleManager'),
                'roleAnalit'    =>  $session->get('roleAnalit'),
                'roleAdmin'     =>  $session->get('roleAdmin')
            ]);
        }

        //  Первоначальный вывод формы регистрации нового сотрудника
        return $this->render('smetric/admin/admin-emplrs-new.html.twig', [
            'Title'         => 'SMetric: Админ:Сотрудники-Новый',
            'user'          =>  $user,
            'errMess'       =>  $errMess,
            'emp'           =>  $emp,
            'scistatus'     =>  $scistatus,
            'sciposition'   =>  $sciposition,
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
     * @Route("/admin/emplrs/view/{eID}", name="sm_admin_emplrs_view")
     */
    public function adminEmplrsView(Request $request, $eID, Connection $conn) {
        $session = $request->getSession();
        $user = [];
        if ($session->get('userID') == 0) {
            $user['empfamily']  =   "Аноним";
            $user['empname']    =   "";
            $user['empsoname']  =   "";
        } else {
            $user = $conn->fetchAssoc('SELECT * FROM empl WHERE id = ?', [$session->get('userID')]);
        }

        $sql = "SELECT * FROM empl WHERE id = '".$eID."'";
        $emp = $conn->fetchAssoc($sql);
        $scistatus = $conn->fetchColumn("SELECT scistatus FROM scistatus WHERE id = ".$emp['empscistatus']);
        $sciposition = $conn->fetchColumn("SELECT sciposition FROM sciposition WHERE id = ".$emp['empsciposition']);

        return $this->render('smetric/admin/admin-emplrs-view.html.twig', [
            'Title'         =>  'SMetric: Админ:Сотрудники-Просмотр',
            'user'          =>  $user,
            'emp'           =>  $emp,
            'scistatus'     =>  $scistatus,
            'sciposition'   =>  $sciposition,
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
     * @Route("/admin/emplrs/edit/{eID}", name="sm_admin_emplrs_edit")
     */
    public function adminEmplrsEdit(Request $request, Connection $conn, $eID) {
        $session = $request->getSession();
        $user = [];
        if ($session->get('userID') == 0) {
            $user['empfamily']  =   "Аноним";
            $user['empname']    =   "";
            $user['empsoname']  =   "";
        } else {
            $user = $conn->fetchAssoc('SELECT * FROM empl WHERE id = ?', [$session->get('userID')]);
            // выборка сотр. (по eID) на редактирование
            $sql = "SELECT * FROM empl WHERE id = '".$eID."'";
            $emp = $conn->fetchAssoc($sql);
            $statuslist     = $conn->fetchAll('SELECT * FROM scistatus ORDER BY id DESC');
            $positionlist   = $conn->fetchAll('SELECT * FROM sciposition ORDER BY id DESC');
            $empstatus      = $conn->fetchColumn("SELECT scistatus FROM scistatus WHERE id = ".$emp['empscistatus']);;
            $empposition    = $conn->fetchColumn("SELECT sciposition FROM sciposition WHERE id = ".$emp['empsciposition']);
        }

        $errMess = ""; //TODO Форма Админ/Сотрудн/Редакт - сделать обработку сообщений об ошибках в
        //TODO Форма Админ/Сотрудн/Редактир - если "день рождения" оставить пустой, вываливается с ошибкой


        $frmName = $request->request->get('frmName');
        $btnSubmit = $request->request->get('btnSubmit');
        if (($frmName == 'AdminEmpEdit') && ($btnSubmit == "SaveEmp")) {
            // обновляем запись о пользователе
            // Выбираем все поля из ответа (они или уже заполнены из базы, или введены админом
            // Проверяем каждое поле на соответствие требованиям (если надо)
            $empFamily  = $request->request->get('empFamily');
            $empName    = $request->request->get('empName');
            $empSoname  = $request->request->get('empSoname');
            $empEMail   = $request->request->get('empEMail');
            $empPass    = $emp['emppassword'];
            if (
                (
                    (($request->request->get('empPass1')) != null) && (($request->request->get('empPass2')) != null)
                ) &&
                (
                    ($request->request->get('empPass1')) == ($request->request->get('empPass2'))
                )
            )
            {
                $empPass = password_hash($request->request->get('empPass1'), PASSWORD_DEFAULT);
            }
            if ((($request->request->get('empPass1')) == null) && (($request->request->get('empPass2')) == null)) {
                $empPass = $emp['emppassword'];
            }
            $empNewStatus   =   $request->request->get('empSciStatus');
            $empNewPosition =   $request->request->get('empSciPosition');
            $empWTel        = $request->request->get('empWTel');
            $empMTel        = $request->request->get('empMTel');
            $empMessenger   = $request->request->get('empMessenger');
            $empBDay        = $request->request->get('empBDay');
            // Обновляем запись в базе
            // Проверка статуса договора сотрудника (в штате/совместитель)
            if ($request->request->get('JobStatus') == 'Shtat') {
                $jobstatus = 1;
            } elseif ($request->request->get('JobStatus') == 'Sovmestitel') {
                $jobstatus = 0;
            }

            if ($request->request->get('grEmpl') == 'on') {     // Установка параметра группы "Сотрудник"
                $roleEmpl = 1;
            } else {
                $roleEmpl = 0;
            }

            if ($request->request->get('grManager') == 'on') {     // Установка параметра группы "Сотрудник"
                $roleManager = 1;
            } else {
                $roleManager = 0;
            }

            if ($request->request->get('grAnalit') == 'on') {     // Установка параметра группы "Сотрудник"
                $roleAnalit = 1;
            } else {
                $roleAnalit = 0;
            }

            if ($request->request->get('grAdmin') == 'on') {     // Установка параметра группы "Сотрудник"
                $roleAdmin = 1;
            } else {
                $roleAdmin = 0;
            }

            $conn->update('empl',[
                'empfamily'         =>  $empFamily,
                'empname'           =>  $empName,
                'empsoname'         =>  $empSoname,
                'empmainemail'      =>  $empEMail,
                'empsciposition'    =>  $empNewPosition,
                'empscistatus'      =>  $empNewStatus,
                'empwtel'           =>  $empWTel,
                'empmtel'           =>  $empMTel,
                'empmessenger'      =>  $empMessenger,
                'empbday'           =>  $empBDay,
                'emppassword'       =>  $empPass,
                'empjobstatus'      =>  $jobstatus,
                'role_empl'         =>  $roleEmpl,
                'role_manager'      =>  $roleManager,
                'role_analit'       =>  $roleAnalit,
                'role_admin'        =>  $roleAdmin
            ],[
                'id'    =>   $eID
            ]);
            // Выборка текущего списка сотрудников
            $empls = $conn->fetchAll('SELECT * FROM empl ORDER BY empl.empfamily');
            // Возвращаемся к списку сотрудников
            return $this->render('smetric/admin/admin-emplrs.html.twig', [
                'Title'         => 'SMetric: Админ:Сотрудники',
                'user'      => $user,
                'empls'     => $empls,
                'UserState'     =>  $session->get('userstate'),
                'UserRole'      =>  $session->get('userrole'),
                'UserID'        =>  $session->get('userID'),
                'roleEmpl'      =>  $session->get('roleEmpl'),
                'roleManager'   =>  $session->get('roleManager'),
                'roleAnalit'    =>  $session->get('roleAnalit'),
                'roleAdmin'     =>  $session->get('roleAdmin')
            ]);
        } elseif (($frmName == 'AdminEmpEdit') && ($btnSubmit == "DontSaveEmp")) {
            // Выборка текущего списка сотрудников
            $empls = $conn->fetchAll('SELECT * FROM empl ORDER BY empl.empfamily');
            return $this->render('smetric/admin/admin-emplrs.html.twig', [
                'Title'         => 'SMetric: Админ:Сотрудники',
                'user'      => $user,
                'empls'     => $empls,
                'UserState'     =>  $session->get('userstate'),
                'UserRole'      =>  $session->get('userrole'),
                'UserID'        =>  $session->get('userID'),
                'roleEmpl'      =>  $session->get('roleEmpl'),
                'roleManager'   =>  $session->get('roleManager'),
                'roleAnalit'    =>  $session->get('roleAnalit'),
                'roleAdmin'     =>  $session->get('roleAdmin')
            ]);
        };
        //  Первичный вывод формы редактирования Сотрудника
        return $this->render('smetric/admin/admin-emplrs-edit.html.twig', [
            'Title'         =>  'SMetric: Админ:Сотрудники-Редактирование',
            'user'          =>  $user,
            'emp'           =>  $emp,
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
    }

    /**
     * @Route("/admin/emplrs/delete/{eID}", name="sm_admin_emplrs_delete")
     */
    //TODO при попытке удалить себя дать юзеру возможность вернуться в список сотрудников
    public function adminEmplrsDelete(Request $request, $eID, Connection $conn) {
        $session    =   $request->getSession();
        $errMess    =   "";
        $user = [];
        if ($session->get('userID') == 0) {
            $user['empfamily']  =   "Аноним";
            $user['empname']    =   "";
            $user['empsoname']  =   "";
        } else {
            $user = $conn->fetchAssoc('SELECT * FROM empl WHERE id = ?', [$session->get('userID')]);
        }

        // выборка сотр. (по eID) на удаление
        $sql = "SELECT * FROM empl WHERE id = '".$eID."'";
        $emp = $conn->fetchAssoc($sql);
        $scistatus = $conn->fetchColumn("SELECT scistatus FROM scistatus WHERE id = ".$emp['empscistatus']);
        $sciposition = $conn->fetchColumn("SELECT sciposition FROM sciposition WHERE id = ".$emp['empsciposition']);

        if ($emp['id'] == $user['id']) {
            $errMess = "Нельзя удалять самого себя!";
            return $this->render('smetric/admin/admin-emplrs-delete.html.twig', [
                'Title'     => 'SMetric: Админ:Сотрудники-Удаление',
                'user'      =>  $user,
                'emp'       =>  $emp,
                'errMess'   =>  $errMess,
                'scistatus'     =>  $scistatus,
                'sciposition'   =>  $sciposition,
                'UserState'     =>  $session->get('userstate'),
                'UserRole'      =>  $session->get('userrole'),
                'UserID'        =>  $session->get('userID'),
                'roleEmpl'      =>  $session->get('roleEmpl'),
                'roleManager'   =>  $session->get('roleManager'),
                'roleAnalit'    =>  $session->get('roleAnalit'),
                'roleAdmin'     =>  $session->get('roleAdmin')
            ]);
        } else {
            if (($request->request->get('formName') == 'AdminEmplDelete') &&
                ($request->request->get('btnSubmit') == 'DeleteEmp')) {
                // удаляем запись о пользователе
                $conn->delete('empl', [
                    'id'    =>  $eID
                ]);
                // Обновляем список сотрудников для вывода
                $empls = $conn->fetchAll('SELECT * FROM empl ORDER BY empl.empfamily');

                return $this->render('smetric/admin/admin-emplrs.html.twig', [
                    'Title'     => 'SMetric: Админ:Сотрудники-Удаление',
                    'errMess'   =>  $errMess,
                    'user'      =>  $user,
                    'emp'       =>  $emp,
                    'empls'     =>  $empls,
                    'UserState'     =>  $session->get('userstate'),
                    'UserRole'      =>  $session->get('userrole'),
                    'UserID'        =>  $session->get('userID'),
                    'roleEmpl'      =>  $session->get('roleEmpl'),
                    'roleManager'   =>  $session->get('roleManager'),
                    'roleAnalit'    =>  $session->get('roleAnalit'),
                    'roleAdmin'     =>  $session->get('roleAdmin')
                ]);
            } elseif (($request->request->get('formName') == 'AdminEmplDelete') &&
                ($request->request->get('btnSubmit') == "DontDeleteEmp")) {

                // Обновляем список сотрудников для вывода
                $empls = $conn->fetchAll('SELECT * FROM empl ORDER BY empl.empfamily');
                return $this->render('smetric/admin/admin-emplrs.html.twig', [
                    'Title'     => 'SMetric: Админ:Сотрудники-Удаление',
                    'user'      =>  $user,
                    'emp'       =>  $emp,
                    'empls'     =>  $empls,
                    'errMess'   =>  $errMess,
                    'UserState'     =>  $session->get('userstate'),
                    'UserRole'      =>  $session->get('userrole'),
                    'UserID'        =>  $session->get('userID'),
                    'roleEmpl'      =>  $session->get('roleEmpl'),
                    'roleManager'   =>  $session->get('roleManager'),
                    'roleAnalit'    =>  $session->get('roleAnalit'),
                    'roleAdmin'     =>  $session->get('roleAdmin')
                ]);
            };
        }
        return $this->render('smetric/admin/admin-emplrs-delete.html.twig', [
            'Title'     => 'SMetric: Админ:Сотрудники-Удаление',
            'user'      =>  $user,
            'emp'       =>  $emp,
            'errMess'   =>  $errMess,
            'scistatus'     =>  $scistatus,
            'sciposition'   =>  $sciposition,
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
     * @Route("/admin/users", name="sm_admin_users")
     */
    public function adminUsers(Request $request, Connection $conn) {
        $session = $request->getSession();
        $user = [];
        if ($session->get('userID') == 0) {
            $user['empfamily']  =   "Аноним";
            $user['empname']    =   "";
            $user['empsoname']  =   "";
        } else {
            $user = $conn->fetchAssoc('SELECT * FROM empl WHERE id = ?', [$session->get('userID')]);
        }
        return $this->render('smetric/admin/admin-users.html.twig', [
            'Title'         => 'SMetric: Админ:Пользователи',
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
     * @Route("/admin/documents", name="sm_admin_documents")
     */
    public function adminDocuments(Request $request, Connection $conn) {
        $session = $request->getSession();
        $user = [];
        if ($session->get('userID') == 0) {
            $user['empfamily']  =   "Аноним";
            $user['empname']    =   "";
            $user['empsoname']  =   "";
        } else {
            $user = $conn->fetchAssoc('SELECT * FROM empl WHERE id = ?', [$session->get('userID')]);
        }
        return $this->render('smetric/admin/admin-documents.html.twig', [
            'Title'         => 'SMetric: Админ:Документы',
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
     * @Route("/admin/reports", name="sm_admin_reports")
     */
    public function adminReports(Request $request, Connection $conn) {
        $session = $request->getSession();
        $user = [];
        if ($session->get('userID') == 0) {
            $user['empfamily']  =   "Аноним";
            $user['empname']    =   "";
            $user['empsoname']  =   "";
        } else {
            $user = $conn->fetchAssoc('SELECT * FROM empl WHERE id = ?', [$session->get('userID')]);
        }
        return $this->render('smetric/admin/admin-reports.html.twig', [
            'Title'         => 'SMetric: Админ:Отчёты',
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
     * @Route("/admin/backup", name="sm_admin_backup")
     */
    public function adminBackup(Request $request, Connection $conn) {
        $session = $request->getSession();
        $user = [];
        if ($session->get('userID') == 0) {
            $user['empfamily']  =   "Аноним";
            $user['empname']    =   "";
            $user['empsoname']  =   "";
        } else {
            $user = $conn->fetchAssoc('SELECT * FROM empl WHERE id = ?', [$session->get('userID')]);
        }
        return $this->render('smetric/admin/admin-backup.html.twig', [
            'Title'         => 'SMetric: Админ:Архивирование',
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
     * @Route("/admin/restore", name="sm_admin_restore")
     */
    public function adminRestore(Request $request, Connection $conn) {
        $session = $request->getSession();
        $user = [];
        if ($session->get('userID') == 0) {
            $user['empfamily']  =   "Аноним";
            $user['empname']    =   "";
            $user['empsoname']  =   "";
        } else {
            $user = $conn->fetchAssoc('SELECT * FROM empl WHERE id = ?', [$session->get('userID')]);
        }
        return $this->render('smetric/admin/admin-restore.html.twig', [
            'Title'         => 'SMetric: Админ:Восстановление',
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
     * @Route("/admin/parameters", name="sm_admin_parameters")
     */
    public function adminParameters(Request $request, Connection $conn) {
        $session = $request->getSession();
        $user = [];
        if ($session->get('userID') == 0) {
            $user['empfamily']  =   "Аноним";
            $user['empname']    =   "";
            $user['empsoname']  =   "";
        } else {
            $user = $conn->fetchAssoc('SELECT * FROM empl WHERE id = ?', [$session->get('userID')]);
        }
        return $this->render('smetric/admin/admin-parameters.html.twig', [
            'Title'     => 'SMetric: Админ:Восстановление',
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