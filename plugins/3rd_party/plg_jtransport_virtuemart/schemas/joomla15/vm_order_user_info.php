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

class JTransportVirtuemartOrderUserInfo extends JTransport
{
    public function dataHook($rows)
    {
        // Do some custom post processing on the list.
        foreach ($rows as &$row)
        {
            $row = (array) $row;

            // Change fields' name
            if (isset($row['order_info_id']))
            {
                $row['virtuemart_order_userinfo_id'] = $row['order_info_id'];
            }
            
            if (isset($row['order_id']))
            {
                $row['virtuemart_order_id'] = $row['order_id'];    
            }
            
            if (isset($row['user_id']))
            {
                $row['virtuemart_user_id'] = $row['user_id'];    
            }

            JLoader::import("helpers.virtuemart", JPATH_PLUGINS . "/jtransport/jtransport_virtuemart");
            
            $stateId = VirtuemartHelper::getStateId($row['state']);

            if ($stateId)
            {
                $row['virtuemart_state_id'] = $stateId;
            }

            $countryId = VirtuemartHelper::getCountryId($row['country']);

            if ($countryId)
            {
                $row['virtuemart_country_id'] = $countryId;
            }                
            
            if (isset($row['user_email']))
            {
                $row['email'] = $row['user_email'];    
            }

	        // Remove fields not exist in destination table
	        // $this->_removeUnusedFields($row);
        }

        return $rows;
    }

    
}
?>