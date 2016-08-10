<?php

namespace AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;


/**
 * SubscriberDetails
 *
 * @ORM\Table(name="subscriber_details", uniqueConstraints={@ORM\UniqueConstraint(name="subsc_details_pkey", columns={"id"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SubscriberDetailsRepository")
 */
class SubscriberDetails
{
    
    private $optindetails;
            
    public function __construct()
    {
        $this->optindetails = new ArrayCollection();
    }
    
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @Assert\NotBlank (message="Complete First Name field")
     * @ORM\Column(name="firstname", type="string", length=100)
     */
    private $firstname;

    /**
     * @var string
     * @Assert\NotBlank (message="Complete Last Name field")
     * @ORM\Column(name="lastname", type="string", length=100)
     */
    private $lastname;

    /**
     * @var string
     *
     * @Assert\NotBlank (message="Complete Email Address field")
     * @ORM\Column(name="emailaddress", type="string", length=100)
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email.",
     *     checkMX = true,
     *     checkHost = true
     * )
     */
    private $emailaddress;

    /**
     * @var string
     *
     * @Assert\NotBlank (message="Complete Mobile Phone field")
     * @ORM\Column(name="phone", type="string", length=50)
     * @Assert\Length(min=5) (message="Phone lenght must be over 5 characters")
     */
    private $phone;

    /**
     * @var int
     * @Assert\NotBlank (message="Complete Age field")
     * @ORM\Column(name="age", type="smallint")
     */
    private $age;

    /**
     * @var int
     * @Assert\NotBlank (message="Complete Gender field")
     * @ORM\Column(name="gender", type="smallint")
     */
    private $gender;

    /**
     * @var int
     *
     * @ORM\Column(name="educationlevelid", type="smallint")
     */
    private $educationlevelid;

    /**
     * @var string
     *
     * @ORM\Column(name="hash", type="string", length=255)
     */
    private $hash;


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
     * Set firstname
     *
     * @param string $firstname
     *
     * @return SubscriberDetails
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     *
     * @return SubscriberDetails
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set emailaddress
     *
     * @param string $emailaddress
     *
     * @return SubscriberDetails
     */
    public function setEmailaddress($emailaddress)
    {
        $this->emailaddress = $emailaddress;

        return $this;
    }

    /**
     * Get emailaddress
     *
     * @return string
     */
    public function getEmailaddress()
    {
        return $this->emailaddress;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return SubscriberDetails
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set age
     *
     * @param integer $age
     *
     * @return SubscriberDetails
     */
    public function setAge($age)
    {
        $this->age = $age;

        return $this;
    }

    /**
     * Get age
     *
     * @return int
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * Set gender
     *
     * @param integer $gender
     *
     * @return SubscriberDetails
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return int
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set educationlevelid
     *
     * @param integer $educationlevelid
     *
     * @return SubscriberDetails
     */
    public function setEducationlevelid($educationlevelid)
    {
        $this->educationlevelid = $educationlevelid;

        return $this;
    }

    /**
     * Get educationlevelid
     *
     * @return int
     */
    public function getEducationlevelid()
    {
        return $this->educationlevelid;
    }

    /**
     * Set hash
     *
     * @param string $hash
     *
     * @return SubscriberDetails
     */
    public function setHash($hash)
    {
        $this->hash = $hash;

        return $this;
    }

    /**
     * Get hash
     *
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }
    
     public function getOptindetails()
    {
        return $this->optindetails;
    }
    
    public function setOptindetails(ArrayCollection $optindetails)
    {
        $this->optindetails = $optindetails;
    }
}
