<?php
/**
 * JTransport
 *
 * @author vdkhai
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Upgrade class for the Usergroup Map
 *
 * This translates the group mapping table from 1.5 to 3.0.
 * Group id's up to 30 need to be mapped to the new group id's.
 * Group id's over 30 can be used as is.
 * User id's are maintained in this upgrade process.
 *
 */
class JTransportAclaro extends JTransport
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
			$row = (array) $row;

			$row['aro_id'] = $row['id'];
			$row['user_id'] = $row['value'];

			// Remove unused fields.
			unset($row['id']);
			unset($row['section_value']);
			unset($row['value']);
			unset($row['order_value']);
			unset($row['name']);
			unset($row['hidden']);
		}

		return $rows;
	}
}
