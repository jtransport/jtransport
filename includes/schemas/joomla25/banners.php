<?php
/**
 * JTransport
 *
 * @author vdkhai
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Upgrade class for Banners
 *
 * This class takes the banners from the existing site and inserts them into the new site.
 *
 * @since  0.4.5
 */
class JTransportBanners extends JTransport
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

		$new_id = JTransportHelper::getAutoIncrement('banners') - 1;

		foreach ($rows as &$row)
		{
			$row = (array) $row;

			// Create a map of old id and new id
			$old_id = (int) $row['id'];
			$new_id ++;
			$arrTemp = array('old_id' => $old_id, 'new_id' => $new_id);

			$arrBanners = $session->get('arrBanners', null, 'JTransport');

			$arrBanners[] = $arrTemp;

			// Save the map to session
			$session->set('arrBanners', $arrBanners, 'JTransport');

			$row['id'] = null;
			$row['alias'] = $row['alias'] . '_old';

			if ($row['catid'] != '')
			{
				$row['catid'] = JTransportHelper::lookupNewId('arrCategories', (int) $row['catid']);
			}
		}

		return $rows;
	}
}
