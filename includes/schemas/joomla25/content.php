<?php
/**
 * JTransport
 *
 * @author vdkhai
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Migrate class for content
 *
 * This class takes the content from the existing site and inserts them into the new site.
 */
class JTransportContent extends JTransport
{
	/**
	 * Sets the data in the destination database.
	 *
	 * @return	void
	 */
	public function dataHook($rows)
	{
		$session = JFactory::getSession();

		$new_id = JTransportHelper::getAutoIncrement('content') - 1;

		foreach ($rows as &$row)
		{
			$row = (array) $row;

			// Create a map of old id and new id
			$old_id = (int) $row['id'];
			$new_id ++;
			$arrTemp = array('old_id' => $old_id, 'new_id' => $new_id);

			$arrContent = $session->get('arrContent', null, 'jtransport');

			$arrContent[] = $arrTemp;

			// Save the map to session
			$session->set('arrContent', $arrContent, 'jtransport');

			$row['id'] = null;

			if ($row['catid'] != '')
			{
				$row['catid'] = JTransportHelper::lookupNewId('arrCategories', (int) $row['catid']);
			}

			if ($row['created_by'] != '')
			{
				$row['created_by'] = JTransportHelper::lookupNewId('arrUsers', (int) $row['created_by']);
			}

			if ($row['modified_by'] != '')
			{
				$row['modified_by'] = JTransportHelper::lookupNewId('arrUsers', (int) $row['modified_by']);
			}

            // Remove fields not exist in destination table
            // $this->_removeUnusedFields($row);
		}

		return $rows;
	}
}
