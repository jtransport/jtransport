<?php
/**
 * JTransport
 *
 * @author vdkhai
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * JTransport driver class
 *
 * @since  1.0.1
 */
class JTransportDriver
{
	/**
	 * User's config params
	 *
	 * @var object
	 */
	public $params = null;

	/**
	 * Target database
	 *
	 * @var object
	 */
	protected $_db = null;

	/**
	 * Current step
	 *
	 * @var	array
	 */
	protected $_step = null;

	/**
	 * Constructor
	 *
	 * @param   JTransportStep  $step  Step
	 */
	public function __construct(JTransportStep $step = null)
	{
		// JLoader::import('legacy.component.helper');

		// Get user's config params
		$params = JComponentHelper::getParams('com_jtransport');
		$this->params = $params->toObject();

		// Creating dabatase instance for this installation
		$this->_db = JFactory::getDBO();

		// Set the step params
		$this->_step = $step;
	}

	/**
	 * Create driver instance depend on $param->method (database or restful)
	 *
	 * @param   JTransportStep  $step  Step
	 *
	 * @return mixed
	 *
	 * @throws RuntimeException
	 */
	public static function getInstance(JTransportStep $step = null)
	{
		// Loading the JFile class
		JLoader::import('joomla.filesystem.file');

		// Getting the params and Joomla version web and cli
		$params = JComponentHelper::getParams('com_jtransport');
		$params = $params->toObject();

		// Derive the class name from the driver.
		$class_name = 'JTransportDriver' . ucfirst(strtolower($params->transport_method));
		$class_file = JPATH_COMPONENT_ADMINISTRATOR . '/includes/driver/' . $params->transport_method . '.php';

		// Require the driver file
		if (JFile::exists($class_file))
		{
			JLoader::register($class_name, $class_file);
		}

		// If the class still doesn't exist we have nothing left to do but throw an exception.  We did our best.
		if (!class_exists($class_name))
		{
			throw new RuntimeException(sprintf('Unable to load JTransport Driver: %s', $params->transport_method));
		}

		// Create our new JTransportDriver connector based on the options given.
		try
		{
			$instance = new $class_name($step);
		}
		catch (RuntimeException $e)
		{
			throw new RuntimeException(sprintf('Unable to load JTransport object: %s', $e->getMessage()));
		}

		return $instance;
	}

	/**
	 * Get data from source database
	 *
	 * @return null
	 */
	public function getSourceData()
	{
		return null;
	}

	/**
	 * Update step cid
	 *
	 * @return int
	 */
	public function _getStepCID()
	{
		return $this->_step->cid;
	}

	/**
	 * Get step name
	 *
	 * @return null
	 */
	public function _getStepName()
	{
		return $this->_step->name;
	}
}
