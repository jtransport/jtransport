<?php
/**
 * JTransport
 *
 * @author vdkhai
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

JLoader::register('JTransport', JPATH_COMPONENT_ADMINISTRATOR . '/includes/jtransport.class.php');
JLoader::register('JTransportStep', JPATH_COMPONENT_ADMINISTRATOR . '/includes/jtransport.step.class.php');

/**
 * Class JTransportModelStep
 */
class JTransportModelAjaxStep extends JModelLegacy
{
	/**
	 * Initial checks in JTransport
	 *
	 * @param   bool  $name  Name of step
	 *
	 * @return mixed
	 */
	public function step($name = false)
	{
		// Getting the JTransportStep instance
		$step = JTransportStep::getInstance(null);

		// Check if name exists
		if ($name === false)
		{
			$name = $step->name;
		}

		// Get the next step
		$step->getStep($name);

		// Return params to client
		echo $step->getParameters();
	}
} // End class
