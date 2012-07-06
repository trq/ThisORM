<?php
/**
 * @package thorpesystems
 * @subpackage This
 * @category Orm_Interface_Behaviour
 * @version $Id: Behaviour.php 424 2007-08-21 01:42:18Z tonyq $
 */

/**
 * @package thorpesystems
 * @subpackage This
 * @category Orm_Interface_Behaviour
 * @version $Id: Behaviour.php 424 2007-08-21 01:42:18Z tonyq $
 */
interface This_Orm_Interface_Behaviour
{
    public function __construct(This_Orm_Entity $object);
    public function setUp();
}
