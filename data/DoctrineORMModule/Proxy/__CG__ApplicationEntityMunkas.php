<?php

namespace DoctrineORMModule\Proxy\__CG__\Application\Entity;

/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class Munkas extends \Application\Entity\Munkas implements \Doctrine\ORM\Proxy\Proxy
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
            return array('__isInitialized__', '' . "\0" . 'Application\\Entity\\Munkas' . "\0" . 'id', '' . "\0" . 'Application\\Entity\\Munkas' . "\0" . 'name', '' . "\0" . 'Application\\Entity\\Munkas' . "\0" . 'hiredate', '' . "\0" . 'Application\\Entity\\Munkas' . "\0" . 'birthdate', '' . "\0" . 'Application\\Entity\\Munkas' . "\0" . 'fizetes', '' . "\0" . 'Application\\Entity\\Munkas' . "\0" . 'status', '' . "\0" . 'Application\\Entity\\Munkas' . "\0" . 'munkastipus', 'hely');
        }

        return array('__isInitialized__', '' . "\0" . 'Application\\Entity\\Munkas' . "\0" . 'id', '' . "\0" . 'Application\\Entity\\Munkas' . "\0" . 'name', '' . "\0" . 'Application\\Entity\\Munkas' . "\0" . 'hiredate', '' . "\0" . 'Application\\Entity\\Munkas' . "\0" . 'birthdate', '' . "\0" . 'Application\\Entity\\Munkas' . "\0" . 'fizetes', '' . "\0" . 'Application\\Entity\\Munkas' . "\0" . 'status', '' . "\0" . 'Application\\Entity\\Munkas' . "\0" . 'munkastipus', 'hely');
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (Munkas $proxy) {
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
    public function setName($name)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setName', array($name));

        return parent::setName($name);
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getName', array());

        return parent::getName();
    }

    /**
     * {@inheritDoc}
     */
    public function setHiredate($hiredate)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setHiredate', array($hiredate));

        return parent::setHiredate($hiredate);
    }

    /**
     * {@inheritDoc}
     */
    public function getHiredate()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getHiredate', array());

        return parent::getHiredate();
    }

    /**
     * {@inheritDoc}
     */
    public function setBirthdate($birthdate)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setBirthdate', array($birthdate));

        return parent::setBirthdate($birthdate);
    }

    /**
     * {@inheritDoc}
     */
    public function getBirthdate()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getBirthdate', array());

        return parent::getBirthdate();
    }

    /**
     * {@inheritDoc}
     */
    public function setStatus($status)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setStatus', array($status));

        return parent::setStatus($status);
    }

    /**
     * {@inheritDoc}
     */
    public function getStatus()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getStatus', array());

        return parent::getStatus();
    }

    /**
     * {@inheritDoc}
     */
    public function setHely($hely)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setHely', array($hely));

        return parent::setHely($hely);
    }

    /**
     * {@inheritDoc}
     */
    public function getHely()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getHely', array());

        return parent::getHely();
    }

    /**
     * {@inheritDoc}
     */
    public function getHelyString()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getHelyString', array());

        return parent::getHelyString();
    }

    /**
     * {@inheritDoc}
     */
    public function getRegisegHo($from)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getRegisegHo', array($from));

        return parent::getRegisegHo($from);
    }

    /**
     * {@inheritDoc}
     */
    public function setMunkastipus(\Application\Entity\Munkastipus $munkastipus = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setMunkastipus', array($munkastipus));

        return parent::setMunkastipus($munkastipus);
    }

    /**
     * {@inheritDoc}
     */
    public function getMunkastipus()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getMunkastipus', array());

        return parent::getMunkastipus();
    }

    /**
     * {@inheritDoc}
     */
    public function setFizetes($fizetes)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setFizetes', array($fizetes));

        return parent::setFizetes($fizetes);
    }

    /**
     * {@inheritDoc}
     */
    public function getFizetes()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getFizetes', array());

        return parent::getFizetes();
    }

    /**
     * {@inheritDoc}
     */
    public function getFixFizetes(\Doctrine\Common\Persistence\ObjectManager $objectManager, $from, $hely_id)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getFixFizetes', array($objectManager, $from, $hely_id));

        return parent::getFixFizetes($objectManager, $from, $hely_id);
    }

    /**
     * {@inheritDoc}
     */
    public function getFizetesKategoria(\Doctrine\Common\Persistence\ObjectManager $objectManager, $from, $hely_id)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getFizetesKategoria', array($objectManager, $from, $hely_id));

        return parent::getFizetesKategoria($objectManager, $from, $hely_id);
    }

    /**
     * {@inheritDoc}
     */
    public function getReszesedes(\Doctrine\Common\Persistence\ObjectManager $objectManager, $from, $to)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getReszesedes', array($objectManager, $from, $to));

        return parent::getReszesedes($objectManager, $from, $to);
    }

    /**
     * {@inheritDoc}
     */
    public function getEjjeliMuszakok(\Doctrine\Common\Persistence\ObjectManager $objectManager, $from, $to)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getEjjeliMuszakok', array($objectManager, $from, $to));

        return parent::getEjjeliMuszakok($objectManager, $from, $to);
    }

    /**
     * {@inheritDoc}
     */
    public function getNapiMuszakCountByHely(\Doctrine\Common\Persistence\ObjectManager $objectManager, $from, $to, $hely_id = '')
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getNapiMuszakCountByHely', array($objectManager, $from, $to, $hely_id));

        return parent::getNapiMuszakCountByHely($objectManager, $from, $to, $hely_id);
    }

    /**
     * {@inheritDoc}
     */
    public function getLezartTartozasok(\Doctrine\Common\Persistence\ObjectManager $objectManager, $from, $to)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getLezartTartozasok', array($objectManager, $from, $to));

        return parent::getLezartTartozasok($objectManager, $from, $to);
    }

}