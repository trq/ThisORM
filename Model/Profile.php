<?php
/**
 * @package thorpesystems
 * @subpackage This
 * @category Model_Profile
 * @version $Id: Profile.php 696 2007-10-09 05:09:30Z tonyq $
 */

/**
 * @package thorpesystems
 * @subpackage This
 * @category Model_Profile
 * @version $Id: Profile.php 696 2007-10-09 05:09:30Z tonyq $
 */
class This_Model_Profile extends This_Orm_Entity
{
    /**
     * Setup
     */
    public function __construct()
    {
        $this
            ->setTableName('profile')

            ->setPrimary('id')

            ->setColumn('user_id', array('type' => 'int'))
            ->setColumn('name', array('type' => 'varchar'))

            ->setBehaviour('Virtual');
    }

    /**
     * Get a users profile by name.
     *
     * @param string $name
     * @return This_Model_Profile
     */
    public static function getByName($name)
    {
        $id = This_Model_User::getIdByName($name);
        $f = new This_Orm_Fetcher;
        return $f->select('profile')
            ->where('user_id = ?', $id)
            ->execute();
    }

    /**
     * Get a users profile by id.
     *
     * @param int $id
     * @return This_Model_Profile
     */
    public static function getById($id)
    {
        $f = new This_Orm_Fetcher;
        return $f->select('profile')
            ->where('user_id = ?', $id)
            ->execute();
    }

    /**
     * Save a profile object.
     *
     * @param int $id Users id
     * @param string $name
     * @param string $type
     * @param This_Orm_Virtual $data
     */
    public static function add($id, $name, $type, This_Orm_Virtual $data)
    {
        $p = new This_Model_Profile;
        $p->user_id = $id;
        $p->name    = $name;
        $p->type    = $type;
        $p->data    = serialize($data);

        $s = new This_Orm_Store;
        $s->save($p);
    }
}
