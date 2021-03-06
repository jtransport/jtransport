<?php
/**
 * JTransport
 *
 * @author vdkhai
 */

/**
 * Upgrade class for modules
 *
 * This class takes the modules from the existing site and inserts them into the new site.
 *
 * @since  0.4.5
 */
class JTransportModules extends JTransport
{
	private $map = array(
							// Old	                => // New
							'search'				=> 'position-0',
							'top'					=> 'position-1',
							'breadcrumbs'			=> 'position-2',
							'left'					=> 'position-7',
							'right'					=> 'position-6',
							'search'				=> 'position-8',
							'footer'				=> 'position-9',
							'header'				=> 'position-15');

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

		// Get the component parameter with global settings
		$params = $this->getParams();

		// Set up the mapping table for the old positions to the new positions.
		$map_keys = array_keys($this->map);

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

			// Change positions
			if ($params->positions == 0)
			{
				if (in_array($row['position'], $map_keys))
				{
					$row['position'] = $this->map[$row['position']];
				}
			}

			// Fix access
			$row['access'] = $row['access'] + 1;

			// Language
			$row['language'] = "*";

			$row['params'] = $this->convertParams($row['params']);

			// Module field changes
			if ($row['module'] == "mod_mainmenu")
			{
				$row['module'] = "mod_menu";
			}
			elseif ($row['module'] == "mod_archive")
			{
				$row['module'] = "mod_articles_archive";
			}
			elseif ($row['module'] == "mod_latestnews")
			{
				$row['module'] = "mod_articles_latest";
			}
			elseif ($row['module'] == "mod_mostread")
			{
				$row['module'] = "mod_articles_popular";
			}
			elseif ($row['module'] == "mod_newsflash")
			{
				$row['module'] = "mod_articles_news";
			}

			$row['published'] = 0;

			// Remove fields not exist in destination table
			// $this->_removeUnusedFields($row);
		}

		return $rows;
	}

	/**
	 * A hook to be able to modify params prior as they are converted to JSON.
	 *
	 * @param   object  $object  Object
	 *
	 * @return none
	 */
	protected function convertParamsHook($object)
	{
		if (isset($object->startLevel))
		{
			$object->startLevel++;
		}
	}
}
