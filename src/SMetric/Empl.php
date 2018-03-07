<?php
/**
 * Created by PhpStorm.
 * User: smartnet
 * Date: 14.11.17
 * Time: 13:01
 */

namespace App\SMetric;
use Doctrine\DBAL\Driver\Connection;


/*
 * Класс Empl - сотрдники ФИЦ ИУ РАН, работающие в системе SMetric
 */
class Empl
{
    public $empID;             //  внутренний машиночитаемый ID сотрудника
    public $empPersonalID;     //  кадровый номер сотрудника
    public $empFamily;         //  фамилия
    public $empName;           //  имя
    public $empSoname;         //  отчество
    public $empBDay;           //  дата рождения
    public $empSex;            //  пол: true - муж., false - жен.
    public $empMainEMail;      //  главный адрес эл.почты, он же логин для входа
    public $empWTel;           //  рабочий телефон
    public $empMTel;           //  мобильный телефон
    public $empMessenger;      //  адрес в мессенжере (по выбору с указание модели основного мессенджера)
    public $empSciStatus;      //  научная степень сотрудника (ссылка на таблицу степеней)
    public $empSciPosition;    //  научная должность (ссылка на таблицу позиций)
    public $empDivision;       //  подразделение (ссылка на таблицу подразделений)
    public $empJobPosition;    //  должность по штату (ссылка на таблицу должностей)
    public $empJobStatus;      //  статус по штату (в штате/совместитель) - логическое
    public $empPassword;       //  хэш пароля - машиночитаемый

    private $conn;              // Подключение в БД



    /*
     *  Конструктор объекта Empl
     *      создается пустой объект с необходимыми полями (пустыми)
     *      за исключением поля empSex, которое по умолчанию - true (муж)
     *      и поля empPersonalID (персональный ID сотрудника в кадровом учёте (по умолч. = 0)
     */
    public function __construct()
    {
        $this->empID            =   null;   //  станет актуальными только ПОСЛЕ занесения сотр. в базу
        $this->empPersonalID    =   0;      //
        $this->empFamily        =   "";     //  мин.необходим при инициализации сотрудника
        $this->empName          =   "";     //  мин.необходим при инициализации сотрудника
        $this->empSoname        =   "";     //  мин.необходим при инициализации сотрудника
        $this->empBDay          =   "";     //
        $this->empSex           =   true;   //  по умолчанию - муж.(true). жен.(false) надо явно указать
        $this->empMainEMail     =   "";     //  мин.необходим при инициализации сотрудника
        $this->empWTel          =   "";     //
        $this->empMTel          =   "";     //
        $this->empMessenger     =   "";     //
        $this->empSciStatus     =   "";     //
        $this->empSciPosition   =   "";     //
        $this->empDivision      =   "";     //
        $this->empDivision      =   "";     //
        $this->empJobPosition   =   "";     //
        $this->empJobStatus     =   "";     //
        $this->empPassword      =   "";     //  это хэш пароля, а не он сам. Появится только после занесение в базу

    }

    /*
     * Инициализация сотрудника по минимально необходимому набору параметров
     *      При создании объекта Empl его записи в базу не происходит и
     *      поэтому его ID пока не существует.
     */
    public function Init($empName, $empSoname, $empFamily, $empMainEMail)
    {
        $this->empID            =   null;           //  станет актуальными только ПОСЛЕ занесения сотр. в базу
        $this->empPersonalID    =   0;              //
        $this->empFamily        =   $empFamily;     //  мин.необходим при создании сотрудника
        $this->empName          =   $empName;       //  мин.необходим при создании сотрудника
        $this->empSoname        =   $empSoname;     //  мин.необходим при создании сотрудника
        $this->empBDay          =   "";             //
        $this->empSex           =   true;           //  по умолчанию - муж.(true). жен.(false) надо явно указать
        $this->empMainEMail     =   $empMainEMail;  //  мин.необходим при создании сотрудника
        $this->empWTel          =   "";             //
        $this->empMTel          =   "";             //
        $this->empMessenger     =   "";             //
        $this->empSciStatus     =   "";             //
        $this->empSciPosition   =   "";             //
        $this->empDivision      =   "";             //
        $this->empJobPosition   =   "";             //
        $this->empJobStatus     =   "";             //
        $this->empPassword      =   "";             //  это хэш пароля, а не он сам. Появится только после занесение в базу

        return;
    }

    /*
     *  Добавление нового сотрудника в базу с минимальными параметрами
     *  При добавлении сотрудника в базу для него создаётся реальный empID и генерируется хэш пароля
     */
    public function Add(
        $pass                   //  пароль в текстовой форме, по которому будет сформирован хэш
    ) {
        //  Проверка параметров сотрудника
        //  Если (параметры удовлетворяют)
        if (true) {
            //      Генерация хэша пароля
            //      запись в базу нового сотрудника
            //      Определения полученного в базе empID
        }
        if (true) {
            //  Если (успешно добавили)
            return $this->empID;    // если получилось, возвращаем ID добавленного сотрудника
        } else {
            //  иначе
            return false;           //  Возвращаем код ошибки
        }
    }

    /*
     *      Поиск и чтение параметров сотрудника по его ID
     *          если удалось найти сотрудника в базе
     *              возвращаем в объекте все его параметры
     *              возвращаем код true
     *          если НЕ удалось найти сотрудника в базе
     *              объект Сотрудник не изменяется
     *              возвращаем код false
     */
    public function GetByID($empID) {

        // Забираем из базы данные по сотруднику по его emplID
        $empPersonalID     =   "";
        $empFamily         =   "";
        $empName           =   "";
        $empSoname         =   "";
        $empBDay           =   "";
        $empSex            =   "";
        $empMainEMail      =   "";
        $empWTel           =   "";
        $empMTel           =   "";
        $empMessenger      =   "";
        $empSciStatus      =   "";
        $empSciPosition    =   "";
        $empDivision       =   "";
        $empJobPosition    =   "";
        $empJobStatus      =   "";
        $empPassword       =   "";
        //  Если (удалось прочесть Сотрудника)
        if (true) {
            $this->empID           =   $empID;              //  внутренний машиночитаемый ID сотрудника
            $this->empPersonalID   =   $empPersonalID;      //  кадровый номер сотрудника
            $this->empFamily       =   $empFamily;          //  фамилия
            $this->empName         =   $empName;            //  имя
            $this->empSoname       =   $empSoname;          //  отчество
            $this->empBDay         =   $empBDay;            //  дата рождения
            $this->empSex          =   $empSex;             //  пол
            $this->empMainEMail    =   $empMainEMail;       //  главный адрес эл.почты, он же логин для входа
            $this->empWTel         =   $empWTel;            //  рабочий телефон
            $this->empMTel         =   $empMTel;            //  мобильный телефон
            $this->empMessenger    =   $empMessenger;       //  адрес в мессенжере (по выбору с указание модели основного мессенджера)
            $this->empSciStatus    =   $empSciStatus;       //  научная степень сотрудника (ссылка на таблицу степеней)
            $this->empSciPosition  =   $empSciPosition;     //  научная должность (ссылка на таблицу позиций)
            $this->empDivision     =   $empDivision;        //  подразделение (ссылка на таблицу подразделений)
            $this->empJobPosition  =   $empJobPosition;     //  должность по штату (ссылка на таблицу должностей)
            $this->empJobStatus    =   $empJobStatus;       //  статус по штату (в штате/совместитель) - логическое
            $this->empPassword     =   $empPassword;        //  хэш пароля - машиночитаемый
            return true;
        } else {
            //  иначе
            return false;
        }
    }
}