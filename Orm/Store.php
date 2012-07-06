<?php
/**
 * @package thorpesystems
 * @subpackage This
 * @category Orm_Store
 * @version $Id: Store.php 863 2007-11-23 22:21:36Z tonyq $
 */

/**
 * @package thorpesystems
 * @subpackage This
 * @category Orm_Store
 * @version $Id: Store.php 863 2007-11-23 22:21:36Z tonyq $
 */
class This_Orm_Store
{
    /*
     * Store our database connection.
     *
     * @var Zend_Db_Adapter_Abstract
     */
    private $_db;

    /**
     * Instantiate This_Orm_Store
     */
    public function __construct()
    {
        $this->_db = This_Orm_Config::getInstance()->getAdapter();
    }

    /**
     * If property not already set, attempt to apply defaults.
     *
     * @param This_Orm_Entity $obj
     */
    private function _applyDefaults(This_Orm_Entity &$obj)
    {
        foreach ($obj->getColumns() as $key => $val) {
            if (!isset($obj->$key)) {
                if (isset($val['default'])) {
                    $obj->$key = $val['default'];
                }
            }
        }
        return $obj;
    }

    /**
     * // Call any behavour hooks (preInsert() / preUpdate()).
     *
     * @param This_Orm_Entity $obj
     * @param bool $insert
     * @return This_Orm_Entity
     */
    private function _applyBehaviours(This_Orm_Entity &$obj, $insert)
    {
        if (count($obj->getBehaviours())) {
            foreach ($obj->getBehaviours() as $behaviour) {
                if ($insert) {
                    foreach (This_Orm_Config::getInstance()->getBehaviourNamespaces() as $namespace) {
                        if (method_exists($namespace . $behaviour, 'preInsert')) {
                            $behaviour = $namespace . $behaviour;
                            $behaviour = new $behaviour($obj);
                            $obj = $behaviour->preInsert();
                            break;
                        }
                    }
                } else {
                    foreach (This_Orm_Config::getInstance()->getBehaviourNamespaces() as $namespace) {
                        if (method_exists($namespace . $behaviour, 'preUpdate')) {
                            $behaviour = $namespace . $behaviour;
                            $behaviour = new $behaviour($obj);
                            $obj = $behaviour->preUpdate();
                            break;
                        }
                    }
                }
            }
        }
        return $obj;
    }

    /**
     * Mutate properties.
     *
     * @param This_Orm_Entity $obj
     * @param string $key
     * @param string $val
     */
    private function _applyMutators(This_Orm_Entity &$obj, $key, $val)
    {
        foreach ($obj->getMutators() as $mutator) {
            if ($mutator[0] == $key && !isset($mutator[2])) {
                if (method_exists($obj, $mutator[1])) {
                    $obj->$key = $obj->$mutator[1]($val);
                } else {
                    foreach (This_Orm_Config::getInstance()->getMutatorNamespaces() as $namespace) {
                        if (class_exists($namespace . ucfirst(strtolower($mutator[1])))) {
                            $mutator = $namespace . ucfirst(strtolower($mutator[1]));
                            $mutator = new $mutator;
                            $obj->$key = $mutator->execute($val);
                            break;
                        }
                    }
                }
            } else {
                if (!isset($obj->$key)) {
                    $obj->$key = $val;
                }
            }
        }
        return $obj;
    }

    private function _applyConstraints(This_Orm_Entity &$obj, $insert)
    {
        foreach ($obj->getColumns() as $column => $options) {
            if (array_key_exists('constraints', $options)) {
                $constraints = $options['constraints'];
                foreach ($constraints as $constraint => $rule) {
                    $namespaces = This_Orm_Config::getInstance()->getConstraintNamespaces();
                    foreach ($namespaces as $namespace) {
                        if (class_exists($namespace . $constraint)) {
                            $class = $namespace . $constraint;
                            $class = new $class;
                            $class->execute($obj, $column, $obj->{$column}, $rule);
                            break;
                        }
                    }
                }
            }
        }
    }

    /**
     * Build our object applying mutators as we go.
     *
     * @param This_Orm_Entity $obj
     * @return This_Orm_Entity
     */
    private function _buildObject(This_Orm_Entity &$obj, &$data)
    {
        foreach ($obj as $key => $val) {
            if ('_' != substr($key, 0, 1)) {
                if (count($obj->getMutators())) {
                    $this->_applyMutators($obj, $key, $val);
                }
                if ($obj->$val instanceof This_Orm_Virtual) {
                    $data[$key] = serialize($obj->$val);
                } else {
                    $data[$key] = $obj->$key;
                }
            }
        }
        return $obj;
    }

    /**
     * Save our object to the database.
     *
     * @todo (Long Term) Implement the ability to save related data.
     * @link http://devdocs/flyspray/index.php?do=details&task_id=4
     * 
     * @param This_Orm_Entity $obj
     * @return bool
     */
    public function save(This_Orm_Entity &$obj)
    {
        // Check to see if we need to switch from insert to update mode.
        $insert = true;
        if ($obj->getPrimaryKey()) {
            $primary = $obj->getPrimaryKey();
            if (isset($obj->$primary)) {
                if ($obj->$primary == 0) {
                    unset($obj->$primary);
                } else {
                    $insert = false;
                }
            }
        }

        // Store our data
        $data = array();

        $this->_applyDefaults($obj);

        $this->_applyBehaviours($obj, $insert);

        $this->_applyConstraints($obj, $insert);

        $this->_buildObject($obj, $data);

        // Execute actual query.
        if ($insert) {
            return $this->_doInsert($obj, $data);
        } else {
            return $this->_doUpdate($obj, $data);
        }
    }

    /**
     * Remove given model from storage.
     *
     * @param This_Orm_Entity $obj
     * @return int records effected.
     */
    public function remove(This_Orm_Entity &$obj)
    {
        foreach ($obj as $property => $value) {
            if ($value instanceof This_Orm_Entity) {
                $this->remove($value);
            }
            if (is_array($value)) {
                foreach ($value as $k => $v) {
                    if ($v instanceof This_Orm_Entity) {
                        $this->remove($v);
                    }
                }
            }
        }
        $key = $obj->getPrimaryKey();
        $val = $obj->$key;
        return $this->_db->delete($obj->getTablePrefix(). $obj->getTable(), "{$key} = {$val}");
    }

    /**
     * Execute an UPDATE
     *
     * @param This_Orm_Entity $obj
     * @param array $data
     * @return bool
     */
    private function _doUpdate(This_Orm_Entity $obj, $data)
    {
        $primarykey = $obj->getPrimaryKey();
        $key = $data[$primarykey];
        unset($data[$primarykey]);
        if ($this->_db->update(
            $obj->getTablePrefix() . $obj->getTable(),
            $data,
            "{$primarykey} = {$obj->$primarykey}"
        )) {
            return $key;
        }
    }

    /**
     * Execute an INSERT
     *
     * @param This_Orm_Entity $obj
     * @param array $data
     * @return bool
     */
    private function _doInsert(This_Orm_Entity $obj, $data)
    {
        if ($this->_db->insert($obj->getTablePrefix() . $obj->getTable(),$data)) {
            return $this->_db->lastInsertId();
        }
    }
}
