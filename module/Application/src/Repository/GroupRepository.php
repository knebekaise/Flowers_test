<?php
namespace Application\Repository;

use Doctrine\ORM\EntityRepository;
use Application\Entity\Group;

/**
 * This is the custom repository class for Group entity.
 */
class GroupRepository extends EntityRepository
{
    /**
     * Get query for pagination with filtering and ordering
     * @param  array $filter
     * @param  array $order
     * @return Doctrine\ORM\Query
     */
    public function findForPagination($filter, $order)
    {
        $entityManager = $this->getEntityManager();

        $queryBuilder = $entityManager->createQueryBuilder();

        $queryBuilder->select('g')
            ->from(Group::class, 'g')
            ->where('1 = 1');

        // Apply filters
        if (!empty($filter)) {
            $paramIndex = 0;
            foreach ($filter as $column => $value) {
                $paramName = ':param' . $paramIndex;
                $queryBuilder = $queryBuilder
                    ->andWhere('g.`'. $column . '` = ' . $paramName)
                    ->setParameter($paramName, $value);
                $paramIndex++;
            }
        }

        // Apply order
        if (!empty($order)) {
            foreach ($order as $column => $value) {
                $queryBuilder = $queryBuilder->orderBy('g.' . $column, $value);
            }
        }

        return $queryBuilder->getQuery();
    }
}
