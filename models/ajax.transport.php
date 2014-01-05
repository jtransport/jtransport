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

JLoader::register('JTransport', JPATH_COMPONENT_ADMINISTRATOR . '/includes/redmigrator.class.php');
JLoader::register('JTransportStep', JPATH_COMPONENT_ADMINISTRATOR . '/includes/redmigrator.step.class.php');

/**
 * JTransport Model
 *
 */
class JTransportModelAjaxTransport extends RModelAdmin
{
	/**
	 * Migrate
	 *
	 * @param   bool  $table       Table
	 * @param   bool  $extensions  Extension
	 *
	 * @return mixed
	 *
	 * @throws Exception
	 */
	function migrate($table = false, $extensions = false)
	{
		if ($table === false)
		{
			$table = JRequest::getCmd('table', '');
		}

		if ($extensions === false)
		{
			$extensions = JRequest::getCmd('extensions', '');
		}

		// Init the JTransport instance
		$step = JTransportStep::getInstance($table, $extensions);
		$redmigrator = JTransport::getInstance($step);

		// Get the database structure
		if ($step->first == true && $extensions == 'tables')
		{
			$redmigrator->getTableStructure();
		}

		// Run the upgrade
		if ($step->total > 0)
		{
			try
			{
				$redmigrator->upgrade();
			}
			catch (Exception $e)
			{
				throw new Exception($e->getMessage());
			}
		}

		// Javascript flags
		if ($step->cid == $step->stop + 1 && $step->total != 0)
		{
			$step->next = true;
		}

		if ($step->total == $step->cid)
		{
			$step->end = true;
		}

		$empty = false;

		if ($step->cid == 0 && $step->total == 0 && $step->start == 0 && $step->stop == 0)
		{
			$empty = true;
		}

		if ($step->stop == 0)
		{
			$step->stop = -1;
		}

		// Update #__redmigrator_steps table if id = last_id
		if ( ( ($step->total <= $step->cid) || ($step->stop == -1) && ($empty == false) ) )
		{
			$step->next = true;
			$step->status = 2;

			$step->_updateStep();
		}

		if (!JTransportHelper::isCli())
		{
			echo $step->getParameters();
		}
		else
		{
			return $step->getParameters();
		}
	}
} // End class
