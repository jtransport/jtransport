<?php
/**
 * JTransport
 *
 * @author vdkhai
 */

// No direct access.
defined('_JEXEC') or die;

JLoader::import('joomla.application.component.view');

/**
 * @package		Model Transport
 * @subpackage	com_jtransport
 */
class JTransportViewTransport extends JViewLegacy
{
	protected $params;

	/**
	 * Display the view.
	 *
	 * @param	string	$tpl  The sub template to display.
	 *
	 * @return	void
	 */
	public function display($tpl = null)
	{
		$this->params = $this->get('Params');

		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		$this->addToolbar();

		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since   3.0
	 */
	protected function addToolBar()
	{
		JToolbarHelper::title(JText::_('COM_JTRANSPORT_TRANSPORT'), 'JTransport');
		JToolbarHelper::cancel('transport.cancel', 'JTOOLBAR_CLOSE');
	}
}
