<?php
/**
 * JTransport
 *
 * @author vdkhai
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Upgrade class for Weblinks
 *
 * This class takes the weblinks from the existing site and inserts them into the new site.
 *
 * @since  0.4.5
 */
class JTransportWeblinks extends JTransport
{
	/**
	 * Sets the data in the destination database.
	 *
	 * @param   array  $rows  Rows
	 *
	 * @return	void
	 *
	 * @throws	Exception
	 */
	public function dataHook($rows)
	{
		// Do some custom post processing on the list.
		foreach ($rows as &$row)
		{
			// Convert the array into an object.
			$row = (array) $row;

			$row['id'] = null;
			$row['alias'] = $row['alias'] . '_old';

			$row['language'] = '*';

			$row['params'] = $this->convertParams($row['params']);

			if ($row['catid'] != '')
			{
				$row['catid'] = JTransportHelper::lookupNewId('arrCategories', (int) $row['catid']);
			}

			$row['state'] = $row['published'];

			// Remove fields not exist in destination table
			$this->_removeUnusedFields($row);
		}

		return $rows;
	}
}
