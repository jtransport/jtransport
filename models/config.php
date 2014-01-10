<?php
/**
 * JTransport
 *
 * @author vdkhai
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

JLoader::import('joomla.application.component.modeladmin');

/**
 * JTransportModelConfig
 *
 * @package     Joomla.Administrator
 * @subpackage  com_jtransport
 * @since       1.6
 */
class JTransportModelConfig extends JModelAdmin
{
    /**
     * Method to get a single record.
     *
     * @param   integer    The id of the primary key.
     *
     * @return  mixed  Object on success, false on failure.
     */
    public function getItem($pk = null)
    {
	    $item = JComponentHelper::getParams('com_jtransport');

        return $item->toArray();
    }

    /**
     * Method to get the record form.
     *
     * @param   array $data        Data for the form.
     * @param   boolean $loadData    True if the form is to load its own data (default case), false if not.
     *
     * @return  mixed  A JForm object on success, false on failure
     * @since   1.6
     */
    public function getForm($data = array(), $loadData = true)
    {
        // Get the form.
        $form = $this->loadForm('com_jtransport.config', 'config', array('control' => 'jform', 'load_data' => $loadData));

        if (empty($form))
        {
            return false;
        }

        return $form;
    }

    /**
     * Method to get the data that should be injected in the form.
     *
     * @return  mixed  The data for the form.
     * @since   1.6
     */
    protected function loadFormData()
    {
        // Check the session for previously entered form data.
        $app = JFactory::getApplication();
        $data = $app->getUserState('com_jtransport.config.data', array());

        if (empty($data))
        {
            $data = $this->getItem();
        }

        return $data;
    }

	/**
     * Method to save the form data.
     *
     * @param   array  $data  The form data.
     *
     * @return  boolean  True on success.
     *
     * @since   1.6
     */
	public function save($data)
	{
		$query = $this->_db->getQuery(true);

		$query->update("#__extensions")
				->set("params = '" . json_encode($data) . "'")
				->where("type = 'component'")
				->where("element = 'com_jtransport'");

		$this->_db->setQuery($query);

		$this->_db->execute();

		return true;
	}
}
