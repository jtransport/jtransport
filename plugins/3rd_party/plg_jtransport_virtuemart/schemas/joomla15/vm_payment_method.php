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

class JTransportVirtuemartPaymentMethod extends JTransport
{
    public function dataHook($rows)
    {
        $this->insertIntoPaymentMethodENGB($rows);

        // Do some custom post processing on the list.
        foreach ($rows as &$row)
        {
            $row = (array) $row;

            // Change fields' name
            if (isset($row['payment_method_id']))
            {
                $row['virtuemart_paymentmethod_id'] = $row['payment_method_id'];    
            }
            
            if (isset($row['vendor_id']))
            {
                $row['virtuemart_vendor_id'] = $row['vendor_id'];    
            }
            
            if (isset($row['list_order']))
            {
                $row['virtuemart_ordering'] = $row['list_order'];    
            }

	        // Remove fields not exist in destination table
	        // $this->_removeUnusedFields($row);
        }

        return $rows;
    }

    public function insertIntoPaymentMethodENGB($rows)
    {
        $arrFields = array('virtuemart_paymentmethod_id',
                            'payment_name',
                            'slug'
                        );

        // Do some custom post processing on the list.
        foreach ($rows as &$row)
        {
            $row = (array) $row;

            // Change fields' name
            if (isset($row['payment_method_id']))
            {
                $row['virtuemart_paymentmethod_id'] = $row['payment_method_id'];    
            }
            
            if (isset($row['payment_method_name']))
            {
                $row['payment_name'] = $row['payment_method_name'];

                if (isset($row['payment_method_id']))
                {
                    $row['slug'] = JApplication::stringURLSafe($row['payment_method_name'] . '-' . $row['payment_method_id']);        
                }    
            }

            foreach ($row as $key => $value)
            {
                if (!in_array($key, $arrFields))
                {
                    unset($row[$key]);
                }
            }            
        }

        JLoader::import("helpers.virtuemart", JPATH_PLUGINS . "/jtransport/jtransport_virtuemart");
        VirtuemartHelper::insertData('#__virtuemart_paymentmethods_en_gb', $rows);
    }
}
?>