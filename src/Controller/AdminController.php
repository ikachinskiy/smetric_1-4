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

class AdminController extends Controller
{
    /**
     * @Route("/admin", name="sm_admin")
     */
    public function admin() {

    }

    /**
     * @Route("/admin/orgstruct", name="sm_admin_orgstruct")
     */
    public function adminOrgStruct() {

    }

    /**
     * @Route("/admin/emplrs", name="sm_admin_emplrs")
     */
    public function adminEmplrs() {

    }

    /**
     * @Route("/admin/emplrs/new", name="sm_admin_emplrs_new")
     */
    public function adminEmplrsNew() {

    }

    /**
     * @Route("/admin/emplrs/view/{eID}", name="sm_admin_emplrs_view")
     */
    public function adminEmplrsView() {

    }

    /**
     * @Route("/admin/emplrs/edit/{eID}", name="sm_admin_emplrs_edit")
     */
    public function adminEmplrsEdit() {

    }

    /**
     * @Route("/admin/emplrs/delete/{eID}", name="sm_admin_emplrs_delete")
     */
    public function adminEmplrsDelete() {

    }

    /**
     * @Route("/admin/users", name="sm_admin_users")
     */
    public function adminUsers() {

    }

    /**
     * @Route("/admin/documents", name="sm_admin_documents")
     */
    public function adminDocuments() {

    }

    /**
     * @Route("/admin/reports", name="sm_admin_reports")
     */
    public function adminReports() {

    }

    /**
     * @Route("/admin/backup", name="sm_admin_backup")
     */
    public function adminBackup() {

    }

    /**
     * @Route("/admin/restore", name="sm_admin_restore")
     */
    public function adminRestore() {

    }

    /**
     * @Route("/admin/parameters", name="sm_admin_parameters")
     */
    public function adminParameters() {

    }
}