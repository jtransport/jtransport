<?php
/**
 * JTransport
 *
 * @author vdkhai
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Upgrade class for banners clients 
 *
 * @since  2.5.2
 */
class JTransportBannerClients extends JTransport
{
	/**
	 * Sets the data in the destination database.
	 *
	 * @param   array  $rows  Rows
	 *
	 * @return      void
	 *
	 * @throws      Exception
	 */
	public function dataHook($rows = null)
	{
		foreach ($rows as &$row)
		{
			$row = (array) $row;

			$row['id'] = null;

			// Remove fields not exist in destination table
			// $this->_removeUnusedFields($row);
		}

		return $rows;
	}
}
