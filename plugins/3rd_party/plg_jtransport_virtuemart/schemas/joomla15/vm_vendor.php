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

class JTransportVirtuemartVendor extends JTransport
{
    public function dataHook($rows)
    {
        $this->insertIntoVendorENGB($rows);

        $session = JFactory::getSession();
        $mediaId = $session->get('mediaId', 0, 'jtransport_virtuemart');

        if ($mediaId == 0)
        {
            JLoader::import("helpers.virtuemart", JPATH_PLUGINS . "/jtransport/jtransport_virtuemart");
            $mediaId = VirtuemartHelper::getMediaId();
            $session->set('mediaId', $mediaId, 'jtransport_virtuemart');
        }

        $this->insertIntoVendorMedias($rows);

        $this->insertIntoMedias($rows);

        // Do some custom post processing on the list.
        foreach ($rows as &$row)
        {
            $row = (array) $row;

            if (isset($row['vendor_id']))
            {
                $row['virtuemart_vendor_id'] = $row['vendor_id'];    
            }
            
            if (isset($row['cdate']))
            {
                $row['virtuemart_created_on'] = $row['cdate'];    
            }
            
            if (isset($row['mdate']))
            {
                $row['virtuemart_modified_on'] = $row['mdate'];    
            }
            
            if (isset($row['vendor_min_pov']))
            {
                $row['vendor_params'] = 'vendor_min_pov="' . $row['vendor_min_pov'] . '"|';    
            }

            if (isset($row['vendor_freeshipping']))
            {
                $row['vendor_params'] .= 'vendor_freeshipment=' . $row['vendor_freeshipping'] . '|';
            }
            
            if (isset($row['vendor_address_format']))
            {
                $row['vendor_params'] .= 'vendor_address_format="' . $row['vendor_address_format'] . '"|';
            }

	        // Remove fields not exist in destination table
	        // $this->_removeUnusedFields($row);
        }

        return $rows;
    }

    public function insertIntoVendorENGB($rows)
    {
        $arrFields = array('virtuemart_vendor_id',
                            'vendor_store_desc',
                            'vendor_store_name',
                            'vendor_terms_of_service',
                            'vendor_phone',
                            'vendor_url',
                            'slug'
                        );

        // Do some custom post processing on the list.
        foreach ($rows as &$row)
        {
            $row = (array) $row;

            // Change fields' name
            if (isset($row['vendor_id']))
            {
                $row['virtuemart_vendor_id'] = $row['vendor_id'];

                if (isset($row['vendor_name']))    
                {
                    $row['slug'] = JApplication::stringURLSafe($row['vendor_name'] . '-' . $row['vendor_id']);        
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
        VirtuemartHelper::insertData('#__virtuemart_vendors_en_gb', $rows);
    }

    public function insertIntoVendorMedias($rows)
    {
        $arrFields = array('virtuemart_vendor_id',
                            'virtuemart_media_id'
                        );

        // Do some custom post processing on the list.
        foreach ($rows as &$row)
        {
            $row = (array) $row;

            // Change fields' name
            if (isset($row['vendor_id']))
            {
                $row['virtuemart_vendor_id'] = $row['vendor_id'];    
            }

            $session = JFactory::getSession();
            $mediaId = $session->get('mediaId', 0, 'jtransport_virtuemart');
            
            $row['virtuemart_media_id'] = $mediaId;

            $mediaId ++;
            $session->set('mediaId', $mediaId, 'jtransport_virtuemart');

            foreach ($row as $key => $value)
            {
                if (!in_array($key, $arrFields))
                {
                    unset($row[$key]);
                }
            }            
        }

        JLoader::import("helpers.virtuemart", JPATH_PLUGINS . "/jtransport/jtransport_virtuemart");
        VirtuemartHelper::insertData('#__virtuemart_vendor_medias', $rows);
    }

    public function insertIntoMedias($rows)
    {
        JLoader::import('joomla.filesystem.folder');
        JLoader::import('joomla.filesystem.file');
        JLoader::import('helpers.virtuemart', JPATH_PLUGINS . '/jtransport/jtransport_virtuemart');

        $arrFields = array('file_title',
                            'file_mimetype',
                            'file_type',
                            'file_url',
                            'file_url_thumb',
                            'file_is_product_image'
                        );

        // Do some custom post processing on the list.
        foreach ($rows as &$row)
        {
            $row = (array) $row;

            $oldMediaDir = JPATH_ADMINISTRATOR . '/components/com_jtransport/includes/media/joomla15/shop_image/vendor';

            if (JFolder::exists($oldMediaDir))
            {
                $descFolder = JPATH_SITE . '/images/stories/virtuemart/vendor';

                if (!JFolder::exists($descFolder))
                {
                    JFolder::create($descFolder);
                }

                if (isset($row['vendor_full_image']))
                {
                    $row['file_title'] = $row['vendor_full_image'];

                    $src = $oldMediaDir . '/' . $row['vendor_full_image'];

                    if (JFile::exists($src))
                    {
                        $row['file_mimetype'] = VirtuemartHelper::getMimeType($src);
                        $row['file_type'] = 'vendor';
                        $row['file_url'] = 'images/stories/virtuemart/vendor/' . $row['vendor_full_image'];
                        
                        $desc = $descFolder . '/' . $row['vendor_full_image'];
                        JFile::copy($src, $desc);
                    }
                }

                if (isset($row['vendor_thumb_image']))
                {
                    $srcThumb = $oldMediaDir . '/' . $row['vendor_thumb_image'];

                    if (JFile::exists($src))
                    {
                        $row['file_url_thumb'] = 'images/stories/virtuemart/vendor/' . $row['vendor_thumb_image'];
                        
                        $descThumb = $descFolder . '/' . $row['vendor_thumb_image'];
                        JFile::copy($src, $desc);
                    }
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

        VirtuemartHelper::insertData('#__virtuemart_medias', $rows);
    }

    public function afterHook($rows)
    {
        $session = JFactory::getSession();
        $session->set('mediaId', 0, 'jtransport_virtuemart');

        return parent::afterHook($rows);
    }
}
?>