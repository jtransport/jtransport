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
 * @since  1.0.0
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

			// Remove fields not exist in destination table
			// $this->_removeUnusedFields($row);
		}

		return $rows;
	}
}
