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

class JTransportVirtuemartManufacturerCategory extends JTransport
{
    public function dataHook($rows)
    {
        $this->insertIntoManufacturerCategoryENGB($rows);

        // Do some custom post processing on the list.
        foreach ($rows as &$row)
        {
            $row = (array) $row;

            if (isset($row['mf_category_id']))
            {
                $row['virtuemart_manufacturercategories_id'] = $row['mf_category_id'];
            }

	        // Remove fields not exist in destination table
	        $this->_removeUnusedFields($row);
        }

        return $rows;
    }

    public function insertIntoManufacturerCategoryENGB($rows)
    {
        $arrFields = array('virtuemart_manufacturercategories_id',
                            'mf_category_name',
                            'mf_category_desc',
                            'slug'
                        );

        // Do some custom post processing on the list.
        foreach ($rows as &$row)
        {
            $row = (array) $row;

            // Change fields' name
            if (isset($row['mf_category_id']))
            {
                $row['virtuemart_manufacturercategories_id'] = $row['mf_category_id'];

                if (isset($row['mf_category_id']))    
                {
                    $row['slug'] = JApplication::stringURLSafe($row['mf_category_name'] . '-' . $row['mf_category_id']);        
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
        VirtuemartHelper::insertData('#__virtuemart_manufacturercategories_en_gb', $rows);
    }
}
?>