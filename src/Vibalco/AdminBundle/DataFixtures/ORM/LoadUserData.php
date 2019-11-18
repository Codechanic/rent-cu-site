<?php

namespace Vibalco\AdminBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Vibalco\AdminBundle\Entity\User;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;

/**
 * Description of UserLoadData
 *
 * @author vibalco
 */
class LoadUserData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface  {
    
    private $container;
    
    public function getOrder() {
        return 1;
    }

    /**
     * Encode password
     * 
     * @param User $entity
     */
    private function setSecurePassword(&$entity) {
        $encoder = new MessageDigestPasswordEncoder('sha512', true, 10);
        $password = $encoder->encodePassword($entity->getPassword(), $entity->getSalt());
        $entity->setPassword($password);
    }

    public function load($manager) {
        
        // Create default user
        $user = new User();
        $user->setUsername('admin');
        $user->setName('Administrador');
        $user->setEmail('admin@madera.lh');
        $user->setPassword('adminpass');
        $this->setSecurePassword($user);
        $manager->persist($user);
        
        // save
        $manager->flush();
    }

    public function setContainer(ContainerInterface $container = null) {
        $this->container = $container;       
    }
}

?>
