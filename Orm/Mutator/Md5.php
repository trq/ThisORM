<?php
/**
 * @package thorpesystems
 * @subpackage This
 * @category Orm_Mutator_Md5
 * @version $Id: Md5.php 203 2007-07-21 23:18:23Z tonyq $
 */

/**
 * @package thorpesystems
 * @subpackage This
 * @category Orm_Mutator_Md5
 * @version $Id: Md5.php 203 2007-07-21 23:18:23Z tonyq $
 */
class This_Orm_Mutator_Md5 implements This_Orm_Interface_Mutator
{
    /**
     * Apply the md5 function to any value passed to the mutator
     *
     * @param string $val
     * @return string hashed
     */
    public function execute($val) {
        return md5($val);
    }
}
