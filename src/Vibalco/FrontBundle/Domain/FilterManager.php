<?php

namespace Vibalco\FrontBundle\Domain;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Description of FilterManager
 *
 * @author yosbel
 */
class FilterManager {
    
    private $session;
    private $em;

    private $repository;
    private $keys;
    private $order;
    private $filtername;
    
    private $filter;    
    private $entityfilter;
        
    public function __construct(Session $session, EntityManager $em, $filtername, array $keys, array $order, $repository) 
    {
        $this->session = $session;    
        $this->em = $em;
        $this->filtername = $filtername;
        $this->keys = $keys;
        $this->order = is_array($order) ? $order : array();
        $this->repository = $repository;
        
        $this->filter = $session->get($this->filtername, array());
        $this->entityfilter = array();
        
        foreach ($this->filter as $key => $value) {
            $this->setEntityFilter($key);
        }
    }
    
    /**
     * Get a value stored into filter
     * 
     * @param type $key
     * @return filter value for $key
     */
    public function get($key) 
    {
        if(isset($this->filter[$key])) {
            return $this->filter[$key];
        }
        
        return null;
    }
    
    /**
     * Register a new filter param and value, this change is not persisted in session
     * until flush() method is called.
     * 
     * @param type $key
     * @param type $value
     */
    public function set($key, $value) 
    {
        if(!$this->isAllowedKey($key)) {
            return false;
        }

        if($this->isMultiple($key)){
            if(!isset($this->filter[$key])){
                $this->filter[$key] = array();
            }

            $this->filter[$key][$value] = $value;
        }
        else {
            $this->filter[$key] = $value;
        }

        //Find entity in DB and set into $this->entityfilter
        $this->setEntityFilter($key);
        
        return true;
    }
    
    /**
     * Remove an existing filter param, this change is not persisted in session
     * until flush() method is called.
     * 
     * @param type $key
     * @param type $value
     */
    public function remove($key, $value = null) 
    {
        if ($this->isAllowedKey($key) && isset($this->filter[$key])) {
            if(is_array($this->filter[$key])){
                unset($this->filter[$key][$value]);
                unset($this->entityfilter[$key][$value]);
                
                if(count($this->filter[$key]) == 0){
                    unset($this->filter[$key]);
                    unset($this->entityfilter[$key]);
                }
            }
            else {
                unset($this->filter[$key]);
                unset($this->entityfilter[$key]);
            }
        }
    }
    
    /**
     * Flush local instance of filter to session saving changes
     */    
    public function flush() 
    {
        $this->session->set($this->filtername, $this->filter);
    }
    
    /**
     * Remove the filter form session and set local filter to empty array
     */
    public function clear() {
        $this->filter = array();
        $this->entityfilter = array();
        
        $this->flush();
    }
    
    /**
     * Add a list of key, value pairs to filter
     * 
     * @param array $data
     */
    public function setArray(array $data) 
    {
        foreach ($data as $key => $value) {
            $this->set($key, $value);         
        }
    }
    
    /**
     * Search in database filter a key that correspond wiht an entity
     * otherwise the filter key and value is stored in entityfilter 
     * as it comes
     * 
     * @param type $key
     */
    private function setEntityFilter($key) 
    {
        $keys = $this->getAllowedKeys();
        
        if($this->isEntity($key)) {
            $rep = $this->em->getRepository($keys[$key]['class']);

            if($this->isMultiple($key)){
                if(!isset($this->entityfilter[$key])){
                    $this->entityfilter[$key] = array();
                }

                $list = $this->filter[$key];

                foreach ($list as $id => $value) {
                    if(!isset($this->entityfilter[$key][$id])) {          
                        $this->entityfilter[$key][$id] = $rep->find($id);
                    }
                }
            }
            else {
                $this->entityfilter[$key] = $rep->find($this->filter[$key]);
            }
        }
        else {
            $this->entityfilter[$key] = $this->filter[$key];
        }
    }

    /**
     * Returns an array with the same keys of the filter but with all (findAll)
     * database entities for every key. (usefull in twig in order to create
     * lists 
     * TODO create a twig extension for rendering form inputs from this
     * TODO find another way to make filtering using symfony forms, because this
     * intends to replicate pretty much the forms logic
     */
    public function getEntities() 
    {
        $result = array();
        
        foreach ($this->keys as $key => $params) {
            if(isset($params['class'])){
                $rep = $this->em->getRepository($params['class']);                
                $result[$key] = $rep->findAll();
            }
            else if(isset($params['list'])) {
                $rep = $this->em->getRepository($this->repository);
                
                $result[$key] = $rep->findParamValues($key);
            }
        }
        
        return $result;
    }
        
    /**
     * Check for if a key is in the list of allowed values
     * @param type $key
     * @return type
     */
    public function isAllowedKey($key) 
    {   
        return key_exists($key, $this->getAllowedKeys());
    }    
    
    /**
     * Return the filter with database entities instead of filter params
     * ids
     * 
     * @return array
     */
    public function getEntityFilter() {
        return $this->entityfilter;        
    }
    
    /**
     * Return the filter array with filter params as ids
     * 
     * @return array
     */
    public function getFilter() {
        return $this->filter;
    }
    
    /**
     * Return the order array with order params
     * array( 'orderparam' => 'ASC')
     * 
     * @return array
     */
    public function getOrder() {
        return $this->order;
    }
    
    /**
     * Return an array with the allowed filter params and his associated class
     * if corresponds to a database entity ( keys[filterparam] = entity class  or null)
     * @return array
     */
    public function getAllowedKeys() {
        return $this->keys;
    }
    
    /**
     * This method is a shorcut for FilterRepositoryInterface.countByFilter(...)
     */
    public function getTotalResults()
    {
        $rep =  $this->em->getRepository($this->repository);        
        return $rep->countByFilter($this->getFilter());
    }
    
    /**
     * This method is a shorcut for FilterRepositoryInterface.countByFilterParam(...)
     * method
     * 
     * @param type $param filter param name
     * @param type $count max count of results
     * @return ResultSet (FilterEntity entity, int filter_count) 
     */
    public function getParamCounts($param, $count = 5) {
        $rep =  $this->em->getRepository($this->repository);        
        return $rep->countByFilterParam($this, $param, $count);
    }
    
    public function isMultiple($key) {
        $keys = $this->getAllowedKeys();
        return is_array($keys[$key]) && isset($keys[$key]['multiple']);         
    }
    
    public function isEntity($key) {
        $keys = $this->getAllowedKeys();
        return is_array($keys[$key]) && isset($keys[$key]['class']); 
    }
}
