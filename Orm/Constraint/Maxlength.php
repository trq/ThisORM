<?php
/**
 * @package thorpesystems
 * @subpackage This
 * @category Orm_Constraint_Maxlength
 * @version $Id: Maxlength.php 863 2007-11-23 22:21:36Z tonyq $
 */

/**
 * @package thorpesystems
 * @subpackage This
 * @category Orm_Constraint_Maxlength
 * @version $Id: Maxlength.php 863 2007-11-23 22:21:36Z tonyq $
 */
class This_Orm_Constraint_Maxlength implements This_Orm_Interface_Constraint
{
    public function execute(This_Orm_Entity $obj, $column, $data, $rule)
    {
        if (isset($obj->{$column})) {
            if (strlen($data) > $rule) {
                throw new This_Orm_Exception_Constraint_Maxlength;
            }
        }
    }
}
