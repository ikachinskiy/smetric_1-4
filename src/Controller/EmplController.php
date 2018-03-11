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
    public function emplPubsReports() {

    }

    /**
     * @Route("/empl/pubs/reports/new", name="sm_empl_pubs_reports_new")
     */
    public function emplPubsReportsNew() {

    }

    /**
     * @Route("/empl/pubs/reports/view/{repID}", name="sm_empl_pubs_reports_view")
     */
    public function emplPubsReportsView() {

    }

    /**
     * @Route("/empl/pubs/reports/edit/{repID}", name="sm_empl_pubs_reports_edit")
     */
    public function emplPubsReportsEdit() {

    }

    /**
     * @Route("/empl/pubs/reports/delete/{repID}", name="sm_empl_pubs_reports_delete")
     */
    public function emplPubsReportsDelete() {

    }

    /**
     * @Route("/empl/pubs/mono", name="sm_empl_pubs_mono")
     */
    public function emplPubsMono() {

    }

    /**
     * @Route("/empl/pubs/mono/new", name="sm_empl_pubs_mono_new")
     */
    public function emplPubsMonoNew() {

    }

    /**
     * @Route("/empl/pubs/mono/view/{monoID}", name="sm_empl_pubs_mono_view")
     */
    public function emplPubsMonoView() {

    }

    /**
     * @Route("/empl/pubs/mono/edit/{monoID}", name="sm_empl_pubs_mono_edit")
     */
    public function emplPubsMonoEdit() {

    }

    /**
     * @Route("/empl/pubs/mono/delete/{monoID}", name="sm_empl_pubs_mono_delete")
     */
    public function emplPubsMonoDelete() {

    }

    /**
     * @Route("/empl/pubs/chapters", name="sm_empl_pubs_chapters")
     */
    public function emplPubsChapters() {

    }

    /**
     * @Route("/empl/pubs/chapters/new", name="sm_empl_pubs_chapters_new")
     */
    public function emplPubsChaptersNew() {

    }

    /**
     * @Route("/empl/pubs/chapters/view/{chapterID}", name="sm_empl_pubs_chapters_view")
     */
    public function emplPubsChaptersView() {

    }

    /**
     * @Route("/empl/pubs/chapters/edit/{chapterID}", name="sm_empl_pubs_chapters_edit")
     */
    public function emplPubsChaptersEdit() {

    }

    /**
     * @Route("/empl/pubs/chapters/delete/{chapterID}", name="sm_empl_pubs_chapters_delete")
     */
    public function emplPubsChaptersDelete() {

    }

    /**
     * @Route("/empl/patents", name="sm_empl_patents")
     */
    public function emplPatents() {

    }

    /**
     * @Route("/empl/patents/new", name="sm_empl_patents_new")
     */
    public function emplPatentsNew() {

    }

    /**
     * @Route("/empl/patents/view/{oipID}", name="sm_empl_patents_view")
     */
    public function emplPatentsView() {

    }

    /**
     * @Route("/empl/patents/edit/{oipID}", name="sm_empl_patents_edit")
     */
    public function emplPatentsEdit() {

    }

    /**
     * @Route("/empl/patents/delete/{oipID}", name="sm_empl_patents_delete")
     */
    public function emplPatentsDelete() {

    }

    /**
     * @Route("/empl/sciment", name="sm_empl_sciment")
     */
    public function emplSciment() {

    }

    /**
     * @Route("/empl/sciment/new", name="sm_empl_sciment_new")
     */
    public function emplScimentNew() {

    }

    /**
     * @Route("/empl/sciment/view/{sciID}", name="sm_empl_sciment_view")
     */
    public function emplScimentView() {

    }

    /**
     * @Route("/empl/sciment/edit/{sciID}", name="sm_empl_sciment_edit")
     */
    public function emplScimentEdit() {

    }

    /**
     * @Route("/empl/sciment/delete/{sciID}", name="sm_empl_sciment_delete")
     */
    public function emplScimentDelete() {

    }

    /**
     * @Route("/empl/reports", name="sm_empl_reports")
     */
    public function emplReports() {

    }

    /**
     * @Route("/empl/rating", name="sm_empl_rating")
     */
    public function emplRating() {

    }
}