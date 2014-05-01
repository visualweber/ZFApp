<?php
/**
 * App Platform
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.xgoon.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to info@www.xgoon.com so we can send you a copy immediately.
 *
 * @author      LowTower - contact@xgoon.com
 * @copyright   Copyright (c) 2007 - 2010,  XGOON MEDIA VIETNAM (www.xgoon.com)
 * @license     http://www.xgoon.com/license/new-bsd     New BSD License
 * @category    App
 * @package    App.Platform
 * @subpackage  App.PlatformFilter
 * @version     $Id: StripSlashes.php 767 2010-05-04 19:22:01Z contact@xgoon.com $
 * @link        http://www.xgoon.com
 * @since       Release 1.9.0
 */

/**
 * @see Zend_Filter_Interface
 */
require_once 'Zend/Filter/Interface.php';

/**
 * Magic Quotes Filter
 *
 * @author      LowTower - contact@xgoon.com
 * @copyright   Copyright (c) 2007 - 2010,  XGOON MEDIA VIETNAM (www.xgoon.com)
 * @license     http://www.xgoon.com/license/new-bsd     New BSD License
 * @version     Release: @package_version@
 * @link        http://www.xgoon.com
 * @since       Release 1.9.0
 */
class App_Filter_StripSlashes implements Zend_Filter_Interface
{
    /**
     * Value to strip tags from
     *
     * @param string $value
     */
    public function filter($value)
    {
        if (get_magic_quotes_gpc()) {
            return stripslashes($value);
        } else {
            return $value;
        }
    }
}