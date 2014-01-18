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

class JTransportKunenaUser extends JTransport
{
    public function dataHook($rows)
    {
        // Do some custom post processing on the list.
        foreach ($rows as &$row)
        {
            $row = (array) $row;

            // Change fields' name
            if (isset($row['ICQ']))
            {
                $row['icq'] = $row['ICQ'];
            }

            if (isset($row['AIM']))
            {
                $row['aim'] = $row['AIM'];
            }

            if (isset($row['YIM']))
            {
                $row['yim'] = $row['YIM'];
            }

            if (isset($row['MSN']))
            {
                $row['msn'] = $row['MSN'];
            }

            if (isset($row['SKYPE']))
            {
                $row['skype'] = $row['SKYPE'];
            }

            if ($row['GTALK'])
            {
                $row['gtalk'] = $row['GTALK'];
            }

	        // Remove fields not exist in destination table
	        // $this->_removeUnusedFields($row);
        }

        return $rows;
    }
}
?>