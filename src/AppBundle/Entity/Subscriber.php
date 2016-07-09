<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Subscriber
 *
 * @ORM\Table(name="MediaBay_Master", uniqueConstraints={@ORM\UniqueConstraint(name="subscriber_pkey", columns={"id"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SubscriberRepository")
 */
class Subscriber
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
     * @var string
     *
     * @Assert\NotBlank (message="Complete First Name field")
     * @ORM\Column(name="firstname", type="string", length=255)
     */
    private $firstname;

    /**
     * @var string
     *
     * @Assert\NotBlank (message="Complete Last Name field")
     * @ORM\Column(name="lastname", type="string", length=50)
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
     * @Assert\NotBlank (message="Complete Mobile Phone field")
     * @ORM\Column(name="phone", type="string", length=50)
     */
    private $phone;

    /**
     * @var int
     *
     * @Assert\GreaterThanOrEqual("18", message="You must be 18 or over to subscribe")
     * @Assert\NotBlank (message="Complete Age field")
     * @ORM\Column(name="age", type="smallint")
     */
    private $age;

    /**
     * @var int
     *
     * @ORM\Column(name="gender", type="smallint", nullable=true)
     */
    private $gender;

    /**
     * @var int
     *
     * @ORM\Column(name="education_level_id", type="smallint", nullable=true)
     */
    private $educationLevelId;

    /**
     * @var int
     *
     * @ORM\Column(name="resource_id", type="smallint")
     */
    private $resourceId;

    /**
     * @var bool
     * @Assert\NotBlank (message ="You must agree to Terms & Conditions")
     * @ORM\Column(name="agree_terms", type="boolean")
     */
    private $agreeTerms;

    /**
     * @var bool
     * @Assert\NotBlank (message ="You must agree to recieve notifications from OfficeJobsGuru.com")
     * @ORM\Column(name="agree_emails", type="boolean")
     */
    private $agreeEmails;

    /**
     * @var bool
     * Assert\NotBlank (message ="You must agree to recieve notifications from partners of OfficeJobsGuru.com")
     * @ORM\Column(name="agree_partners", type="boolean")
     */
    private $agreePartners;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="subscription_date", type="datetime")
     */
    private $subscriptionDate;

    /**
     * @var string
     *
     * @ORM\Column(name="subscription_ip", type="string", length=50)
     */
    private $subscriptionIp;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="unsubscription_date", type="datetime", nullable=true)
     */
    private $unsubscriptionDate;

    /**
     * @var string
     *
     * @ORM\Column(name="unsubscription_ip", type="string", length=50, nullable=true)
     */
    private $unsubscriptionIp;

    /**
     * @var string
     *
     * @ORM\Column(name="continent_code", type="string", length=50, nullable=true)
     */
    private $continentCode;

    /**
     * @var string
     *
     * @ORM\Column(name="country_name", type="string", length=50, nullable=true)
     */
    private $countryName;

    /**
     * @var string
     *
     * @ORM\Column(name="region", type="string", length=255, nullable=true)
     */
    private $region;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255, nullable=true)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="postal_code", type="string", length=50, nullable=true)
     */
    private $postalCode;

    /**
     * @var string
     *
     * @ORM\Column(name="latitude", type="string", length=50, nullable=true)
     */
    private $latitude;

    /**
     * @var string
     *
     * @ORM\Column(name="longitude", type="string", length=50, nullable=true)
     */
    private $longitude;

    /**
     * @var string
     *
     * @ORM\Column(name="dma_code", type="string", length=50, nullable=true)
     */
    private $dmaCode;

    /**
     * @var string
     *
     * @ORM\Column(name="area_code", type="string", length=50, nullable=true)
     */
    private $areaCode;

    /**
     * @var string
     *
     * @ORM\Column(name="hash", type="string", length=255, nullable=true)
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
     * @return Subscriber
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
     * @return Subscriber
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
     * @return Subscriber
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
     * @return Subscriber
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
     * @return Subscriber
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
     * @return Subscriber
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
     * Set educationLevelId
     *
     * @param integer $educationLevelId
     *
     * @return Subscriber
     */
    public function setEducationLevelId($educationLevelId)
    {
        $this->educationLevelId = $educationLevelId;

        return $this;
    }

    /**
     * Get educationLevelId
     *
     * @return int
     */
    public function getEducationLevelId()
    {
        return $this->educationLevelId;
    }

    /**
     * Set resourceId
     *
     * @param integer $resourceId
     *
     * @return Subscriber
     */
    public function setResourceId($resourceId)
    {
        $this->resourceId = $resourceId;

        return $this;
    }

    /**
     * Get resourceId
     *
     * @return int
     */
    public function getResourceId()
    {
        return $this->resourceId;
    }

    /**
     * Set agreeTerms
     *
     * @param boolean $agreeTerms
     *
     * @return Subscriber
     */
    public function setAgreeTerms($agreeTerms)
    {
        $this->agreeTerms = $agreeTerms;

        return $this;
    }

    /**
     * Get agreeTerms
     *
     * @return bool
     */
    public function getAgreeTerms()
    {
        return $this->agreeTerms;
    }

    /**
     * Set agreeEmails
     *
     * @param boolean $agreeEmails
     *
     * @return Subscriber
     */
    public function setAgreeEmails($agreeEmails)
    {
        $this->agreeEmails = $agreeEmails;

        return $this;
    }

    /**
     * Get agreeEmails
     *
     * @return bool
     */
    public function getAgreeEmails()
    {
        return $this->agreeEmails;
    }

    /**
     * Set agreePartners
     *
     * @param boolean $agreePartners
     *
     * @return Subscriber
     */
    public function setAgreePartners($agreePartners)
    {
        $this->agreePartners = $agreePartners;

        return $this;
    }

    /**
     * Get agreePartners
     *
     * @return bool
     */
    public function getAgreePartners()
    {
        return $this->agreePartners;
    }

    /**
     * Set subscriptionDate
     *
     * @param \DateTime $subscriptionDate
     *
     * @return Subscriber
     */
    public function setSubscriptionDate($subscriptionDate)
    {
        $this->subscriptionDate = $subscriptionDate;

        return $this;
    }

    /**
     * Get subscriptionDate
     *
     * @return \DateTime
     */
    public function getSubscriptionDate()
    {
        return $this->subscriptionDate;
    }

    /**
     * Set subscriptionIp
     *
     * @param string $subscriptionIp
     *
     * @return Subscriber
     */
    public function setSubscriptionIp($subscriptionIp)
    {
        $this->subscriptionIp = $subscriptionIp;

        return $this;
    }

    /**
     * Get subscriptionIp
     *
     * @return string
     */
    public function getSubscriptionIp()
    {
        return $this->subscriptionIp;
    }

    /**
     * Set unsubscriptionDate
     *
     * @param \DateTime $unsubscriptionDate
     *
     * @return Subscriber
     */
    public function setUnsubscriptionDate($unsubscriptionDate)
    {
        $this->unsubscriptionDate = $unsubscriptionDate;

        return $this;
    }

    /**
     * Get unsubscriptionDate
     *
     * @return \DateTime
     */
    public function getUnsubscriptionDate()
    {
        return $this->unsubscriptionDate;
    }

    /**
     * Set unsubscriptionIp
     *
     * @param string $unsubscriptionIp
     *
     * @return Subscriber
     */
    public function setUnsubscriptionIp($unsubscriptionIp)
    {
        $this->unsubscriptionIp = $unsubscriptionIp;

        return $this;
    }

    /**
     * Get unsubscriptionIp
     *
     * @return string
     */
    public function getUnsubscriptionIp()
    {
        return $this->unsubscriptionIp;
    }

    /**
     * Set continentCode
     *
     * @param string $continentCode
     *
     * @return Subscriber
     */
    public function setContinentCode($continentCode)
    {
        $this->continentCode = $continentCode;

        return $this;
    }

    /**
     * Get continentCode
     *
     * @return string
     */
    public function getContinentCode()
    {
        return $this->continentCode;
    }

    /**
     * Set countryName
     *
     * @param string $countryName
     *
     * @return Subscriber
     */
    public function setCountryName($countryName)
    {
        $this->countryName = $countryName;

        return $this;
    }

    /**
     * Get countryName
     *
     * @return string
     */
    public function getCountryName()
    {
        return $this->countryName;
    }

    /**
     * Set region
     *
     * @param string $region
     *
     * @return Subscriber
     */
    public function setRegion($region)
    {
        $this->region = $region;

        return $this;
    }

    /**
     * Get region
     *
     * @return string
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return Subscriber
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set postalCode
     *
     * @param string $postalCode
     *
     * @return Subscriber
     */
    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    /**
     * Get postalCode
     *
     * @return string
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * Set latitude
     *
     * @param string $latitude
     *
     * @return Subscriber
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return string
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param string $longitude
     *
     * @return Subscriber
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return string
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set dmaCode
     *
     * @param string $dmaCode
     *
     * @return Subscriber
     */
    public function setDmaCode($dmaCode)
    {
        $this->dmaCode = $dmaCode;

        return $this;
    }

    /**
     * Get dmaCode
     *
     * @return string
     */
    public function getDmaCode()
    {
        return $this->dmaCode;
    }

    /**
     * Set areaCode
     *
     * @param string $areaCode
     *
     * @return Subscriber
     */
    public function setAreaCode($areaCode)
    {
        $this->areaCode = $areaCode;

        return $this;
    }

    /**
     * Get areaCode
     *
     * @return string
     */
    public function getAreaCode()
    {
        return $this->areaCode;
    }

    /**
     * Set hash
     *
     * @param string $hash
     *
     * @return Subscriber
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
}

