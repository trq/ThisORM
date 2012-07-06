<?php
/**
 * @package thorpesystems
 * @subpackage This
 * @category Model_Resource
 * @version $Id: Resource.php 696 2007-10-09 05:09:30Z tonyq $
 */

/**
 * @package thorpesystems
 * @subpackage This
 * @category Model_Resource
 * @version $Id: Resource.php 696 2007-10-09 05:09:30Z tonyq $
 */
class This_Model_Resource extends This_Orm_Entity
{
    /**
     * Setup
     */
    public function __construct()
    {
        $this
            ->setTableName('resource')

            ->setPrimary('id')

            ->setColumn('name', array('type' => 'varchar'));
    }

    /**
     * Get a resource name by id.
     *
     * @param int $id
     * @return string
     */
    public static function getNameById($id)
    {
        $f = new This_Orm_Fetcher;
        $r = $f->select('resource')
            ->columns('name')
            ->where('id = ?', $id)
            ->execute();
        return $r->name;
    }

    /**
     * Get a resource id by name.
     *
     * @param string $name
     * @return int
     */
    public static function getIdByName($name)
    {
        $f = new This_Orm_Fetcher;
        $r = $f->select('resource')
            ->columns('id')
            ->where('name = ?', $name)
            ->execute();
        return $r->id;
    }
}
