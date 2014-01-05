<?php
/**
 * @package     RedMIGRATOR.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 * 
 *  redMIGRATOR is based on JUpgradePRO made by Matias Aguirre
 */

/**
 * Upgrade class for Users
 *
 * This class takes the users from the existing site and inserts them into the new site.
 *
 * @since  0.4.4
 */
class RedMigratorUsers extends RedMigrator
{


	/**
	 * Change structure of table and value of fields
	 * so data can be inserted into target db
	 *
	 * @param   array  $rows  Rows of source db
	 *
	 * @return mixed
	 */
	public function dataHook($rows)
	{
		$session = JFactory::getSession();

		$new_id = RedMigratorHelper::getAutoIncrement('users') - 1;

		// Do some custom post processing on the list.
		foreach ($rows as &$row)
		{
			$row = (array) $row;

			// Create a map of old id and new id
			$old_id = (int) $row['id'];
			$new_id ++;
			$arrTemp = array('old_id' => $old_id, 'new_id' => $new_id);

			$arrUsers = $session->get('arrUsers', null, 'redmigrator');

			$arrUsers[] = $arrTemp;

			// Save the map to session
			$session->set('arrUsers', $arrUsers, 'redmigrator');

			$row['id'] = null;

			if ($this->_checkUserExist($row['username'], $row['email']))
			{
				$row['username'] = $row['username'] . '_old';
				$row['email'] = $row['email'] . '_old';
			}

			unset($row['gid']);

			if (version_compare(PHP_VERSION, '3.0', '>='))
			{
				unset($row['usertype']);
			}
		}

		return $rows;
	}

	/**
	 * Check if username or email exist in target db
	 *
	 * @param   string  $username  Username of source db
	 * @param   string  $email     Email of source db
	 *
	 * @return mixed
	 */
	protected function _checkUserExist($username, $email)
	{
		$query = $this->_db->getQuery(true);

		$query->select('count(id)')
			->from('#__users')
			->where('username = "' . $username . '" OR email = "' . $email . '"');

		$this->_db->setQuery($query);

		$exist = $this->_db->loadResult();

		return $exist;
	}
}
