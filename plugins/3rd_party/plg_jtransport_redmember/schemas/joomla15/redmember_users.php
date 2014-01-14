<?php
/**
 * @package     JTransport.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 * 
 *  JTransport is based on JUpgradePRO made by Matias Aguirre
 */

class JTransportRedmemberUser extends JTransport
{
    public function dataHook($rows)
    {
        // Do some custom post processing on the list.
        foreach ($rows as &$row)
        {
            $row = (array) $row;

            $row['user_status'] = $row['deactivestatus'];
            $row['status_change_date'] = $row['deactivedate'];

	        // Remove fields not exist in destination table
	        $this->_removeUnusedFields($row);
        }

        return $rows;
    }
}
?>