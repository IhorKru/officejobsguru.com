<?php

namespace AppBundle\Entity;

use AppBundle\Entity\SubscriberDetails;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * SubscriberOptInDetails
 *
 * @ORM\Table(name="subscriber_opt_in_details", uniqueConstraints={@ORM\UniqueConstraint(name="subsc_optin_pkey", columns={"id"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SubscriberOptInDetailsRepository")
 */
class SubscriberOptInDetails
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="SubscriberDetails", cascade={"persist"})
     */
    private $user;

    /**
     * @var int
     *
     * @ORM\Column(name="resourceid", type="smallint")
     */
    private $resourceid;

    /**
     * @var bool
     * @Assert\NotBlank (message ="You must agree to OfficeJobsGuru.com Terms & Conditions")
     * @ORM\Column(name="agreeterms", type="boolean")
     */
    private $agreeterms;

    /**
     * @var bool
     * @Assert\NotBlank (message ="You must agree to recieve notifications from OfficeJobsGuru.com")
     * @ORM\Column(name="agreeemails", type="boolean")
     */
    private $agreeemails;

    /**
     * @var bool
     * @Assert\NotBlank (message ="You must agree to recieve notifications from partners of OfficeJobsGuru.com")
     * @ORM\Column(name="agreepartners", type="boolean")
     */
    private $agreepartners;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="optindate", type="datetime")
     */
    private $optindate;

    /**
     * @var string
     *
     * @ORM\Column(name="optinip", type="string", length=50)
     */
    private $optinip;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set userid
     *
     * @param integer $userid
     *
     * @return SubscriberOptInDetails
     */
    public function setUserid($userid)
    {
        $this->userid = $userid;

        return $this;
    }

    /**
     * Get userid
     *
     * @return int
     */
    public function getUserid()
    {
        return $this->userid;
    }

    /**
     * Set resourceid
     *
     * @param integer $resourceid
     *
     * @return SubscriberOptInDetails
     */
    public function setResourceid($resourceid)
    {
        $this->resourceid = $resourceid;

        return $this;
    }

    /**
     * Get resourceid
     *
     * @return int
     */
    public function getResourceid()
    {
        return $this->resourceid;
    }

    /**
     * Set agreeterms
     *
     * @param boolean $agreeterms
     *
     * @return SubscriberOptInDetails
     */
    public function setAgreeterms($agreeterms)
    {
        $this->agreeterms = $agreeterms;

        return $this;
    }

    /**
     * Get agreeterms
     *
     * @return bool
     */
    public function getAgreeterms()
    {
        return $this->agreeterms;
    }

    /**
     * Set agreeemails
     *
     * @param boolean $agreeemails
     *
     * @return SubscriberOptInDetails
     */
    public function setAgreeemails($agreeemails)
    {
        $this->agreeemails = $agreeemails;

        return $this;
    }

    /**
     * Get agreeemails
     *
     * @return bool
     */
    public function getAgreeemails()
    {
        return $this->agreeemails;
    }

    /**
     * Set agreepartners
     *
     * @param boolean $agreepartners
     *
     * @return SubscriberOptInDetails
     */
    public function setAgreepartners($agreepartners)
    {
        $this->agreepartners = $agreepartners;

        return $this;
    }

    /**
     * Get agreepartners
     *
     * @return bool
     */
    public function getAgreepartners()
    {
        return $this->agreepartners;
    }

    /**
     * Set optindate
     *
     * @param \DateTime $optindate
     *
     * @return SubscriberOptInDetails
     */
    public function setOptindate($optindate)
    {
        $this->optindate = $optindate;

        return $this;
    }

    /**
     * Get optindate
     *
     * @return \DateTime
     */
    public function getOptindate()
    {
        return $this->optindate;
    }

    /**
     * Set optinip
     *
     * @param string $optinip
     *
     * @return SubscriberOptInDetails
     */
    public function setOptinip($optinip)
    {
        $this->optinip = $optinip;

        return $this;
    }

    /**
     * Get optinip
     *
     * @return string
     */
    public function getOptinip()
    {
        return $this->optinip;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\SubscriberDetails $id
     *
     * @return SubscriberOptInDetails
     */
    public function setUser(\AppBundle\Entity\SubscriberDetails $id = null)
    {
        $this->user = $id;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\SubscriberDetails
     */
    public function getUser()
    {
        return $this->user;
    }
}
