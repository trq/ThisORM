<?php
/**
 * @package thorpesystems
 * @subpackage This
 * @category Orm_Interface_Constraint
 * @version $Id: Constraint.php 456 2007-08-25 00:30:18Z tonyq $
 */

/**
 * @package thorpesystems
 * @subpackage This
 * @category Orm_Interface_Constraint
 * @version $Id: Constraint.php 456 2007-08-25 00:30:18Z tonyq $
 */
interface This_Orm_Interface_Constraint
{
    public function execute(This_Orm_Entity $obj, $column, $data, $rule);
}
