<?php


namespace Vibalco\AdminBundle\Command;


use Doctrine\Bundle\DoctrineBundle\Command\DoctrineCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ClearVisitCommand extends DoctrineCommand
{
    protected function configure()
    {
        $this
            ->setName('vibalco:clear:visit')
            ->addOption('days', 'd', InputOption::VALUE_OPTIONAL, 'Days before')
            ->setDescription('Add admin user to administrator.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $emName = 'default';
        $days = $input->getOption('days');
        if ($days == null) {
            $days = 730;
        } else {
            $days = intval($days);
        }
        $emServiceName = sprintf('doctrine.orm.%s_entity_manager', $emName);
        $em = $this->getContainer()->get($emServiceName);
        $rep = $em->getRepository('MainBundle:Visit');
        $rep->clearOutdated($days);
        $output->writeln("Deleted from " . $input->getOption('days') . " on visit");

    }

}