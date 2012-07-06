<?php
/**
 * @package thorpesystems
 * @subpackage This
 * @category Orm_Virtual
 * @version $Id: Virtual.php 767 2007-11-02 03:52:17Z tonyq $
 */

/**
 * @package thorpesystems
 * @subpackage This
 * @category Orm_Virtual
 * @version $Id: Virtual.php 767 2007-11-02 03:52:17Z tonyq $
 *
 * This object exists simply for the children of virtuals to extend.
 * It is required for these children to have there own <i>type</i>
 * so we can determine there existence within any This_Orm_Store::save calls.
 */
class This_Orm_Virtual
{
    /**
     * Set a default primary key that is non-existant within the db
     * @var int
     */
    //public $id = 0;
}
