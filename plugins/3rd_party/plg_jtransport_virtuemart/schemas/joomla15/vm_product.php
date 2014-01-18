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

class JTransportVirtuemartProduct extends JTransport
{
    public function dataHook($rows)
    {
        $this->insertIntoProductENGB($rows);

        $session = JFactory::getSession();
        $mediaId = $session->get('mediaId', 0, 'jtransport_virtuemart');

        if ($mediaId == null || $mediaId == 0)
        {
            JLoader::import("helpers.virtuemart", JPATH_PLUGINS . "/jtransport/jtransport_virtuemart");
            $mediaId = VirtuemartHelper::getMediaId();
            $session->set('mediaId', $mediaId, 'jtransport_virtuemart');
        }

        $this->insertIntoProductMedias($rows);

        $this->insertIntoMedias($rows);

        // Do some custom post processing on the list.
        foreach ($rows as &$row)
        {
            $row = (array) $row;

            // Change fields' name
            if (isset($row['product_id']))
            {
                $row['virtuemart_product_id'] = $row['product_id'];
            }
            
            if (isset($row['vendor_id']))
            {
                $row['virtuemart_vendor_id'] = $row['vendor_id'];    
            }
            
            if (isset($row['product_publish']))
            {
                if ($row['product_publish'] == 'Y')
                {
                    $row['published'] = 1;
                }
                else
                {
                    $row['published'] = 0;
                }    
            }
                 
            if (isset($row['product_order_levels']))
            {
                $row['pordering'] = $row['product_order_levels'];    
            }
            
            if (isset($row['cdate']))
            {
                $row['created_on'] = $row['cdate'];    
            }
            
            if (isset($row['mdate']))
            {
                $row['modified_on'] = $row['mdate'];    
            }

	        // Remove fields not exist in destination table
	        // $this->_removeUnusedFields($row);
        }

        return $rows;
    }

    public function insertIntoProductENGB($rows)
    {
        $arrFields = array('virtuemart_product_id',
                            'product_s_desc',
                            'product_desc',
                            'product_name',
                            'slug'
                        );

        // Do some custom post processing on the list.
        foreach ($rows as &$row)
        {
            $row = (array) $row;

            // Change fields' name
            if (isset($row['product_id']))
            {
                $row['virtuemart_product_id'] = $row['product_id'];    
            
                if (isset($row['product_id']))
                {
                    $row['slug'] = JApplication::stringURLSafe($row['product_name'] . '-' . $row['product_id']);        
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
        VirtuemartHelper::insertData('#__virtuemart_products_en_gb', $rows);
    }

    public function insertIntoProductMedias($rows)
    {
        $arrFields = array('virtuemart_product_id',
                            'virtuemart_media_id'
                        );

        // Do some custom post processing on the list.
        foreach ($rows as &$row)
        {
            $row = (array) $row;

            // Change fields' name
            if (isset($row['product_id']))
            {
                $row['virtuemart_product_id'] = $row['product_id'];    
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
        VirtuemartHelper::insertData('#__virtuemart_product_medias', $rows);
    }

    public function insertIntoMedias($rows)
    {   
        JLoader::import('joomla.filesystem.folder');
        JLoader::import('joomla.filesystem.file');
        JLoader::import("helpers.virtuemart", JPATH_PLUGINS . "/jtransport/jtransport_virtuemart");

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

            $oldMediaDir = JPATH_ADMINISTRATOR . '/components/com_jtransport/includes/media/joomla15/shop_image/product';

            if (JFolder::exists($oldMediaDir))
            {
                $descFolder = JPATH_SITE . '/images/stories/virtuemart/product';

                if (!JFolder::exists($descFolder))
                {
                    JFolder::create($descFolder);
                }

                if (isset($row['product_full_image']))
                {
                    $row['file_title'] = $row['product_full_image'];

                    $src = $oldMediaDir . '/' . $row['product_full_image'];

                    if (JFile::exists($src))
                    {
                        $row['file_mimetype'] = VirtuemartHelper::getMimeType($src);
                        $row['file_type'] = 'product';
                        $row['file_url'] = 'images/stories/virtuemart/product/' . $row['product_full_image'];
                        
                        $desc = $descFolder . '/' . $row['product_full_image'];
                        JFile::copy($src, $desc);
                    }
                }

                if (isset($row['product_thumb_image']))
                {
                    $srcThumb = $oldMediaDir . '/' . $row['product_thumb_image'];

                    if (JFile::exists($src))
                    {
                        $row['file_url_thumb'] = 'images/stories/virtuemart/product/' . $row['product_thumb_image'];
                        
                        $descThumb = $descFolder . '/' . $row['product_thumb_image'];
                        JFile::copy($src, $desc);
                    }
                }

                $row['file_is_product_image'] = 1;
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