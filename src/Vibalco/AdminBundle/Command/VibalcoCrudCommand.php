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

use Vibalco\AdminBundle\Generator\ControllerGenerator;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Doctrine\Bundle\DoctrineBundle\Command\DoctrineCommand;
use Doctrine\Bundle\DoctrineBundle\Mapping\MetadataFactory;

/**
 * Load data fixtures from bundles.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 * @author Jonathan H. Wage <jonwage@gmail.com>
 */
class VibalcoCrudCommand extends DoctrineCommand {

    protected function configure() {
        $this
                ->setName('vibalco:generate:crud')
                ->addArgument('model', InputArgument::REQUIRED, 'The fully qualified model class')
                ->addOption('bundle', 'b', InputOption::VALUE_REQUIRED, 'The bundle name')
                ->addOption('list', 'l', InputOption::VALUE_REQUIRED, 'The databale list(long,short)')
                ->setDescription('Generate Controler for Entity.');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $skeletonDirectory = __DIR__ . '/../Resources/skeleton';
        $controllerGenerator = new ControllerGenerator($skeletonDirectory);
        $controllerClassBasename = $input->getArgument('model') . 'Controller';
        $bundle = $this->getBundle($input->getOption('bundle'));
        $entity = $input->getArgument('model');
        $list = $input->getOption('list');
        $entityClass = $this->getContainer()->get('doctrine')->getAliasNamespace($input->getOption('bundle')) . '\\' . $entity;

       
      
        if($list!="long" && $list!="short"){
            $this->writeError($output, "The databale list VALUE_REQUIRED -l option.Only support short and long....Ok no problem??");
            
        }else{ 
             
        $command = $this->getApplication()->find('doctrine:generate:form');
        $arguments = array(
            'command' => 'doctrine:generate:form',
            'entity' => $input->getOption('bundle').':'.$entity,
        );

        $input2 = new \Symfony\Component\Console\Input\ArrayInput($arguments);
        $command->run($input2, $output); 
        $metadata = $this->getEntityMetadata($entityClass);
        try {
            $controllerGenerator->generate($bundle, $controllerClassBasename, $input->getArgument('model'), $metadata, $list);
            $output->writeln(sprintf(
                            '%sThe controller class "<info>%s</info>" has been generated under the file "<info>%s</info>".', PHP_EOL, $controllerGenerator->getClass(), realpath($controllerGenerator->getFile())
            ));
            $output->writeln("The formtype need que le añadas estas clases a los atributos por ahora: , array('attr' => array('class' => 'form-control')");
            
        } catch (\Exception $e) {
            $this->writeError($output, $e->getMessage());
        }
        
        }
    }

    protected function getEntityMetadata($entity) {
        $factory = new MetadataFactory($this->getContainer()->get('doctrine'));

        return $factory->getClassMetadata($entity)->getMetadata();
    }

    private function writeError(OutputInterface $output, $message) {
        $output->writeln(sprintf("\n<error>%s</error>", $message));
    }

    private function getBundle($name) {
        return $this->getKernel()->getBundle($name);
    }

    /**
     * @return KernelInterface
     */
    private function getKernel() {
        $application = $this->getApplication();
        /* @var $application Application */

        return $application->getKernel();
    }

}
