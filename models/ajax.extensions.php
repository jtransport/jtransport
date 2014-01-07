<?php
/**
 * @package     RedMIGRATOR.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 * 
 *  redMIGRATOR is based on JUpgradePRO made by Matias Aguirre
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

JLoader::register('JTransport', JPATH_COMPONENT_ADMINISTRATOR . '/includes/jtransport.class.php');
JLoader::register('JTransportStep', JPATH_COMPONENT_ADMINISTRATOR . '/includes/jtransport.step.class.php');
JLoader::register('JTransportExtensions', JPATH_COMPONENT_ADMINISTRATOR . '/includes/jtransport.extensions.class.php');

/**
 * JTransport Model
 *
 */
class JTransportModelAjaxExtensions extends JModelLegacy
{
	/**
	 * Migrate the extensions
	 *
	 * @return	none
	 *
	 * @since	2.5.0
	 */
	function extensions()
	{
		// Get the step
		$step = JTransportStep::getInstance('extensions', true);

		// Get JTransportExtensions instance
		$extensions = JTransport::getInstance($step);

		// Initialize 3rd extensions
		$success = $extensions->upgrade();

		if ($success === true)
		{
			$step->status = 2;
			$step->_updateStep();

			if (!JTransportHelper::isCli())
			{
				echo "success";
			}

			return true;
		}
	}
} // End class
