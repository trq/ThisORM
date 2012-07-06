<?php
/**
 * @package thorpesystems
 * @subpackage This
 * @category Orm_Fetcher
 * @version $Id: Fetcher.php 925 2007-12-17 04:42:43Z tonyq $
 */

/**
 * @package thorpesystems
 * @subpackage This
 * @category Orm_Fetcher
 * @version $Id: Fetcher.php 925 2007-12-17 04:42:43Z tonyq $
 *
 */
class This_Orm_Fetcher
{
    /**
     * Our database connection.
     *
     * @var Zend_Db_Adapter_Pdo_Mssql
     */
    private $_db;

    /**
     * The raw model name (ie, with no namespace prepended)
     *
     * @var string
     */
    private $_modelName;

    /**
     * WHERE clause.
     *
     * @var array
     */
    private $_where = array();

    /**
     * OR WHERE clause
     *
     * @var array
     */
    private $_orWhere = array();

    /**
     * LIMIT clause
     *
     * @var array.
     */
    private $_limit = array();

    /**
     * Clean meta?
     *
     * @var bool
     */
    private $_clean = true;

    /**
     * An array of relationships to include.
     *
     * @var array
     */
    private $_with = array();

    /**
     * The collection to return.
     *
     * @var array
     */
    private $_collection = array();

    /**
     * List of properties to include.
     *
     * @var array
     */
    private $_columns = array();

    private $_orderby = '';

    /**
     * Setup
     */
    public function __construct()
    {
        $this->_db = This_Orm_Config::getInstance()->getAdapter();
    }

    /**
     * Build valid model name.
     *
     * @param string $model
     * @return This_Orm_Fetcher
     */
    public function select($model)
    {
        $this->_modelName = ucfirst(strtolower($model));
        return $this;
    }

    /**
     *
     */
    public function columns($columns)
    {
        if (is_array($columns)) {
            $this->_columns = array_merge($this->_columns, $columns);
        } else {
            $this->_columns[] = $columns;
        }
        return $this;
    }

    /**
     * Build WHERE clause.
     *
     * @return This_orm_Fetcher
     */
    public function where()
    {
        $this->_where[] = func_get_args();
        return $this;
    }

    /**
     * Build OR WHERE clause.
     *
     * @return This_orm_Fetcher
     */
    public function orWhere()
    {
        $this->_orWhere[] = func_get_args();
        return $this;
    }

    /**
     * Build LIMIT clause
     *
     * @return This_orm_Fetcher
     */
    public function limit($amount=0, $start=0)
    {
        $this->_limit[0] = $amount;
        $this->_limit[1] = $start;
        return $this;
    }

    public function order($order)
    {
        $this->_order = $order;
        return $this;
    }

    /**
     * If called meta data is returned with the object.
     */
    public function withMeta()
    {
        $this->_clean = false;
        return $this;
    }

    /**
     * Build our relationships filter.
     *
     * @param array $with
     * @return This_orm_Fetcher
     */
    public function with($with)
    {
        $this->_with = $with;
        return $this;
    }

    /**
     * Execute queries and build our collection.
     *
     * @return array|This_Orm_Entity
     */
    public function execute()
    {
        $obj = $this->_getModel($this->_modelName);
        try {
            if (is_object($obj)) {
                // build query.
                $q = $this->_db->select();
                if (count($this->_columns)) {
                    $q->from($obj->getTablePrefix() . $obj->getTable(), $this->_columns);
                } else {
                    $q->from($obj->getTablePrefix() . $obj->getTable());
                }

                foreach ($this->_where as $where => $clause) {
                    $w = $this->_db->quoteInto("{$clause[0]}", "{$clause[1]}");
                    $q->where($w);
                }

                foreach ($this->_orWhere as $where => $clause) {
                    $w = $this->_db->quoteInto($clause[0], $clause[1]);
                    $q->orWhere($w);
                }

                if (!empty($this->_order)) {
                    $q->order($this->_order);
                } else if (array_key_exists('sort', $obj->getColumns())) {
                    $q->order('sort ASC');
                }

                if (count($this->_limit)) {
                    $q->limit($this->_limit[0], $this->_limit[1]);
                }

                This_Orm_Config::log($q->__toString());

                $s = $this->_db->query($q);
                $records = $s->fetchAll();
                if ($records) {
                    // build objects
                    foreach ($records as $record) {
                        $obj = $this->_getModel($this->_modelName);
                        foreach ($record as $property => $value) {
                            $obj->{$property} = trim($value);
                        }
                        // polulate children
                        $obj->populateChildren($this->_with, $this->_clean);

                        // If this object is a virtual
                        // unserialize its data and populate
                        // virtual fields.
                        if ($obj->isVirtual()) {
                            $obj->data = unserialize($obj->data);
                            foreach ($obj->data as $p => $v) {
                                //if (isset($obj->name) && !isset($obj->{$obj->name})) {
                                //    $obj->{$obj->name} = $v;
                                //}
                                $obj->{$p} = $v;
                            }
                            // Do not remove the data property.
                            // unset($obj->data);
                        }

                        // clean meta data.
                        if ($this->_clean) {
                            $obj->cleanMeta($obj);
                        }// else {
                        //    if (isset($obj->value)) {
                        //        unset($obj->value);
                        //    }
                        //}

                        if (count($records) > 1) {
                            $this->_collection[] = $obj;
                        } else {
                            $this->_collection = $obj;
                        }
                    }
                    return $this->_collection;
                }
                return false;
            } else {
                throw new This_Orm_Exception_Model(
                    sprintf("%s not found within model paths", $this->_modelName)
                    );
            }
        } catch (This_Orm_Exception_Model $e) {
            throw new $e;
        }
    }

    /**
     * Simple method to iterate through the Model Namespaces
     * and return a valid object.
     *
     * @param string $name
     *
     * @return This_Orm_Entity|This_Orm_Virtual
     */
    private function _getModel($name)
    {
        if (@class_exists($name)) {
            $obj = new $name;
        } else {
            $namespaces = This_Orm_Config::getInstance()->getModelNamespaces();
            foreach ($namespaces as $namespace) {
                if (@class_exists($namespace . $name)) {
                    $class = $namespace . $name;
                    $obj = new $class;
                    break;
                }
            }
        }
        return $obj;
    }
}
