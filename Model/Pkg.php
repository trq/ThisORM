<?php
/**
 * @package thorpesystems
 * @subpackage This
 * @category Model_Pkg
 * @version $Id: Pkg.php 740 2007-10-22 02:31:11Z tonyq $
 */

/**
 * @package thorpesystems
 * @subpackage This
 * @category Model_Pkg
 * @version $Id: Pkg.php 740 2007-10-22 02:31:11Z tonyq $
 */
class This_Model_Pkg extends This_Orm_Entity
{
    /**
     * Setup
     */
    public function __construct()
    {
        $this
            ->setTablePrefix('tbl_')

            ->setTableName('pkg')

            ->setPrimary('id')

            ->setColumn('object', array('type' => 'varchar'))
            ->setColumn('resource_id', array('type' => 'int'))

            ->setRelation(
                'resource',
                array(
                    'type' => 'one',
                    'local' => 'resource_id',
                    'foreign' => 'id',
                )
            );
    }

}
