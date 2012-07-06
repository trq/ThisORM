<?php
/**
 * @package thorpesystems
 * @subpackage This
 * @category Orm_Constraint_Regex
 * @version $Id: Regex.php 863 2007-11-23 22:21:36Z tonyq $
 */

/**
 * @package thorpesystems
 * @subpackage This
 * @category Orm_Constraint_Regex
 * @version $Id: Regex.php 863 2007-11-23 22:21:36Z tonyq $
 */
class This_Orm_Constraint_Regex implements This_Orm_Interface_Constraint
{
    public function execute(This_Orm_Entity $obj, $column, $data, $rule)
    {
        if (isset($obj->{$column})) {
            if (!preg_match($rule, trim($data))) {
                throw new This_Orm_Exception_Constraint_Regex;
            }
        }
    }
}
