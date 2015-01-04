<?php

namespace DoctrineORMModule\Proxy\__CG__\Admin\Entity;

/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class Event extends \Admin\Entity\Event implements \Doctrine\ORM\Proxy\Proxy
{
    /**
     * @var \Closure the callback responsible for loading properties in the proxy object. This callback is called with
     *      three parameters, being respectively the proxy object to be initialized, the method that triggered the
     *      initialization process and an array of ordered parameters that were passed to that method.
     *
     * @see \Doctrine\Common\Persistence\Proxy::__setInitializer
     */
    public $__initializer__;

    /**
     * @var \Closure the callback responsible of loading properties that need to be copied in the cloned object
     *
     * @see \Doctrine\Common\Persistence\Proxy::__setCloner
     */
    public $__cloner__;

    /**
     * @var boolean flag indicating if this object was already initialized
     *
     * @see \Doctrine\Common\Persistence\Proxy::__isInitialized
     */
    public $__isInitialized__ = false;

    /**
     * @var array properties to be lazy loaded, with keys being the property
     *            names and values being their default values
     *
     * @see \Doctrine\Common\Persistence\Proxy::__getLazyProperties
     */
    public static $lazyPropertiesDefaults = array();



    /**
     * @param \Closure $initializer
     * @param \Closure $cloner
     */
    public function __construct($initializer = null, $cloner = null)
    {

        $this->__initializer__ = $initializer;
        $this->__cloner__      = $cloner;
    }







    /**
     * 
     * @return array
     */
    public function __sleep()
    {
        if ($this->__isInitialized__) {
            return array('__isInitialized__', 'id', 'title', 'titleslug', 'dateslug', 'dateTimeEvent', 'image', 'city', 'venue', 'description', 'details', 'user', 'entrancefee', 'pubished', 'newsletter', 'newsletterCreated', 'newsletterSend', 'dateTimeCreated');
        }

        return array('__isInitialized__', 'id', 'title', 'titleslug', 'dateslug', 'dateTimeEvent', 'image', 'city', 'venue', 'description', 'details', 'user', 'entrancefee', 'pubished', 'newsletter', 'newsletterCreated', 'newsletterSend', 'dateTimeCreated');
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (Event $proxy) {
                $proxy->__setInitializer(null);
                $proxy->__setCloner(null);

                $existingProperties = get_object_vars($proxy);

                foreach ($proxy->__getLazyProperties() as $property => $defaultValue) {
                    if ( ! array_key_exists($property, $existingProperties)) {
                        $proxy->$property = $defaultValue;
                    }
                }
            };

        }
    }

    /**
     * 
     */
    public function __clone()
    {
        $this->__cloner__ && $this->__cloner__->__invoke($this, '__clone', array());
    }

    /**
     * Forces initialization of the proxy
     */
    public function __load()
    {
        $this->__initializer__ && $this->__initializer__->__invoke($this, '__load', array());
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __isInitialized()
    {
        return $this->__isInitialized__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setInitialized($initialized)
    {
        $this->__isInitialized__ = $initialized;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setInitializer(\Closure $initializer = null)
    {
        $this->__initializer__ = $initializer;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __getInitializer()
    {
        return $this->__initializer__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setCloner(\Closure $cloner = null)
    {
        $this->__cloner__ = $cloner;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific cloning logic
     */
    public function __getCloner()
    {
        return $this->__cloner__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     * @static
     */
    public function __getLazyProperties()
    {
        return self::$lazyPropertiesDefaults;
    }

    
    /**
     * {@inheritDoc}
     */
    public function getId()
    {
        if ($this->__isInitialized__ === false) {
            return (int)  parent::getId();
        }


        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getId', array());

        return parent::getId();
    }

    /**
     * {@inheritDoc}
     */
    public function setTitle($title)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setTitle', array($title));

        return parent::setTitle($title);
    }

    /**
     * {@inheritDoc}
     */
    public function getTitle()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTitle', array());

        return parent::getTitle();
    }

    /**
     * {@inheritDoc}
     */
    public function setTitleSlug($titleslug)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setTitleSlug', array($titleslug));

        return parent::setTitleSlug($titleslug);
    }

    /**
     * {@inheritDoc}
     */
    public function getTitleSlug()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTitleSlug', array());

        return parent::getTitleSlug();
    }

    /**
     * {@inheritDoc}
     */
    public function setDateSlug($dateslug)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setDateSlug', array($dateslug));

        return parent::setDateSlug($dateslug);
    }

    /**
     * {@inheritDoc}
     */
    public function getDateSlug()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getDateSlug', array());

        return parent::getDateSlug();
    }

    /**
     * {@inheritDoc}
     */
    public function setImage($image)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setImage', array($image));

        return parent::setImage($image);
    }

    /**
     * {@inheritDoc}
     */
    public function getImage()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getImage', array());

        return parent::getImage();
    }

    /**
     * {@inheritDoc}
     */
    public function setVenue($venue)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setVenue', array($venue));

        return parent::setVenue($venue);
    }

    /**
     * {@inheritDoc}
     */
    public function getVenue()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getVenue', array());

        return parent::getVenue();
    }

    /**
     * {@inheritDoc}
     */
    public function setCity($city)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setCity', array($city));

        return parent::setCity($city);
    }

    /**
     * {@inheritDoc}
     */
    public function getCity()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getCity', array());

        return parent::getCity();
    }

    /**
     * {@inheritDoc}
     */
    public function getDateTimeEvent()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getDateTimeEvent', array());

        return parent::getDateTimeEvent();
    }

    /**
     * {@inheritDoc}
     */
    public function setDateTimeEvent($dateTimeEvent)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setDateTimeEvent', array($dateTimeEvent));

        return parent::setDateTimeEvent($dateTimeEvent);
    }

    /**
     * {@inheritDoc}
     */
    public function getDetails()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getDetails', array());

        return parent::getDetails();
    }

    /**
     * {@inheritDoc}
     */
    public function setDetails($details)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setDetails', array($details));

        return parent::setDetails($details);
    }

    /**
     * {@inheritDoc}
     */
    public function getDescription()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getDescription', array());

        return parent::getDescription();
    }

    /**
     * {@inheritDoc}
     */
    public function setDescription($description)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setDescription', array($description));

        return parent::setDescription($description);
    }

    /**
     * {@inheritDoc}
     */
    public function setUser($user)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setUser', array($user));

        return parent::setUser($user);
    }

    /**
     * {@inheritDoc}
     */
    public function getUser()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getUser', array());

        return parent::getUser();
    }

    /**
     * {@inheritDoc}
     */
    public function setEntranceFee($entrancefee)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setEntranceFee', array($entrancefee));

        return parent::setEntranceFee($entrancefee);
    }

    /**
     * {@inheritDoc}
     */
    public function getEntranceFee()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getEntranceFee', array());

        return parent::getEntranceFee();
    }

    /**
     * {@inheritDoc}
     */
    public function getDateTimeCreated()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getDateTimeCreated', array());

        return parent::getDateTimeCreated();
    }

    /**
     * {@inheritDoc}
     */
    public function setPubished($pubished)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setPubished', array($pubished));

        return parent::setPubished($pubished);
    }

    /**
     * {@inheritDoc}
     */
    public function getPubished()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPubished', array());

        return parent::getPubished();
    }

    /**
     * {@inheritDoc}
     */
    public function setNewsletter($newsletter)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setNewsletter', array($newsletter));

        return parent::setNewsletter($newsletter);
    }

    /**
     * {@inheritDoc}
     */
    public function getNewsletter()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getNewsletter', array());

        return parent::getNewsletter();
    }

    /**
     * {@inheritDoc}
     */
    public function setNewsletterCreated($newsletterCreated)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setNewsletterCreated', array($newsletterCreated));

        return parent::setNewsletterCreated($newsletterCreated);
    }

    /**
     * {@inheritDoc}
     */
    public function getNewsletterCreated()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getNewsletterCreated', array());

        return parent::getNewsletterCreated();
    }

    /**
     * {@inheritDoc}
     */
    public function setNewsletterSend($newsletterSend)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setNewsletterSend', array($newsletterSend));

        return parent::setNewsletterSend($newsletterSend);
    }

    /**
     * {@inheritDoc}
     */
    public function getNewsletterSend()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getNewsletterSend', array());

        return parent::getNewsletterSend();
    }

}
