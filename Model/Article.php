<?php
/**
 * @package thorpesystems
 * @subpackage This
 * @category Model_Article
 * @version $Id: Article.php 689 2007-10-08 22:46:58Z tonyq $
 */

/**
 * @package thorpesystems
 * @subpackage This
 * @category Model_Article
 * @version $Id: Article.php 689 2007-10-08 22:46:58Z tonyq $
 */
class This_Model_Article extends This_Orm_Entity
{
    /**
     * Setup
     */
    public function __construct()
    {
        $this
            ->setTableName('article')

            ->setPrimary('id')

            ->setColumn('parent_id', array('type' => 'int'))
            ->setColumn('author_id', array('type' => 'int'))
            ->setColumn('publisher_id', array('type' => 'int'))
            ->setColumn('status_id', array('type' => 'int'))
            ->setColumn('resource_id', array('type' => 'int'))
            ->setColumn('sort', array('type' => 'int'))
            ->setColumn('name', array('type' => 'varchar'))
            ->setColumn('description', array('type' => 'varchar'))
            ->setColumn('type', array('type' => 'varchar'))

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
                'element as elements',
                array(
                    'type' => 'many',
                    'local' => 'id',
                    'foreign' => 'article_id'
                )
            );
    }

}
