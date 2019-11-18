<?php
namespace Vibalco\AdminBundle\Generator\HtmlcssBundle;

use Twig_Extension;
use Twig_Filter_Method;

class HtmlcssExtension extends Twig_Extension
{
    public function getFilters()
    {
        return array(
            'is_bool' => new Twig_Filter_Method($this, 'is_bollFilter'),
        );
    }

    public function is_bollFilter($number)
    {
        if (is_bool($number)) {
            return true;
        }else{
            return false;
        }
                
    }

    public function getName()
    {
        return 'htmlcss_extension';
    }
}