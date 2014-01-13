<?php
/**
 * JTransport
 *
 * @author vdkhai
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Upgrade class for Users
 *
 * This class takes the users from the existing site and inserts them into the new site.
 *
 * @since  0.4.4
 */
class JTransportUsers extends JTransport
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
		$session = JFactory::getSession();

		$new_id = JTransportHelper::getAutoIncrement('users') - 1;

		// Do some custom post processing on the list.
		foreach ($rows as &$row)
		{
			$row = (array) $row;

			// Create a map of old id and new id
			$old_id = (int) $row['id'];
			$new_id ++;
			$arrTemp = array('old_id' => $old_id, 'new_id' => $new_id);

			$arrUsers = $session->get('arrUsers', null, 'jtransport');

			$arrUsers[] = $arrTemp;

			// Save the map to session
			$session->set('arrUsers', $arrUsers, 'jtransport');

            // Avoid conflict id with rows of destination table
			$row['id'] = null;

            // Change username if it is exist in destination table
			if ($this->_checkUsernameExist($row['username']))
			{
				$row['username'] = $row['username'] . '_old';
			}

            // Change email if it is exist in destination table
            if ($this->_checkEmailExist($row['email']))
            {
                $row['email'] = $row['email'] . '_old';
            }

            // Remove fields not exist in destination table
            $this->_removeUnusedFields($row);
		}

		return $rows;
	}

	/**
	 * Check if username exist in target db
	 *
	 * @param   string  $username  Username of source db
	 *
	 * @return mixed
	 */
	protected function _checkUsernameExist($username)
	{
		$query = $this->_db->getQuery(true);

		$query->select('count(id)')
			->from('#__users')
			->where('username = "' . $username . '"');

		$this->_db->setQuery($query);

		$exist = $this->_db->loadResult();

		return $exist;
	}

    /**
     * Check if email exist in target db
     *
     * @param   string  $email  Email of source db
     *
     * @return mixed
     */
    protected function _checkEmailExist($email)
    {
        $query = $this->_db->getQuery(true);

        $query->select('count(id)')
            ->from('#__users')
            ->where('email = "' . $email . '"');

        $this->_db->setQuery($query);

        $exist = $this->_db->loadResult();

        return $exist;
    }
}
