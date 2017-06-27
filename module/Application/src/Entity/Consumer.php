<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * This class represents a single consumer.
 * @ORM\Entity(repositoryClass="Application\Repository\ConsumerRepository")
 * @ORM\Table(name="`consumer`")
 */
class Consumer
{
    /**
     * @ORM\Id
     * @ORM\Column(name="consumerId")
     * @ORM\GeneratedValue
     */
    protected $consumerId;

    /**
     * @ORM\Column(name="groupId")
     */
    protected $groupId;

    /**
     * @ORM\Column(name="login")
     */
    protected $login;

    /**
     * @ORM\Column(name="password")
     */
    protected $password;

    /**
     * @ORM\Column(name="email")
     */
    protected $email;

    /**
     * @ORM\Column(name="expirationDateTime")
     */
    protected $expirationDateTime;

    /**
     * @ORM\Column(name="imageExtention")
     */
    protected $imageExtention;

    /**
     * @ORM\ManyToOne(targetEntity="\Application\Entity\Group")
     * @ORM\JoinColumn(name="groupId", referencedColumnName="groupId")
     */
    protected $group;


    /**
     * Returns ID of this post.
     * @return integer
     */
    public function getId()
    {
        return $this->consumerId;
    }

    /**
     * Sets ID of this post.
     * @param int $consumerId
     */
    public function setId($consumerId)
    {
        $this->consumerId = $consumerId;
    }

    /**
     * Returns groupId.
     * @return string
     */
    public function getGroupId()
    {
        return $this->groupId;
    }

    /**
     * Sets groupId.
     * @param string $groupId
     */
    public function setGroupId($groupId)
    {
        $this->groupId = $groupId;
    }

    /**
     * Return group.
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * Adds groupId.
     * @param $group
     */
    public function setGroup($group)
    {
        $this->group = $group;
    }

    /**
     * Returns login.
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Sets login.
     * @param string $login
     */
    public function setLogin($login)
    {
        $this->login = $login;
    }

    /**
     * Returns password.
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Sets password.
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * Returns email.
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Sets email.
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Returns expirationDateTime.
     * @return string
     */
    public function getExpirationDateTime()
    {
        return $this->expirationDateTime;
    }

    /**
     * Sets expirationDateTime.
     * @param string $expirationDateTime
     */
    public function setExpirationDateTime($expirationDateTime)
    {
        $this->expirationDateTime = $expirationDateTime;
    }
}
