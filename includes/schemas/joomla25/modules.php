<?php
/**
 * JTransport
 *
 * @author vdkhai
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Upgrade class for modules
 *
 * This class takes the modules from the existing site and inserts them into the new site.
 *
 * @since  0.4.5
 */
class JTransportModules extends JTransport
{
	/**
	 * Sets the data in the destination database.
	 *
	 * @param   array  $rows  Rows
	 *
	 * @return	void
	 */
	public function dataHook($rows = null)
	{
		$session = JFactory::getSession();

		$new_id = JTransportHelper::getAutoIncrement('modules') - 1;

		// Do some custom post processing on the list.
		foreach ($rows as &$row)
		{
			$row = (array) $row;

			// Create a map of old id and new id
			$old_id = (int) $row['id'];
			$new_id ++;
			$arrTemp = array('old_id' => $old_id, 'new_id' => $new_id);

			$arrModules = $session->get('arrModules', null, 'jtransport');

			$arrModules[] = $arrTemp;

			// Save the map to session
			$session->set('arrModules', $arrModules, 'jtransport');

			$row['id'] = null;

			$row['published'] = 0;
		}

		return $rows;
	}
}
