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
use Vibalco\AdminBundle\Entity\User;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use Symfony\Component\Console\Input\InputOption;

/**
 *
 * @author Vibalco Team <admin_madera@gmail.com>
 */
class AdminCommand extends DoctrineCommand
{
    protected function configure()
    {
        $this
            ->setName('vibalco:add:admin')
            ->addOption('user', 'u', InputOption::VALUE_REQUIRED, 'The new admin User')
            ->addOption('password', 'p', InputOption::VALUE_REQUIRED, 'The user password')
            ->setDescription('Add admin user to administrator.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
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
        $adm = $em->getRepository('AdminBundle:User')->findBy(array('username' => $input->getOption('user')));
        $role = $em->getRepository('AdminBundle:Role')->findBy(array('role' => "ROLE_ADMIN"));
        if (count($role) <= 0) {
            $role = new \Vibalco\AdminBundle\Entity\Role();
            $role->setRole("ROLE_ADMIN");
            $role->setName("SUPER ADMINISTRATOR");
            $em->persist($role);
            $em->flush();
        }
        $role = $em->getRepository('AdminBundle:Role')->findBy(array('role' => "ROLE_ADMIN"));
        if (count($adm) <= 0) {
            $name = uniqid();
            $user = new User();
            $user->setUsername($input->getOption('user'));
            $user->setName('Generate Username');
            $user->setEmail($name . '@madera.lh');
            $user->setPassword($input->getOption('password'));


            $user->setRoles($role);
            $this->setSecurePassword($user);
            $em->persist($user);
            $em->flush();
        } else {
            $output->writeln("The user " . $input->getOption('user') . "exists..");
        }


    }


    protected function setSecurePassword(&$entity)
    {
        $encoder = new MessageDigestPasswordEncoder('sha512', true, 10);
        $password = $encoder->encodePassword($entity->getPassword(), $entity->getSalt());
        $entity->setPassword($password);
    }
}
