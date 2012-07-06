<?php
/**
 * @package thorpesystems
 * @subpackage This
 * @category Orm_Entity
 * @version $Id: Entity.php 767 2007-11-02 03:52:17Z tonyq $
 */

/**
 * @package thorpesystems
 * @subpackage This
 * @category Orm_Entity
 * @version $Id: Entity.php 767 2007-11-02 03:52:17Z tonyq $
 */
class This_Orm_Entity
{
    /**
     * Set a default primary key that is non-existant within the db
     * @var int
     */
    //public $id = 0;
    /*
     * The database table to retrieve data from
     */
    private $_table;

    /*
     * Table prefix.
     */
    private $_prefix = 'tbl_';

    /*
     * Primary key.
     */
    private $_primary = 'id';

    /*
     * Related collections pulled into this same 'provided' object.
     */
    private $_relations = array();

    /*
     * List of columns within table.
     */
    private $_cols = array();

    /**
     * Array of mutators
     *
     * @var array
     */
    private $_mutators = array();

    /**
     * Store any plugged in behavours.
     * @var array
     */
    private $_behaviours = array();

    /**
     * Store a bool, is this object virtual?
     *
     * @var bool
     */
    private $_virtual = false;

    /**
     * Is a property set?
     * @param string $key
     * @return bool
     */
    public function __isset($key)
    {
        if ('_' != substr($key, 0, 1)) {
            return isset($this->$key);
        }
        return false;
    }

    /**
     * Set a public property.
     * @param string $key
     * @param mixed $val
     * @return void
     */
    public function __set($key, $val)
    {
        if ('_' != substr($key, 0, 1)) {
            $this->$key = $val;
            return;
        }
    }

    /**
     * Get a public property.
     *
     * @param string $key
     * @return mixed
     */
    public function __get($key)
    {
        if (isset($this->{$key})) {
            return $this->{$key};
        // attempt to handle a virtual.
        } else if (isset($this->data->{$key})) {
            return $this->data->{$key};
        }
    }

    /**
     * The name of the table this object saves to.
     *
     * @param string $table
     * @return This_Orm_Entity
     */
    public function setTableName($table)
    {
        $this->_table = $table;
        return $this;
    }

    /**
     * Set this model to be a vrtual.
     *
     * This means this object will take on the
     * properties of its child.
     *
     * @return This_Orm_Entity
     */
    public function setVirtual()
    {
        $this->_virtual = true;
        return $this;
    }

    /**
     * Is this model a virtual ?
     * @return bool
     */
    public function isVirtual()
    {
        return $this->_virtual;
    }

    /**
     * The prefix to prepend to a table name.
     *
     * @param string $prefix
     * @return This_Orm_Entity
     */
    public function setTablePrefix($prefix)
    {
        $this->_prefix = $prefix;
        return $this;
    }

    /**
     * The name of the primary key column.
     *
     * @param string $primary
     * @return This_Orm_Entity
     */
    public function setPrimary($primary)
    {
        $this->_primary = $primary;
        return $this;
    }

    /**
     * Add columns (and there options) to the _cols array.
     *
     * @param string $column
     * @param array $options
     * @return This_Orm_Entity
     */
    public function setColumn($column, $options)
    {
        if (!array_key_exists($column, $this->_cols)) {
            $this->_cols[$column] = $options;
            return $this;
        }
    }

    /**
     * Add relations (and there options) to the _relations array.
     *
     * - Relations are other This_Orm_Entity objects that relate to $this
     *   object. You may also pass an alias in via the $name string.
     *   eg; setRelation('article as blog', $options)
     *
     * @param string $name
     * @param array $options
     * @return This_Orm_Entity
     */
    public function setRelation($name, $options)
    {
        if (!array_key_exists($name, $this->_relations)) {
            $this->_relations[$name] = $options;
        }
        return $this;
    }

    /**
     * Add a mutator to the _mutators array.
     *
     * - Mutators provide the ability to change property data (via a callback)
     *   upon insertion (default) or retrieval (see 3rd param).
     *
     * - The callback itself can be defined within your This_Orm_Entity
     *   extended class or you can build a mutator plugin by implementing the
     *   This_Orm_Interface_Mutator interface and placing your plugin within
     *   the This/Orm/Mutator directory.
     *
     * @param string $name
     * @param string $callback
     * @param bool $outboundonly
     * @return This_Orm_Entity
     */
    public function setMutator($name, $callback, $outboundonly = false)
    {
        $this->_mutators[] = func_get_args();
        return $this;
    }

    /**
     * Add a behaviour to the _behaviours array.
     *
     * - Behaviours allow you to extend the functionality (and properties)
     *   of your Model through the use of plugins.
     *
     * - At minimum the plgins simply need to implement the
     *   This_Orm_Interface_Behaviour interface.
     *
     * - Plugins can also implement either or both a preInsert() & preUpdate()
     *   hook that will be called during This_Orm_Store::save()
     *
     * @param string $behaviour
     * @return This_Model_Entity
     */
    public function setBehaviour($behaviour)
    {
        $this->_behaviours[] = $behaviour;
        $namespaces = This_orm_Config::getInstance()->getBehaviourNamespaces();
        foreach ($namespaces as $namespace) {
            if (class_exists($namespace . $behaviour)) {
                $class = $namespace . $behaviour;
                $behaviour = new $class($this);
                return $behaviour->setUp();
            }
        }
    }

    /**
     * Get defined columns.
     *
     * @return array|bool
     */
    public function getColumns()
    {
        return (isset($this->_cols)) ? $this->_cols : array();
    }

    /**
     * Get defined table name.
     *
     * @return string|bool
     */
    public function getTable()
    {
        return (isset($this->_table)) ? $this->_table : false;
    }

    /**
     * Get defined table prefix.
     *
     * @return string|bool
     */
    public function getTablePrefix()
    {
        return (isset($this->_prefix)) ? $this->_prefix : false;
    }

    /**
     * Get defined primary key name.
     *
     * @return string|bool
     */
    public function getPrimaryKey()
    {
        return (isset($this->_primary)) ? $this->_primary : false;
    }

    /*
     * Get array of relations.
     */
    public function getRelations()
    {
        return $this->_relations;
    }

    /*
     * Check if we have relations.
     */
    public function hasRelations()
    {
        return count($this->_relations);
    }

    /**
     * Get defined behaviours.
     *
     * @return array|bool
     */
    public function getBehaviours()
    {
        return (isset($this->_behaviours)) ? $this->_behaviours : array();
    }

    /**
     * Get defined mutators.
     *
     * @return array|bool
     */
    public function getMutators()
    {
        return (isset($this->_mutators)) ? $this->_mutators : array();
    }

    /**
     * Remove meta data.
     */
    public function cleanMeta()
    {
        foreach ($this as $property => $value) {
            if ('_' == substr($property, 0, 1)) {
                unset($this->$property);
            }
        }
    }

    /**
     * Called recursively to retrieve a models children.
     *
     * @param array $with
     * @param bool $meta
     * 
     * @return This_Orm_Entity
     */
    public function populateChildren($with, $meta)
    {
        if ($this->hasRelations()) {
            foreach ($this->getRelations() as $relation => $options) {
                if (preg_match('/([a-z]*)\sas\s([a-z]*)/', $relation, $parts)) {
                    $alias = $parts[2];
                    $relation = $parts[1];
                } else {
                    $alias = $relation;
                }
                if (array_key_exists($this->getTable(), $with)) {
                    if (in_array($relation, $with[$this->getTable()]['relations']) ||
                        array_key_exists($relation, $with[$this->getTable()]['relations'])
                    ) {
                        $q = new This_Orm_Fetcher;
                        $q->select($relation)
                            ->where($options['foreign'] . ' = ?', $this->{$options['local']});
                            if (isset($with[$relation]['relations'][$relation]['where'])) {
                                $q->where(
                                    $with[$relation]['relations'][$relation]['where'][0],
                                    $with[$relation]['relations'][$relation]['where'][1]
                                );
                            }
                            $q->with($with);
                        if (!$meta) {
                            $q->withMeta();
                        }
                        $p = $q->execute();
                        if ($p) {
                            $this->{$alias} = $p;
                        }
                    }
                }
            }
        }
        return $this;
    }
}
