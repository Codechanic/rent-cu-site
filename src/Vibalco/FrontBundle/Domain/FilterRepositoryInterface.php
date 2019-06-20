<?php

namespace Vibalco\FrontBundle\Domain;

/**
 *
 * @author yosbel
 */
interface FilterRepositoryInterface 
{
    /**
     * Count for a given filter param, how much entities exists for every
     * single instance of that filter param. The total count of entities are
     * filtered first by session filter params.
     * 
     * Exp: How much homestays exists for each service 
     * (service_1 : 20, service_2 : 40, ...)
     * 
     * @param FilterManager $fm
     * @param string $filterparam
     * @return ResultSet (FilterEntity entity, int filter_count)
     */
    public function countByFilterParam(FilterManager $fm, $filterparam, $limitcount = null);
    
    /**
     * Returns a ResultSet of entities filtered by $filter
     */
    public function findByFilter(array $filter, array $order);    
    
    /**
     * Returns total count of results for a given filter
     */
    public function countByFilter(array $filter); 
    
    /**
     * Returns distinct values registered for a given param
     * in database (distinct column values)
     */
    public function findParamValues($key, $limit = -1);
}
