<?php
/**
 * @package thorpesystems
 * @subpackage This
 * @category Model_Element
 * @version $Id: Element.php 713 2007-10-15 23:44:24Z tonyq $
 */

/**
 * @package thorpesystems
 * @subpackage This
 * @category Model_Element
 * @version $Id: Element.php 713 2007-10-15 23:44:24Z tonyq $
 */
class This_Model_Element extends This_Orm_Entity
{
    /**
     * Setup
     */
    public function __construct()
    {
        $this
            ->setTableName('element')

            ->setPrimary('id')

            ->setColumn('article_id', array('type' => 'int'))
            ->setColumn('sort', array('type' => 'int'))
            ->setColumn('name', array('type' => 'varchar'))
            ->setColumn('type', array('type' => 'varchar'))
            ->setColumn('description', array('type' => 'varchar'))

            ->setBehaviour('Virtual');
    }

    /**
     * Search our tbl_element table for a term.
     *
     * Returns an array of arrays, each array containing
     * the url and name of the page the term was found in
     * along with the element model (which will be a virtual)
     * the term was found in.
     *
     * @param string $term
     * @return array
     */
    public static function search($term)
    {
        $sql = "
            SELECT p.uri as uri, p.name as name, e.data as data
            FROM tbl_element AS e
            INNER JOIN tbl_article AS a ON e.article_id = a.id
            INNER JOIN tbl_page AS p ON a.parent_id = p.id
            WHERE e.data LIKE '%{$term}%'
        ";

        foreach ($this->_db->fetchAll($sql) as $result) {
            $result['data'] = unserialize($result['data']);
        }

        return isset($result) ? $result : false;
    }
}
