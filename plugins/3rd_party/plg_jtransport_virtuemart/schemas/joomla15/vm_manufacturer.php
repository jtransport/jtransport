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

class JTransportVirtuemartManufacturer extends JTransport
{
    public function dataHook($rows)
    {
        $this->insertIntoManufacturerENGB($rows);

        // Do some custom post processing on the list.
        foreach ($rows as &$row)
        {
            $row = (array) $row;

            if (isset($row['manufacturer_id']))
            {
                $row['virtuemart_manufacturer_id'] = $row['manufacturer_id'];    
            }
            
            if (isset($row['mf_category_id']))
            {
                $row['virtuemart_manufacturercategories_id'] = $row['mf_category_id'];    
            }

	        // Remove fields not exist in destination table
	        // $this->_removeUnusedFields($row);
        }

        return $rows;
    }

    public function insertIntoManufacturerENGB($rows)
    {
        $arrFields = array('virtuemart_manufacturer_id',
                            'mf_name',
                            'mf_email',
                            'mf_desc',
                            'mf_url',
                            'slug'
                        );

        // Do some custom post processing on the list.
        foreach ($rows as &$row)
        {
            $row = (array) $row;

            // Change fields' name
            if (isset($row['manufacturer_id']))
            {
                $row['virtuemart_manufacturer_id'] = $row['manufacturer_id'];
                
                if (isset($row['mf_name']))
                {
                    $row['slug'] = JApplication::stringURLSafe($row['mf_name'] . '-' . $row['manufacturer_id']);                            
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
        VirtuemartHelper::insertData('#__virtuemart_manufacturers_en_gb', $rows);
    }
}
?>