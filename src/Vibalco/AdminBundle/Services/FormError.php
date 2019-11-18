<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FormError
 *
 * @author ruben
 */

namespace Vibalco\AdminBundle\Services;

class FormError {

    public function getArray(\Symfony\Component\Form\Form $form) {
        return $this->getErrors($form);
    }

    public function generateMessage(\Symfony\Component\Form\Form $form) {
        $string = "";
        global $kernel;
        foreach ($this->getErrors($form) as $value) {
            
                if (array_key_exists(0, $value)) {
                    $string .= "ERROR: " . $value[0] . "<br>";
                } else {
                    if (array_key_exists('password', $value)) {
                        $string .= "ERROR:". $kernel->getContainer()->get('translator')->trans('admin.user.repeat') . "<br>";
                    }
                }
            
            return $string;
        }
    }

    private function getErrors($form) {
        $errors = array();

        if ($form instanceof \Symfony\Component\Form\Form) {

            // соберем ошибки элемента
            foreach ($form->getErrors() as $error) {

                $errors[] = $error->getMessage();
            }

            // пробежимся под дочерним элементам
            foreach ($form->all() as $key => $child) {
                /** @var $child \Symfony\Component\Form\Form */
                if ($err = $this->getErrors($child)) {
                    $errors[$key] = $err;
                }
            }
        }

        return $errors;
    }

}
