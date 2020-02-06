<?php


namespace Vibalco\AdminBundle\Command;


use Doctrine\Bundle\DoctrineBundle\Command\DoctrineCommand;
use InvalidArgumentException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use Vibalco\AdminBundle\Entity\User;
use Vibalco\MainBundle\Entity\Homestay;

class FlushCommand extends DoctrineCommand
{

    protected function configure()
    {
        $this
            ->setName('vibalco:flush:token')
            ->setDescription('Flush expired tokens.');
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
        try {
            $q = $em->createQueryBuilder();
            $query = $q->delete('MainBundle:RefreshToken', 'r')
                ->where('r.expires < :date')
                ->setParameter('date', new \DateTime())
                ->getQuery();
            $query->execute();
            $output->writeln('Cleared');

        }
        catch (\Exception $exception) {
            $output->writeln($exception->getMessage());
        }
    }
}