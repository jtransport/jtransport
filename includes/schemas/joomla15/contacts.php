<?php
/**
 * JTransport
 *
 * @author vdkhai
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Upgrade class for Contacts
 *
 * This class takes the contacts from the existing site and inserts them into the new site.
 */
class JTransportContacts extends JTransport
{
	/**
	 * Sets the data in the destination database.
	 *
	 * @param   array  $rows  $rows
	 *
	 * @return null
	 */
	public function dataHook($rows = null)
	{
		// Do some custom post processing on the list.
		foreach ($rows as &$row)
		{
			$row = (array) $row;

			$row['id'] = null;
			$row['alias'] = $row['alias'] . '_old';

			if ($row['user_id'] != '')
			{
				$row['user_id'] = JTransportHelper::lookupNewId('arrUsers', (int) $row['user_id']);
			}

			if ($row['catid'] != '')
			{
				$row['catid'] = JTransportHelper::lookupNewId('arrCategories', (int) $row['catid']);
			}

			if (version_compare(PHP_VERSION, '3.0', '>='))
			{
				unset($row['imagepos']);
			}
		}

		return $rows;
	}
}
