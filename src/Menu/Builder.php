<?php
declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: smartnet
 * Date: 29.03.2017
 * Time: 10:11
 */
namespace App\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\HttpFoundation\Request;

class Builder implements ContainerAwareInterface
{
    use ContainerAwareTrait;

// Тестовое меню - не используется
    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');
        $menu->setChildrenAttributes(['class'=>'nav nav-bar']);

        $menu->addChild('Начало', [
            'route' => 'sm_homepage',
            'attributes' => [
                'class' => 'nav-item'
            ]
        ]);

        // access services from the container!
        $em = $this->container->get('doctrine')->getManager();

        // create another menu item
        $menu->addChild('Про нас...', [
                'route' => 'smetric_about',
                'attributes' => [
                    'class' => 'nav-item'
                ]
            ]);
        $menu['Про нас...']->setChildrenAttributes(['class'=>'nav']);
        // you can also add sub level's to your menu's as follows
        $menu['Про нас...']->addChild('Профиль',
            [
                'route' => 'profile',
                'attributes' => [
                    'class' => 'nav-item'
                ]
            ]
        );

        // ... add more children

        return $menu;
    }

// Меню регистрации Анонима
    /**
     * @param FactoryInterface $factory
     * @param array $options
     * @return \Knp\Menu\ItemInterface
     */
    public function regMenuA(FactoryInterface $factory, array $options) {
        $menu = $factory->createItem('regMenuA');
        $menu->setChildrenAttributes(['class'=>'regmenu nav d-flex flex-column']);
        $menu->addChild($options['L'], [
            'route' => 'sm_login',
            'attributes' => [
                'class' => 'nav-item'
            ]
        ]);
        $menu[$options['L']]->setLinkAttributes([
            'class' => 'nav-link'
        ]);

        $menu->addChild('Регистрация', [
            'route' => 'sm_reg',
            'attributes' => [
                'class' => 'nav-item'
            ]
        ]);
        $menu['Регистрация']->setLinkAttributes([
            'class' => 'nav-link'
        ]);

        return $menu;
    }

// Меню регистрации Авторизованного пользователя
    /**
     * @param FactoryInterface $factory
     * @param array $options
     * @return \Knp\Menu\ItemInterface
     */
    public function regMenuR(FactoryInterface $factory, array $options) {
        $menu = $factory->createItem('regMenuR');
        $menu->setChildrenAttributes(['class'=>'regmenu nav d-flex flex-column']);
        $menu->addChild('Выход', [
            'route' => 'sm_logout',
            'attributes' => [
                'class' => 'nav-item'
            ]
        ]);
        $menu['Выход']->setLinkAttributes([
            'class' => 'nav-link'
        ]);

        $menu->addChild('Профиль', [
            'route' => 'sm_profile',
            'attributes' => [
                'class' => 'nav-item'
            ]
        ]);
        $menu['Профиль']->setLinkAttributes([
            'class' => 'nav-link'
        ]);

        return $menu;
    }

// Главное верхнее меню (горизонтальное)

    /**
     * @param FactoryInterface $factory
     * @param array $options
     * @return \Knp\Menu\ItemInterface
     */
    public function topMenu(FactoryInterface $factory, array $options) {
        $menu = $factory->createItem('topMenu');
        $menu->setChildrenAttributes(['class'=>'top-menu nav nav-tabs nav-fill']);

// Формирование пункта меню "Сотрудник"

        if ($_SESSION['_sf2_attributes']['roleEmpl'] == true) {
            $menu->addChild('Сотрудник', [      // Пункт меню для Сотрудников
                'route' => 'sm_empl',            // Маршрут к шаблону страницы - рабочего места Сотрудника
                'attributes' => [
                    'class' => 'nav-item'
                ]
            ]);

            if ($_SESSION['tMenu'] == 'Emp') {  // если роль юзера "Сотрудник"
                $menu['Сотрудник']->setLinkAttributes([
                    'class' => 'nav-link active'         // то пункт меню "Сотрудник" активен
                ]);
            } else {
                $menu['Сотрудник']->setLinkAttributes([
                    'class' => 'nav-link'         // иначе пункт меню "Сотрудник" НЕактивен
                ]);
            }
        }
// Формирование пункта меню "Руководитель"

        if (($_SESSION['_sf2_attributes']['roleEmpl'] == true) &&
            (($_SESSION['_sf2_attributes']['roleManager'] == true) ||
                ($_SESSION['_sf2_attributes']['roleAdmin'] == true))) {
            $menu->addChild('Руководитель', [   // Пункт меню для Руководителя
                'route' => 'sm_manager',            // Маршрут к шаблону страницы - рабочего места Руководителя
                'attributes' => [
                    'class' => 'nav-item'
                ]
            ]);

            if ($_SESSION['tMenu'] == 'Manager') {
                $menu['Руководитель']->setLinkAttributes([
                    'class' => 'nav-link active'
                ]);
            } else {
                $menu['Руководитель']->setLinkAttributes([
                    'class' => 'nav-link'
                ]);
            }
        }
// Формирование пункта меню "Аналитик"

        if (($_SESSION['_sf2_attributes']['roleEmpl'] == true) &&
            (($_SESSION['_sf2_attributes']['roleAnalit'] == true) ||
             ($_SESSION['_sf2_attributes']['roleAdmin'] == true))) {

            $menu->addChild('Аналитик', [       // Пункт меню для Аналитика
                'route' => 'sm_analit',            // Маршрут к шаблону страницы - рабочего места Аналитика
                'attributes' => [
                    'class' => 'nav-item'
                ]
            ]);

            if ($_SESSION['tMenu'] == 'Analit') {
                $menu['Аналитик']->setLinkAttributes([
                    'class' => 'nav-link active'
                ]);
            } else {
                $menu['Аналитик']->setLinkAttributes([
                    'class' => 'nav-link'
                ]);
            }
        }
// Формирование пункта меню "Админ"

        if (($_SESSION['_sf2_attributes']['roleEmpl'] == true) &&
            ($_SESSION['_sf2_attributes']['roleAdmin'] == true)) {

            $menu->addChild('Админ', [          // Пункт меню для Админа
                'route' => 'sm_admin',            // Маршрут к шаблону страницы - рабочего места Админа
                'attributes' => [
                    'class' => 'nav-item'
                ]
            ]);

            if ($_SESSION['tMenu'] == 'Admin') {
                $menu['Админ']->setLinkAttributes([
                    'class' => 'nav-link active'
                ]);
            } else {
                $menu['Админ']->setLinkAttributes([
                    'class' => 'nav-link'
                ]);
            }
        }

        return $menu;
    }

    /**
     * Рабочее меню Сотрудника в левой колонке
     *
     * @param FactoryInterface $factory
     * @param array $options
     * @return \Knp\Menu\ItemInterface
     */
    public function emplMenu(FactoryInterface $factory, array $options) {

        $menu = $factory->createItem('emplMenu');
        $menu->setChildrenAttribute('class', 'nav flex-column');

        $menu->addChild('Личная карточка', [
            'route' => 'sm_profile',
            'routeParameters' =>
                ['command' => 'first']]);
        $menu['Личная карточка']->setAttribute('class', 'nav-item');
        $menu['Личная карточка']->setLinkAttribute('class', 'nav-link');


        $menu->addChild('Публикации', [
            'route' => 'sm_empl_pubs',
            'routeParameters' =>
                ['command' => 'second']]);
        $menu['Публикации']->setAttribute('class', 'nav-item');
        $menu['Публикации']->setLinkAttribute('class', 'nav-link');
        $menu['Публикации']->setChildrenAttributes([
            'class'=>'nav flex-column'
        ]);

        $menu['Публикации']->addChild('Статьи', [
            'route' => 'sm_empl_pubs_article',
            'routeParameters'   => [
                'command'   => 'second'
            ]
        ]);
        $menu['Публикации']['Статьи']->setAttribute('class', 'nav-item');
        $menu['Публикации']['Статьи']->setLinkAttribute('class', 'nav-link');

        $menu['Публикации']->addChild('Доклады', [
            'route' => 'sm_empl_pubs_reports',
            'routeParameters'   => [
                'command'   => 'second'
            ]
        ]);
        $menu['Публикации']['Доклады']->setAttribute('class', 'nav-item');
        $menu['Публикации']['Доклады']->setLinkAttribute('class', 'nav-link');

        $menu['Публикации']->addChild('Учебники, пособия и монографии', [
            'route' => 'sm_empl_pubs_mono',
            'routeParameters'   => [
                'command'   => 'second'
            ]
        ]);
        $menu['Публикации']['Учебники, пособия и монографии']->setAttribute('class', 'nav-item');
        $menu['Публикации']['Учебники, пособия и монографии']->setLinkAttribute('class', 'nav-link');

        $menu['Публикации']->addChild('Главы в учебниках, пособиях и монографиих', [
            'route' => 'sm_empl_pubs_chapters',
            'routeParameters'   => [
                'command'   => 'second'
            ]
        ]);
        $menu['Публикации']['Главы в учебниках, пособиях и монографиих']->setAttribute('class', 'nav-item');
        $menu['Публикации']['Главы в учебниках, пособиях и монографиих']->setLinkAttribute('class', 'nav-link');

        $menu->addChild('Объекты интелл.собств.', [
            'route' => 'sm_empl_patents',
            'routeParameters' =>
                ['command' => 'third']]);
        $menu['Объекты интелл.собств.']->setAttribute('class', 'nav-item');
        $menu['Объекты интелл.собств.']->setLinkAttribute('class', 'nav-link');

        $menu->addChild('Подготовка научн.кадров', [
            'route' => 'sm_empl_sciment',
            'routeParameters' =>
                ['command' => 'third']]);
        $menu['Подготовка научн.кадров']->setAttribute('class', 'nav-item');
        $menu['Подготовка научн.кадров']->setLinkAttribute('class', 'nav-link');

        $menu->addChild('Отчёты', [
            'route' => 'sm_empl_reports',
            'routeParameters' =>
                ['command' => 'third']]);
        $menu['Отчёты']->setAttribute('class', 'nav-item');
        $menu['Отчёты']->setLinkAttribute('class', 'nav-link');

        $menu->addChild('Рейтинг', [
            'route' => 'sm_empl_rating',
            'routeParameters' =>
                ['command' => 'third']]);
        $menu['Рейтинг']->setAttribute('class', 'nav-item');
        $menu['Рейтинг']->setLinkAttribute('class', 'nav-link');

        return $menu;
    }

    /**
     * Рабочее меню Руководителя в левой колонке
     *
     * @param FactoryInterface $factory
     * @param array $options
     * @return \Knp\Menu\ItemInterface
     */
    public function managerMenu(FactoryInterface $factory, array $options) {

        $menu = $factory->createItem('managerMenu');

        $menu->setChildrenAttribute('class', 'nav flex-column');

        $menu->addChild('Сотрудники', ['route' => 'sm_manager_emplrs',
            'routeParameters' =>
                ['command' => 'first']]);
        $menu['Сотрудники']->setAttribute('class', 'nav-item');
        $menu['Сотрудники']->setLinkAttribute('class', 'nav-link');

        return $menu;
    }

    /**
     * Рабочее меню Аналитика в левой колонке
     *
     * @param FactoryInterface $factory
     * @param array $options
     * @return \Knp\Menu\ItemInterface
     */
    public function analitMenu(FactoryInterface $factory, array $options) {

        $menu = $factory->createItem('analitMenu');

        $menu->setChildrenAttribute('class', 'nav flex-column');

        $menu->addChild('Сотрудники', ['route' => 'sm_analit_emplrs',
            'routeParameters' =>
                ['command' => 'first']]);
        $menu['Сотрудники']->setAttribute('class', 'nav-item');
        $menu['Сотрудники']->setLinkAttribute('class', 'nav-link');

        $menu->addChild('Орг.структура', ['route' => 'sm_analit_orgstruct',
            'routeParameters' =>
                ['command' => 'first']]);
        $menu['Орг.структура']->setAttribute('class', 'nav-item');
        $menu['Орг.структура']->setLinkAttribute('class', 'nav-link');


        $menu->addChild('Отчёты', ['route' => 'sm_analit_reports',
            'routeParameters' =>
                ['command' => 'first']]);
        $menu['Отчёты']->setAttribute('class', 'nav-item');
        $menu['Отчёты']->setLinkAttribute('class', 'nav-link');

        $menu->addChild('Параметры', ['route' => 'sm_analit_parameters',
            'routeParameters' =>
                ['command' => 'first']]);
        $menu['Параметры']->setAttribute('class', 'nav-item');
        $menu['Параметры']->setLinkAttribute('class', 'nav-link');

        return $menu;
    }

    /**
     * Рабочее меню Админа в левой колонке
     *
     * @param FactoryInterface $factory
     * @param array $options
     * @return \Knp\Menu\ItemInterface
     */
    public function adminMenu(FactoryInterface $factory, array $options) {

        $menu = $factory->createItem('adminMenu');

        $menu->setChildrenAttribute('class', 'nav flex-column');

        $menu->addChild('Структура ФИЦ', ['route' => 'sm_admin_orgstruct',
            'routeParameters' =>
                ['command' => 'first']]);
        $menu['Структура ФИЦ']->setAttribute('class', 'nav-item');
        $menu['Структура ФИЦ']->setLinkAttribute('class', 'nav-link');

        $menu->addChild('Сотрудники', ['route' => 'sm_admin_emplrs',
            'routeParameters' =>
                ['command' => 'first']]);
        $menu['Сотрудники']->setAttribute('class', 'nav-item');
        $menu['Сотрудники']->setLinkAttribute('class', 'nav-link');

        $menu->addChild('Пользователи', ['route' => 'sm_admin_users',
            'routeParameters' =>
                ['command' => 'first']]);
        $menu['Пользователи']->setAttribute('class', 'nav-item');
        $menu['Пользователи']->setLinkAttribute('class', 'nav-link');

        $menu->addChild('Документы', ['route' => 'sm_admin_documents',
            'routeParameters' =>
                ['command' => 'first']]);
        $menu['Документы']->setAttribute('class', 'nav-item');
        $menu['Документы']->setLinkAttribute('class', 'nav-link');

        $menu->addChild('Отчёты', ['route' => 'sm_admin_reports',
            'routeParameters' =>
                ['command' => 'first']]);
        $menu['Отчёты']->setAttribute('class', 'nav-item');
        $menu['Отчёты']->setLinkAttribute('class', 'nav-link');

        $menu->addChild('Архивирование', ['route' => 'sm_admin_backup',
            'routeParameters' =>
                ['command' => 'first']]);
        $menu['Архивирование']->setAttribute('class', 'nav-item');
        $menu['Архивирование']->setLinkAttribute('class', 'nav-link');

        $menu->addChild('Восстановление', ['route' => 'sm_admin_restore',
            'routeParameters' =>
                ['command' => 'first']]);
        $menu['Восстановление']->setAttribute('class', 'nav-item');
        $menu['Восстановление']->setLinkAttribute('class', 'nav-link');

        $menu->addChild('Параметры', ['route' => 'sm_admin_parameters',
            'routeParameters' =>
                ['command' => 'first']]);
        $menu['Параметры']->setAttribute('class', 'nav-item');
        $menu['Параметры']->setLinkAttribute('class', 'nav-link');

        return $menu;
    }
}