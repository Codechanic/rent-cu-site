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
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use Symfony\Component\Console\Input\ArgvInput;

/**
 * 
 * @author Vibalco Team <admin_madera@gmail.com>
 */
class InstallCommand extends DoctrineCommand {

    protected function configure() {
        $this
                ->setName('vibalco:install')
                ->setDescription('Install al Site.');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $emName = 'default';
        $emServiceName = sprintf('doctrine.orm.%s_entity_manager', $emName);
        $command = $this->getApplication()->find('doctrine:database:create');
        $arguments = array(
            'command' => 'doctrine:database:create',
        );

        $output->writeln("Adding default database..");
        $input2 = new \Symfony\Component\Console\Input\ArrayInput($arguments);
        $command->run($input2, $output);


        $output->writeln("Updating database schema..");
        $command = $this->getApplication()->find('doctrine:schema:create');
        $arguments = array(
            'command' => 'doctrine:schema:create',
        );
        $input2 = new \Symfony\Component\Console\Input\ArrayInput($arguments);
        $command->run($input2, $output);

        $output->writeln("Instaling default assets..");
        $command = $this->getApplication()->find('assets:install');
        $arguments = array(
            'command' => 'assets:install',
            'target' => 'public_html'
        );
        
        $input2 = new \Symfony\Component\Console\Input\ArrayInput($arguments);
        $command->run($input2, $output);


        $output->writeln("Dumping all assets..");
        
        $command = $this->getApplication()->find('assetic:dump');
        $arguments = array(
            'command' => 'assetic:dump --env prod --no-debug',
        );
        $input2 = new \Symfony\Component\Console\Input\ArrayInput($arguments);
        $command->run($input2, $output);



        $output->writeln("Adding default Settings..");

        if (!$this->getContainer()->has($emServiceName)) {
            throw new InvalidArgumentException(
            sprintf(
                    'Could not find an entity manager configured with the name "%s". Check your ' .
                    'application configuration to configure your Doctrine entity managers.', $emName
            )
            );
        }

        $em = $this->getContainer()->get($emServiceName);

        $settings = new \Vibalco\AdminBundle\Entity\Settings();
        $settings->setOffline(false);
        $settings->setTitle("Settings");
        $em->persist($settings);
        $em->flush();
        
        
        $output->writeln("Adding default Administrator User..");
        
        $adm = $em->getRepository('AdminBundle:User')->findBy(array('username' => 'admin'));

        $role = $em->getRepository('AdminBundle:Role')->findBy(array('role' => "ROLE_ADMIN"));
        if (count($role) <= 0) {
            $role = new \Vibalco\AdminBundle\Entity\Role();
            $role->setRole("ROLE_ADMIN");
            $role->setName("SUPER ADMINISTRATOR");
            $em->persist($role);
            $em->flush();
        }
        $role = $em->getRepository('AdminBundle:Role')->findBy(array('role' => "ROLE_ADMIN"));
        $name = uniqid();
        if (count($adm) <= 0) {
            $user = new \Vibalco\AdminBundle\Entity\User();
            $user->setUsername("admin");
            $user->setName('Generate Username');
            $user->setEmail($name.'@madera.lh');
            $user->setPassword("adminpass");            
            $user->setRoles($role);
            $this->setSecurePassword($user);

            $em->persist($user);
            $em->flush();
        }
        
        $output->writeln("Loading default Roles..");
        $command = $this->getApplication()->find('vibalco:roles');
        $arguments = array(
            'command' => 'vibalco:roles -u update',
        );
        
        
        $input2 = new \Symfony\Component\Console\Input\ArrayInput($arguments);
        $command->run($input2, $output);
        
        
    }

    protected function setSecurePassword(&$entity) {
        $encoder = new MessageDigestPasswordEncoder('sha512', true, 10);
        $password = $encoder->encodePassword($entity->getPassword(), $entity->getSalt());
        $entity->setPassword($password);
    }

}
