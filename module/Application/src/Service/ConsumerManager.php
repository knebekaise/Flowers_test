<?php

namespace Application\Service;

use Application\Entity\Consumer;
use Application\Entity\Group;
use Zend\Filter\StaticFilter;
use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\ServiceManagerAwareInterface;

/**
 * The ConsumerManager service is responsible for adding new consumers, updating existing
 * consumers, etc.
 */
class ConsumerManager
{
    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager;
     */
    private $entityManager;

    /**
     * Image manager.
     * @var Application\Service\ImageManager;
     */
    private $imageManager;

    /**
     * Constructor.
     */
    public function __construct($entityManager, $imageManager)
    {
        $this->entityManager = $entityManager;
        $this->imageManager = $imageManager;
    }

    /**
     * This method adds a new consumer.
     */
    public function addConsumer($data)
    {
        // Create new Consumer entity.
        $consumer = new Consumer();
        $consumer->setLogin($data['login']);
        $consumer->setPassword($data['password']);
        $consumer->setEmail($data['email']);
        $consumer->setExpirationDateTime($data['expirationDateTime']);

        if (!empty($data['avatar']['name'])) {
            $extension = $this->imageManager->getFileExtension($data['avatar']['name']);
            $consumer->setImageExtension($extension);
        }

        $group = $this->entityManager
            ->getRepository(Group::class)
            ->find($data['groupId']);

        $consumer->setGroup($group);

        // Add the entity to entity manager.
        $this->entityManager->persist($consumer);

        // Apply changes to database.
        $this->entityManager->flush();

        if (isset($data['avatar']['tmp_name'])
            && $consumer->getId()
            && $consumer->getImageExtension()
        ) {
            $this->imageManager->moveUploadedFile(
                $data['avatar']['tmp_name'],
                $consumer->getAvatarFileName()
            );
        }

        return $consumer;
    }

    /**
     * This method updates data of an existing consumer.
     */
    public function updateConsumer($consumer, $data)
    {
        $consumer->setLogin($data['login']);
        $consumer->setEmail($data['email']);
        $consumer->setGroupId($data['groupId']);
        $consumer->setExpirationDateTime($data['expirationDateTime']);
        if (!empty($data['avatar']['name'])) {
            $extension = $this->imageManager->getFileExtension($data['avatar']['name']);
            $consumer->setImageExtension($extension);
        }
        if (!empty($data['password'])) {
            $consumer->setPassword($data['password']);
        }

        // Apply changes to database.
        $this->entityManager->flush();

        if (isset($data['avatar']['tmp_name'])
            && $consumer->getId()
            && $consumer->getImageExtension()
        ) {
            $this->imageManager->moveUploadedFile(
                $data['avatar']['tmp_name'],
                $consumer->getAvatarFileName()
            );
        }

        return true;
    }

    /**
     * Removes consumer and all associated comments.
     */
    public function removeConsumer($consumer)
    {
        $this->entityManager->remove($consumer);

        $this->entityManager->flush();
    }
}
