<?php
/**
 * JTransport
 *
 * @author vdkhai
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Upgrade class for Newsfeeds
 *
 * This class takes the newsfeeds from the existing site and inserts them into the new site.
 *
 * @since  0.4.5
 */
class JTransportNewsfeeds extends JTransport
{
	/**
	 * Sets the data in the destination database.
	 *
	 * @param   array  $rows  Rows
	 *
	 * @return	void
	 *
	 * @since	3.0.
	 * @throws	Exception
	 */
	public function dataHook($rows = null)
	{
		// Do some custom post processing on the list.
		foreach ($rows as &$row)
		{
			$row = (array) $row;

			$row['id'] = null;

			$row['access'] = 1;
			$row['language'] = '*';

			if ($row['catid'] != '')
			{
				$row['catid'] = JTransportHelper::lookupNewId('arrCategories', (int) $row['catid']);
			}

			// Remove fields not exist in destination table
			$this->_removeUnusedFields($row);
		}

		return $rows;
	}
}
