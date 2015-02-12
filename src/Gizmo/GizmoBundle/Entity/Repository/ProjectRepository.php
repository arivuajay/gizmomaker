<?php
/**
 * Created by PhpStorm.
 * User: asanka
 * Date: 12/8/14
 * Time: 2:18 PM
 */

namespace Gizmo\GizmoBundle\Entity\Repository;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;

class ProjectRepository extends EntityRepository
{

    public function getProjectsPaged($paginator, $page, $limit = 10){
        $qb = $this->_em->createQueryBuilder();
        $qb->select('p')
            ->from('GizmoBundle:Project','p')
            ->where('p.isPublished = :isPublished')->setParameter('isPublished',1);
        $pagination = $paginator->paginate(
            $qb->getQuery(),
            $page,
            $limit
        );
        return $pagination;
    }

    public function getPublishedRandomProjects($limit, $for_home, &$current_ids)
    {

        $qb = $this->_em->createQueryBuilder();
        $qb->select('partial p.{id}');
        $qb->from(' GizmoBundle:Project', 'p')
            ->where('p.isPublished = :isPublished')->setParameter('isPublished', 1);

        //skip previously selected ids...
        if ($for_home && !empty($current_ids)) {
            $qb->andWhere('p.id NOT IN (:ids)')
                ->setParameter('ids', $current_ids);
        }

        $result = $qb->getQuery()->getScalarResult();
        $ids = array_map('current', $result);
        shuffle($ids);
        $ids = array_slice($ids, 0, $limit);
        $qb = $this->_em->createQueryBuilder();
        $qb->select('p')->from('GizmoBundle:Project', 'p')->distinct();

        // $qb ->andWhere($qb->expr()->notIn('p.id', \Session::get('random_projects_selected_ids')));
        $qb->andWhere('p.id IN (:ids)')
            ->setParameter('ids', $ids);


        $query = $qb->getQuery();
        $result = $query->getResult();

        $current_ids = $ids;

        return $result;

    }




}