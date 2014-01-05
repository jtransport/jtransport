<?php
/**
 * JTransport
 *
 * @author  vdkhai
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// Turn off all error reporting
error_reporting(0);

// Set un-limit timeout
ini_set('max_execution_time', 0);

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_jtransport'))
{
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

// Import joomla controller library
Jloader::import('joomla.application.component.controller');

// Loading the helper
JLoader::import('helpers.jtransport', JPATH_COMPONENT_ADMINISTRATOR);

// Getting the controller
$controller	= JControllerLegacy::getInstance('JTransport');
$controller->execute(JFactory::getApplication()->input->get('task'));

// Redirect if set by the controller
$controller->redirect();
