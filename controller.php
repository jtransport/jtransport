<?php
/**
 * JTransport
 *
 * @author  vdkhai
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// Import Joomla controller library
JLoader::import('joomla.application.component.controller');

/**
 * General Controller of RedMigrator component
 */
class JTransportController extends JControllerLegacy
{
	/**
	 * Display task
	 *
	 * @param   bool  $cachable   Cachable
	 * @param   array $urlparams  Url params
	 *
	 * @return JControllerLegacy|void
	 */
	function display($cachable = false, $urlparams = array())
	{
		// Set default view if not set
		JRequest::setVar('view', JRequest::getCmd('view', 'config'));

		// Call parent behavior
		parent::display($cachable);
	}
}