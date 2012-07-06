<?php
/**
 * @package thorpesystems
 * @subpackage This
 * @category Model_Role
 * @version $Id: Role.php 863 2007-11-23 22:21:36Z tonyq $
 */

/**
 * @package thorpesystems
 * @subpackage This
 * @category Model_Role
 * @version $Id: Role.php 863 2007-11-23 22:21:36Z tonyq $
 */
class This_Model_Role extends This_Orm_Entity
{
    /**
     * Setup
     */
    public function __construct()
    {
        $this
            ->setTableName('role')

            ->setPrimary('id')

            ->setColumn('name', array('type' => 'varchar'));
    }

    /**
     * Get a role name by id.
     *
     * @param int $id
     * @return string
     */
    public static function getNameById($id)
    {
        $f = new This_Orm_Fetcher;
        $r = $f->select('role')
            ->columns('name')
            ->where('id = ?', $id)
            ->execute();
        return $r->name;
    }

    /**
     * Get a role id by name.
     *
     * @param string $name
     * @return int
     */
    public static function getIdByName($name)
    {
        $f = new This_Orm_Fetcher;
        $r = $f->select('role')
            ->columns('id')
            ->where('name = ?', $name)
            ->execute();
        return $r->id;
    }

    public static function getAll()
    {
        $f = new This_Orm_Fetcher;
        return $f->select('role')
            ->execute();
    }
}
