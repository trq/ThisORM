<?php
/**
 * @package thorpesystems
 * @subpackage This
 * @category Orm_Constraint_Unique
 * @version $Id: Unique.php 863 2007-11-23 22:21:36Z tonyq $
 */

/**
 * @package thorpesystems
 * @subpackage This
 * @category Orm_Constraint_Unique
 * @version $Id: Unique.php 863 2007-11-23 22:21:36Z tonyq $
 */
class This_Orm_Constraint_Unique implements This_Orm_Interface_Constraint
{
    public function execute(This_Orm_Entity $obj, $column, $data, $rule)
    {
        if ($rule) {
            $insert = true;
            if ($obj->getPrimaryKey()) {
                $primary = $obj->getPrimaryKey();
                if (isset($obj->$primary)) {
                    $insert = false;
                }
            }

            if ($insert) {
                $q = new This_Orm_Fetcher;
                $result = $q
                    ->select($obj->getTable())
                    ->where("$column = ?", $data)
                    ->execute();
            } else {
                $q = new This_Orm_Fetcher;
                $result = $q
                    ->select($obj->getTable())
                    ->where($obj->getPrimaryKey() . ' != ?', $obj->{$primary})
                    ->where("$column = ?", $data)
                    ->execute();
            }

            if ($result) {
                foreach (This_Orm_Config::getInstance()->getExceptionNamespaces() as $namespace) {
                    if (class_exists($namespace . ucfirst(strtolower($column)))) {
                        $exception = $namespace . ucfirst(strtolower($column));
                        throw new $exception;
                        return;
                    }
                }
                throw new This_Orm_Exception_Constraint_Unique;
            }
        }
    }
}
