<?php
/**
 * @package thorpesystems
 * @subpackage This
 * @category Orm_Behaviour_Virtual
 * @version $Id: Virtual.php 420 2007-08-17 23:18:00Z tonyq $
 */

/**
 * @package thorpesystems
 * @subpackage This
 * @category Orm_Behaviour_Virtual
 * @version $Id: Virtual.php 420 2007-08-17 23:18:00Z tonyq $
 */
class This_Orm_Behaviour_Virtual implements This_Orm_Interface_Behaviour
{
    private $_obj;

    public function __construct(This_Orm_Entity $object)
    {
        $this->_obj = $object;
    }

    /**
     * Setup any required tables
     *
     * @return This_Orm_Entity
     */
    public function setUp()
    {
        $this->_obj->setVirtual();
        
        if (!array_key_exists('type', $this->_obj->getColumns())) {
            $this->_obj->setColumn(
                'type',
                array(
                    'type' => 'varchar',
                )
            );
        }
        if (!array_key_exists('data', $this->_obj->getColumns())) {
            $this->_obj->setColumn(
                'data',
                array(
                    'type' => 'text',
                )
            );
        }
        return $this->_obj;
    }
}
