<?php

namespace Vibalco\AdminBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\Bundle\DoctrineBundle\Command\DoctrineCommand;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use Symfony\Component\Console\Input\ArgvInput;

class SecretKeyCommand extends DoctrineCommand {

    protected function configure() 
    {
        $this
                ->setName('vibalco:secret')
                ->setDescription('Generate new Secret Key');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $uuid = uniqid('rent');
        $secretKey = hash('sha256', $uuid);
        $output->writeln($secretKey);
    }


}