<?php
/**
 * @package thorpesystems
 * @subpackage This
 * @category Orm_Config
 * @version $Id: Config.php 903 2007-12-03 00:45:29Z tonyq $
 */

/**
 * @package thorpesystems
 * @subpackage This
 * @category Orm_Config
 * @version $Id: Config.php 903 2007-12-03 00:45:29Z tonyq $
 */
class This_Orm_Config
{
    /**
     * Store an instance.
     *
     * @var This_Orm_Config
     */
    private static $_self;

    /**
     * Should logging be carried out.
     * @var bool
     */
    private static $_logger = false;

    /**
     * Path to log file.
     * @var string
     */
    private static $_logpath = '';

    /**
     * Store our db adapter
     *
     * @var Zend_Db_Adapter_Abstract
     */
    private $_db;

    /**
     * Store an array of namespaces to search for models.
     *
     * @var array
     */
    private $_modelSpaces = array('This_Model_', 'This_Model_Module_');

    /**
     * Store an array of namespaces to search for behaviours.
     *
     * @var array
     */
    private $_behaviourSpaces = array('This_Orm_Behaviour_');

    /**
     * Store an array of namespaces to search for mutators.
     *
     * @var array
     */
    private $_mutatorSpaces = array('This_Orm_Mutator_');

    /**
     * Store an array of namespaces to search for constraints.
     *
     * @var array
     */
    private $_constraintSpaces = array('This_Orm_Constraint_');

    /**
     * Store an array of namespaces to search for custom exceptions.
     *
     * @var array
     */
    private $_exceptionSpaces = array(
        'This_Orm_Exception_',
        'This_Orm_Exception_Constraint_',
        'This_Orm_Exception_Constraint_Unique_',
    );

    /*
     * Create a singleton.
     */
    public static function getInstance()
    {
        if(!self::$_self) {
            self::$_self = new self();
        }
        
	return self::$_self;
    }

    /*
     * Prohibit this object from being instantiated directly.
     */
    private function __construct()
    {}

    public function setLogging($switch, $path)
    {
        self::$_logger = $switch;
        self::$_logpath = $path;
        return self::$_self;
    }

    public static function log($msg)
    {
        if (self::$_logger) {
            $date = date('d.m.y H:i:s');
            file_put_contents(
                self::$_logpath,
                sprintf("%s - %s\n", $date, $msg),
                FILE_APPEND
            );
        }
    }

    /*
     * Set the default database adapter.
     */
    public function setAdapter(Zend_Db_Adapter_Abstract $db)
    {
        $this->_db = $db;
        return $this;
    }

    /**
     * Retrieve the default database adapter.
     *
     * @return Zend_Db_Adapter_Abstract
     */
    public function getAdapter()
    {
        if ($this->_db instanceof Zend_Db_Adapter_Abstract) {
            return $this->_db;
        }
        return false;
    }

    /**
     * Add model namespace to search path.
     *
     * @param string|array $namespace
     * @return This_Orm_Config
     */
    public function setModelNamespaces($namespace)
    {
        if (is_array($namespace)) {
            array_merge($this->_modelSpaces, $namespace);
        }
        $this->_modelSpaces[] = $namespace;
        return $this;
    }

    /**
     * Retrieve array of namespaces
     *
     * @return array
     */
    public function getModelNamespaces()
    {
        return $this->_modelSpaces;
    }

    /**
     * Add behaviour namespace to search path.
     *
     * @param string|array $namespace
     * @return This_Orm_Config
     */
    public function setBehaviourNamespaces($namespace)
    {
        if (is_array($namespace)) {
            array_merge($this->_behaviourSpaces, $namespace);
        }
        $this->_behaviourSpaces[] = $namespace;
        return $this;
    }

    /**
     * Retrieve array of namespaces
     *
     * @return array
     */
    public function getBehaviourNamespaces()
    {
        return $this->_behaviourSpaces;
    }

    /**
     * Add mutator namespace to search path.
     *
     * @param string|array $namespace
     * @return This_Orm_Config
     */
    public function setMutatorNamespaces($namespace)
    {
        if (is_array($namespace)) {
            array_merge($this->_mutatorSpaces, $namespace);
        }
        $this->_mutatorSpaces[] = $namespace;
        return $this;
    }

    /**
     * Retrieve array of namespaces
     *
     * @return array
     */
    public function getMutatorNamespaces()
    {
        return $this->_mutatorSpaces;
    }

    /**
     * Add exception namespace to search path.
     *
     * @param string|array $namespace
     * @return This_Orm_Config
     */
    public function setExceptionNamespaces($namespace)
    {
        if (is_array($namespace)) {
            array_merge($this->_exceptionSpaces, $namespace);
        }
        $this->_exceptionSpaces[] = $namespace;
        return $this;
    }

    /**
     * Retrieve array of namespaces
     *
     * @return array
     */
    public function getExceptionNamespaces()
    {
        return $this->_exceptionSpaces;
    }

    /**
     * Add constraint namespace to search path.
     *
     * @param string|array $namespace
     * @return This_Orm_Config
     */
    public function setConstraintNamespaces($namespace)
    {
        if (is_array($namespace)) {
            array_merge($this->_constraintSpaces, $namespace);
        }
        $this->_constraintSpaces[] = $namespace;
        return $this;
    }

    /**
     * Retrieve array of namespaces.
     *
     * @return array
     */
    public function getConstraintNamespaces()
    {
        return $this->_constraintSpaces;
    }
}
