<?php
/**
 * @package thorpesystems
 * @subpackage This
 * @category Orm_Behaviour_Timestampable
 * @version $Id: Timestampable.php 908 2007-12-04 02:40:17Z tonyq $
 */

/**
 * @package thorpesystems
 * @subpackage This
 * @category Orm_Behaviour_Timestampable
 * @version $Id: Timestampable.php 908 2007-12-04 02:40:17Z tonyq $
 */
class This_Orm_Behaviour_Timestampable implements This_Orm_Interface_Behaviour
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
        if (!array_key_exists('created_at', $this->_obj->getColumns())) {
            $this->_obj->setColumn(
                'created_at',
                array(
                    'type' => 'datetime',
                    'default' => new Zend_Db_Expr('NOW()')
                )
            );
        }
        if (!array_key_exists('updated_at', $this->_obj->getColumns())) {
            $this->_obj->setColumn(
                'updated_at',
                array(
                    'type' => 'datetime',
                    'default' => new Zend_Db_Expr('NOW()')
                )
            );
        }
        return $this->_obj;
    }

    /**
     * The preUpdate hook.
     *
     * Make sure the created_at property is not
     * changed on a call to This_Orm_Store::save
     *
     * @return This_Orm_Entity
     */
    public function preUpdate()
    {
        if (isset($this->_obj->created_at)) {
            unset($this->_obj->created_at);
        }

        $this->_obj->updated_at = new Zend_Db_Expr('NOW()');

        return $this->_obj;
    }
}
