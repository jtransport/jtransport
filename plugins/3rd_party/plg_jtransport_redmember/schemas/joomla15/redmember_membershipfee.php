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

class JTransportRedmemberMembershipfee extends JTransport
{
    public function dataHook($rows)
    {
        // Do some custom post processing on the list.
        foreach ($rows as &$row)
        {
            $row = (array) $row;

            // Change fields' name
            if (isset($row['membershipfee_id']))
            {
                $row['membership_id'] = $row['membershipfee_id'];    
            }

            if (isset($row['membershipfee_name']))
            {
                $row['membership_name'] = $row['membershipfee_name'];
            }

            if (isset($row['user_type']))
            {
                $row['user_group_ids'] = $row['user_type'];    
            }

            if (isset($row['membershipfee_period']))
            {
                $row['period'] = $row['membershipfee_period'];    
            }

            if (isset($row['membershipfee_price']))
            {
                $row['price'] = $row['membershipfee_price'];    
            }

	        // Remove fields not exist in destination table
	        // $this->_removeUnusedFields($row);
        }

        return $rows;
    }
}
?>