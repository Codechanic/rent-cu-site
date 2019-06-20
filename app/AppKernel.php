<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel {

    public function registerBundles() {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new Avalanche\Bundle\ImagineBundle\AvalancheImagineBundle(),
            new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
            new Ideup\SimplePaginatorBundle\IdeupSimplePaginatorBundle(),
            new Vibalco\DatatableBundle\VibalcoDatatableBundle(),
            new Vibalco\AdminBundle\AdminBundle(),
            new Genemu\Bundle\FormBundle\GenemuFormBundle(),
            new A2lix\TranslationFormBundle\A2lixTranslationFormBundle(),         

            /* new JMS\I18nRoutingBundle\JMSI18nRoutingBundle(),
              new JMS\TranslationBundle\JMSTranslationBundle(), */
            
            new JMS\AopBundle\JMSAopBundle(),           
            new JMS\DiExtraBundle\JMSDiExtraBundle($this),
            
            new Lsw\SecureControllerBundle\LswSecureControllerBundle(),
            new Vibalco\FrontBundle\FrontBundle(),
            new ENC\Bundle\BackupRestoreBundle\BackupRestoreBundle(),
            
            new Vibalco\SliderBundle\SliderBundle(),
            new Vibalco\GalleryBundle\GalleryBundle(),

            new Vibalco\MainBundle\MainBundle(),
            new Vibalco\ContenBundle\ContenBundle(),
            new Vibalco\CommentBundle\CommentBundle()
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader) {
        $loader->load(__DIR__ . '/config/config_' . $this->getEnvironment() . '.yml');
    }

}
