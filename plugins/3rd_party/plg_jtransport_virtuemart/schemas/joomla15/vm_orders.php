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

class JTransportVirtuemartOrder extends JTransport
{
    public function dataHook($rows)
    {
        // Do some custom post processing on the list.
        foreach ($rows as &$row)
        {
            $row = (array) $row;

            // Change fields' name
            if (isset($row['order_id']))
            {
                $row['virtuemart_order_id'] = $row['order_id'];    
            }
            
            if (isset($row['user_id']))
            {
                $row['virtuemart_user_id'] = $row['user_id'];    
            }
            
            if (isset($row['vendor_id']))
            {
                $row['virtuemart_vendor_id'] = $row['vendor_id'];    
            }
            
            if (isset($row['order_shipping']))
            {
                $row['order_shipment'] = $row['order_shipping'];    
            }
            
            if (isset($row['order_shipping_tax']))
            {
                $row['order_shipment_tax'] = $row['order_shipping_tax'];    
            }
            
            if (isset($row['cdate']))
            {
                $row['created_on'] = $row['cdate'];    
            }
            
            if (isset($row['mdate']))
            {
                $row['modified_on'] = $row['mdate'];    
            }
            
            if (isset($row['ship_method_id']))
            {
                $row['virtuemart_shipmentmethod_id'] = $row['ship_method_id'];    
            }

	        // Remove fields not exist in destination table
	        // $this->_removeUnusedFields($row);
        }

        return $rows;
    }
}
?>