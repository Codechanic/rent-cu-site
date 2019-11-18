<?php

/*
 * This file is part of the Sonata package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace Vibalco\AdminBundle\Generator;

use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Vibalco\AdminBundle\Generator\AbstractBcGenerator;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
/**
 * @author Marek Stipek <mario.dweller@seznam.cz>
 * @author Simon Cosandey <simon.cosandey@simseo.ch>
 */
class ControllerGenerator extends AbstractBcGenerator
{
    /** @var string|null */
    private $class;

    /** @var string|null */
    private $file;

    /**
     * @param array|string $skeletonDirectory
     */
    public function __construct($skeletonDirectory)
    {
        $this->setSkeletonDirs($skeletonDirectory);
    }

    /**
     * @param BundleInterface $bundle
     * @param string $controllerClassBasename
     * @throws \RuntimeException
     */
    public function generate(BundleInterface $bundle, $controllerClassBasename, $model, $metadata, $list)
    {
        
        
      
        $this->class = sprintf('%s\Controller\%s', $bundle->getNamespace(), $controllerClassBasename);
        $this->file = sprintf(
            '%s/Controller/%s.php',
            $bundle->getPath(),
            str_replace('\\', '/', $controllerClassBasename)
        );
        $parts = explode('\\', $this->class);

        if (file_exists($this->file)) {
            throw new \RuntimeException(sprintf(
                'Unable to generate the admin controller class "%s". The file "%s" already exists.',
                $this->class,
                realpath($this->file)
            ));
        }
        
        
        $this->renderFileBc('AdminController.php.twig', $this->file, array(
            'classBasename' => array_pop($parts),
            'namespace' => implode('\\', $parts),
            'routing' => strtolower($model),
            'model' => $model,
            'bundle' => $bundle->getName(),
            'list' => $list,
        ));
        $dir = sprintf('%s/Resources/views/%s', $bundle->getPath(), str_replace('\\', '/', $model));
    
        if (!file_exists($dir)) {
            mkdir($dir, 0777);
        }
        
        $trans = sprintf('%s/Entity/%sTranslation.php', $bundle->getPath(), str_replace('\\', '/', $model));
        $this->renderFileBc('TransEntity.php.twig', $trans, array(
            'bundle'            => $bundle->getName(),            
            'model' => $model,
            'list'=>$list,
        ));
        
        
        $this->generateIndexView($dir, $bundle, $model,$list);
        $this->generateEdit($dir, $bundle, $model,$list);
        $this->generateNew($dir, $bundle, $model,$list);
        $this->generateList($dir, $bundle, $model,$list);
        
       
        
        $this->generateShow($dir, $bundle, $model, $this->getFieldsFromMetadata($metadata), $list);

    }


    protected function generateIndexView($dir, $bundle, $module, $list)
    {
        $this->renderFileBc('crud/views/index.html.twig.twig', $dir.'/index.html.twig', array(
            'bundle'            => $bundle->getName(),
            'module' => strtolower($module),
            'model' => $module,
            'list'=>$list,
        ));
    }

    protected function generateEdit($dir, $bundle, $module, $list)
    {
        $this->renderFileBc('crud/views/edit.html.twig.twig', $dir.'/edit.html.twig', array(
            'bundle'            => $bundle->getName(),
            'module' => strtolower($module),
            'list' => $list,
        ));
    }

    protected function generateNew($dir, $bundle, $module, $list)
    {
        $this->renderFileBc('crud/views/new.html.twig.twig', $dir.'/new.html.twig', array(
            'bundle'            => $bundle->getName(),
            'module' => strtolower($module),
            'list' => $list,
        ));
    }

    protected function generateList($dir, $bundle, $module, $list)
    {
        $this->renderFileBc('crud/views/list.html.twig.twig', $dir.'/list.html.twig', array(
            'bundle'            => $bundle->getName(),
            'module' => strtolower($module),
            'list' => $list,
        ));
    }

    protected function generateShow($dir, $bundle, $module, $entiy_metadata, $list)
    {
        
        
        $this->renderFileBc('crud/views/show.html.twig.twig', $dir.'/show.html.twig', array(
            'bundle'            => $bundle->getName(),
            'module' => strtolower($module),
            'fields'=> $entiy_metadata,
            'list' => $list,           
        ));
    }
    
    
        /**
     * Returns an array of fields. Fields can be both column fields and
     * association fields.
     *
     * @param  ClassMetadataInfo $metadata
     * @return array             $fields
     */
    private function getFieldsFromMetadata($metadata)
    {
        $fields = $metadata[0]->fieldNames;

        // Remove the primary key field if it's not managed manually
        /*if (!$metadata->isIdentifierNatural()) {
            $fields = array_diff($fields, $metadata->identifier);
        }

        foreach ($metadata->associationMappings as $fieldName => $relation) {
            if ($relation['type'] !== ClassMetadataInfo::ONE_TO_MANY) {
                $fields[] = $fieldName;
            }
        }*/

        return $fields;
    }








    /**
     * @return string|null
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @return string|null
     */
    public function getFile()
    {
        return $this->file;
    }
}
