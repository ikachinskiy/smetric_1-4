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

class EmplController extends Controller
{
    /**
     * @Route("/empl", name="sm_empl")
     */
    public function empl(Request $request, Connection $conn) {
        $_SESSION['tMenu'] = 'Emp';
        $session = $request->getSession();
        $user = [];
        if ($session->get('userID') == 0) {
            $user['empfamily']  =   "Аноним";
            $user['empname']    =   "";
            $user['empsoname']  =   "";
        } else {
            $user = $conn->fetchAssoc('SELECT * FROM empl WHERE id = ?', [$session->get('userID')]);
        }
        return $this->render('smetric/empl/empl.html.twig', [   // Выводим шаблон default/empl/empl
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

    /**
     * @Route("/empl/pubs", name="sm_empl_pubs")
     */
    public function emplPubs(Request $request, Connection $conn) {
        $session = $request->getSession();
        $user = [];
        if ($session->get('userID') == 0) {
            $user['empfamily']  =   "Аноним";
            $user['empname']    =   "";
            $user['empsoname']  =   "";
        } else {
            $user = $conn->fetchAssoc('SELECT * FROM empl WHERE id = ?', [$session->get('userID')]);
        }
        return $this->render('smetric/empl/empl-pubs.html.twig', [   // Выводим шаблон default/empl/empl-pubs
            'Title'         => 'SMetric: Сотрудник:Публикации',
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
     * @Route("/empl/pubs/article/", name="sm_empl_pubs_article")
     */
    public function emplPubsArticle(Request $request, Connection $conn) {
        $session    = $request->getSession();
        $user       =   [];
        $pubs       =   [];
        if ($session->get('userID') == 0) {
            $user['empfamily']  =   "Аноним";
            $user['empname']    =   "";
            $user['empsoname']  =   "";
            $pubs   =   null;
        } else {
            $user = $conn->fetchAssoc('SELECT * FROM empl WHERE id = ?', [$session->get('userID')]);
            // Выбираем список статей, принадлежащих user
            $pubs = $conn->fetchAll('SELECT * FROM articles WHERE author = ?', [$session->get('userID')]);
        }

//        $pubs = $conn->fetchAll('SELECT * FROM articles WHERE author = ?', [$session->get('userID')]);
        return $this->render('smetric/empl/empl-pubs-article.html.twig', [   // шаблон default/empl/empl-pubs-article
            'Title'         => 'SMetric: Сотрудник:Публикации статей',
            'user'      => $user,
            'pubs'      => $pubs,
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
     * @Route("/empl/pubs/article/new", name="sm_empl_pubs_article_new")
     */
    public function emplPubsArticleNew(Request $request, Connection $conn) {
        $session = $request->getSession();
        $user = [];
        if ($session->get('userID') == 0) {
            $user['empfamily']  =   "Аноним";
            $user['empname']    =   "";
            $user['empsoname']  =   "";
        } else {
            $user = $conn->fetchAssoc('SELECT * FROM empl WHERE id = ?', [$session->get('userID')]);
        }
        // Обработка ответа формы создания новой статьи
        //  Если (не сохранять)
        if (($request->request->get('formName') == 'EmpArtNew') &&
            ($request->request->get('btnSubmit') == 'DontSaveNewArt')) {
            // Выбираем список статей, принадлежащих user
            $pubs = $conn->fetchAll('SELECT * FROM articles WHERE author = ?', [$session->get('userID')]);
            //      Возврат в форму отображения списка статей
            return $this->render('smetric/empl/empl-pubs-article.html.twig', [   // шаблон default/empl/empl-pubs-article
                'Title'         => 'SMetric: Сотрудник:Публикации статей',
                'user'      => $user,
                'pubs'      => $pubs,
                'UserState'     =>  $session->get('userstate'),
                'UserRole'      =>  $session->get('userrole'),
                'UserID'        =>  $session->get('userID'),
                'roleEmpl'      =>  $session->get('roleEmpl'),
                'roleManager'   =>  $session->get('roleManager'),
                'roleAnalit'    =>  $session->get('roleAnalit'),
                'roleAdmin'     =>  $session->get('roleAdmin')
            ]);
        }
        //  Если (сохранять)
        if (($request->request->get('formName') == 'EmpArtNew') &&
            ($request->request->get('btnSubmit') == 'SaveNewArt')) {
            //      Внести новую статья в базу
            if ($request->request->get('WoS-Core') == 'on') {
                $catwoscore = 1;
            } else {
                $catwoscore = 0;
            }
            if ($request->request->get('WoS-RSCI') == 'on') {
                $catwosrsci = 1;
            } else {
                $catwosrsci = 0;
            }
            if ($request->request->get('Scopus') == 'on') {
                $catscopus = 1;
            } else {
                $catscopus = 0;
            }
            if ($request->request->get('VAK') == 'on') {
                $catvak = 1;
            } else {
                $catvak = 0;
            }
            if ($request->request->get('RINC') == 'on') {
                $catrinc = 1;
            } else {
                $catrinc = 0;
            }
            if ($request->request->get('Others') == 'on') {
                $catothers = 1;
            } else {
                $catothers = 0;
            }
            //TODO При задании пустых значений числовых параметров в Сотр/Статья/Создать вылетает SQL
            // Ввести проверку входных данных на пустые значение (допускаются, но надо что то придумать
            $conn->insert('articles', [
                'author'        =>  $session->get('userID'),
                'allauthors'    =>  $request->request->get('articleAuthors'),
                'numauthfrc'    =>  $request->request->get('articleNumbAuth'),
                'title'         =>  $request->request->get('articleTitle'),
                'magazine'      =>  $request->request->get('articleMagTitle'),
                'year'          =>  $request->request->get('articleYear'),
                'volume'        =>  $request->request->get('articleVolume'),
                'number'        =>  $request->request->get('articleMagNumber'),
                'startpage'     =>  $request->request->get('articlePageStart'),
                'endpage'       =>  $request->request->get('articlePageEnd'),
                'catwoscore'    =>  $catwoscore,
                'catwosrsci'    =>  $catwosrsci,
                'catscopus'     =>  $catscopus,
                'catvak'        =>  $catvak,
                'catrinc'       =>  $catrinc,
                'catothers'     =>  $catothers
            ]);
            // Выбираем список статей, принадлежащих user
            $pubs = $conn->fetchAll('SELECT * FROM articles WHERE author = ?', [$session->get('userID')]);
            //      Возврат в форму отображения списка статей
            return $this->render('@App/default/empl/empl-pubs-article.html.twig', [   // шаблон default/empl/empl-pubs-article
                'Title'         => 'SMetric: Сотрудник:Публикации статей',
                'user'      => $user,
                'pubs'      => $pubs,
                'UserState'     =>  $session->get('userstate'),
                'UserRole'      =>  $session->get('userrole'),
                'UserID'        =>  $session->get('userID'),
                'roleEmpl'      =>  $session->get('roleEmpl'),
                'roleManager'   =>  $session->get('roleManager'),
                'roleAnalit'    =>  $session->get('roleAnalit'),
                'roleAdmin'     =>  $session->get('roleAdmin')
            ]);
        }
        // Первичное отображение формы создания новой статьи
        return $this->render('smetric/empl/empl-pubs-article-new.html.twig', [   // шаблон default/empl/empl-pubs-article-new
            'Title'         => 'SMetric: Сотрудник:Публикации статей-Новая',
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
     * @Route("/empl/pubs/article/view", name="sm_empl_pubs_article_view")
     */
    public function emplPubsArticleView(Request $request, Connection $conn, $artID) {
        $session = $request->getSession();
        $user = [];
        $art  = [];
        $fintype = [];
        if ($session->get('userID') == 0) {
            $user['empfamily']  =   "Аноним";
            $user['empname']    =   "";
            $user['empsoname']  =   "";
        } else {
            $user = $conn->fetchAssoc('SELECT * FROM empl WHERE id = ?', [$session->get('userID')]);
            $art = $conn->fetchAssoc('SELECT * FROM articles WHERE id = ?', [$artID]);
            $fintype = $conn->fetchAll('SELECT * FROM fintype');
        }
        return $this->render('smetric/empl/empl-pubs-article-view.html.twig', [   // шаблон default/empl/empl-pubs-article-view
            'Title'         => 'SMetric: Сотрудник:Публикации статей-Просмотр',
            'user'      =>  $user,
            'art'       =>  $art,
            'fintype'   =>  $fintype,
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
     * @Route("/empl/pubs/article/edit/{artID}", name="sm_empl_pubs_article_edit")
     */
    public function emplPubsArticleEdit(Request $request, Connection $conn, $artID) {
        $session = $request->getSession();
        $user = [];
        $art   =   [];
        $fintype = $conn->fetchAll('SELECT * FROM fintype');
        if ($session->get('userID') == 0) {
            $user['empfamily']  =   "Аноним";
            $user['empname']    =   "";
            $user['empsoname']  =   "";
        } else {
            // Обработка ответа от формы редактирование
            $user = $conn->fetchAssoc('SELECT * FROM empl WHERE id = ?', [$session->get('userID')]);
            //  Если (вернулись без сохранениея)
            if (($request->request->get('formName') == 'EmpArtEdit') &&
                ($request->request->get('btnSubmit') == 'DontSaveEditArt')) {
                //      Показать список всех статей сотрудника
                $pubs = $conn->fetchAll('SELECT * FROM articles WHERE author = ?', [$session->get('userID')]);
                return $this->render('smetric/empl/empl-pubs-article.html.twig', [   // шаблон default/empl/empl-pubs-article
                    'Title'         => 'SMetric: Сотрудник:Публикации статей',
                    'user'      => $user,
                    'pubs'      => $pubs,
                    'UserState'     =>  $session->get('userstate'),
                    'UserRole'      =>  $session->get('userrole'),
                    'UserID'        =>  $session->get('userID'),
                    'roleEmpl'      =>  $session->get('roleEmpl'),
                    'roleManager'   =>  $session->get('roleManager'),
                    'roleAnalit'    =>  $session->get('roleAnalit'),
                    'roleAdmin'     =>  $session->get('roleAdmin')
                ]);
            }
            //  Если (вернулись с сохранение)
            if (($request->request->get('formName') == 'EmpArtEdit') &&
                ($request->request->get('btnSubmit') == 'SaveEditArt')) {
                $art = $conn->fetchAssoc('SELECT * FROM articles WHERE id = ?', [$artID]);
                //      Выбрать из ответа введённые параметры
                //      Обновить запись статьи в базе
                if ($request->request->get('WoS-Core') == 'on') {
                    $catWoSCore = 1;
                } else {
                    $catWoSCore = 0;
                }
                if ($request->request->get('WoS-RSCI') == 'on') {
                    $catWoSRSCI = 1;
                } else {
                    $catWoSRSCI = 0;
                }
                if ($request->request->get('Scopus') == 'on') {
                    $catScopus = 1;
                } else {
                    $catScopus = 0;
                }
                if ($request->request->get('VAK') == 'on') {
                    $catVAK = 1;
                } else {
                    $catVAK = 0;
                }
                if ($request->request->get('RINC') == 'on') {
                    $catRINC = 1;
                } else {
                    $catRINC = 0;
                }
                if ($request->request->get('Others') == 'on') {
                    $catOthers = 1;
                } else {
                    $catOthers = 0;
                }
                $conn->update('articles',[
                    'numauthfrc'    => $request->request->get('articleNumbAuth'),
                    'title'         => $request->request->get('articleTitle'),
                    'magazine'      => $request->request->get('articleMagTitle'),
                    'volume'        => $request->request->get('articleVolume'),
                    'number'        => $request->request->get('articleMagNumber'),
                    'startpage'     => $request->request->get('articlePageStart'),
                    'endpage'       => $request->request->get('articlePageEnd'),
                    'year'          => $request->request->get('articleYear'),
                    'allauthors'    => $request->request->get('articleAuthors'),
                    'catwoscore'    => $catWoSCore,
                    'catwosrsci'    => $catWoSRSCI,
                    'catscopus'     => $catScopus,
                    'catvak'        => $catVAK,
                    'catrinc'       => $catRINC,
                    'catothers'     => $catOthers,
                    'fintype'       => $request->request->get('finType')
                ],[
                    'id' => $artID
                ]);
                //      Показать список всех статей сотрудника
                $pubs = $conn->fetchAll('SELECT * FROM articles WHERE author = ?', [$session->get('userID')]);
                return $this->render('smetric/empl/empl-pubs-article.html.twig', [   // шаблон default/empl/empl-pubs-article
                    'Title'         => 'SMetric: Сотрудник:Публикации статей',
                    'user'      => $user,
                    'pubs'      => $pubs,
                    'UserState'     =>  $session->get('userstate'),
                    'UserRole'      =>  $session->get('userrole'),
                    'UserID'        =>  $session->get('userID'),
                    'roleEmpl'      =>  $session->get('roleEmpl'),
                    'roleManager'   =>  $session->get('roleManager'),
                    'roleAnalit'    =>  $session->get('roleAnalit'),
                    'roleAdmin'     =>  $session->get('roleAdmin')
                ]);
            }
            // Выбираем параметры сотрудника
            $user = $conn->fetchAssoc('SELECT * FROM empl WHERE id = ?', [$session->get('userID')]);
            // Выбираем все параметры статьи, которую собрались редактировать - для заполнения формы
            $art = $conn->fetchAssoc('SELECT * FROM articles WHERE id = ?', [$artID]);
        }
        return $this->render('smetric/empl/empl-pubs-article-edit.html.twig', [   // шаблон default/empl/empl-pubs-article-edit
            'Title'         => 'SMetric: Сотрудник:Публикации статей-Редактирование',
            'user'      =>  $user,
            'art'       =>  $art,
            'fintype'   =>  $fintype,
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
     * @Route("/empl/pubs/article/delete/{artID}", name="sm_empl_pubs_article_delete")
     */
    public function emplPubsArticleDelete(Request $request, Connection $conn, $artID) {
        $session = $request->getSession();
        $user = [];
        $art    = [];
        $pubs   =   null;
        $fintype = $conn->fetchAll('SELECT * FROM fintype');
        if ($session->get('userID') == 0) {
            $user['empfamily']  =   "Аноним";
            $user['empname']    =   "";
            $user['empsoname']  =   "";
        } else {
            $user = $conn->fetchAssoc('SELECT * FROM empl WHERE id = ?', [$session->get('userID')]);
            $art = $conn->fetchAssoc('SELECT * FROM articles WHERE id = ?', [$artID]);
            // Выбираем список статей, принадлежащих user
            $pubs = $conn->fetchAll('SELECT * FROM articles WHERE author = ?', [$session->get('userID')]);
        }
        // Обработка ответа формы удаления статьи Сотрудником
        //  Если (не удалять)
        if (($request->request->get('formName') == 'EmpArtDelete') &&
            ($request->request->get('btnSubmit') == 'DontDeleteArt')) {
            //      Возврат в форму отображения списка статей
            return $this->render('smetric/empl/empl-pubs-article.html.twig', [   // шаблон default/empl/empl-pubs-article
                'Title'         => 'SMetric: Сотрудник:Публикации статей',
                'user'      => $user,
                'pubs'      => $pubs,
                'UserState'     =>  $session->get('userstate'),
                'UserRole'      =>  $session->get('userrole'),
                'UserID'        =>  $session->get('userID'),
                'roleEmpl'      =>  $session->get('roleEmpl'),
                'roleManager'   =>  $session->get('roleManager'),
                'roleAnalit'    =>  $session->get('roleAnalit'),
                'roleAdmin'     =>  $session->get('roleAdmin')
            ]);
        }
        //  Если (удалять)
        if (($request->request->get('formName') == 'EmpArtDelete') &&
            ($request->request->get('btnSubmit') == 'DeleteArt')) {
            //      удаляем запись о статье
            $conn->delete('articles', ['id' => $artID]);
            //      Возврат в форму отображения списка статей
            $pubs = $conn->fetchAll('SELECT * FROM articles WHERE author = ?', [$session->get('userID')]);
            return $this->render('smetric/empl/empl-pubs-article.html.twig', [   // шаблон default/empl/empl-pubs-article
                'Title'         => 'SMetric: Сотрудник:Публикации статей',
                'user'      => $user,
                'pubs'      => $pubs,
                'UserState'     =>  $session->get('userstate'),
                'UserRole'      =>  $session->get('userrole'),
                'UserID'        =>  $session->get('userID'),
                'roleEmpl'      =>  $session->get('roleEmpl'),
                'roleManager'   =>  $session->get('roleManager'),
                'roleAnalit'    =>  $session->get('roleAnalit'),
                'roleAdmin'     =>  $session->get('roleAdmin')
            ]);
        }
        // первичный вывод формы
        return $this->render('smetric/empl/empl-pubs-article-delete.html.twig', [   // шаблон default/empl/empl-pubs-article-delete
            'Title'         => 'SMetric: Сотрудник:Публикации статей-Удаление',
            'user'      =>  $user,
            'art'       =>  $art,
            'fintype'   =>  $fintype,
            'UserState'     =>  $session->get('userstate'),
            'UserRole'      =>  $session->get('userrole'),
            'UserID'        =>  $session->get('userID'),
            'roleEmpl'      =>  $session->get('roleEmpl'),
            'roleManager'   =>  $session->get('roleManager'),
            'roleAnalit'    =>  $session->get('roleAnalit'),
            'roleAdmin'     =>  $session->get('roleAdmin')
        ]);

    }

    //TODO Отсюда функции-заглушки
    /**
     * @Route("/empl/pubs/reports", name="sm_empl_pubs_reports")
     */
    public function emplPubsReports(Request $request, Connection $conn) {
        $session = $request->getSession();
        $user = [];
        $pubs       =   [];
        if ($session->get('userID') == 0) {
            $user['empfamily']  =   "Аноним";
            $user['empname']    =   "";
            $user['empsoname']  =   "";
        } else {
            $user = $conn->fetchAssoc('SELECT * FROM empl WHERE id = ?', [$session->get('userID')]);
            $pubs = $conn->fetchAll('SELECT * FROM reports WHERE author = ?', [$session->get('userID')]);
        }
        return $this->render('smetric/empl/empl-pubs-reports.html.twig', [   // шаблон default/empl/empl-pubs-reports
            'Title'         => 'SMetric: Сотрудник:Публикации докладов',
            'user'      => $user,
            'pubs'      => $pubs,
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
     * @Route("/empl/pubs/reports/new", name="sm_empl_pubs_reports_new")
     */
    public function emplPubsReportsNew(Request $request, Connection $conn) {
        $session = $request->getSession();
        $user = [];
        if ($session->get('userID') == 0) {
            $user['empfamily']  =   "Аноним";
            $user['empname']    =   "";
            $user['empsoname']  =   "";
        } else {
            $user = $conn->fetchAssoc('SELECT * FROM empl WHERE id = ?', [$session->get('userID')]);
        }
        // Обработка формы "Создание нового доклада"
        //  Если (не сохранять)
        if (($request->request->get('formName') == 'EmpRepNew') &&
            ($request->request->get('btnSubmit') == 'DontSaveNewRep')) {
            //      Выбираем список докладов, принадлежащих user
            $pubs = $conn->fetchAll('SELECT * FROM reports WHERE author = ?', [$session->get('userID')]);
            //      Возврат в форму отображения списка докладов
            return $this->render('smetric/empl/empl-pubs-reports.html.twig', [   // шаблон default/empl/empl-pubs-reports-new
                'Title'         => 'SMetric: Сотрудник:Публикации докладов - новый',
                'user'      => $user,
                'pubs'      => $pubs,
                'UserState'     =>  $session->get('userstate'),
                'UserRole'      =>  $session->get('userrole'),
                'UserID'        =>  $session->get('userID'),
                'roleEmpl'      =>  $session->get('roleEmpl'),
                'roleManager'   =>  $session->get('roleManager'),
                'roleAnalit'    =>  $session->get('roleAnalit'),
                'roleAdmin'     =>  $session->get('roleAdmin')
            ]);
        }
        //  Если (сохранять)
        if (($request->request->get('formName') == 'EmpRepNew') &&
            ($request->request->get('btnSubmit') == 'SaveNewRep')) {
            //      Внести новые доклад в базу
            //TODO Ввести проверку пустых значений числовых параметров в Сотр/Доклады/Создать
            //  Обработка категорий доклада
            if ($request->request->get('WoS') == 'on') {
                $catwos = 1;
            } else {
                $catwos = 0;
            }
            if ($request->request->get('Scopus') == 'on') {
                $catscopus = 1;
            } else {
                $catscopus = 0;
            }
            if ($request->request->get('ISSN') == 'on') {
                $catissn = 1;
            } else {
                $catissn = 0;
            }
            if ($request->request->get('ISBN') == 'on') {
                $catisbn = 1;
            } else {
                $catisbn = 0;
            }
            if ($request->request->get('Иное') == 'on') {
                $catothers = 1;
            } else {
                $catothers = 0;
            }
            // Обработка типа "Доклад/тезисы"
            if ($request->request->get('reportType') == 'reportFull') {
                $reptype = 1;
            } elseif ($request->request->get('reportType') == 'reportShort') {
                $reptype = 0;
            }
            //  Вносим в базу новую запись с параметрами доклада/тезисов
            $conn->insert('reports', [
                'author'        =>  $session->get('userID'),
                'allauthors'    =>  $request->request->get('reportAuthors'),
                'numauthfrc'    =>  $request->request->get('reportAuthFRC'),
                'title'         =>  $request->request->get('reportTitle'),
                'reptype'       =>  $reptype,
                'eventname'     =>  $request->request->get('reportEvent'),
                'eventplace'    =>  $request->request->get('reportEventPlace'),
                'eventstartdate'    =>  $request->request->get('reportEventStartDate'),
                'eventenddate'      =>  $request->request->get('reportEventEndDate'),
                'magazine'      =>  $request->request->get('reportMagTitle'),
                'publisher'     =>  $request->request->get('reportPub'),
                'pubplace'      =>  $request->request->get('reportPubPlace'),
                'pubyear'       =>  $request->request->get('reportPubYear'),
                'startpage'     =>  $request->request->get('reportPubPageStart'),
                'endpage'       =>  $request->request->get('reportPubPageEnd'),
                'catwos'        =>  $catwos,
                'catscopus'     =>  $catscopus,
                'catissn'       =>  $catissn,
                'catisbn'       =>  $catisbn,
                'catothers'     =>  $catothers
            ]);
            //  Выбираем обновлённый список докладов, принадлежащих user
            $pubs = $conn->fetchAll('SELECT * FROM reports WHERE author = ?', [$session->get('userID')]);
            //  Возврат в форму отображения списка докладов
            return $this->render('smetric/empl/empl-pubs-reports.html.twig', [   // шаблон default/empl/empl-pubs-reports-new
                'Title'         => 'SMetric: Сотрудник:Публикации докладов - новый',
                'user'      => $user,
                'pubs'      => $pubs,
                'UserState'     =>  $session->get('userstate'),
                'UserRole'      =>  $session->get('userrole'),
                'UserID'        =>  $session->get('userID'),
                'roleEmpl'      =>  $session->get('roleEmpl'),
                'roleManager'   =>  $session->get('roleManager'),
                'roleAnalit'    =>  $session->get('roleAnalit'),
                'roleAdmin'     =>  $session->get('roleAdmin')
            ]);
        }
        // Первичное отображение формы создания нового доклада/тезисов доклада
        $pubs = $conn->fetchAll('SELECT * FROM reports WHERE author = ?', [$session->get('userID')]);
        return $this->render('smetric/empl/empl-pubs-reports-new.html.twig', [   // шаблон default/empl/empl-pubs-reports-new
            'Title'         => 'SMetric: Сотрудник:Публикации докладов - новый',
            'user'      => $user,
            'pubs'      => $pubs,
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
     * @Route("/empl/pubs/reports/view/{repID}", name="sm_empl_pubs_reports_view")
     */
    public function emplPubsReportsView(Request $request, Connection $conn, $repID) {
        $session = $request->getSession();
        $user = [];
        if ($session->get('userID') == 0) {
            $user['empfamily']  =   "Аноним";
            $user['empname']    =   "";
            $user['empsoname']  =   "";
        } else {
            $user = $conn->fetchAssoc('SELECT * FROM empl WHERE id = ?', [$session->get('userID')]);
            $rep = $conn->fetchAssoc('SELECT * FROM reports WHERE id = ?', [$repID]);
        }
        return $this->render('smetric/empl/empl-pubs-reports-view.html.twig', [   // шаблон default/empl/empl-pubs-reports-view
            'Title'         => 'SMetric: Сотрудник:Публикации докладов - просмотр',
            'user'      => $user,
            'rep'       => $rep,
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
     * @Route("/empl/pubs/reports/edit/{repID}", name="sm_empl_pubs_reports_edit")
     */
    public function emplPubsReportsEdit(Request $request, Connection $conn, $repID) {
        $session = $request->getSession();
        $user   =   [];
        $rep    =   [];
        if ($session->get('userID') == 0) {
            $user['empfamily']  =   "Аноним";
            $user['empname']    =   "";
            $user['empsoname']  =   "";
        } else {    // Обработка ответа от формы редактирования
            $user = $conn->fetchAssoc('SELECT * FROM empl WHERE id = ?', [$session->get('userID')]);
            //  Если (вернуться без сохранения)
            if (($request->request->get('formName') == 'EmpRepEdit') &&
                ($request->request->get('btnSubmit') == 'DontSaveEditRep')) {
                //      Показать список всех докладов сотрудника
                $pubs = $conn->fetchAll('SELECT * FROM reports WHERE author = ?', [$session->get('userID')]);
                return $this->render('smetric/empl/empl-pubs-reports.html.twig', [   // шаблон default/empl/empl-pubs-article
                    'Title'         => 'SMetric: Сотрудник:Публикации докладов и тезисов',
                    'user'      => $user,
                    'pubs'      => $pubs,
                    'UserState'     =>  $session->get('userstate'),
                    'UserRole'      =>  $session->get('userrole'),
                    'UserID'        =>  $session->get('userID'),
                    'roleEmpl'      =>  $session->get('roleEmpl'),
                    'roleManager'   =>  $session->get('roleManager'),
                    'roleAnalit'    =>  $session->get('roleAnalit'),
                    'roleAdmin'     =>  $session->get('roleAdmin')
                ]);
            }
            //  Если (вернуться с сохранением)
            if (($request->request->get('formName') == 'EmpRepEdit') &&
                ($request->request->get('btnSubmit') == 'SaveEditRep')) {
                //      Выбрать от ответа введённые параметры
                if ($request->request->get('WoS') == 'on') {
                    $catWoS = 1;
                } else {
                    $catWoS = 0;
                }
                if ($request->request->get('Scopus') == 'on') {
                    $catScopus = 1;
                } else {
                    $catScopus = 0;
                }
                if ($request->request->get('ISSN') == 'on') {
                    $catISSN = 1;
                } else {
                    $catISSN = 0;
                }
                if ($request->request->get('ISBN') == 'on') {
                    $catISBN = 1;
                } else {
                    $catISBN = 0;
                }
                if ($request->request->get('Others') == 'on') {
                    $catOthers = 1;
                } else {
                    $catOthers = 0;
                }
                // Определение типа документа
                if ($request->request->get('reportType') == 'reportFull') {
                    $reptype = 1;
                } else {
                    $reptype = 0;
                }
                //      Обновить запись доклада в базе
                $conn->update('reports', [
                    'allauthors'    =>  $request->request->get('reportAuthors'),
                    'numauthfrc'    =>  $request->request->get('reportAuthFRC'),
                    'title'         =>  $request->request->get('reportTitle'),
                    'reptype'       =>  $reptype,
                    'eventname'     =>  $request->request->get('reportEvent'),
                    'eventplace'    =>  $request->request->get('reportEventPlace'),
                    'eventstartdate'    =>  $request->request->get('reportEventStartDate'),
                    'eventenddate'      =>  $request->request->get('reportEventEndDate'),
                    'magazine'      =>  $request->request->get('reportMagTitle'),
                    'publisher'     =>  $request->request->get('reportPub'),
                    'pubplace'      =>  $request->request->get('reportPubPlace'),
                    'pubyear'       =>  $request->request->get('reportPubYear'),
                    'startpage'     =>  $request->request->get('reportPubPageStart'),
                    'endpage'       =>  $request->request->get('reportPubPageEnd'),
                    'catwos'        =>  $catWoS,
                    'catscopus'     =>  $catScopus,
                    'catissn'       =>  $catISSN,
                    'catisbn'       =>  $catISBN,
                    'catothers'     =>  $catOthers
                ],[
                    'id'    => $repID
                ]);
                //      Выбрать обновленный список докладов
                $pubs = $conn->fetchAll('SELECT * FROM reports WHERE author = ?', [$session->get('userID')]);
                //      Показать новый список докладов
                return $this->render('smetric/empl/empl-pubs-reports.html.twig', [   // шаблон default/empl/empl-pubs-article
                    'Title'         => 'SMetric: Сотрудник:Публикации докладов и тезисов',
                    'user'      => $user,
                    'pubs'      => $pubs,
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
        //  Выбираем параметры доклада для редактирования
        $rep = $conn->fetchAssoc('SELECT * FROM reports WHERE id = ?', [$repID]);
        return $this->render('smetric/empl/empl-pubs-reports-edit.html.twig', [   // шаблон default/empl/empl-pubs-reports-edit
            'Title'         => 'SMetric: Сотрудник:Публикации докладов - редактирование',
            'user'      => $user,
            'rep'       => $rep,
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
     * @Route("/empl/pubs/reports/delete/{repID}", name="sm_empl_pubs_reports_delete")
     */
    public function emplPubsReportsDelete(Request $request, Connection $conn, $repID) {
        $session = $request->getSession();
        $user   = [];
        $rep    = [];
        $pubs   = null;
        if ($session->get('userID') == 0) {
            $user['empfamily']  =   "Аноним";
            $user['empname']    =   "";
            $user['empsoname']  =   "";
        } else {
            $user = $conn->fetchAssoc('SELECT * FROM empl WHERE id = ?', [$session->get('userID')]);
            $rep = $conn->fetchAssoc('SELECT * FROM reports WHERE id = ?', [$repID]);
            // Выбираем список статей, принадлежащих user
            $pubs = $conn->fetchAll('SELECT * FROM reports WHERE author = ?', [$session->get('userID')]);
        }
        // Обработка ответа формы удаления доклада Сотрудником
        //  Если (не удалять)
        if (($request->request->get('formName') == 'EmpRepDelete') &&
            ($request->request->get('btnSubmit') == 'DontDeleteRep')) {
            //      Возврат в форму отображения списка докладов
            return $this->render('smetric/empl/empl-pubs-reports.html.twig', [   // шаблон default/empl/empl-pubs-reports-delete
                'Title'         => 'SMetric: Сотрудник:Публикации докладов - удаление',
                'user'      => $user,
                'pubs'      => $pubs,
                'UserState'     =>  $session->get('userstate'),
                'UserRole'      =>  $session->get('userrole'),
                'UserID'        =>  $session->get('userID'),
                'roleEmpl'      =>  $session->get('roleEmpl'),
                'roleManager'   =>  $session->get('roleManager'),
                'roleAnalit'    =>  $session->get('roleAnalit'),
                'roleAdmin'     =>  $session->get('roleAdmin')
            ]);
        }
        //  Если (удалять)
        if (($request->request->get('formName') == 'EmpRepDelete') &&
            ($request->request->get('btnSubmit') == 'DeleteRep')) {
            //      Удаляем запись о докладе
            $conn->delete('reports', ['id' => $repID]);
            //      Выбираем обновлённый список статей, принадлежащих user
            $pubs = $conn->fetchAll('SELECT * FROM reports WHERE author = ?', [$session->get('userID')]);
            //      Возврат в форму отображения списка докладов
            return $this->render('smetric/empl/empl-pubs-reports.html.twig', [   // шаблон default/empl/empl-pubs-reports-delete
                'Title'         => 'SMetric: Сотрудник:Публикации докладов - удаление',
                'user'      => $user,
                'pubs'      => $pubs,
                'UserState'     =>  $session->get('userstate'),
                'UserRole'      =>  $session->get('userrole'),
                'UserID'        =>  $session->get('userID'),
                'roleEmpl'      =>  $session->get('roleEmpl'),
                'roleManager'   =>  $session->get('roleManager'),
                'roleAnalit'    =>  $session->get('roleAnalit'),
                'roleAdmin'     =>  $session->get('roleAdmin')
            ]);
        }
        // Первичный вывод формы удаления доклада
        return $this->render('smetric/empl/empl-pubs-reports-delete.html.twig', [   // шаблон default/empl/empl-pubs-reports-delete
            'Title'         => 'SMetric: Сотрудник:Публикации докладов - удаление',
            'user'      => $user,
            'rep'       => $rep,
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
     * @Route("/empl/pubs/mono", name="sm_empl_pubs_mono")
     */
    public function emplPubsMono(Request $request, Connection $conn) {
        $session    = $request->getSession();
        $user       = [];
        $pubs       =   [];
        if ($session->get('userID') == 0) {
            $user['empfamily']  =   "Аноним";
            $user['empname']    =   "";
            $user['empsoname']  =   "";
        } else {
            $user = $conn->fetchAssoc('SELECT * FROM empl WHERE id = ?', [$session->get('userID')]);
            // Выбираем список статей, принадлежащих user
            $pubs = $conn->fetchAll('SELECT * FROM monos WHERE author = ?', [$session->get('userID')]);
        }
        return $this->render('smetric/empl/empl-pubs-mono.html.twig', [   // шаблон default/empl/empl-pubs-mono
            'Title'     =>  'SMetric: Сотрудник:Публикации учебников и монографий',
            'user'      =>  $user,
            'pubs'      =>  $pubs,
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
     * @Route("/empl/pubs/mono/new", name="sm_empl_pubs_mono_new")
     */
    public function emplPubsMonoNew(Request $request, Connection $conn) {
        $session = $request->getSession();
        $user = [];
        if ($session->get('userID') == 0) {
            $user['empfamily']  =   "Аноним";
            $user['empname']    =   "";
            $user['empsoname']  =   "";
        } else {
            $user = $conn->fetchAssoc('SELECT * FROM empl WHERE id = ?', [$session->get('userID')]);
        }
        // Обработка формы "Создание новой монографии"
        //  Если (не сохранять)
        if (($request->request->get('formName') == 'EmpMonoNew') &&
            ($request->request->get('btnSubmit') == 'DontSaveNewMono')) {
            //      Выбираем список монографий, принадлежаших user
            $pubs = $conn->fetchAll('SELECT * FROM monos WHERE author = ?', [$session->get('userID')]);
            //      Возврат в форму отображения списка монографий
            return $this->render('smetric/empl/empl-pubs-mono.html.twig', [   // шаблон default/empl/empl-pubs-reports-new
                'Title'         => 'SMetric: Сотрудник:Публикации монографий',
                'user'      => $user,
                'pubs'      => $pubs,
                'UserState'     =>  $session->get('userstate'),
                'UserRole'      =>  $session->get('userrole'),
                'UserID'        =>  $session->get('userID'),
                'roleEmpl'      =>  $session->get('roleEmpl'),
                'roleManager'   =>  $session->get('roleManager'),
                'roleAnalit'    =>  $session->get('roleAnalit'),
                'roleAdmin'     =>  $session->get('roleAdmin')
            ]);
        }
        //  Если (сохранять)
        if (($request->request->get('formName') == 'EmpMonoNew') &&
            ($request->request->get('btnSubmit') == 'SaveNewMono')) {
            //      Вносим в базу новую запись в параметрами монографии
            $conn->insert('monos', [
                'author'        => $session->get('userID'),
                'allauthors'    => $request->request->get('monoAuthors'),
                'numbauthfrc'   => $request->request->get('monoAuthorsFRC'),
                'title'         => $request->request->get('monoTitle'),
                'publisher'     => $request->request->get('monoPub'),
                'pubplace'      => $request->request->get('monoPlacePub'),
                'pubyear'       => $request->request->get('monoYear'),
                'pages'         => $request->request->get('monoPages'),
                'isbn'          => $request->request->get('monoISBN'),
                'circulation'   => $request->request->get('monoCirculation')
            ]);
            //      Выбираем обновлённый список монографий, принадлежащих user
            $pubs = $conn->fetchAll('SELECT * FROM monos WHERE author = ?', [$session->get('userID')]);
            //      Возврат в форму отображения списка монографий
            return $this->render('smetric/empl/empl-pubs-mono.html.twig', [   // шаблон default/empl/empl-pubs-mono
                'Title'         => 'SMetric: Сотрудник:Публикации учебников и монографий - новый',
                'user'      => $user,
                'pubs'      => $pubs,
                'UserState'     =>  $session->get('userstate'),
                'UserRole'      =>  $session->get('userrole'),
                'UserID'        =>  $session->get('userID'),
                'roleEmpl'      =>  $session->get('roleEmpl'),
                'roleManager'   =>  $session->get('roleManager'),
                'roleAnalit'    =>  $session->get('roleAnalit'),
                'roleAdmin'     =>  $session->get('roleAdmin')
            ]);
        }
        //  Первичное отображение формы создания новой монографии
        return $this->render('smetric/empl/empl-pubs-mono-new.html.twig', [   // шаблон default/empl/empl-pubs-mono
            'Title'         => 'SMetric: Сотрудник:Публикации учебников и монографий - новый',
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
     * @Route("/empl/pubs/mono/view/{monoID}", name="sm_empl_pubs_mono_view")
     */
    public function emplPubsMonoView(Request $request, Connection $conn, $monoID) {
        $session = $request->getSession();
        $user = [];
        if ($session->get('userID') == 0) {
            $user['empfamily']  =   "Аноним";
            $user['empname']    =   "";
            $user['empsoname']  =   "";
        } else {
            $user = $conn->fetchAssoc('SELECT * FROM empl WHERE id = ?', [$session->get('userID')]);
            $mono = $conn->fetchAssoc('SELECT * FROM monos WHERE id = ?', [$monoID]);
        }
        return $this->render('smetric/empl/empl-pubs-mono-view.html.twig', [
            'Title'         => 'SMetric: Сотрудник:Просмотр учебника или монографии',
            'user'      => $user,
            'mono'      => $mono,
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
     * @Route("/empl/pubs/mono/edit/{monoID}", name="sm_empl_pubs_mono_edit")
     */
    public function emplPubsMonoEdit(Request $request, Connection $conn, $monoID) {
        $session = $request->getSession();
        $user = [];
        if ($session->get('userID') == 0) {
            $user['empfamily']  =   "Аноним";
            $user['empname']    =   "";
            $user['empsoname']  =   "";
            $mono               = [];
        } else { // Обработка ответа от формы редактирования
            $user = $conn->fetchAssoc('SELECT * FROM empl WHERE id = ?', [$session->get('userID')]);
            $mono = $conn->fetchAssoc('SELECT * FROM monos WHERE id = ?', [$monoID]);
            //  Если (Вернуться БЕЗ сохранения)
            if (($request->request->get('formName') == 'EmpEditMono') &&
                ($request->request->get('btnSubmit') == 'DontEditSaveMono')) {
                //      Показать список всех монографий Сотрудника
                $pubs = $conn->fetchAll('SELECT * FROM monos WHERE author = ?', [$session->get('userID')]);
                return $this->render('smetric/empl/empl-pubs-mono.html.twig', [   // шаблон default/empl/empl-pubs-article
                    'Title'         => 'SMetric: Сотрудник:Публикации учебников и монографий',
                    'user'      => $user,
                    'pubs'      => $pubs,
                    'UserState'     =>  $session->get('userstate'),
                    'UserRole'      =>  $session->get('userrole'),
                    'UserID'        =>  $session->get('userID'),
                    'roleEmpl'      =>  $session->get('roleEmpl'),
                    'roleManager'   =>  $session->get('roleManager'),
                    'roleAnalit'    =>  $session->get('roleAnalit'),
                    'roleAdmin'     =>  $session->get('roleAdmin')
                ]);
            }
            //  Если (Сохранить изменения и вернуться к списку)
            if (($request->request->get('formName') == 'EmpEditMono') &&
                ($request->request->get('btnSubmit') == 'EditSaveMono')) {
                //      Выбрать из ответа введённые параметры
                //      Обновить запись монографии в базе
                $conn->update('monos', [
                    'allauthors'    => $request->request->get('monoAuthors'),
                    'numbauthfrc'   => $request->request->get('monoAuthorsFRC'),
                    'title'         => $request->request->get('monoTitle'),
                    'publisher'     => $request->request->get('monoPub'),
                    'pubplace'      => $request->request->get('monoPlacePub'),
                    'pubyear'       => $request->request->get('monoYear'),
                    'pages'         => $request->request->get('monoPages'),
                    'isbn'          => $request->request->get('monoISBN'),
                    'circulation'   => $request->request->get('monoCirculation')
                ], [
                    'id'    => $monoID
                ]);
                //      Выбрать обновленный список монографий
                $pubs = $conn->fetchAll('SELECT * FROM monos WHERE author = ?', [$session->get('userID')]);
                //      Показать обновленный список монографий
                return $this->render('smetric/empl/empl-pubs-mono.html.twig', [   // шаблон default/empl/empl-pubs-article
                    'Title'         => 'SMetric: Сотрудник:Публикации учебников и монографий',
                    'user'      => $user,
                    'pubs'      => $pubs,
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
        return $this->render('smetric/empl/empl-pubs-mono-edit.html.twig', [
            'Title'         => 'SMetric: Сотрудник:Редактирование учебника или монографии',
            'user'      => $user,
            'mono'      => $mono,
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
     * @Route("/empl/pubs/mono/delete/{monoID}", name="sm_empl_pubs_mono_delete")
     */
    public function emplPubsMonoDelete(Request $request, Connection $conn, $monoID) {
        $session    =   $request->getSession();
        $user       =   [];
        $mono       =   [];
        $pubs       =   null;
        if ($session->get('userID') == 0) {
            $user['empfamily']  =   "Аноним";
            $user['empname']    =   "";
            $user['empsoname']  =   "";
        } else {
            $user = $conn->fetchAssoc('SELECT * FROM empl WHERE id = ?', [$session->get('userID')]);
            $mono = $conn->fetchAssoc('SELECT * FROM monos WHERE id = ?', [$monoID]);
            // Выбираем список монографий, принадлежащих user
            $pubs = $conn->fetchAll('SELECT * FROM monos WHERE author = ?', [$session->get('userID')]);
        }
        //  Обработка ответа формы удаления монографии Сотрудником
        //  Если (НЕ удалять)
        if (($request->request->get('formName') == 'EmpDeleteMono') &&
            ($request->request->get('btnSubmit') == 'DontDeleteMono')) {
            //      Возврат в форму отображения списка монографий
            return $this->render('smetric/empl/empl-pubs-mono.html.twig', [   // шаблон default/empl/empl-pubs-mono
                'Title'         => 'SMetric: Сотрудник:Публикации учебников и монографий',
                'user'      => $user,
                'pubs'      => $pubs,
                'UserState'     =>  $session->get('userstate'),
                'UserRole'      =>  $session->get('userrole'),
                'UserID'        =>  $session->get('userID'),
                'roleEmpl'      =>  $session->get('roleEmpl'),
                'roleManager'   =>  $session->get('roleManager'),
                'roleAnalit'    =>  $session->get('roleAnalit'),
                'roleAdmin'     =>  $session->get('roleAdmin')
            ]);
        }
        //  Если (удалять)
        if (($request->request->get('formName') == 'EmpDeleteMono') &&
            ($request->request->get('btnSubmit') == 'DeleteMono')) {
            //      Удаляем запись о монографии
            $conn->delete('monos', ['id' => $monoID]);
            //      Обновляем список монографий, принадлежащих Сотруднику
            $pubs = $conn->fetchAll('SELECT * FROM monos WHERE author = ?', [$session->get('userID')]);
            //      Возврат в форму отображения списка монографий
            return $this->render('smetric/empl/empl-pubs-mono.html.twig', [   // шаблон default/empl/empl-pubs-mono
                'Title'         => 'SMetric: Сотрудник:Публикации учебников и монографий',
                'user'      => $user,
                'pubs'      => $pubs,
                'UserState'     =>  $session->get('userstate'),
                'UserRole'      =>  $session->get('userrole'),
                'UserID'        =>  $session->get('userID'),
                'roleEmpl'      =>  $session->get('roleEmpl'),
                'roleManager'   =>  $session->get('roleManager'),
                'roleAnalit'    =>  $session->get('roleAnalit'),
                'roleAdmin'     =>  $session->get('roleAdmin')
            ]);
        }

        //  Первичный вывод формы удаления монографии
        return $this->render('smetric/empl/empl-pubs-mono-delete.html.twig', [
            'Title'         => 'SMetric: Сотрудник:Удаление учебника или монографии',
            'user'      => $user,
            'mono'      => $mono,
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
     * @Route("/empl/pubs/chapters", name="sm_empl_pubs_chapters")
     */
    public function emplPubsChapters(Request $request, Connection $conn) {
        $session = $request->getSession();
        $user = [];
        if ($session->get('userID') == 0) {
            $user['empfamily']  =   "Аноним";
            $user['empname']    =   "";
            $user['empsoname']  =   "";
            $pubs               =   [];
        } else {
            $user = $conn->fetchAssoc('SELECT * FROM empl WHERE id = ?', [$session->get('userID')]);
            $pubs = $conn->fetchAll('SELECT * FROM chapters WHERE author = ?', [$session->get('userID')]);
        }
        return $this->render('smetric/empl/empl-pubs-chapters.html.twig', [   // шаблон default/empl/empl-pubs-chapters
            'Title'         => 'SMetric: Сотрудник:Публикации глав учебников и монографий',
            'user'      => $user,
            'pubs'      => $pubs,
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
     * @Route("/empl/pubs/chapters/new", name="sm_empl_pubs_chapters_new")
     */
    public function emplPubsChaptersNew(Request $request, Connection $conn) {
        $session = $request->getSession();
        $user = [];
        if ($session->get('userID') == 0) {
            $user['empfamily']  =   "Аноним";
            $user['empname']    =   "";
            $user['empsoname']  =   "";
        } else {
            $user = $conn->fetchAssoc('SELECT * FROM empl WHERE id = ?', [$session->get('userID')]);
        }
        //  Обработка формы "Создать новую главу монографии"
        //  Если (НЕ сохранять)
        if (($request->request->get('formName') == 'EmpNewChapter') &&
            ($request->request->get('btnSubmit') == 'DontSaveAndReturn')) {
            //      Выбираем список глав, принадлежащих Сотруднику
            $pubs = $conn->fetchAll('SELECT * FROM chapters WHERE author = ?', [$session->get('userID')]);
            //      Возврат в форму отображения списка глав
            return $this->render('smetric/empl/empl-pubs-chapters.html.twig', [   // шаблон default/empl/empl-pubs-chapters
                'Title'         => 'SMetric: Сотрудник:Публикации глав учебников и монографий',
                'user'      => $user,
                'pubs'      => $pubs,
                'UserState'     =>  $session->get('userstate'),
                'UserRole'      =>  $session->get('userrole'),
                'UserID'        =>  $session->get('userID'),
                'roleEmpl'      =>  $session->get('roleEmpl'),
                'roleManager'   =>  $session->get('roleManager'),
                'roleAnalit'    =>  $session->get('roleAnalit'),
                'roleAdmin'     =>  $session->get('roleAdmin')
            ]);
        }
        //  Если (сохранять)
        if (($request->request->get('formName') == 'EmpNewChapter') &&
            ($request->request->get('btnSubmit') == 'SaveNewChapter')) {
            //      Вносим в базу запись о новой главе
            $conn->insert('chapters', [
                'author'        =>  $session->get('userID'),
                'allauthors'    =>  $request->request->get('chaptersAuthor'),
                'titlechapter'  =>  $request->request->get('chaptersChTitle'),
                'titlemono'     =>  $request->request->get('chaptersPubTitle'),
                'editor'        =>  $request->request->get('chaptersEditor'),
                'pubplace'      =>  $request->request->get('chaptersPubPlace'),
                'publisher'     =>  $request->request->get('chaptersPub'),
                'pubyear'       =>  $request->request->get('chaptersYear'),
                'pages'         =>  $request->request->get('chaptersPages')
            ]);
            //      Выбираем обновлённый список глав
            $pubs = $conn->fetchAll('SELECT * FROM chapters WHERE author = ?', [$session->get('userID')]);
            //      Возврат в форму отображения глав
            return $this->render('smetric/empl/empl-pubs-chapters.html.twig', [   // шаблон default/empl/empl-pubs-chapters
                'Title'         => 'SMetric: Сотрудник:Публикации глав учебников и монографий',
                'user'      => $user,
                'pubs'      => $pubs,
                'UserState'     =>  $session->get('userstate'),
                'UserRole'      =>  $session->get('userrole'),
                'UserID'        =>  $session->get('userID'),
                'roleEmpl'      =>  $session->get('roleEmpl'),
                'roleManager'   =>  $session->get('roleManager'),
                'roleAnalit'    =>  $session->get('roleAnalit'),
                'roleAdmin'     =>  $session->get('roleAdmin')
            ]);
        }
        //  Первичное отображение формы создания новой главы
        return $this->render('smetric/empl/empl-pubs-chapters-new.html.twig', [   // шаблон default/empl/empl-pubs-chapters
            'Title'         => 'SMetric: Сотрудник:Публикации глав учебников и монографий',
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
     * @Route("/empl/pubs/chapters/view/{chapterID}", name="sm_empl_pubs_chapters_view")
     */
    public function emplPubsChaptersView(Request $request, Connection $conn, $chapterID) {
        $session = $request->getSession();
        $user = [];
        if ($session->get('userID') == 0) {
            $user['empfamily']  =   "Аноним";
            $user['empname']    =   "";
            $user['empsoname']  =   "";
        } else {
            $user = $conn->fetchAssoc('SELECT * FROM empl WHERE id = ?', [$session->get('userID')]);
            $chapter = $conn->fetchAssoc('SELECT * FROM chapters WHERE id = ?', [$chapterID]);
        }
        return $this->render('smetric/empl/empl-pubs-chapters-view.html.twig', [
            'Title'         => 'SMetric: Сотрудник:Просмотр глав учебников и монографий',
            'user'      => $user,
            'chapter'   => $chapter,
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
     * @Route("/empl/pubs/chapters/edit/{chapterID}", name="sm_empl_pubs_chapters_edit")
     */
    public function emplPubsChaptersEdit(Request $request, Connection $conn, $chapterID) {
        $session = $request->getSession();
        $user = [];
        if ($session->get('userID') == 0) {
            $user['empfamily']  =   "Аноним";
            $user['empname']    =   "";
            $user['empsoname']  =   "";
            $chapter            =   [];
        } else {    // Обработка ответа от формы редактирования
            $user = $conn->fetchAssoc('SELECT * FROM empl WHERE id = ?', [$session->get('userID')]);
            $chapter = $conn->fetchAssoc('SELECT * FROM chapters WHERE id = ?', [$chapterID]);
            //  Если (Вернуться БЕЗ сохранения)
            if (($request->request->get('formName') == 'EmpEditChapter') &&
                ($request->request->get('btnSubmit') == 'DontEditSaveChapter')) {
                //      Показать список всех глав Сотрудника
                $pubs = $conn->fetchAll('SELECT * FROM chapters WHERE author = ?', [$session->get('userID')]);
                return $this->render('smetric/empl/empl-pubs-chapters.html.twig', [   // шаблон default/empl/empl-pubs-chapters
                    'Title'         => 'SMetric: Сотрудник:Публикации глав учебников и монографий',
                    'user'      => $user,
                    'pubs'      => $pubs,
                    'UserState'     =>  $session->get('userstate'),
                    'UserRole'      =>  $session->get('userrole'),
                    'UserID'        =>  $session->get('userID'),
                    'roleEmpl'      =>  $session->get('roleEmpl'),
                    'roleManager'   =>  $session->get('roleManager'),
                    'roleAnalit'    =>  $session->get('roleAnalit'),
                    'roleAdmin'     =>  $session->get('roleAdmin')
                ]);
            }
            //  Если (Сохранить изменения и вернуться к списку глав)
            if (($request->request->get('formName') == 'EmpEditChapter') &&
                ($request->request->get('btnSubmit') == 'EditSaveChapter')) {
                //      Обновить запись о главе в базе
                $conn->update('chapters', [
                    'allauthors'    =>  $request->request->get('chaptersAuthor'),
                    'titlechapter'  =>  $request->request->get('chaptersChTitle'),
                    'titlemono'     =>  $request->request->get('chaptersPubTitle'),
                    'editor'        =>  $request->request->get('chaptersEditor'),
                    'pubplace'      =>  $request->request->get('chaptersPubPlace'),
                    'publisher'     =>  $request->request->get('chaptersPub'),
                    'pubyear'       =>  $request->request->get('chaptersYear'),
                    'pages'         =>  $request->request->get('chaptersPages')
                ], [
                    'id'    => $chapterID
                ]);
                //      Выбрать обновленный список глав
                $pubs = $conn->fetchAll('SELECT * FROM chapters WHERE author = ?', [$session->get('userID')]);
                //      Показать обновленный список глав
                return $this->render('smetric/empl/empl-pubs-chapters.html.twig', [   // шаблон default/empl/empl-pubs-chapters
                    'Title'         => 'SMetric: Сотрудник:Публикации глав учебников и монографий',
                    'user'      => $user,
                    'pubs'      => $pubs,
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
        //  Первичное отображение формы редактирования главы
        return $this->render('smetric/empl/empl-pubs-chapters-edit.html.twig', [
            'Title'         => 'SMetric: Сотрудник:Редактирование глав учебников и монографий',
            'user'      => $user,
            'chapter'   => $chapter,
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
     * @Route("/empl/pubs/chapters/delete/{chapterID}", name="sm_empl_pubs_chapters_delete")
     */
    public function emplPubsChaptersDelete(Request $request, Connection $conn, $chapterID) {
        $session    =   $request->getSession();
        $user       =   [];
        $chapter    =   [];
        $pubs       =   null;
        if ($session->get('userID') == 0) {
            $user['empfamily']  =   "Аноним";
            $user['empname']    =   "";
            $user['empsoname']  =   "";
        } else {
            $user = $conn->fetchAssoc('SELECT * FROM empl WHERE id = ?', [$session->get('userID')]);
            $chapter = $conn->fetchAssoc('SELECT * FROM chapters WHERE id = ?', [$chapterID]);
            // Выбираем список монографий, принадлежащих user
            $pubs = $conn->fetchAll('SELECT * FROM chapters WHERE author = ?', [$session->get('userID')]);
        }
        //  Обработка ответа формы удаления главы монографии Сотрудником
        //  Если (НЕ удалять)
        if (($request->request->get('formName') == 'EmpDeleteChapter') &&
            ($request->request->get('btnSubmit') == 'DontDeleteChapter')) {
            //      Возврат в форму отображения списка глав монографий
            return $this->render('smetric/empl/empl-pubs-chapters.html.twig', [   // шаблон default/empl/empl-pubs-chapters
                'Title'         => 'SMetric: Сотрудник:Публикации глав учебников и монографий',
                'user'      => $user,
                'pubs'      => $pubs,
                'UserState'     =>  $session->get('userstate'),
                'UserRole'      =>  $session->get('userrole'),
                'UserID'        =>  $session->get('userID'),
                'roleEmpl'      =>  $session->get('roleEmpl'),
                'roleManager'   =>  $session->get('roleManager'),
                'roleAnalit'    =>  $session->get('roleAnalit'),
                'roleAdmin'     =>  $session->get('roleAdmin')
            ]);
        }
        //  Если (удалять)
        if (($request->request->get('formName') == 'EmpDeleteChapter') &&
            ($request->request->get('btnSubmit') == 'DeleteChapter')) {
            //      Удаляем запись о главе
            $conn->delete('chapters', ['id' => $chapterID]);
            //      Обновляем список глав
            $pubs = $conn->fetchAll('SELECT * FROM chapters WHERE author = ?', [$session->get('userID')]);
            //      Возврат в форму отображения списка глав
            return $this->render('smetric/empl/empl-pubs-chapters.html.twig', [   // шаблон default/empl/empl-pubs-chapters
                'Title'         => 'SMetric: Сотрудник:Публикации глав учебников и монографий',
                'user'      => $user,
                'pubs'      => $pubs,
                'UserState'     =>  $session->get('userstate'),
                'UserRole'      =>  $session->get('userrole'),
                'UserID'        =>  $session->get('userID'),
                'roleEmpl'      =>  $session->get('roleEmpl'),
                'roleManager'   =>  $session->get('roleManager'),
                'roleAnalit'    =>  $session->get('roleAnalit'),
                'roleAdmin'     =>  $session->get('roleAdmin')
            ]);
        }
        //  Первичный вывод формы удаления главы
        return $this->render('smetric/empl/empl-pubs-chapters-delete.html.twig', [
            'Title'         => 'SMetric: Сотрудник:Удаление глав учебников и монографий',
            'user'      => $user,
            'chapter'   => $chapter,
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
     * @Route("/empl/patents", name="sm_empl_patents")
     */
    public function emplPatents(Request $request, Connection $conn) {
        $session    =   $request->getSession();
        $user       =   [];
        $pubs       =   [];
        if ($session->get('userID') == 0) {
            $user['empfamily']  =   "Аноним";
            $user['empname']    =   "";
            $user['empsoname']  =   "";
        } else {
            $user = $conn->fetchAssoc('SELECT * FROM empl WHERE id = ?', [$session->get('userID')]);
            $pubs = $conn->fetchAll('SELECT * FROM patents WHERE author = ?', [$session->get('userID')]);
        }
        return $this->render('smetric/empl/empl-patents.html.twig', [   // Выводим шаблон default/empl/empl-patents
            'Title'         => 'SMetric: Сотрудник:Объекты интелл.собственности',
            'user'      =>  $user,
            'pubs'      =>  $pubs,
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
     * @Route("/empl/patents/new", name="sm_empl_patents_new")
     */
    public function emplPatentsNew(Request $request, Connection $conn) {
        $session = $request->getSession();
        $user = [];
        if ($session->get('userID') == 0) {
            $user['empfamily']  =   "Аноним";
            $user['empname']    =   "";
            $user['empsoname']  =   "";
        } else {
            $user = $conn->fetchAssoc('SELECT * FROM empl WHERE id = ?', [$session->get('userID')]);
        }
        //  Обработка формы "Создание нового ОИС"
        //  Если (НЕ сохранять)
        if (($request->request->get('formName') == 'EmpPatentNew') &&
            ($request->request->get('btnSubmit') == 'DontSaveNewPat')) {
            //      Выбираем список ОИС Сотрудника
            $pubs = $conn->fetchAll('SELECT * FROM patents WHERE author = ?', [$session->get('userID')]);
            //      Возврат в форму "Список ОИС"
            return $this->render('smetric/empl/empl-patents.html.twig', [   // Выводим шаблон default/empl/empl-patents
                'Title'         => 'SMetric: Сотрудник:Объекты интелл.собственности',
                'user'      =>  $user,
                'pubs'      =>  $pubs,
                'UserState'     =>  $session->get('userstate'),
                'UserRole'      =>  $session->get('userrole'),
                'UserID'        =>  $session->get('userID'),
                'roleEmpl'      =>  $session->get('roleEmpl'),
                'roleManager'   =>  $session->get('roleManager'),
                'roleAnalit'    =>  $session->get('roleAnalit'),
                'roleAdmin'     =>  $session->get('roleAdmin')
            ]);
        }
        //  Если (сохранять)
        if (($request->request->get('formName') == 'EmpPatentNew') &&
            ($request->request->get('btnSubmit') == 'SaveNewPat')) {
            //      Вносим в базу новую запись об ОИС
            $conn->insert('patents', [
                'author'        =>  $session->get('userID'),
                'allauthors'    =>  $request->request->get('patAuthors'),
                'numbauthfrc'   =>  $request->request->get('patAuthorsFRC'),
                'title'         =>  $request->request->get('patTitle'),
                'type'          =>  $request->request->get('patType'),
                'regnum'        =>  $request->request->get('patNumber'),
                'priordate'     =>  $request->request->get('patPriorDate'),
                'regdate'       =>  $request->request->get('patRegDate'),
                'pubnum'        =>  $request->request->get('patNumPub')

            ]);
            //      Выбираем обновлённый список ОИС
            $pubs = $conn->fetchAll('SELECT * FROM patents WHERE author = ?', [$session->get('userID')]);
            //      Возврат в форму "Список ОИС"
            return $this->render('smetric/empl/empl-patents.html.twig', [   // Выводим шаблон default/empl/empl-patents
                'Title'         => 'SMetric: Сотрудник:Объекты интелл.собственности',
                'user'      =>  $user,
                'pubs'      =>  $pubs,
                'UserState'     =>  $session->get('userstate'),
                'UserRole'      =>  $session->get('userrole'),
                'UserID'        =>  $session->get('userID'),
                'roleEmpl'      =>  $session->get('roleEmpl'),
                'roleManager'   =>  $session->get('roleManager'),
                'roleAnalit'    =>  $session->get('roleAnalit'),
                'roleAdmin'     =>  $session->get('roleAdmin')
            ]);
        }
        //  Первичное отображение формы "Новый ОИС"
        $ptypes =   $conn->fetchAll('SELECT * FROM patentstype ORDER BY id');
        return $this->render('smetric/empl/empl-patents-new.html.twig', [   // Выводим шаблон default/empl/empl-patents-new
            'Title'         => 'SMetric: Сотрудник:ОИС - новый',
            'user'      =>  $user,
            'ptypes'    =>  $ptypes,
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
     * @Route("/empl/patents/view/{oipID}", name="sm_empl_patents_view")
     */
    public function emplPatentsView(Request $request, Connection $conn, $oipID) {
        $session = $request->getSession();
        $user = [];
        if ($session->get('userID') == 0) {
            $user['empfamily']  =   "Аноним";
            $user['empname']    =   "";
            $user['empsoname']  =   "";
        } else {
            $user = $conn->fetchAssoc('SELECT * FROM empl WHERE id = ?', [$session->get('userID')]);
            $oip = $conn->fetchAssoc('SELECT * FROM patents WHERE id = ?', [$oipID]);
        }
        $pdesc =   $conn->fetchColumn("SELECT description FROM patentstype WHERE id =".$oip['type']);
        return $this->render('smetric/empl/empl-patents-view.html.twig', [
            'Title'         => 'SMetric: Сотрудник:ОИС - просмотр',
            'user'      =>  $user,
            'oip'       =>  $oip,
            'pdesc'     =>  $pdesc,
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
     * @Route("/empl/patents/edit/{oipID}", name="sm_empl_patents_edit")
     */
    public function emplPatentsEdit(Request $request, Connection $conn, $oipID) {
        $session    =   $request->getSession();
        $user       =   [];
        $oip        =   [];
        if ($session->get('userID') == 0) {
            $user['empfamily']  =   "Аноним";
            $user['empname']    =   "";
            $user['empsoname']  =   "";
        } else {    // Обработка ответа от формы редактирования ОИС
            $user = $conn->fetchAssoc('SELECT * FROM empl WHERE id = ?', [$session->get('userID')]);
            //  Если (вернуться БЕЗ сохранения)
            if (($request->request->get('formName') == 'EmpPatentEdit') &&
                ($request->request->get('btnSubmit') == 'DontSaveEditPatent')) {
                //      Показать список всех ОИС Сотрудника
                //      Выбираем обновлённый список ОИС
                $pubs = $conn->fetchAll('SELECT * FROM patents WHERE author = ?', [$session->get('userID')]);
                //      Возврат в форму "Список ОИС"
                return $this->render('smetric/empl/empl-patents.html.twig', [   // Выводим шаблон default/empl/empl-patents
                    'Title'         => 'SMetric: Сотрудник:Объекты интелл.собственности',
                    'user'      =>  $user,
                    'pubs'      =>  $pubs,
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
        //  Если (вернуться с сохранением)
        if (($request->request->get('formName') == 'EmpPatentEdit') &&
            ($request->request->get('btnSubmit') == 'SaveEditPatent')) {
            //      Выбираем из ответа введённые параметры ОИС
            //      Определяем заданный тип ОИС
            //      Обновляем запись ОИС в базе
            $conn->update('patents',[
                'allauthors'    =>  $request->request->get('patAuthors'),
                'numbauthfrc'   =>  $request->request->get('patAuthorsFRC'),
                'title'         =>  $request->request->get('patTitle'),
                'type'          =>  $request->request->get('patType'),
                'regnum'        =>  $request->request->get('patNumber'),
                'priordate'     =>  $request->request->get('patPriorDate'),
                'regdate'       =>  $request->request->get('patRegDate'),
                'pubnum'        =>  $request->request->get('patNumPub')
            ],[
                    'id'    =>  $oipID
                ]
            );
            //      Выбираем обновлённый список ОИС
            $pubs = $conn->fetchAll('SELECT * FROM patents WHERE author = ?', [$session->get('userID')]);
            //      Показываем обновлённый список ОИС
            return $this->render('smetric/empl/empl-patents.html.twig', [   // Выводим шаблон default/empl/empl-patents
                'Title'         => 'SMetric: Сотрудник:Объекты интелл.собственности',
                'user'      =>  $user,
                'pubs'      =>  $pubs,
                'UserState'     =>  $session->get('userstate'),
                'UserRole'      =>  $session->get('userrole'),
                'UserID'        =>  $session->get('userID'),
                'roleEmpl'      =>  $session->get('roleEmpl'),
                'roleManager'   =>  $session->get('roleManager'),
                'roleAnalit'    =>  $session->get('roleAnalit'),
                'roleAdmin'     =>  $session->get('roleAdmin')
            ]);
        }
        //  Выбираем параметры ОИС для редактирования
        $oip = $conn->fetchAssoc('SELECT * FROM patents WHERE id = ?', [$oipID]);
        //  Формируем список типов ОИС для передачи в форму редактирования
        $ptypes =   $conn->fetchAll('SELECT * FROM patentstype ORDER BY id');
        //  Первичное отображение формы редактирования ОИС
        return $this->render('smetric/empl/empl-patents-edit.html.twig', [
            'Title'     =>  'SMetric: Сотрудник:ОИС - редактирование',
            'user'      =>  $user,
            'oip'       =>  $oip,
            'ptypes'    =>  $ptypes,
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
     * @Route("/empl/patents/delete/{oipID}", name="sm_empl_patents_delete")
     */
    public function emplPatentsDelete(Request $request, Connection $conn, $oipID) {
        $session    =   $request->getSession();
        $user       =   [];
        $oip        =   [];
        if ($session->get('userID') == 0) {
            $user['empfamily']  =   "Аноним";
            $user['empname']    =   "";
            $user['empsoname']  =   "";
        } else {
            $user = $conn->fetchAssoc('SELECT * FROM empl WHERE id = ?', [$session->get('userID')]);
            $oip = $conn->fetchAssoc('SELECT * FROM patents WHERE id = ?', [$oipID]);
            // Выбираем список статей, принадлежащих user
            $pubs = $conn->fetchAll('SELECT * FROM patents WHERE author = ?', [$session->get('userID')]);
        }
        // Обработка ответа формы удаления ОИС Сотрудником
        //  Если (НЕ удалять)
        if (($request->request->get('formName') == 'EmpPatentDelete') &&
            ($request->request->get('btnSubmit') == 'DontDeletePatent')) {
            //      Возврат в форму отображения списка ОИС
            return $this->render('smetric/empl/empl-patents.html.twig', [   // Выводим шаблон default/empl/empl-patents
                'Title'         => 'SMetric: Сотрудник:Объекты интелл.собственности',
                'user'      =>  $user,
                'pubs'      =>  $pubs,
                'UserState'     =>  $session->get('userstate'),
                'UserRole'      =>  $session->get('userrole'),
                'UserID'        =>  $session->get('userID'),
                'roleEmpl'      =>  $session->get('roleEmpl'),
                'roleManager'   =>  $session->get('roleManager'),
                'roleAnalit'    =>  $session->get('roleAnalit'),
                'roleAdmin'     =>  $session->get('roleAdmin')
            ]);
        }
        //  Если (удалять)
        if (($request->request->get('formName') == 'EmpPatentDelete') &&
            ($request->request->get('btnSubmit') == 'DeletePatent')) {
            //      Удаляем запись об ОИС
            $conn->delete('patents', ['id' => $oipID]);
            //      Выбираем обновлённый список ОИС, принадлежащих Сотруднику
            $pubs = $conn->fetchAll('SELECT * FROM patents WHERE author = ?', [$session->get('userID')]);
            //      Возврат в форму отображения списка ОИС
            return $this->render('smetric/empl/empl-patents.html.twig', [   // Выводим шаблон default/empl/empl-patents
                'Title'         => 'SMetric: Сотрудник:Объекты интелл.собственности',
                'user'      =>  $user,
                'pubs'      =>  $pubs,
                'UserState'     =>  $session->get('userstate'),
                'UserRole'      =>  $session->get('userrole'),
                'UserID'        =>  $session->get('userID'),
                'roleEmpl'      =>  $session->get('roleEmpl'),
                'roleManager'   =>  $session->get('roleManager'),
                'roleAnalit'    =>  $session->get('roleAnalit'),
                'roleAdmin'     =>  $session->get('roleAdmin')
            ]);
        }
        //  Выбор описания типа ОИС для заданного экземпляря ОИС
        $pdesc =   $conn->fetchColumn("SELECT description FROM patentstype WHERE id =".$oip['type']);
        //  Первичный вывод формы удаления ОИС
        return $this->render('smetric/empl/empl-patents-delete.html.twig', [
            'Title'         => 'SMetric: Сотрудник:ОИС - удаление',
            'user'      =>  $user,
            'oip'       =>  $oip,
            'pdesc'     =>  $pdesc,
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
     * @Route("/empl/sciment", name="sm_empl_sciment")
     */
    public function emplSciment(Request $request, Connection $conn) {
        $session    =   $request->getSession();
        $user       =   [];
        $pubs       =   [];
        if ($session->get('userID') == 0) {
            $user['empfamily']  =   "Аноним";
            $user['empname']    =   "";
            $user['empsoname']  =   "";
            $pubs               =   null;
        } else {
            $user = $conn->fetchAssoc('SELECT * FROM empl WHERE id = ?', [$session->get('userID')]);
            // Выбираем список активностей по подготовке, принадлежащих user
            $pubs = $conn->fetchAll('SELECT * FROM sciment WHERE personalid = ?', [$session->get('userID')]);
            $scitypes   = $conn->fetchAll('SELECT * FROM scitype');
            $scistatus  = $conn->fetchAll('SELECT * FROM scistatus');
        }
        return $this->render('smetric/empl/empl-sciment.html.twig', [   // Выводим шаблон default/empl/empl-sciment
            'Title'         => 'SMetric: Сотрудник:Подготовка научных кадров',
            'user'      =>  $user,
            'pubs'      =>  $pubs,
            'scitypes'  =>  $scitypes,
            'scistatus' =>  $scistatus,
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
     * @Route("/empl/sciment/new", name="sm_empl_sciment_new")
     */
    public function emplScimentNew(Request $request, Connection $conn) {
        $session = $request->getSession();
        $user = [];
        if ($session->get('userID') == 0) {
            $user['empfamily']  =   "Аноним";
            $user['empname']    =   "";
            $user['empsoname']  =   "";
        } else {
            $user = $conn->fetchAssoc('SELECT * FROM empl WHERE id = ?', [$session->get('userID')]);
            $pubs = $conn->fetchAll('SELECT * FROM sciment WHERE personalid = ?', [$session->get('userID')]);
            $scitypes   = $conn->fetchAll('SELECT * FROM scitype');
            $scistatus  = $conn->fetchAll('SELECT * FROM scistatus ORDER BY id DESC');
            $discouncil = $conn->fetchAll('SELECT * FROM discouncil ORDER BY number');
            $speciality = $conn->fetchAll('SELECT * FROM speciality');
        }
        //  Если (НЕ сохранять)
        if (($request->request->get('formName') == 'NewSci') &&
            ($request->request->get('btnSubmit') == 'DontSaveNewSci')) {
            //      Вернуться к отображению списка активностей
            return $this->render('smetric/empl/empl-sciment.html.twig', [   // Выводим шаблон default/empl/empl-sciment
                'Title'         => 'SMetric: Сотрудник:Подготовка научных кадров',
                'user'      =>  $user,
                'pubs'      =>  $pubs,
                'scitypes'  =>  $scitypes,
                'scistatus' =>  $scistatus,
                'UserState'     =>  $session->get('userstate'),
                'UserRole'      =>  $session->get('userrole'),
                'UserID'        =>  $session->get('userID'),
                'roleEmpl'      =>  $session->get('roleEmpl'),
                'roleManager'   =>  $session->get('roleManager'),
                'roleAnalit'    =>  $session->get('roleAnalit'),
                'roleAdmin'     =>  $session->get('roleAdmin')
            ]);
        }
        //  Если (сохранить)
        if (($request->request->get('formName') == 'NewSci') &&
            ($request->request->get('btnSubmit') == 'SaveNewSci')) {
            //      Добавить новую запись в базу
            if ($request->request->get('sciType') == null) {
                $sciType = 4;
            } else {
                $sciType = $request->request->get('sciType');
            }
            if (($request->request->get('RegDate')) == null) {
                $regDate = date("d-m-Y");
            } else {
                $regDate = $request->request->get('RegDate');
            }
            if (($request->request->get('disDefDate')) == null) {
                $defDate = date("d-m-Y");
            } else {
                $defDate = $request->request->get('disDefDate');
            }
            if (($request->request->get('disVAKDate')) == null) {
                $vakDate = date("d-m-Y");
            } else {
                $vakDate = $request->request->get('disVAKDate');
            }
            $conn->insert('sciment', [
                'personalid'    =>  $session->get('userID'),
                'scitype'       =>  $sciType,
                'regdate'       =>  $regDate,
                'disauthor'     =>  $request->request->get('disFIO'),
                'distitle'      =>  $request->request->get('disTitle'),
                'disstatus'     =>  $request->request->get('Status'),
                'disspeciality' =>  $request->request->get('Speciality'),
                'discouncil'    =>  $request->request->get('disCouncil'),
                'disdefdate'    =>  $defDate,
                'disvakdate'    =>  $vakDate
            ]);
            //      Обновить список активностей
            $pubs = $conn->fetchAll('SELECT * FROM sciment WHERE personalid = ?', [$session->get('userID')]);
            //      Вернуться к отображению списка активностей
            return $this->render('smetric/empl/empl-sciment.html.twig', [   // Выводим шаблон default/empl/empl-sciment
                'Title'         => 'SMetric: Сотрудник:Подготовка научных кадров',
                'user'      =>  $user,
                'pubs'      =>  $pubs,
                'scitypes'  =>  $scitypes,
                'scistatus' =>  $scistatus,
                'UserState'     =>  $session->get('userstate'),
                'UserRole'      =>  $session->get('userrole'),
                'UserID'        =>  $session->get('userID'),
                'roleEmpl'      =>  $session->get('roleEmpl'),
                'roleManager'   =>  $session->get('roleManager'),
                'roleAnalit'    =>  $session->get('roleAnalit'),
                'roleAdmin'     =>  $session->get('roleAdmin')
            ]);
        }
        //  Первичное отображение формы ввода новой активности
        return $this->render('smetric/empl/empl-sciment-new.html.twig', [   // Выводим шаблон default/empl/empl-sciment-new
            'Title'     =>  'SMetric: Сотрудник:Подготовка научных кадров - новая',
            'user'      =>  $user,
            'scitypes'  =>  $scitypes,
            'scistatus' =>  $scistatus,
            'discouncil'    =>  $discouncil,
            'speciality'    =>  $speciality,
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
     * @Route("/empl/sciment/view/{sciID}", name="sm_empl_sciment_view")
     */
    public function emplScimentView(Request $request, Connection $conn, $sciID) {
        $session = $request->getSession();
        $user = [];
        if ($session->get('userID') == 0) {
            $user['empfamily']  =   "Аноним";
            $user['empname']    =   "";
            $user['empsoname']  =   "";
        } else {
            $user = $conn->fetchAssoc('SELECT * FROM empl WHERE id = ?', [$session->get('userID')]);
            $scitypes   = $conn->fetchAll('SELECT * FROM scitype');
            $scistatus  = $conn->fetchAll('SELECT * FROM scistatus ORDER BY id DESC');
            $discouncil = $conn->fetchAll('SELECT * FROM discouncil ORDER BY number');
            $speciality = $conn->fetchAll('SELECT * FROM speciality');
        }
        // Если (возврат из просмотра Активности к списку всех Активностей)
        if (($request->request->get('formName') == 'SciView') &&
            ($request->request->get('btnSubmit') == 'ViewSciList')) {
            //      Выводим форму со списком активностей Сотрудника
            $pubs = $conn->fetchAll('SELECT * FROM sciment WHERE personalid = ?', [$session->get('userID')]);
            return $this->render('smetric/empl/empl-sciment.html.twig', [   // Выводим шаблон default/empl/empl-sciment
                'Title'         => 'SMetric: Сотрудник:Подготовка научных кадров',
                'user'      =>  $user,
                'pubs'      =>  $pubs,
                'scitypes'  =>  $scitypes,
                'scistatus' =>  $scistatus,
                'UserState'     =>  $session->get('userstate'),
                'UserRole'      =>  $session->get('userrole'),
                'UserID'        =>  $session->get('userID'),
                'roleEmpl'      =>  $session->get('roleEmpl'),
                'roleManager'   =>  $session->get('roleManager'),
                'roleAnalit'    =>  $session->get('roleAnalit'),
                'roleAdmin'     =>  $session->get('roleAdmin')
            ]);
        }
        //  Выбираем параметры ОИС для просмотра
        $sci = $conn->fetchAssoc('SELECT * FROM sciment WHERE id = ?', [$sciID]);
        //  Первичное отображение формы просмотра Активности
        return $this->render('smetric/empl/empl-sciment-view.html.twig', [
            'Title'         => 'SMetric: Сотрудник:Подготовка научных кадров - просмотр',
            'user'      =>  $user,
            'sci'       =>  $sci,
            'scitypes'  =>  $scitypes,
            'scistatus' =>  $scistatus,
            'discouncil'    =>  $discouncil,
            'speciality'    =>  $speciality,
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
     * @Route("/empl/sciment/edit/{sciID}", name="sm_empl_sciment_edit")
     */
    public function emplScimentEdit(Request $request, Connection $conn, $sciID) {
        $session    =   $request->getSession();
        $user       =   [];
        $sci        =   [];
        if ($session->get('userID') == 0) {
            $user['empfamily']  =   "Аноним";
            $user['empname']    =   "";
            $user['empsoname']  =   "";
        } else {
            $user = $conn->fetchAssoc('SELECT * FROM empl WHERE id = ?', [$session->get('userID')]);
            $scitypes   = $conn->fetchAll('SELECT * FROM scitype');
            $scistatus  = $conn->fetchAll('SELECT * FROM scistatus ORDER BY id DESC');
            $discouncil = $conn->fetchAll('SELECT * FROM discouncil ORDER BY number');
            $speciality = $conn->fetchAll('SELECT * FROM speciality');
        }
        //  Если (НЕ сохранять)
        if (($request->request->get('formName') == 'SciEdit') &&
            ($request->request->get('btnSubmit') == 'DontSaveSci')) {
            //      Выводим форму со списком активностей Сотрудника
            $pubs = $conn->fetchAll('SELECT * FROM sciment WHERE personalid = ?', [$session->get('userID')]);
            return $this->render('smetric/empl/empl-sciment.html.twig', [   // Выводим шаблон default/empl/empl-sciment
                'Title'         => 'SMetric: Сотрудник:Подготовка научных кадров',
                'user'      =>  $user,
                'pubs'      =>  $pubs,
                'scitypes'  =>  $scitypes,
                'scistatus' =>  $scistatus,
                'UserState'     =>  $session->get('userstate'),
                'UserRole'      =>  $session->get('userrole'),
                'UserID'        =>  $session->get('userID'),
                'roleEmpl'      =>  $session->get('roleEmpl'),
                'roleManager'   =>  $session->get('roleManager'),
                'roleAnalit'    =>  $session->get('roleAnalit'),
                'roleAdmin'     =>  $session->get('roleAdmin')
            ]);
        }
        //  Если (сохранить)
        if (($request->request->get('formName') == 'SciEdit') &&
            ($request->request->get('btnSubmit') == 'SaveSci')) {
            //      Обновляем запись активности в базе
            $conn->update('sciment', [
                'scitype'       =>  $request->request->get('sciType'),
                'regdate'       =>  $request->request->get('RegDate'),
                'disauthor'     =>  $request->request->get('disFIO'),
                'distitle'      =>  $request->request->get('disTitle'),
                'disstatus'     =>  $request->request->get('Status'),
                'discouncil'    =>  $request->request->get('disCouncil'),
                'disspeciality' =>  $request->request->get('Speciality'),
                'disdefdate'    =>  $request->request->get('disDefDate'),
                'disvakdate'    =>  $request->request->get('disVAKDate'),
            ],[
                'id'    => $sciID
            ]);
            //      Обновляем список активностей Сотрудника
            $pubs = $conn->fetchAll('SELECT * FROM sciment WHERE personalid = ?', [$session->get('userID')]);
            //      Выводим форму со списком активностей Сотрудника
            return $this->render('smetric/empl/empl-sciment.html.twig', [   // Выводим шаблон default/empl/empl-sciment
                'Title'         => 'SMetric: Сотрудник:Подготовка научных кадров',
                'user'      =>  $user,
                'pubs'      =>  $pubs,
                'scitypes'  =>  $scitypes,
                'scistatus' =>  $scistatus,
                'UserState'     =>  $session->get('userstate'),
                'UserRole'      =>  $session->get('userrole'),
                'UserID'        =>  $session->get('userID'),
                'roleEmpl'      =>  $session->get('roleEmpl'),
                'roleManager'   =>  $session->get('roleManager'),
                'roleAnalit'    =>  $session->get('roleAnalit'),
                'roleAdmin'     =>  $session->get('roleAdmin')
            ]);
        }
        //  Выбираем параметры ОИС для редактирования
        $sci = $conn->fetchAssoc('SELECT * FROM sciment WHERE id = ?', [$sciID]);
        //  Первичное отображение формы редактирование Активности
        return $this->render('smetric/empl/empl-sciment-edit.html.twig', [
            'Title'         => 'SMetric: Сотрудник:Подготовка научных кадров - новая',
            'user'      =>  $user,
            'sci'       =>  $sci,
            'scitypes'  =>  $scitypes,
            'scistatus' =>  $scistatus,
            'discouncil'    =>  $discouncil,
            'speciality'    =>  $speciality,
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
     * @Route("/empl/sciment/delete/{sciID}", name="sm_empl_sciment_delete")
     */
    public function emplScimentDelete(Request $request, Connection $conn, $sciID) {
        $session = $request->getSession();
        $user = [];
        if ($session->get('userID') == 0) {
            $user['empfamily']  =   "Аноним";
            $user['empname']    =   "";
            $user['empsoname']  =   "";
        } else {
            $user = $conn->fetchAssoc('SELECT * FROM empl WHERE id = ?', [$session->get('userID')]);
            $scitypes   = $conn->fetchAll('SELECT * FROM scitype');
            $scistatus  = $conn->fetchAll('SELECT * FROM scistatus ORDER BY id DESC');
            $discouncil = $conn->fetchAll('SELECT * FROM discouncil ORDER BY number');
            $speciality = $conn->fetchAll('SELECT * FROM speciality');
        }
        //  Если (НЕ удалять)
        if (($request->request->get('formName') == 'SciDelete') &&
            ($request->request->get('btnSubmit') == 'SciDontDeleteReturn')) {
            //      Выводим форму со списком активностей Сотрудника
            $pubs = $conn->fetchAll('SELECT * FROM sciment WHERE personalid = ?', [$session->get('userID')]);
            return $this->render('smetric/empl/empl-sciment.html.twig', [   // Выводим шаблон default/empl/empl-sciment
                'Title'         => 'SMetric: Сотрудник:Подготовка научных кадров',
                'user'      =>  $user,
                'pubs'      =>  $pubs,
                'scitypes'  =>  $scitypes,
                'scistatus' =>  $scistatus,
                'UserState'     =>  $session->get('userstate'),
                'UserRole'      =>  $session->get('userrole'),
                'UserID'        =>  $session->get('userID'),
                'roleEmpl'      =>  $session->get('roleEmpl'),
                'roleManager'   =>  $session->get('roleManager'),
                'roleAnalit'    =>  $session->get('roleAnalit'),
                'roleAdmin'     =>  $session->get('roleAdmin')
            ]);
        }
        //  Если (удалить)
        if (($request->request->get('formName') == 'SciDelete') &&
            ($request->request->get('btnSubmit') == 'SciDeleteReturn')) {
            //      Удалить запись об Активности из базы
            $conn->delete('sciment',['id' => $sciID]);
            //      Обновить список Активностей
            $pubs = $conn->fetchAll('SELECT * FROM sciment WHERE personalid = ?', [$session->get('userID')]);
            //      Вернуться в просмотр списка Активностей
            return $this->render('smetric/empl/empl-sciment.html.twig', [   // Выводим шаблон default/empl/empl-sciment
                'Title'         => 'SMetric: Сотрудник:Подготовка научных кадров',
                'user'      =>  $user,
                'pubs'      =>  $pubs,
                'scitypes'  =>  $scitypes,
                'scistatus' =>  $scistatus,
                'UserState'     =>  $session->get('userstate'),
                'UserRole'      =>  $session->get('userrole'),
                'UserID'        =>  $session->get('userID'),
                'roleEmpl'      =>  $session->get('roleEmpl'),
                'roleManager'   =>  $session->get('roleManager'),
                'roleAnalit'    =>  $session->get('roleAnalit'),
                'roleAdmin'     =>  $session->get('roleAdmin')
            ]);
        }
        //  Выбираем параметры ОИС для удаление
        $sci = $conn->fetchAssoc('SELECT * FROM sciment WHERE id = ?', [$sciID]);
        //  Первичное отображение формы удаления Активности
        return $this->render('smetric/empl/empl-sciment-delete.html.twig', [
            'Title'     =>  'SMetric: Сотрудник:Подготовка научных кадров - удалить',
            'user'      =>  $user,
            'sci'       =>  $sci,
            'scitypes'  =>  $scitypes,
            'scistatus' =>  $scistatus,
            'discouncil'    =>  $discouncil,
            'speciality'    =>  $speciality,
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
     * @Route("/empl/reports", name="sm_empl_reports")
     */
    public function emplReports(Request $request, Connection $conn) {
        $session = $request->getSession();
        $user = [];
        if ($session->get('userID') == 0) {
            $user['empfamily']  =   "Аноним";
            $user['empname']    =   "";
            $user['empsoname']  =   "";
        } else {
            $user = $conn->fetchAssoc('SELECT * FROM empl WHERE id = ?', [$session->get('userID')]);
        }
        return $this->render('smetric/empl/empl-reports.html.twig', [   // Выводим шаблон default/empl/empl-reports
            'Title'         => 'SMetric: Сотрудник:Отчёты',
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
     * @Route("/empl/rating", name="sm_empl_rating")
     */
    public function emplRating(Request $request, Connection $conn) {
        $session = $request->getSession();
        $user = [];
        if ($session->get('userID') == 0) {
            $user['empfamily']  =   "Аноним";
            $user['empname']    =   "";
            $user['empsoname']  =   "";
        } else {
            $user = $conn->fetchAssoc('SELECT * FROM empl WHERE id = ?', [$session->get('userID')]);
        }
        // Расчёт рейтинга по всем объектам сотрудника user
        //
        $rating = 0;    // Начальное обнуление рейтинга
        //
        //  Расчет вклада в рейтинг Статей
        //
        // Выбрать из базы таблицу всех статей сотрудника user
        $pubs = $conn->fetchAll('SELECT * FROM articles WHERE author = ?', [$session->get('userID')]);
        // Цикл по всем статьям - пока не закончатся
        for ($i = 0; $i < count($pubs); $i++) {
            $r = 0;
            $r1 = 0;
            $r2 = 0;
            $r3 = 0;
            //  Анализ рейтинга статьи
            if ($pubs[$i]['catwoscore'] == true || $pubs[$i]['catscopus'] == true) {
                $r1 = 40;
            }
            if ($pubs[$i]['catwosrsci'] == true && $pubs[$i]['catscopus'] == false) {
                $r2 = 30;
            }
            if ($pubs[$i]['catvak'] == true || $pubs[$i]['catrinc'] == true) {
                $r3 = 20;
            }
            $r = max($r1, $r2, $r3);
            if ($pubs[$i]['numauthfrc'] > 4 ) {
                $an = 4;
            } else {
                $an = $pubs[$i]['numauthfrc'];
            }
            $r = round($r/$an,0,PHP_ROUND_HALF_UP);
            if ($pubs[$i]['fintype'] == 4) {
                $rating = $rating + $r;
            }
        }

        //
        //  Расчёт вклада в рейтинг Докладов/Тезисов
        //
        // Выбрать из базы таблицу всех Докладов и Тезисов сотрудника user
        $pubs = $conn->fetchAll('SELECT * FROM reports WHERE (author = ? AND reptype != FALSE)', [$session->get('userID')]);
        // Цикл по всем докладам - пока не закончатся
        for ($i = 0; $i < count($pubs); $i++) {
            $r = 0;
            $r1 = 0;
            $r2 = 0;
            //  Анализ рейтинга статьи
            if ($pubs[$i]['catwos'] == true || $pubs[$i]['catscopus'] == true) {
                $r1 = 40;
            }
            if ($pubs[$i]['catissn'] == true || $pubs[$i]['catisbn'] == true) {
                $r2 = 15;
            }
            $r = max($r1, $r2);
            if ($pubs[$i]['numauthfrc'] > 4 ) {
                $an = 4;
            } else {
                $an = $pubs[$i]['numauthfrc'];
            }
            $r = round($r/$an,0,PHP_ROUND_HALF_UP);
            $rating = $rating + $r;
        }

        //
        // Расчёт вклада в рейтинг Учебников/Пособий/Монографий
        //
        $pubs = $conn->fetchAll('SELECT * FROM monos WHERE (author = ? AND isbn > 0 AND circulation > 299)', [$session->get('userID')]);
        for ($i = 0; $i < count($pubs); $i++) {
            if ($pubs[$i]['numbauthfrc'] > 4 ) {
                $an = 4;
            } else {
                $an = $pubs[$i]['numbauthfrc'];
            }
            $rating = $rating + round(20/$an, 0, PHP_ROUND_HALF_UP);
        }

        //
        //  Расчёт вклада в рейтинг Глав Учебников/Пособий/Монографий
        //      в н.в. не предусмотрен

        //
        // Расчёт вклада в рейтинг Объектов интеллектуальной собственности
        //
        $pubs = $conn->fetchAll('SELECT * FROM patents WHERE author = ?', [$session->get('userID')]);
        for ($i = 0; $i < count($pubs); $i++) {
            $r = 0;
            if ($pubs[$i]['type'] == 1) {
                $r = 50;
            } elseif ($pubs[$i]['type'] == 2) {
                $r = 30;
            } elseif ($pubs[$i]['type'] == 3) {
                $r = 20;
            } elseif ($pubs[$i]['type'] == 4) {
                $r = 20;
            }
            if ($pubs[$i]['numbauthfrc'] > 4 ) {
                $an = 4;
            } else {
                $an = $pubs[$i]['numbauthfrc'];
            }
            $r = round($r/$an,0,PHP_ROUND_HALF_UP);
            $rating = $rating + $r;
        }
        //
        //TODO  Расчёт вклада в рейтинг Подготовки научных кадров
        //
        $pubs = $conn->fetchAll('SELECT * FROM sciment WHERE personalid = ?', [$session->get('userID')]);
        for ($i = 0; $i < count($pubs); $i++) {
            if ($pubs[$i]['scitype'] == 1) { // Защита докторской диссертации
                $rating = $rating + 80;
            }
            if ($pubs[$i]['scitype'] == 2) { // Защита кандидатской диссертации
                $rating = $rating + 40;
            }
            if ($pubs[$i]['scitype'] == 3) { // Консультирование доктора
                $rating = $rating + 40;
            }
            if ($pubs[$i]['scitype'] == 4) { // Консультирование кандидата
                $rating = $rating + 20;
            }
        }

//  Фактор "молодой учёный"
        $bday = $user['empbday'];
        $bday_ts = strtotime($user['empbday']);
        $age = time() - $bday_ts;
        $age = floor($age / 31536000);
        if ($age <= 39) {
            $rating = round($rating*1.5, 0, PHP_ROUND_HALF_UP);
        }
        return $this->render('smetric/empl/empl-rating.html.twig', [   // Выводим шаблон default/empl/empl-rating
            'Title'         => 'SMetric: Сотрудник:Рейтинг',
            'user'      => $user,
            'empR'      => $rating,
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