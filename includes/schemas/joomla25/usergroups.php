<?php
/**
 * JTransport
 *
 * @author vdkhai
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Upgrade class for Usergroups
 *
 * This class takes the usergroups from the source site and inserts them into the target site.
 */
class JTransportUsergroups extends JTransport
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
		$session = JFactory::getSession();

		$new_id = JTransportHelper::getAutoIncrement('usergroups') - 1;

		// Do some custom post processing on the list.
		foreach ($rows as &$row)
		{
			$row = (array) $row;

			// Create a map of old id and new id
			$old_id = (int) $row['id'];

			if ((int) $row['parent_id'] == 0)
			{
				$new_root_id = $this->getRootId();
				$arrTemp = array('old_id' => $old_id, 'new_id' => $new_root_id);
			}
			else
			{
				$new_id ++;
				$arrTemp = array('old_id' => $old_id, 'new_id' => $new_id);
			}

			$arrUsergroups = $session->get('arrUsergroups', null, 'jtransport');

			$arrUsergroups[] = $arrTemp;

			// Save the map to session
			$session->set('arrUsergroups', $arrUsergroups, 'jtransport');

			if ((int) $row['parent_id'] != 0)
			{
				// Parent item was inserted, so lookup new id
				if ((int) $row['id'] > (int) $row['parent_id'])
				{
					$row['parent_id'] = JTransportHelper::lookupNewId('arrUsergroups', (int) $row['parent_id']);
				}
				else // Parent item haven't been inserted, so will lookup new id and update item apter hook
				{
					$arrUsergroupsSwapped = $session->get('arrUsergroupsSwapped', null, 'jtransport');

					$arrUsergroupsSwapped[] = array('new_id' => $new_id, 'old_parent_id' => (int) $row['parent_id']);

					$session->set('arrUsergroupsSwapped', $arrUsergroupsSwapped, 'jtransport');

					$row['parent_id'] = $this->getRootId();
				}

				$row['id'] = null;
				$row['lft'] = null;
				$row['rgt'] = null;

				// Change group title if it is exist in destination table
				if ($this->_checkUsergroupExist($row['name']))
				{
					$row['title'] = $row['name'] . '_old';
				}
				else
				{
					$row['title'] = $row['name'];
				}

                // Remove fields not exist in destination table
                // $this->_removeUnusedFields($row);
			}
		}

		return $rows;
	}

	/**
	 * Update items have patent item after itself
	 *
	 * @return bool
	 */
	public function afterHook()
	{
		$session = JFactory::getSession();

		$arrMenuSwapped = $session->get('arrUsergroupsSwapped', null, 'jtransport');

		foreach ($arrMenuSwapped as $item)
		{
			$objTable = JTable::getInstance('usergroup', 'JTable', array('dbo' => $this->_db));

			$objTable->load($item['new_id']);

			$objTable->parent_id = JTransportHelper::lookupNewId('arrUsergroups', $item['old_parent_id']);

			if (!$objTable->store())
			{
				echo JError::raiseError(500, $objTable->getError());
			}
		}

		return parent::afterHook();
	}

	/**
	 * @param $rows Rows for target db
	 *
	 * @return bool|void
	 * @throws Exception
	 */
	protected function insertData($rows)
	{
		if (is_array($rows))
		{
			foreach ($rows as $row)
			{
				if ($row != false && (int) $row['parent_id'] != 0)
				{
					try
					{
						$objTable = JTable::getInstance('usergroup', 'JTable', array('dbo' => $this->_db));

						// Bind data to save category
						if (!$objTable->bind($row))
						{
							echo JError::raiseError(500, $objTable->getError());
						}

						if (!$objTable->store())
						{
							echo JError::raiseError(500, $objTable->getError());
						}
					}
					catch (Exception $e)
					{
						throw new Exception($e->getMessage());
					}
				}

				$this->_step->_nextCID();
			}
		}
		elseif (is_object($rows))
		{
			if ($rows != false && $rows->parent_id != 0)
			{
				try
				{
					$objTable = JTable::getInstance('usergroup', 'JTable', array('dbo' => $this->_db));

					// Bind data to save category
					if (!$objTable->bind($rows))
					{
						echo JError::raiseError(500, $objTable->getError());
					}

					if (!$objTable->store())
					{
						echo JError::raiseError(500, $objTable->getError());
					}
				}
				catch (Exception $e)
				{
					throw new Exception($e->getMessage());
				}
			}
		}

		return !empty($this->_step->error) ? false : true;
	}

	/**
	 * Get the id of root usergroup
	 *
	 * @return mixed
	 */
	protected function getRootId()
	{
		$query = $this->_db->getQuery(true);

		$query->select('id')
				->from('#__usergroups')
				->where('parent_id = 0');

		$this->_db->setQuery($query);

		$id = $this->_db->loadResult();

		return (int) $id;
	}

	/**
	 * Check if usergroup exist in target db
	 *
	 * @param   string  $grouptitle  Group title of source db
	 *
	 * @return mixed
	 */
	protected function _checkUsergroupExist($grouptitle)
	{
		$query = $this->_db->getQuery(true);

		$query->select('count(id)')
			->from('#__usergroups')
			->where('title = "' . $grouptitle . '"');

		$this->_db->setQuery($query);

		$exist = $this->_db->loadResult();

		return $exist;
	}
}
