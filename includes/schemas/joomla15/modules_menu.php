<?php
/**
 * JTransport
 *
 * @author vdkhai
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Upgrade class for modules menu
 *
 * This class takes the modules from the existing site and inserts them into the new site.
 *
 * @since  0.4.5
 */
class JTransportModulesMenu extends JTransport
{
	/**
	 * Sets the data in the destination database.
	 *
	 * @param   array  $rows  Rows
	 *
	 * @return	object
	 */
	public function dataHook($rows = null)
	{
		foreach ($rows as $k => &$row)
		{
			// Convert the array into an object.
			$row = (array) $row;

			if ($row['moduleid'] != '')
			{
				$row['moduleid'] = JTransportHelper::lookupNewId('arrModules', (int) $row['moduleid']);
			}

			if ($row['menuid'] != '' && (int) $row['menuid'] > 0)
			{
				$row['menuid'] = JTransportHelper::lookupNewId('arrMenu', (int) $row['menuid']);
			}

			// Module or menu item doesn't exist
			if ((int) $row['moduleid'] == -1 || (int) $row['menuid'] == -1)
			{
				$rows[$k] = false;
			}
		}

		return $rows;
	}
}
