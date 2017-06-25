<?php

namespace Application\Service;

use Application\Entity\Group;
use Zend\Filter\StaticFilter;
use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\ServiceManagerAwareInterface;

/**
 * The GroupManager service is responsible for adding new groups, updating existing
 * groups, etc.
 */
class GroupManager
{
    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager;
     */
    private $entityManager;

    /**
     * Constructor.
     */
    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * This method adds a new group.
     */
    public function addGroup($data)
    {
        // Create new Group entity.
        $group = new Group();
        $group->setName($data['name']);

        // Add the entity to entity manager.
        $this->entityManager->persist($group);

        // Apply changes to database.
        $this->entityManager->flush();
    }

    /**
     * This method updates data of an existing group.
     */
    public function updategroup($group, $data)
    {
        $group->setName($data['name']);

        // Apply changes to database.
        $this->entityManager->flush();

        return true;
    }

    /**
     * Removes group and all associated comments.
     */
    public function removeGroup($group)
    {
        $this->entityManager->remove($group);

        $this->entityManager->flush();
    }
}
