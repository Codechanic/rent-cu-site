<?php


namespace Vibalco\FrontBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Vibalco\MainBundle\Entity\Homestay;

class CommentRepository extends EntityRepository
{
   public function getCommentByHomestayQuery(Homestay $homestay)
   {
       $qb = $this->createQueryBuilder('c');
       $qb->where('c.enabled = true');
       $qb->andWhere('c.homestay = :homestay');
       $qb->setParameter('homestay', $homestay);
       return $qb->getQuery();
   }
}