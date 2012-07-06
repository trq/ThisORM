<?php
/**
 * @package thorpesystems
 * @subpackage This
 * @category Model_Flash
 * @version $Id: Flash.php 696 2007-10-09 05:09:30Z tonyq $
 */

/**
 * @package thorpesystems
 * @subpackage This
 * @category Model_Flash
 * @version $Id: Flash.php 696 2007-10-09 05:09:30Z tonyq $
 */
class This_Model_Flash extends This_Orm_Entity
{
    /**
     * Setup
     */
    public function __construct()
    {
        $this
            ->setTableName('flash')

            ->setPrimary('id')

            ->setColumn('article_id', array('type' => 'int'))
            ->setColumn('img', array('type' => 'varchar'))
            ->setColumn('uri', array('type' => 'varchar'));
    }

    /**
     * Get a flash article by id.
     *
     * @param int $id
     * @return This_Model_Flash
     */
    public static function getByArticleId($id)
    {
        $f = new This_Orm_Fetcher;
        return $f->select('flash')
            ->where('article_id = ?', $id)
            ->execute();
    }

}
