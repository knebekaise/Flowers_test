<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * This class represents a single consumer group.
 * @ORM\Entity(repositoryClass="Application\Repository\GroupRepository")
 * @ORM\Table(name="`group`")
 */
class Group
{
    /**
     * @ORM\Id
     * @ORM\Column(name="groupId")
     * @ORM\GeneratedValue
     */
    protected $groupId;

    /**
     * @ORM\Column(name="name")
     */
    protected $name;

    /**
     * Returns ID of this post.
     * @return integer
     */
    public function getId()
    {
        return $this->groupId;
    }

    /**
     * Sets ID of this post.
     * @param int $groupId
     */
    public function setId($groupId)
    {
        $this->groupId = $groupId;
    }

    /**
     * Returns name.
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets name.
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
}
