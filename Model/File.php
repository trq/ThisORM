<?php
/**
 * @package thorpesystems
 * @subpackage This
 * @category Model_Profile
 * @version $Id$
 */

/**
 * @package thorpesystems
 * @subpackage This
 * @category Model_Profile
 * @version $Id$
 */
class This_Model_File extends This_Orm_Entity
{
    /**
     * Setup
     */
    public function __construct()
    {
        $this
            ->setTableName('file')

            ->setPrimary('id')

            ->setColumn('user_id', array('type' => 'int'))
            ->setColumn('path', array('type' => 'varchar'))
            ->setColumn('name', array('type' => 'varchar'))
            ->setColumn('mime', array('type' => 'varchar'))
            ->setColumn('size', array('type' => 'int'))
            ->setColumn('extension', array('type' => 'varchar'))
            ->setColumn('height', array('type' => 'int'))
            ->setColumn('width', array('type' => 'int'))
            ->setColumn('misc', array('type' => 'varchar'))
            ->setColumn('description', array('type' => 'varchar'))
            ->setColumn('type', array('type' => 'varchar'))
            ->setColumn('locked', array('type' => 'int'))
            ->setBehaviour('Timestampable')
            ->setRelation(
                'user as author',
                array(
                    'type' => 'one',
                    'local' => 'user_id',
                    'foreign' => 'id'
                )
            );
    }

    /**
     * @param string $path
     * @return This_Model_File
     */
    public static function getFileByCompletePath($path)
    {
        $f = new This_Orm_Fetcher;
        $name = basename($path);
        $path = substr($path,0,strrpos($path,'/'));
        if ($path == '') {
            return $f->select('file')
                ->where('name = ?', $name)
                ->with(
                    array(
                        'file' => array(
                            'relations' => array('user')
                        )
                    )
                )
                ->execute();
        } else {
            return $f->select('file')
                ->where('path = ?', $path)
                ->where('name = ?', $name)
                ->with(
                    array(
                        'file' => array(
                            'relations' => array('user')
                        )
                    )
                )
                ->execute();
        }
    }

    /**
     *
     * @param string $path
     * @return This_Model_File
     */
    public static function getFileByCompletePathWithMetaAndNoChildren($path)
    {
        $f = new This_Orm_Fetcher;
        $name = basename($path);
        $path = substr($path,0,strrpos($path,'/'));
        if ($path == '') {
            return $f->select('file')
                ->where('name = ?', $name)
                ->withMeta()
                ->execute();
        } else {
            return $f->select('file')
                ->where('path = ?', $path)
                ->where('name = ?', $name)
                ->withMeta()
                ->execute();
        }
    }

    /**
     * Get a This_Model_File object containing all images
     * uder the cms's controll.
     *
     * @return array|This_Model_File
     */
    public static function getImages($w,$h)
    {
        $f = new This_Orm_Fetcher();
        if ($w && $h) {
            return $f->select('file')
                ->where('width = ?', $w)
                ->where('height = ?', $h)
                ->where('mime LIKE ?', 'image%')
                ->execute();
        } else {
            return $f->select('file')
                ->where('mime LIKE ?', 'image%')
                ->execute();
        }
    }

    public static function getOwnerIdByName($n)
    {
        $f = new This_Orm_Fetcher();
        $r = $f->select('file')
            ->where('name = ?', basename(trim($n)))
            ->columns('user_id')
            ->limit(1)
            ->execute();
        return (@$r->user_id) ? $r->user_id : 0;
    }
}
