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
 * JTransport Model
 *
 * @since  1.0.0
 */
class JTransportModelAjaxTransport extends JModelLegacy
{
	/**
	 * Migrate
	 *
	 * @param   bool  $table  Table
	 *
	 * @return mixed
	 *
	 * @throws Exception
	 */
	function transport($table = false)
	{
		if ($table === false)
		{
			$table = JRequest::getCmd('table', '');
		}

		// Init the JTransport instance
		$step = JTransportStep::getInstance($table);
		$jtransport = JTransport::getInstance($step);

		// Run the upgrade
		if ($step->total > 0)
		{
			try
			{
				$jtransport->transport();
			}
			catch (Exception $e)
			{
				JTransportHelper::writeFile('log_transport_progress_error.txt', $e->getMessage() . "\n");
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

		// Update #__jtransport_steps table if id = last_id
		if ( ( ($step->total <= $step->cid) || ($step->stop == -1) && ($empty == false) ) )
		{
			$step->next = true;
			$step->status = 2;

			$step->_updateStep();
		}

		// Return params to client
		echo $step->getParameters();
	}
} // End class
