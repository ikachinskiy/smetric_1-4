<?php
/**
 * Created by PhpStorm.
 * User: smartnet
 * Date: 31.08.18
 * Time: 17:05
 */
declare(strict_types=1);


namespace App\SMetric;


/**
 * @package App\SMetric
 *
 * Class CurrentUser - паттерн Singleton. Реализует единое место хранения информации о
 *  текущем состоянии пользователя и его реквизитах. Сохраняет эту информацию для
 *  всех остальных модулей в течении сессии - в переменных сессии. Других способов
 *  доступа к этим перменнным сессии быть не должно.
 *
 */

//TODO Сделать функциональность класса CurrentUser в соответствии со спекой

class CurrentUser
{
    private static $instances = [];
    private $userState = "a";
    private $userRole = [
        "Empl"      => 0,
        "Manager"   => 0,
        "Analit"    => 0,
        "Admin"     => 0
    ];
    private $userID = "";

    /**
     * CurrentUser constructor.
     */
    protected function __construct()
    {
    }

    /**
     *
     */
    protected function __clone()
    {
    }

    /**
     * @throws \Exception
     */
    public function __wakeup()
    {
        throw new \Exception("Нельзя сериализовать синглетон CurrentUser()!");
    }

    /**
     * @return array
     */
    public static function init(): CurrentUser
    {
        $cls = get_called_class();
        if (!isset(self::$instances[$cls])) {
            self::$instances[$cls] = new static();
        }
        return self::$instances[$cls];
    }

    /**
     *
     */
    public function isAnonym() {
        $this->userState = "a";
    }

    /**
     *
     */
    public function isReg() {
        $this->userState = "r";
    }

    /**
     * @return string
     */
    public function userState():string {
        return $this->userState;
    }
}