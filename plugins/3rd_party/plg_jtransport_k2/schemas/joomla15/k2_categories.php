<?php
/**
 * @package     RedMIGRATOR.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 * 
 *  redMIGRATOR is based on JUpgradePRO made by Matias Aguirre
 */

class JTransportK2Category extends JTransport
{
    public function dataHook($rows)
    {
        foreach ($rows as &$row)
        {
            $row = (array) $row;
            
            $row['access'] = 1;
        }

        return $rows;
    }
}
?>