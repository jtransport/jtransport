<?php
/**
 * JTransport
 *
 * @author vdkhai
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Upgrade class for Users
 *
 * This class takes the users from the source site and inserts them into the target site.
 */
class JTransportUsernotes extends JTransport
{
	/**
	 * Change structure of table and value of fields
	 * so data can be inserted into target db
	 *
	 * @param $rows Rows of source db
	 *
	 * @return mixed
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

			if (!empty($row['created_user_id']))
			{
				$newCreatedUserId = JTransportHelper::lookupNewId('arrUsers', $row['created_user_id']);
				$row['created_user_id'] = $newCreatedUserId;
			}
		}

		return $rows;
	}
}
