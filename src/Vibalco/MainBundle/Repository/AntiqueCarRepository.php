<?php

namespace Vibalco\MainBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Vibalco\FrontBundle\Domain\FilterManager;
use Vibalco\FrontBundle\Domain\FilterRepositoryInterface;

class AntiqueCarRepository extends EntityRepository implements FilterRepositoryInterface
{    
    public function findByFilter(array $filter, array $order = array()) 
    {        
        return $this->queryFilter($filter, $order)->getResult();
    }
    
     public function countByFilter(array $filter) 
    {
        $qb = $this->queryBuilderFilter($filter);        
        $qb->select('COUNT(DISTINCT h)');
        
        return $qb->getQuery()->getSingleScalarResult();
    }

    public function countByFilterParam(FilterManager $fm, $filterparam, $limitcount = null) 
    {
        $keys = $fm->getAllowedKeys();
        
        //if filterparam is null or has no class defined or is not a valid param
        if($filterparam == null || !isset($keys[$filterparam]) || $keys[$filterparam] == null) {
            return null;
        }
        
        $filter =  $fm->getFilter();        
        
        //remove filter param if exist and is not multiple (avoid collisions)
        $fp = null;
        if(isset($filter[$filterparam])) {
            $fp = $filter[$filterparam];
            if(!$fm->isMultiple($filterparam)){
                unset($filter[$filterparam]);
            }
        }
        
        // query builder order by count ('filter_entity, filter_count')
        $qb = $this->getEntityManager()->createQueryBuilder();
        
        if($fm->isEntity($filterparam)) {
            //if entity then filter alias and entity alias are diferent
            $alias = 'filter';
           
            $qb->select("filter AS entity, COUNT(DISTINCT h) AS filter_count")
               ->from($keys[$filterparam]['class'], 'filter')
               ->groupBy("filter.id")
               ->orderBy('filter_count', 'DESC')
            ;
        }
        else {
            //if is not entity then filter alias and entity are the same
            //because the filtered param is a field inside the main entity
            $alias = 'h';
            $dbparam = $this->mapParam($filterparam);
           
            $qb->select("h AS entity, COUNT(h) AS filter_count")
               ->from('MainBundle:AntiqueCar', 'h')
               ->groupBy("h.$dbparam")
               ->orderBy('filter_count', 'DESC')
            ;
        }
        
        //exclude from search params selected if multiple
        if($fm->isMultiple($filterparam) && is_array($fp)){
            $qb->andHaving("$alias.id NOT IN (:array)")
               ->setParameter('array', $fp)
            ;
        }
        
        //how do i go from filter param to antiquecar entity (joins)        
        $this->appendEntityJoin($qb, $filterparam);
        
        //apply other filter params (narrowed resultset to filter in session)
        $this->appendFilterParams($qb, $filter);
        
        if(is_int($limitcount)){
            $qb->setMaxResults($limitcount);
        }
        
        return $qb->getQuery()->getResult();
    }
    
    
    /**
     * Returns distinct values registered for a given param
     * in database (distinct column values)
     */
    public function findParamValues($key, $limit = -1)
    {
        $qb = $this->createQueryBuilder('h');
        
        // year
        if($key == 'year') {
            $qb->select('h.year AS key')
               ->distinct();
        }
        
        if($limit > 0)
        {
            $qb->setMaxResults($limit);
        }
        
        $result = $qb->getQuery()->getScalarResult();
        
        $array = array();
        
        foreach ($result as $value) {
            $array[] = $value['key'];
        }
        
        return $array;
    }
    
    /**
     * Returns a QueryBuilder with $filter params applied
     */
    public function queryBuilderFilter(array $filter, array $order = array())
    {       
        $qb = $this->createQueryBuilder('h');
        
        $this->appendFilterParams($qb, $filter);        
        $this->appendFilterOrder($qb, $order);
                
        return $qb;
    }
    
    public function queryFilter($filter, $order = array()) {
        return $this->queryBuilderFilter($filter, $order)->getQuery();
    }
    
    
    private function appendFilterOrder($qb, array $order)
    {
        foreach ($order as $key => $value) {
            $str = $value == 'ASC' ? 'ASC' : 'DESC';
            
            switch ($key){
                case 'rank':
                    $qb->orderBy('h.rank', $str);
                    break;
            }
        }
    }
    
    /**
     * Append filter params to a query builder
     * 
     * @param $qb
     * @param array $filter
     */
    private function appendFilterParams(\Doctrine\ORM\QueryBuilder $qb, array $filter) 
    {
        $qb->andWhere('h.enabled = TRUE');
        
        // brand
        if(isset($filter['brand'])) {
            $qb->andWhere('h.brand = :brand')
               ->setParameter('brand', $filter['brand']);
        }
        
        // location
        if(isset($filter['municipality'])) {
            $qb->andWhere('h.municipality = :municipality')
               ->setParameter('municipality', $filter['municipality']);
        }
        else if(isset($filter['province'])) {
            $qb->join('h.municipality', 'm')
               ->andWhere('m.province = :province')
               ->setParameter('province', $filter['province']);
        }

        // price min-max
        if(isset($filter['price-min'])) {
            $qb->andWhere('h.pricehour >= :pricemin')
               ->setParameter('pricemin', $filter['price-min']);
        }
        
        if(isset($filter['price-max'])) {
            $qb->andWhere('h.pricehour <= :pricemax')
               ->setParameter('pricemax', $filter['price-max']);
        }
        
        // year amount
        if(isset($filter['year'])) {
          $qb->andWhere('h.year = :year')
             ->setParameter('year', $filter['year']);
        }
        
        // hardcover amount
        if(isset($filter['hardcover'])) {
          $qb->andWhere('h.hardcover = :hardcover')
             ->setParameter('hardcover', $filter['hardcover']);
        }
        
        // convertible amount
        if(isset($filter['convertible'])) {
          $qb->andWhere('h.convertible = :convertible')
             ->setParameter('convertible', $filter['convertible']);
        }
                
        // text search
        if(isset($filter['search'])) {
            $expr = $qb->expr();
            
            $qb->andWhere($expr->orX(
                    $expr->like('h.name', ':nsearch'),
                    $expr->like('h.owner', ':dsearch')
                ))
                ->setParameter('nsearch', "%{$filter['search']}%")
                ->setParameter('dsearch',"%{$filter['search']}%")
            ;
        }
    }
      
    /**
     * Append joins from filter param entity to main filtered entity
     * 
     * Exp: From Municipality (m) to AntiqueCar (h) is one join
     * $qb->join('m.antiquecars', 'h');
     * 
     * @param type $qb
     * @param string $filterparam
     */
    private function appendEntityJoin($qb, $filterparam) 
    {
        //how do i go from filter param to antiquecar entity (joins)
        switch ($filterparam)
        {
            case 'municipality':
                $qb->join('filter.antiquecars', 'h');
                break;
            case 'brand':
                $qb->join('filter.cars', 'h');
                break;
            case 'province':
                $qb->join('filter.municipalities', 'm')
                   ->join('m.antiquecars', 'h');
                break;
        }
    }
    
    public function mapParam($filterparam) {
        switch ($filterparam)
        {
            case 'year':
                return 'year';
        }
    }
}
