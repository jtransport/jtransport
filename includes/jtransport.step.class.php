<?php
/**
 * JTransport
 *
 * @author vdkhai
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * redmigrator step class
 */
class JTransportStep
{
	public $id = null;

	public $name = null;

	public $title = null;

	public $type = null;

	public $class = null;

	public $replace = '';

	public $xmlpath = '';

	public $element = null;

	public $conditions = null;

	public $tbl_key = '';

	public $source = '';

	public $destination = '';

	public $cid = 0;

	public $cache = 0;

	public $status = 0;

	public $total = 0;

	public $start = 0;

	public $stop = 0;

	public $laststep = '';

	public $stepTotal = 0;

	public $first = false;

	public $next = false;

	public $middle = false;

	public $end = false;

	public $extensions = false;

	public $_table = false;

	public $debug = '';

	public $error = '';

	/**
	 * @var      
	 * @since  3.0
	 */
	protected $_db = null;

	/**
	 * Constructor
	 *
	 * @param null $name
	 * @param bool $extensions
	 */
	function __construct($name = null, $extensions = false)
	{
		JLoader::import('legacy.component.helper');

		// Creating dabatase instance for this installation
		$this->_db = JFactory::getDBO();

		// Set step table
		if ($extensions == false)
		{
			$this->_table = '#__redmigrator_steps';
		}
		elseif ($extensions == true)
		{
			$this->_table = '#__redmigrator_extensions';
		}

		// Load the last step from database
		$this->_load($name);
	}

	/**
	 * Get step instance
	 *
	 * @param   string  $name        Name
	 * @param   bool    $extensions  True if there 3rd extensions
	 *
	 * @return  redmigrator  A redmigrator object.
	 */
	public static function getInstance($name = null, $extensions = false)
	{
		// Create our new redmigrator connector based on the options given.
		try
		{
			$instance = new JTransportStep($name, $extensions);
		}
		catch (RuntimeException $e)
		{
			throw new RuntimeException(sprintf('Unable to load JTransportStep object: %s', $e->getMessage()));
		}

		return $instance;
	}

	/**
	 * Method to set the parameters. 
	 *
	 * @param   array  $data  The parameters to set.
	 *
	 * @return  void
	 *
	 * @since   3.0.0
	 */
	public function setParameters($data)
	{
		// Ensure that only valid OAuth parameters are set if they exist.
		if (!empty($data))
		{
			foreach ($data as $k => $v)
			{
				if (property_exists($this, $k))
				{
					// Perform url decoding so that any use of '+' as the encoding of the space character is correctly handled.
					$this->$k = urldecode((string) $v);
				}
			}
		}
	}

	/**
	 * Method to get the parameters. 
	 *
	 * @return  array  $parameters  The parameters of this object.
	 */
	public function getParameters()
	{
		$return = array();

		foreach ($this as $k => $v)
		{
			if (property_exists($this, $k))
			{
				if (!is_object($v))
				{
					if ($v != "" || $k == 'total' || $k == 'start' || $k == 'stop')
					{
						// Perform url decoding so that any use of '+' as the encoding of the space character is correctly handled.
						$return[$k] = urldecode((string) $v);
					}
				}
			}
		}

		// Get the last step and save to session
		$session = JFactory::getSession();
		$laststep = $session->get('laststep', '', 'redmigrator');

		if ($laststep == '')
		{
			// Reset the $query object
			$query = $this->_db->getQuery(true);

			// Select last step
			$query->select('name')
					->from($this->_table)
					->where('status = 0')
					->order('id DESC')
					->limit(1);

			$this->_db->setQuery($query);

			$laststep = $this->_db->loadResult();

			$session->set('laststep', $laststep, 'redmigrator');
		}
		else
		{
			$return['laststep'] = $laststep;
		}

		// Get total of steps and save to session
		$stepTotal = $session->get('stepTotal', 0, 'redmigrator');

		if ($stepTotal == 0)
		{
			// Reset the $query object
			$query = $this->_db->getQuery(true);

			$query->select('count(*)')
					->from('#__redmigrator_steps')
					->where('status = 0');

			$this->_db->setQuery($query);

			$stepTotal = $this->_db->loadResult();

			$session->set('stepTotal', $stepTotal, 'redmigrator');
		}
		else
		{
			$return['stepTotal'] = $stepTotal;
		}

		return json_encode($return);
	}

	/**
	 * Get the next step
	 *
	 * @param   bool  $name  Name
	 * @param   bool  $json  Json
	 *
	 * @return array|bool
	 */
	public function getStep($name = false, $json = true)
	{
		// Check if step is loaded
		if (empty($name))
		{
			return false;
		}

		$params = JTransportHelper::getParams();

		$limit = $params->chunk_limit;

		// Getting the total
		if (isset($this->source))
		{
			$this->total = JTransportHelper::getTotal($this);
		}

		// We must to fragment the steps
		if ($this->total > $limit)
		{
			if ($this->cache == 0 && $this->status == 0)
			{
				if (version_compare(PHP_VERSION, '5.3.0') >= 0)
				{
					$this->cache = round(($this->total - 1) / $limit, 0, PHP_ROUND_HALF_DOWN);
				}
				else
				{
					$this->cache = round(($this->total - 1) / $limit);
				}

				$this->start = 0;
				$this->stop = $limit - 1;
				$this->first = true;
				$this->debug = "{{{1}}}";

			}
			elseif ($this->cache == 1 && $this->status == 1)
			{
				$this->start = $this->cid;
				$this->cache = 0;
				$this->stop = $this->total - 1;
				$this->debug = "{{{2}}}";
				$this->first = false;

			}
			elseif ($this->cache > 0)
			{
				$this->start = $this->cid;
				$this->stop = ($this->start - 1) + $limit;
				$this->cache = $this->cache - 1;
				$this->debug = "{{{3}}}";
				$this->first = false;

				if ($this->stop > $this->total)
				{
					$this->stop = $this->total - 1;
					$this->next = true;
					$this->end = true;
				}
				else
				{
					$this->middle = true;
				}
			}

			// Status == 1
			$this->status = 1;

		}
		elseif ($this->total == 0)
		{
			$this->stop = -1;
			$this->next = 1;
			$this->first = true;

			$this->cache = 0;
			$this->status = 2;
			$this->debug = "{{{4}}}";

		}
		else
		{
			$this->start = 0;
			$this->first = 1;
			$this->cache = 0;
			$this->stop = $this->total - 1;
			$this->debug = "{{{5}}}";
		}

		// Updating the status flag to database
		$this->_updateStep();

		return $this->getParameters();
	}

	/**
	 * Getting the current step from database and put it into object properties
	 *
	 * @param   null  $name  Name
	 *
	 * @return bool
	 */
	public function _load($name = null)
	{
		// Getting the data
		$query = $this->_db->getQuery(true);
		$query->select('*');
		$query->from($this->_table);

		if (!empty($name))
		{
			$query->where("name = '{$name}'");
		}
		else
		{
			$query->where("status != 2");
		}

		$query->order('id ASC');
		$query->limit(1);

		$this->_db->setQuery($query);
		$step = $this->_db->loadAssoc();

		// Check for query error.
		$error = $this->_db->getErrorMsg();

		if ($error)
		{
			return false;
		}

		// Check if step is an array
		if (!is_array($step))
		{
			return false;
		}

		// Set the parameters
		$this->setParameters($step);

		return true;
	}

	/**
	 * Update Step
	 *
	 * @return	none
	 */
	public function _updateStep()
	{
		$query = $this->_db->getQuery(true);

		$query->update($this->_table);

		$columns = array('status', 'cache', 'cid', 'total', 'start', 'stop', 'first');

		foreach ($columns as $column)
		{
			if (!empty($this->$column))
			{
				// $query->set("{$column} = {$this->$column}");
				$query->set($column . " = " . $this->$column);
			}
		}

		// $query->where("name = {$this->_db->quote($this->name)}");
		$query->where("name = " . $this->_db->quote($this->name));

		$this->_db->setQuery($query);

		$this->_db->execute();

		// Check for query error.
		$error = $this->_db->getErrorMsg();

		if ($error)
		{
			throw new Exception($error);
		}

		return true;
	}

	/**
	 * Update cid
	 *
	 * @param   int  $cid  Current id
	 *
	 * @return boolean True if the user and pass are authorized
	 *
	 * @throws  InvalidArgumentException
	 */
	public function _updateCID($cid)
	{
		$name = $this->_getStepName();

		$query = $this->_db->getQuery(true);
		$query->update($this->_table);
		$query->set("`cid` = '{$cid}'");
		$query->where("name = {$this->_db->quote($name)}");

		// Execute the query
		return $this->_db->setQuery($query)->execute();
	}

	/**
	 * Get next cid
	 *
	 * @return  boolean  True if the user and pass are authorized
	 *
	 * @throws  InvalidArgumentException
	 */
	public function _nextCID()
	{
		$update_cid = $this->_getStepCID() + 1;

		$this->_updateCID($update_cid);

		if (JTransportHelper::isCli())
		{
			echo "•";
		}
	}

	/**
	 * Update the step id
	 *
	 * @return  int  The next id
	 */
	public function _getStepCID()
	{
		$this->_load();

		return $this->cid;
	}

	/**
	 * Get step name
	 *
	 * @return string The step name
	 */
	public function _getStepName()
	{
		return $this->name;
	}
}
