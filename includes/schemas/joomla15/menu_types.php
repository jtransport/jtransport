<?php
/**
 * JTransport
 *
 * @author vdkhai
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Upgrade class for MenusTypes
 *
 * This class takes the menus from the existing site and inserts them into the new site.
 *
 * @since  0.4.5
 */
class JTransportMenuTypes extends JTransport
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
		foreach ($rows as $k => &$row)
		{
			$row = (array) $row;

			$row['id'] = null;

			if ($this->checkMenutypeExist($row['menutype']))
			{
				$rows[$k] = false;
			}

            // Remove fields not exist in destination table
            $this->_removeUnusedFields($row);
		}

		return $rows;
	}

	/**
	 * Check if menu type exist
	 *
	 * @param   string  $menutype  Menutype
	 *
	 * @return mixed
	 */
	protected function checkMenutypeExist($menutype)
	{
		$query = $this->_db->getQuery(true);

		$query->select('count(id)')
			->from('#__menu_types')
			->where('menutype = "' . $menutype . '"');

		$this->_db->setQuery($query);

		$exist = $this->_db->loadResult();

		return $exist;
	}
}
