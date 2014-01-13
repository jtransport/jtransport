<?php
/**
 * JTransport
 *
 * @author vdkhai
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Upgrade class for Weblinks
 *
 * This class takes the weblinks from the existing site and inserts them into the new site.
 *
 * @since  0.4.5
 */
class JTransportWeblinks extends JTransport
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
		foreach ($rows as &$row)
		{
			// Convert the array into an object.
			$row = (array) $row;

            $row['id'] = null;
            $row['alias'] = $row['alias'] . '_old';

			$row['language'] = '*';

			unset($row['published']);

			if (version_compare(PHP_VERSION, '3.0', '>='))
			{
				$row['created'] = $row['date'];
				unset($row['approved']);
				unset($row['archived']);
				unset($row['date']);
				unset($row['sid']);
			}
		}

		return $rows;
	}
}
