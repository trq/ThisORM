<?php
/**
 * @package thorpesystems
 * @subpackage This
 * @category Model_Expiry
 * @version $Id: Expiry.php 245 2007-07-27 05:55:05Z tonyq $
 */

/**
 * @package thorpesystems
 * @subpackage This
 * @category Model_Expiry
 * @version $Id: Expiry.php 245 2007-07-27 05:55:05Z tonyq $
 */
class This_Model_Expiry extends This_Orm_Entity
{
    /**
     * Setup
     */
    public function __construct()
    {
        $this
            ->setTableName('expiry')

            ->setPrimary('id')

            ->setColumn('name', array('type' => 'varchar'))
            ->setColumn('val', array('type' => 'varchar'));
    }

}
