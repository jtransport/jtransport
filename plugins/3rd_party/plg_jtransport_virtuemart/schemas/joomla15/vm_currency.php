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

class JTransportVirtuemartCurrency extends JTransport
{
    public function dataHook($rows)
    {
        // Do some custom post processing on the list.
        foreach ($rows as &$row)
        {
            $row = (array) $row;

            // Change fields' name
            if (isset($row['currency_id']))
            {
                $row['virtuemart_currency_id'] = $row['currency_id'];
            }

            if (isset($row['currency_code']))
            {
                if (strlen(trim($row['currency_code'])) == 2)
                {
                    $row['currency_code_2'] = $row['currency_code'];
                }
                else
                {
                    $row['currency_code_3'] = $row['currency_code'];
                }
            }

	        // Remove fields not exist in destination table
	        $this->_removeUnusedFields($row);
        }

        return $rows;
    }
}
?>