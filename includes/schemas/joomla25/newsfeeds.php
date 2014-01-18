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

			if ($row['created_by'] != '')
			{
				$row['created_by'] = JTransportHelper::lookupNewId('arrUsers', (int) $row['created_by']);
			}

			if ($row['modified_by'] != '')
			{
				$row['modified_by'] = JTransportHelper::lookupNewId('arrUsers', (int) $row['modified_by']);
			}

            // Remove fields not exist in destination table
            // $this->_removeUnusedFields($row);
		}

		return $rows;
	}
}
