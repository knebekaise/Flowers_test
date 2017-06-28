<?php
namespace Application\Repository;

use Doctrine\ORM\EntityRepository;
use Application\Entity\Consumer;

/**
 * This is the custom repository class for Consumer entity.
 */
class ConsumerRepository extends EntityRepository
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

        $queryBuilder->select('c')
            ->from(Consumer::class, 'c')
            ->join('c.group', 'g');

        // Apply filters
        if (!empty($filter)) {
            if (!empty($filter['expirationDateTime'])) {
                $expirationDate = new \DateTime($filter['expirationDateTime']);
                $startTimestamp = $expirationDate->getTimestamp();
                $expirationDate->modify('+1 day');
                $endTimestamp = $expirationDate->getTimestamp();
                $queryBuilder = $queryBuilder
                        ->andWhere('c.expirationDateTime >= :startDate')
                        ->andWhere('c.expirationDateTime < :endDate')
                        ->setParameter(':startDate', $startTimestamp)
                        ->setParameter(':endDate', $endTimestamp);
                unset($filter['expirationDateTime']);
            }

            $paramIndex = 0;
            foreach ($filter as $column => $value) {
                if (!empty($value)) {
                    $paramName = ':param' . $paramIndex;
                    $alias = ($column == 'group') ? 'g' : 'c';
                    $queryBuilder = $queryBuilder
                        ->andWhere($alias . '.'. $column . ' = ' . $paramName)
                        ->setParameter($paramName, $value);
                    $paramIndex++;
                }
            }
        }

        // Apply order
        if (!empty($order)) {
            foreach ($order as $column => $value) {
                $queryBuilder = $queryBuilder->orderBy('c.' . $column, $value);
            }
        }

        return $queryBuilder->getQuery();
    }
}
