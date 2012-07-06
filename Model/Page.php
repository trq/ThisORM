<?php
/**
 * @package thorpesystems
 * @subpackage This
 * @category Model_Page
 * @version $Id: Page.php 900 2007-12-02 22:50:51Z tonyq $
 */

/**
 * @package thorpesystems
 * @subpackage This
 * @category Model_Page
 * @version $Id: Page.php 900 2007-12-02 22:50:51Z tonyq $
 */
class This_Model_Page extends This_Orm_Entity
{
    /**
     * Setup
     * 
     */
    public function __construct()
    {
        $this
            ->setTableName('page')

            ->setPrimary('id')

            ->setColumn('parent_id', array('type' => 'int'))
            ->setColumn('lvl', array('type' => 'int'))
            ->setColumn('author_id', array('type' => 'int'))
            ->setColumn('publisher_id', array('type' => 'int'))
            ->setColumn('status_id', array('type' => 'int'))
            ->setColumn('resource_id', array('type' => 'int'))
            ->setColumn('pkg_id', array('type' => 'int'))
            ->setColumn('variation', array('type' => 'varchar'))
            ->setColumn('uri', array('type' => 'varchar'))
            ->setColumn('sort', array('type' => 'int'))
            ->setColumn('name', array('type' => 'varchar'))
            ->setColumn('description', array('type' => 'varchar'))

            ->setBehaviour('Timestampable')

            ->setRelation(
                'user as author',
                array(
                    'type' => 'one',
                    'local' => 'author_id',
                    'foreign' => 'id'
                )
            )
            ->setRelation(
                'user as publisher',
                array(
                    'type' => 'one',
                    'local' => 'publisher_id',
                    'foreign' => 'id'
                )
            )
            ->setRelation(
                'pkg',
                array(
                    'type' => 'one',
                    'local' => 'pkg_id',
                    'foreign' => 'id',
                )
            )
            ->setRelation(
                'resource',
                array(
                    'type' => 'one',
                    'local' => 'resource_id',
                    'foreign' => 'id',
                )
            )
            ->setRelation(
                'status',
                array(
                    'type' => 'one',
                    'local' => 'status_id',
                    'foreign' => 'id',
                )
            )
            ->setRelation(
                'article as articles',
                array(
                    'type' => 'many',
                    'local' => 'id',
                    'foreign' => 'parent_id'
                )
            )
            ->setRelation(
                'page as children',
                array(
                    'type' => 'many',
                    'local' => 'id',
                    'foreign' => 'parent_id'
                )
            );
    }

    /**
     * Replaces / with - within a string.
     * Usefull for attaching a path to a url within it
     * being interpreted as part of a uri.
     *
     * @param string $col
     * @return string
     */
    public function sluggable($col)
    {
        $value = $this->{$col};
        return str_replace('/','-',$value);
    }

    /**
     * Get a page (prepared for the cms) by uri.
     *
     * This method is specifically designed for use within the CMSController
     * and drags with it each pages 'article','template' and 'element'
     * relations.
     *
     * @param string $uri
     * @return This_Model_Page
     */
    public static function getCMSPageByUri($uri)
    {
        $uri = str_replace(array('-',' '),array('/','+'), $uri);
        $q = new This_Orm_Fetcher;
        return $q->select('page')
            ->where('uri = ?', $uri)
            ->where('status_id = ?', 1) // make sure the page is actually published.
            ->with(
                array(
                    'page' => array(
                        'relations' => array('article', 'pkg')
                    ),
                    'article' => array(
                        'relations' => array('element')
                    )
                )
            )
            ->execute();
    }

    public static function getCMSPageById($id)
    {
        $q = new This_Orm_Fetcher;
        return $q->select('page')
            ->where('id = ?', $id)
            ->with(
                array(
                    'page' => array(
                        'relations' => array('article', 'pkg')
                    ),
                    'article' => array(
                        'relations' => array('element')
                    )
                )
            )
            ->execute();
    }

    /**
     * Get a page by uri.
     *
     * This method is specifically designed for use within Admin/Page::version()
     *
     * @param string $uri
     * @return This_Model_Page
     */
    public static function getPagesByUri($uri)
    {
        $uri = str_replace(array('-',' '),array('/','+'), $uri);
        $q = new This_Orm_Fetcher;
        return $q->select('page')
            ->where('uri = ?', $uri)
            ->with(
                array(
                    'page' => array(
                        'relations' => array('pkg', 'user', 'status', 'resource')
                    )/*,
                    'article' => array(
                        'relations' => array('element')
                    )*/
                )
            )
            ->execute();
    }

    /**
     * Get a page tree by name.
     *
     * This method is specifically designed for use with the View_Helper_Maketree
     * view helper.
     *
     * This will return a Page object and all its related Page objects.
     *
     * @param string $name
     * @return This_Model_Page
     */
    public static function getPageTreeByName($name)
    {
        $q = new This_Orm_Fetcher;
        return $q->select('page')
            ->where('name = ?', $name)
            //->where('status_id = ?', 1)
            ->with(
                array(
                    'page' => array(
                        'relations' => array(
                            'page',
                            'status',
                            'article',
                            'pkg'
                        )
                    )
                )
            )
            ->execute();
    }

    /**
     * Get a page by id.
     *
     * @param int $id
     * @return This_Model_Page
     */
    public static function getPageDetailById($id)
    {
        $q = new This_Orm_Fetcher;
        return $q->select('page')
            ->where('id = ?', $id)
            ->with(
                array(
                    'page' => array(
                        'relations' => array(
                            'article',
                            'pkg',
                            'user',
                            'resource',
                            'status'
                        )
                    ),
                    'article' => array(
                        'relations' => array('element')
                    )
                )
            )
            ->execute();
    }

    public static function getLevelById($id)
    {
        $q = new This_orm_Fetcher;
        $p = $q->select('page')
            ->where('id = ?', $id)
            ->execute();
        if ($p instanceof This_Model_Page) {
            return $p->lvl;
        }
    }

    /**
     * This will retrieve the first child of 'home'
     * that is an ancestor of the gievn node.
     *
     * @param int $id
     * @return string name of ancestor page.
     *
     * @todo: Look into optimizations. This
     * method recurses and can be quite excessive.
     */
    public static function getAncestorNameById($id)
    {
        $q = new This_Orm_Fetcher;
        $o = '';
        $r = $q->select('page')
            ->where('id = ?', $id)
            ->columns(
                array(
                    'name', 'lvl', 'parent_id'
                )
            )
            ->execute();
        if ($r->lvl > 1) {
            $o = This_Model_Page::getAncestorNameById($r->parent_id);
        } else {
            $o = $r->name;
        }
        return $o;
    }
}
