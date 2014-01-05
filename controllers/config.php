<?php
/**
 * JTransport
 *
 * @author  vdkhai
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Note: this view is intended only to be opened in a popup
 *
 * @package     Joomla.Administrator
 * @subpackage  com_transport
 * @since       1.5
 */
class JTransportControllerConfig extends JControllerLegacy
{
	/**
	 * Constructor
	 *
	 * @param array $config
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);

		// Map the apply task to the save method.
		$this->registerTask('apply', 'save');
	}

	/**
	 * Cancel operation
	 *
	 * @return  void
	 *
	 * @since   3.0
	 */
	public function cancel()
	{
		$this->setRedirect('index.php?option=com_jtransport&view=transport');
	}

	/**
	 * Save the configuration
	 */
	public function save()
	{
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$model  = $this->getModel('Config');
		$input = JFactory::getApplication()->input;
		$data = $input->get('jform', array(), 'array');

		$model->save($data);

		// Set the redirect based on the task.
		switch ($this->getTask())
		{
			case 'apply':
				$message = JText::_('COM_CONFIG_SAVE_SUCCESS');

				$this->setRedirect('index.php?option=com_jtransport&view=config', $message);
				break;

			case 'save':
			default:
				$message = JText::_('COM_CONFIG_SAVE_SUCCESS');

				$this->setRedirect('index.php?option=com_jtransport&view=transport', $message);
				break;
		}

		return true;
	}
}
