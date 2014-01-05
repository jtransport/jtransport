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

/**
 * Upgrade class for Banners
 *
 * This class takes the banners from the existing site and inserts them into the new site.
 *
 * @since  0.4.5
 */
class RedMigratorBanners extends RedMigrator
{
	/**
	 * Sets the data in the destination database.
	 *
	 * @return      void
	 *
	 * @since       0.4.
	 * @throws      Exception
	 */
	public function dataHook($rows = null)
	{
		$session = JFactory::getSession();

		$new_id = RedMigratorHelper::getAutoIncrement('banners') - 1;

		foreach ($rows as &$row)
		{
			$row = (array) $row;

			// Create a map of old id and new id
			$old_id = (int) $row['id'];
			$new_id ++;
			$arrTemp = array('old_id' => $old_id, 'new_id' => $new_id);

			$arrBanners = $session->get('arrBanners', null, 'redmigrator');

			$arrBanners[] = $arrTemp;

			// Save the map to session
			$session->set('arrBanners', $arrBanners, 'redmigrator');

			$row['id'] = null;
			$row['alias'] = $row['alias'] . '_old';

			if ($row['catid'] != '')
			{
				$row['catid'] = RedMigratorHelper::lookupNewId('arrCategories', (int) $row['catid']);
			}
		}

		return $rows;
	}
}
