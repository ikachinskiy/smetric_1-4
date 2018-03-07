<?php
declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: smartnet
 * Date: 23.03.2017
 * Time: 18:14
 */
namespace App\SMetric;

class Init {

    public function all() {
        /* Все меню устанавливаются в KNP
        //  Создаём объект меню регистрации для анонима
        $_SESSION['regm_a_top'] = new MenuItem('Вход', 'login', '', '');
        $_SESSION['regm_a_bottom'] = new MenuItem('Регистрация', 'reg', '', '');

        //  Создаём объект меню регистрации для авторизованного
        $_SESSION['regm_r_top'] = new MenuItem('Выход', 'logout', '', '');
        $_SESSION['regm_r_bottom'] = new MenuItem('Профиль', 'profile', '', '');

        // Создаём объект основных меню - верхнего и левого
        $main_menu = [
            [
                new MenuItem('Главная', '/', 'active', ''),
                new MenuItem('', '', '', ''),
                new MenuItem('', '', '', '')
            ],
            [
                new MenuItem('Публикации', '/publication', '', ''),
                new MenuItem('', '', '', ''),
                new MenuItem('', '', '', '')
            ],
            [
                new MenuItem('Выступления', '/appearance', '', ''),
                new MenuItem('', '', '', ''),
                new MenuItem('', '', '', ''),
                new MenuItem('', '', '', ''),
                new MenuItem('', '', '', '')
            ],
            [
                new MenuItem('Защиты', '/dissertation', '', ''),
                new MenuItem('', '', '', '')
            ],
            [
                new MenuItem('Отчёты', '/reports', '', ''),
                new MenuItem('', '', '', ''),
                new MenuItem('', '', '', ''),
                new MenuItem('', '', '', '')
            ],
            [
            new MenuItem('Админ', '/admin', '', ''),
                new MenuItem('', '', '', ''),
                new MenuItem('', '', '', ''),
                new MenuItem('', '', '', '')
            ]
        ];
        // Пишем основное меню в сессию
        $_SESSION['main_menu'] = $main_menu;
        */
        if (!isset($_SESSION['userstate'])) {   //  Если состояние юзера не установлено (начальный вход)
            $_SESSION['userstate'] = 'a';       //  то юзер - аноним, в противном случае ничего не меняем
        }
        if (!isset($_SESSION['userrole'])) {    // Если роль юзера не установлене (начальный вход)
            $_SESSION['userrole'] = 'unknown';  // то роль юзера - неизвестен
        }
        if (!isset($_SESSION['userID'])) {    // Если ID юзера не установлен (начальный вход)
            $_SESSION['userrole'] = '0';  // то ID юзера - 0
        }
    }
}

?>