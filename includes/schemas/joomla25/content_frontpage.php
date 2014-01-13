<?php
/**
 * JTransport
 *
 * @author vdkhai
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Upgrade class for FrontEnd content
 *
 */
class JTransportContentFrontpage extends JTransport
{
	/**
	 * Sets the data in the destination database.
	 *
	 * @return	void
	 */
	public function dataHook($rows)
	{
		foreach ($rows as &$row)
		{
			$row = (array) $row;

			if ($row['content_id'] != '')
			{
				$row['content_id'] = JTransportHelper::lookupNewId('arrContent', (int) $row['content_id']);
			}

            // Remove fields not exist in destination table
            $this->_removeUnusedFields($row);
		}

		return $rows;
	}
}
