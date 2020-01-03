<?php


namespace Vibalco\AdminBundle\Command;


use Doctrine\Bundle\DoctrineBundle\Command\DoctrineCommand;
use InvalidArgumentException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use Vibalco\AdminBundle\Entity\User;
use Vibalco\MainBundle\Entity\Homestay;

class PopulateOwnerCommand extends DoctrineCommand
{
    protected function configure()
    {
        $this
            ->setName('vibalco:populate:owner')
            ->setDescription('Populate owners from email homestay.');
    }

    protected function setSecurePassword($entity)
    {
        $encoder = new MessageDigestPasswordEncoder('sha512', true, 10);
        $password = $encoder->encodePassword($entity->getPassword(), $entity->getSalt());
        return $password;
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
        $em = $this->getContainer()->get('doctrine')->getManager();
        $role = $em->getRepository('AdminBundle:Role')->findBy(array('role' => "ROLE_USER"));
        try {
            if (count($role) <= 0) {
                $role = new \Vibalco\AdminBundle\Entity\Role();
                $role->setRole("ROLE_USER");
                $role->setName("OWNER");
                $em->persist($role);
                $em->flush();
            }
            $role = $em->getRepository('AdminBundle:Role')->findBy(array('role' => "ROLE_USER"));
            $homeStays = $em->getRepository('MainBundle:Homestay')->findAll();
            $batchSize = 100;
            foreach ($homeStays as $index => $homeStay) {
                /**
                 * @var Homestay $homeStay
                 */
                $email = $homeStay->getEmail();
                $adm = $em->getRepository('AdminBundle:User')->findBy(array('username' => $email));
                if (count($adm) <= 0 && !empty($email)) {
                    $name = $homeStay->getOwner();
                    $user = new User();
                    $user->setUsername($email);
                    $user->setName($name);
                    $user->setEmail($email);
                    $user->setPassword('user');
                    $password = $this->setSecurePassword($user);
                    $user->setPassword($password);
                    $user->setRoles($role);
                    $homeStay->setOwnerId($user);
                    $em->persist($user);
                    if (($index % $batchSize) == 0) {
                        $em->flush();
                        $em->clear();
                        $output->writeln("Created  " . $index . "users");
                    }

                } else {
                    if (empty($email)) {
                        $output->writeln("The house has no email registered");
                    } else {
                        $output->writeln("The user " . $email. "exists..");
                    }
                }

            }
            $em->flush();
            $em->clear();
        }
        catch (\Exception $exception) {
            $output->writeln($exception->getMessage());
        }
    }

}