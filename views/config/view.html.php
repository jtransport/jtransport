<?php
/**
 * JTransport
 *
 * @author  vdkhai
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * View for the component configuration
 *
 * @package     Joomla.Administrator
 * @subpackage  com_jtransport
 * @since       1.5
 */
class JTransportViewConfig extends JViewLegacy
{
    protected $form;

    /**
     * Display the view
     */
    public function display($tpl = null)
    {
        $this->form = $this->get('Form');

        // Check for errors.
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
    protected function addToolbar()
    {
        JToolbarHelper::title(JText::_('COM_JTRANSPORT_CONFIG'), 'config.png');
        JToolbarHelper::apply('config.apply');
        JToolbarHelper::save('config.save');
        JToolbarHelper::divider();
        JToolbarHelper::cancel('config.cancel');
    }
}
