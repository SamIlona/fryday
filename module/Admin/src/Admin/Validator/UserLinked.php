<?php

namespace Admin\Validator;

use Zend\Validator\AbstractValidator;

use Doctrine\ORM\EntityManager;

class UserLinked extends AbstractValidator
{
    const ERROR_USER_LINKED    = 'objectFound';

    protected $messageTemplates = array(
        self::ERROR_USER_LINKED => "User already added"
    );

    protected $cityID;
    protected $em;

    public function __construct($options)
    {
        parent::__construct($options);
        
        $this->cityID = $options['cityID'];
        $this->em = $options['em'];
    }

    public function isValid($value)
    {
        $this->setValue($value);

        if (!$this->em->getRepository('Admin\Entity\LinkCityUser')->isUserLinked($this->cityID, $value)) 
        {
            $this->error(self::ERROR_USER_LINKED);
            return false;
        }

        return true;
    }
}