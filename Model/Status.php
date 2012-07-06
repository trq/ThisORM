<?php
/**
 * @package thorpesystems
 * @subpackage This
 * @category Model_Status
 * @version $Id$
 */

/**
 * @package thorpesystems
 * @subpackage This
 * @category Model_Status
 * @version $Id$
 */
class This_Model_Status extends This_Orm_Entity
{
    /**
     * Setup
     */
    public function __construct()
    {
        $this
            ->setTableName('status')

            ->setPrimary('id')

            ->setColumn('name', array('type' => 'varchar'));
    }

    /**
     * Get a status name by id.
     *
     * @param int $id
     * @return string
     */
    public static function getNameById($id)
    {
        $f = new This_Orm_Fetcher;
        $s = $f->select('status')
            ->columns('name')
            ->where('id = ?', $id)
            ->execute();
        return $s->name;
    }

    /**
     * Get a status id by name.
     *
     * @param string $name
     * @return int
     */
    public static function getIdByName($name)
    {
        $f = new This_Orm_Fetcher;
        $s = $f->select('status')
            ->columns('id')
            ->where('name = ?', $name)
            ->execute();
        return $s->id;
    }

    /**
     * @return This_Model_Status
     */
    public static function getAll()
    {
        $f = new This_Orm_Fetcher;
        return $f->select('status')
            ->execute();
    }
}
