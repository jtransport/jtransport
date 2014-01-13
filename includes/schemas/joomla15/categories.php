<?php
/**
 * JTransport
 *
 * @author vdkhai
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Upgrade class for categories
 *
 * This class takes the categories from the existing site and inserts them into the new site.
 *
 * @since  0.4.5
 */
class JTransportCategories extends JTransport
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
		// Do some custom post processing on the list.
		foreach ($rows as &$row)
		{
			$row = (array) $row;

			if (is_numeric($row['section']))
			{
				$row['parent_id'] = JTransportHelper::lookupNewId('arrCategories', (int) $row['section']);
				$row['extension'] = 'com_content';
			}
			else
			{
				$row['parent_id'] = 0;

				if ($row['section'] == 'com_banner')
				{
					$row['extension'] = 'com_banners';
				}
				elseif ($row['section'] == 'com_contact_details')
				{
					$row['extension'] = 'com_contact';
				}
				else
				{
					$row['extension'] = $row['section'];
				}
			}

			$row['alias'] = $row['alias'] . '_old_' . $row['id'];
			$row['id'] = null;
			$row['lft'] = null;
			$row['rgt'] = null;

            // Remove fields not exist in destination table
            $this->_removeUnusedFields($row);
		}

		return $rows;
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
						$objTable = JTable::getInstance('category', 'JTable', array('dbo' => $this->_db));

						$objTable->setLocation((int) $row['parent_id'], 'last-child');

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
					$objTable = JTable::getInstance('category', 'JTable', array('dbo' => $this->_db));

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
}
