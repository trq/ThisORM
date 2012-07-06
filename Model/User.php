<?php
/**
 * @package thorpesystems
 * @subpackage This
 * @category Model_User
 * @version $Id: User.php 863 2007-11-23 22:21:36Z tonyq $
 */

/**
 * @package thorpesystems
 * @subpackage This
 * @category Model_User
 * @version $Id: User.php 863 2007-11-23 22:21:36Z tonyq $
 */
class This_Model_User extends This_Orm_Entity
{
    /**
     * Setup
     *
     * Causing PHP Stack Overflow Fatal.
     */
    public function __construct()
    {
        $this
            ->setTableName('user')

            ->setPrimary('id')

            ->setColumn(
                'name',
                array(
                    'type' => 'varchar',
                    'constraints' => array(
                        'unique' => true,
                        'maxlength' => 20,
                        'regex' => '/^[a-zA-Z0-9]+$/'
                    )
                )
            )
            ->setColumn(
                'pass',
                array(
                    'type' => 'varchar',
                    'constraints' => array(
                        'maxlength' => 32,
                        'regex' => '/^[a-zA-Z0-9]+$/'
                    )
                )
            )
            ->setColumn('salt',array('type' => 'varchar'))
            ->setColumn('role_id', array('type' => 'int'))

            ->setBehaviour('Timestampable')

            ->setRelation(
                'article as authored',
                array(
                    'type' => 'many',
                    'local' => 'id',
                    'foreign' => 'author_id'
                )
            )
            ->setRelation(
                'article as published',
                array(
                    'type' => 'many',
                    'local' => 'id',
                    'foreign' => 'publisher_id'
                )
            )
            ->setRelation(
                'role',
                array(
                    'type' => 'one',
                    'local' => 'role_id',
                    'foreign' => 'id'
                )
            )
            ->setRelation(
                'profile',
                array(
                    'type' => 'many',
                    'local' => 'id',
                    'foreign' => 'user_id'
                )
            )

            ->setMutator('pass', 'md5')
            ->setMutator('salt', 'md5');
    }

    /**
     * Save a new user.
     *
     * @param string $name
     * @param string $pass
     * @param string $email
     * @param string $role
     */
    public static function add(
        $name,
        $pass,
        $email,
        $role
        ) {

        $u = new This_Model_User;
        $u->name = $name;
        $u->pass = $pass;
        $u->salt = $pass;

        $u->role_id = This_Model_Role::getIdByName($role);

        $s = new This_Orm_Store;
        $id = $s->save($u);

        $e = new This_Model_Profile_Email;
        $e->value = $email;
        This_Model_Profile::add($id, 'email', 'email', $e);

    }

    /**
     * Get a user by name.
     *
     * @param string $name
     * @param bool $meta
     * @return This_Model_user
     */
    public static function getByName($name, $meta=true)
    {
        $f = new This_Orm_Fetcher;
        if ($meta) {
            return $f->select('user')
                ->where('name = ?', $name)
                ->with(
                    array(
                        'user' => array(
                            'relations' => array('role')
                        )
                    )
                )
                ->execute();
        } else {
            return $f->select('user')
                ->where('name = ?', $name)
                ->with(
                    array(
                        'user' => array(
                            'relations' => array('role')
                        )
                    )
                )
                ->cleanMeta()
                ->execute();
        }
    }

    /**
     * Get a user by id.
     *
     * @param int $id
     * @param bool $meta
     * @return This_Model_user
     */
    public static function getById($id, $meta=true)
    {
        $f = new This_Orm_Fetcher;
        if ($meta) {
            return $f->select('user')
                ->where('id = ?', $id)
                ->with(
                    array(
                        'user' => array(
                            'relations' => array('role', 'profile')
                        )
                    )
                )
                ->execute();
        } else {
            return $f->select('user')
                ->where('id = ?', $id)
                ->with(
                    array(
                        'user' => array(
                            'relations' => array('role', 'profile')
                        )
                    )
                )
                ->cleanMeta()
                ->execute();
        }
    }

    /**
     * Get a user id by name.
     *
     * @param string $name
     * @return int User id
     */
    public static function getIdByName($name)
    {
        $f = new This_Orm_Fetcher;
        $u = $f->select('user')
            ->columns('id')
            ->where('name = ?', $name)
            ->execute();
        return $u->id;
    }

    /**
     * Get a user name by id.
     *
     * @param int $id
     * @return string User name
     */
    public static function getNameById($id)
    {
        $f = new This_Orm_Fetcher;
        $u = $f->select('user')
            ->columns('name')
            ->where('id = ?', $id)
            ->execute();
        return $u->name;
    }

    public static function getAll() {
        $f = new This_Orm_Fetcher;
        return $f->select('user')
            ->with(
                array(
                    'user' => array(
                        'relations' => array('role', 'profile')
                    )
                )
            )
            ->execute();
    }

}
