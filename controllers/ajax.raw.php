<?php
/**
 * JTransport
 *
 * @author   vdkhai
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * The JTransport ajax controller
 *
 * @package     JTransport
 * @subpackage  com_jtransport
 * @since       3.0.3
 */
class JTransportControllerAjax extends JControllerLegacy
{
	/**
	 * Get Model
	 *
	 * @param string $name
	 * @param string $prefix
	 *
	 * @return object
	 */
	public function getModel($name = '', $prefix = 'JTransportModel')
	{
		return parent::getModel($name, $prefix, array('ignore_request' => true));
	}

	/**
	 * Run the JTransport checks
	 *
	 * @return none
	 */
	public function preTransport()
	{
		// Get the model for the view.
		$model = $this->getModel('AjaxPreTransport');

		// Running the checks
		try
		{
			$model->preTransport();
		}
		catch (Exception $e)
		{
			JTransportHelper::returnError(500, $e->getMessage());
		}

	}

	/**
	 * Run JTransport step
	 *
	 * @return none
	 */
	public function step()
	{
		// Get the model for the view.
		$model = $this->getModel('AjaxStep');

		// Running the step
		try
		{
			$model->step();
		}
		catch (Exception $e)
		{
			JTransportHelper::returnError(500, $e->getMessage());
		}
	}

	/**
	 * Run JTransport transport
	 *
	 * @return none
	 */
	public function transport()
	{
		// Get the model for the view.
		$model = $this->getModel('AjaxTransport');

		// Running the migrate
		try
		{
			$model->transport();
		}
		catch (Exception $e)
		{
			JTransportHelper::returnError(500, $e->getMessage());
		}
	}

	/**
	 * Run JTransport extensions
	 *
	 * @return none
	 */
	public function extensions()
	{
		// Get the model for the view.
		$model = $this->getModel('AjaxExtensions');

		// Running the extensions
		try
		{
			$model->extensions();
		}
		catch (Exception $e)
		{
			JTransportHelper::returnError(500, $e->getMessage());
		}
	}
}
