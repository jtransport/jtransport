<?php
/**
 * JTransport
 *
 * @author vdkhai
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Upgrade class for Menus
 *
 * This class takes the menus from the existing site and inserts them into the new site.
 *
 * @since  0.4.5
 */

class JTransportMenu extends JTransport
{
	private $arrSystemComponents = array('Home');

	/**
	 * Sets the data in the destination database.
	 *
	 * @param   null  $rows  Rows
	 *
	 * @return null
	 */
	public function dataHook($rows = null)
	{
		$session = JFactory::getSession();

		$new_id = JTransportHelper::getAutoIncrement('menu') - 1;

		foreach ($rows as $k => &$row)
		{
			$row = (array) $row;

			$row['title'] = $row['name'];

			// Not migrate system menus
			if (in_array($row['title'], $this->arrSystemComponents))
			{
				$rows[$k] = false;
			}
			else
			{
				// Create a map of old id and new id
				$old_id = (int) $row['id'];
				$new_id ++;
				$arrTemp = array('old_id' => $old_id, 'new_id' => $new_id);

				$arrMenu = $session->get('arrMenu', null, 'jtransport');

				$arrMenu[] = $arrTemp;

				// Save the map to session
				$session->set('arrMenu', $arrMenu, 'jtransport');

				if ((int) $row['parent'] == 0)
				{
					$row['parent_id'] = $this->getRootId();
				}
				else
				{
					// Parent item was inserted, so lookup new id
					if ((int) $row['id'] > (int) $row['parent'])
					{
						$row['parent_id'] = JTransportHelper::lookupNewId('arrMenu', (int) $row['parent']);
					}
					else // Parent item haven't been inserted, so will lookup new id and update item after hook
					{
						$arrMenuSwapped = $session->get('arrMenuSwapped', null, 'jtransport');

						$arrMenuSwapped[] = array('new_id' => $new_id, 'old_parent_id' => (int) $row['parent_id']);

						$session->set('arrMenuSwapped', $arrMenuSwapped, 'jtransport');

						$row['parent_id'] = $this->getRootId();
					}
				}

				$row['alias'] = $row['alias'] . '_old_' . $row['id'];
				$row['id'] = null;
				$row['lft'] = null;
				$row['rgt'] = null;

				$row['published'] = 0;

                // Remove fields not exist in destination table
                $this->_removeUnusedFields($row);
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

		$arrMenuSwapped = $session->get('arrMenuSwapped', null, 'jtransport');

		foreach ($arrMenuSwapped as $item)
		{
			$objTable = JTable::getInstance('menu', 'JTable', array('dbo' => $this->_db));

			$objTable->load($item['new_id']);

			$objTable->parent_id = JTransportHelper::lookupNewId('arrMenu', $item['old_parent_id']);

			$objTable->setLocation($objTable->parent_id, 'last-child');

			if (!$objTable->store())
			{
				echo JError::raiseError(500, $objTable->getError());
			}
		}

		return parent::afterHook();
	}

	/**
	 * Insert data
	 *
	 * @param   array  $rows  Rows for target db
	 *
	 * @return bool|void
	 *
	 * @throws Exception
	 */
	protected function insertData($rows)
	{
		if (is_array($rows))
		{
			foreach ($rows as $row)
			{
				if ($row != false)
				{
					try
					{
						$objTable = JTable::getInstance('menu', 'JTable', array('dbo' => $this->_db));

						$objTable->setLocation($row['parent_id'], 'last-child');

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
			if ($rows != false)
			{
				try
				{
					$objTable = JTable::getInstance('menu', 'JTable', array('dbo' => $this->_db));

					$objTable->setLocation($rows->parent_id, 'last-child');

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
			->from('#__menu')
			->where('parent_id = 0');

		$this->_db->setQuery($query);

		$id = $this->_db->loadResult();

		return (int) $id;
	}
}
