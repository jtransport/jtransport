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
class JTransportUsergroupMap extends JTransport
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
		foreach ($rows as $k => &$row)
		{
			$row = (array) $row;

			if (!empty($row['aro_id']))
			{
				$oldUserId = $this->_lookupUserId($row['aro_id']);
				$newUserId = JTransportHelper::lookupNewId('arrUsers', $oldUserId);
				$row['user_id'] = $newUserId;

				if ($row['user_id'] == -1)
				{
					$rows[$k] = false;
				}
			}

			if (!empty($row['group_id']))
			{
				$newGroupId = JTransportHelper::lookupNewId('arrUsergroups', $row['group_id']);
				$row['group_id'] = $newGroupId;

				if ($row['group_id'] == -1)
				{
					$rows[$k] = false;
				}
			}

			// Remove fields not exist in destination table
			$this->_removeUnusedFields($row);
		}

		return $rows;
	}

	/**
	 * Lookup user id from aro id
	 *
	 * @param   int  $aroId  Aro id
	 *
	 * @return int
	 */
	protected function _lookupUserId($aroId)
	{
		$db = JFactory::getDbo();

		$query = $db->getQuery(true);

		$query->select('user_id')
				->from('#__jtransport_core_acl_aro')
				->where('aro_id = ' . $aroId);

		$db->setQuery($query);

		$user_id = $db->loadResult();

		return (int) $user_id;
	}
}
