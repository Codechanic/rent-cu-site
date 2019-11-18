<?php

namespace Vibalco\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Finder\Finder;
use Lsw\SecureControllerBundle\Annotation\Secure;
/**
 * SecuredController
 * @Secure(roles="ROLE_ADMIN_BACKUPS", name="ADMIN BACKUPS")
 * @Route("/admin")
 */
class BackupController extends Controller {

    /**
     * @Route("/remove_backup/{backup}", name="admin_remove")
     * @Template("AdminBundle:Backup:backups.html.twig")
     */
    public function removedAction($backup) {
        $backup = $this->get('kernel')->getRootDir() . "/../backups/" . $backup;
        unlink($backup);
        $finder = new Finder();
        $finder->files()->in($this->get('kernel')->getRootDir() . "/../backups")->sortByChangedTime();
        $backups = array();
        foreach ($finder as $file) {
            $backups[] = $file->getRelativePathname();
        }
        return array('backups' => array_reverse($backups));
    }

    /**
     * @Route("/backup", name="backup")
     * @Template("AdminBundle:Backup:backups.html.twig")
     */
    public function backupAction() {
        $factory = $this->container->get('backup_restore.factory');
        $backupInstance = $factory->getBackupInstance("doctrine.dbal.default_connection");
        $date = date("l\_j\_\of\_F_h:i:s\_A");
        
        $backup = $date . "_" . ".sql";
        $backupInstance->backupDatabase($this->get('kernel')->getRootDir() . "/../backups", $backup);

        $finder = new Finder();
        $finder->files()->in($this->get('kernel')->getRootDir() . "/../backups")->sortByChangedTime();
        $backups = array();
        foreach ($finder as $file) {
            $backups[] = $file->getRelativePathname();
        }
        return array('backups' => array_reverse($backups));
    }

    /**
     * @Route("/backup/{backup}", name="restore")
     * 
     */
    public function restoreAction($backup) {

        $factory = $this->container->get('backup_restore.factory');
        $restoreInstance = $factory->getRestoreInstance("doctrine.dbal.default_connection");
        $backup = $this->get('kernel')->getRootDir() . "/../backups/" . $backup;
        $restoreInstance->restoreDatabase($backup);
        echo json_encode($this->get('translator')->trans('admin.backup.restore_success'));
        die;
    }

    /**
     * @Route("/backups", name="admin_backups")
     * @Template()
     */
    public function backupsAction() {

        $finder = new Finder();
        $finder->files()->in($this->get('kernel')->getRootDir() . "/../backups")->sortByChangedTime();
        $backups = array();
        foreach ($finder as $file) {
            $backups[] = $file->getRelativePathname();
        }
        return array('backups' => array_reverse($backups));
    }


}

?>
