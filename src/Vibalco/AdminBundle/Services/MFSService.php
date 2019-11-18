<?php

namespace Vibalco\AdminBundle\Services;

/**
 * Description of MFSService
 *
 * @author yosbel
 */
class MFSService 
{
    public $session;

    /**
     * @param \Symfony\Component\HttpFoundation\Session\Session $session
     */
    public function __construct($session) {
        $this->session = $session;
    }

    /*
     * TODO create as a service.
     * 
     * - checkMFS checks msf(multiple form submit) field vs the one store in the 
     * user session
     * 
     * - generateMFS generate a mfs field
     * into session and form
     */    
    public function checkMFS(\Symfony\Component\Form\Form $form) 
    {
        $mfsf = $form->get('mfs')->getData();
        $mfss = $this->session->get($form->getName().'_mfs');
        
        return ($mfsf != null && $mfss != null && $mfsf == $mfss);
    }

    public function generateMFS(\Symfony\Component\Form\Form $form) 
    {
        $mfs = $this->generateUID();

        $form->add('mfs', 'hidden', array('mapped' => false, 'data' => $mfs));
        $this->session->set($form->getName() . '_mfs', $mfs);
    }

    public function addMFS(\Symfony\Component\Form\Form $form) 
    {
        $form->add('mfs', 'hidden', array('mapped' => false));
    }

    private function generateUID() 
    {
        return uniqid();
    }
}
