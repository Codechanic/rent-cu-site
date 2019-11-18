<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vibalco\AdminBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\Bundle\DoctrineBundle\Command\DoctrineCommand;
use InvalidArgumentException;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use Symfony\Component\Console\Input\InputOption;
use Doctrine\Common\Annotations\AnnotationReader;

/**
 *
 * @author Vibalco Team <admin_madera@gmail.com>
 */
class RoleCommand extends DoctrineCommand {

    protected function configure() {
        $this
                ->setName('vibalco:roles')
                ->addOption('action', '-a', InputOption::VALUE_OPTIONAL, 'Action (update-restore)')
                ->setDescription('Update al roles and save in BD.');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $emName = 'default';
        $emServiceName = sprintf('doctrine.orm.%s_entity_manager', $emName);

        if (!$this->getContainer()->has($emServiceName)) {
            throw new InvalidArgumentException(
            sprintf(
                    'Could not find an entity manager configured with the name "%s". Check your ' .
                    'application configuration to configure your Doctrine entity managers.', $emName
            )
            );
        }

        $em = $this->getContainer()->get($emServiceName);

        $finalBundles = array();
        $bundles = $this->getContainer()->get('kernel')->getBundles();

        $roles_din = array();
        foreach ($bundles as $bundle => $bundleObject) {
            $parent = $bundleObject->getParent();
            if (null === $parent) {
                $finalBundles[$bundle] = array();
            } else {
                $finalBundles[$bundle] = array($parent);
                foreach ($finalBundles[$parent] as $bundleName) {
                    $finalBundles[$bundle][] = $bundleName;
                }
                unset($finalBundles[$parent]);
            }
            $t = $this->getControllers($bundleObject);
            if (count($t) > 0) {
                $roles_din[] = $t;
            }
        }

  
        $action = $input->getOption('action');
        if($action != "update"){
            $query = $em->createQuery('DELETE FROM AdminBundle:Role r WHERE r.role != \'ROLE_ADMIN\'');
             $query->execute();
        }
       
        $role = $em->getRepository('AdminBundle:Role')->findBy(array('role' => "ROLE_ADMIN"));
        if (count($role) <= 0) {
            $role = new \Vibalco\AdminBundle\Entity\Role();
            $role->setRole("ROLE_ADMIN");
            $role->setName("Super Administartor");
            $em->persist($role);
            $em->flush();
        }

        foreach ($roles_din as $r) {
            foreach ($r as $name => $rols) {
                $role = $em->getRepository('AdminBundle:Role')->findBy(array('name' => $name));
                if (count($role) <= 0) {
                    $role = new \Vibalco\AdminBundle\Entity\Role();
                    $role->setRole($rols);
                    $role->setName($name);
                    $em->persist($role);
                    $em->flush();
                }
            }
        }
    }

    /**
     * @param $bundleObject \Symfony\Component\HttpKernel\Bundle\Bundle
     * @return array
     */
    public function getControllers($bundleObject) {
        $ret = array();

        $controllerDir = $bundleObject->getPath() . '/Controller';
        if (is_dir($controllerDir)) {
            $finder = new \Symfony\Component\Finder\Finder;
            $namespace = $bundleObject->getNamespace() . '\\Controller';
            $finder->files()->name('*Controller.php')->in($controllerDir);
            $annotationReader = new AnnotationReader();
            foreach ($finder as $file) {
                $ns = $namespace;
                if ($relativePath = $file->getRelativePath()) {
                    $ns .= '\\' . strtr($relativePath, '/', '\\');
                }
                $className = $file->getBasename('.php');
                $r = new \ReflectionClass($ns . '\\' . $file->getBasename('.php'));

                if ($r->isInstantiable() && $className != 'Controller') {
                    $classAnnotations = $annotationReader->getClassAnnotations($r);
                    foreach ($classAnnotations as $annotation) {
                        if (is_a($annotation, "Lsw\SecureControllerBundle\Annotation\Secure")) {
                            $ret[$annotation->getName()] = $annotation->getRoles();
                        }
                    }
                }
            }
            return $ret;
        }
    }

}
