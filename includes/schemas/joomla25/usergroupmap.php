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
 * This translates the group mapping table from 2.5 to 3.0.
 * User id's are maintained in this upgrade process.
 *
 */
class JTransportUsergroupMap extends JTransport
{
	/**
	 * Sets the data in the destination database.
	 *
	 * @return	void
	 *
	 * @since	0.4.
	 * @throws	Exception
	 */
	public function dataHook($rows)
	{
		// Do some custom post processing on the list.
		foreach ($rows as &$row)
		{
			$row = (array) $row;

			if (!empty($row['user_id']))
			{
				$newUserId = JTransportHelper::lookupNewId('arrUsers', $row['user_id']);
				$row['user_id'] = $newUserId;
			}

			if (!empty($row['group_id']))
			{
				$newGroupId = JTransportHelper::lookupNewId('arrUsergroups', $row['group_id']);
				$row['group_id'] = $newGroupId;
			}

			// Remove fields not exist in destination table
			$this->_removeUnusedFields($row);
		}

		return $rows;
	}
}
