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

class JTransportJoomfishLanguage extends JTransport
{
    public function dataHook($rows)
    {
        // Do some custom post processing on the list.
        foreach ($rows as &$row)
        {
            $row = (array) $row;

            if ($row['image'] == '')
            {
	            $row['image'] = 'image_' . $row['lang_id'];
            }

	        // Remove fields not exist in destination table
	        $this->_removeUnusedFields($row);
        }

        return $rows;
    }
}
?>