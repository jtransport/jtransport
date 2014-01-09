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
class JTransportBannerTracks extends JTransport
{
	/**
	 * Sets the data in the destination database.
	 *
	 * @param   array  $rows  Rows
	 *
	 * @return      void
	 */
	public function dataHook($rows = null)
	{
		foreach ($rows as &$row)
		{
			$row = (array) $row;

			$row['id'] = null;

			if ($row['banner_id'] != '')
			{
				$row['banner_id'] = JTransportHelper::lookupNewId('arrBanners', (int) $row['banner_id']);
			}
		}

		return $rows;
	}
} // End class
