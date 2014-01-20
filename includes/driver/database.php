<?php
/**
 * JTransport
 *
 * @author vdkhai
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * JTransport database utility class
 *
 * @since  1.0.0
 */
class JTransportDriverDatabase extends JTransportDriver
{
	/**
	 * Source database
	 *
	 * @var object
	 */
	public $_db_old = null;

	/**
	 * Constructor
	 *
	 * @param   JTransportStep  $step  Step
	 */
	function __construct(JTransportStep $step = null)
	{
		parent::__construct($step);

		$class = 'JTransport';

		if (!empty($step->class))
		{
			$class = $step->class;
		}

		$name = '';

		if (!empty($step->name))
		{
			$name = $step->name;
		}

		$type = 'core15';

		if (!empty($step->type))
		{
			$type = $step->type;
		}

		JTransportHelper::requireClass($name, $type, $class);

		// @@ Fix bug using PHP < 5.2.3 version
		// $this->_conditions = call_user_func($class . '::getConditionsHook');

		$db_config = array();
		$db_config['driver'] = $this->params->database_driver;
		$db_config['host'] = $this->params->database_hostname;
		$db_config['user'] = $this->params->database_username;
		$db_config['password'] = $this->params->database_password;
		$db_config['database'] = $this->params->database_name;
		$db_config['prefix'] = $this->params->table_prefix;

		$this->_db_old = JDatabase::getInstance($db_config);
	}

	/**
	 * Get data from source database
	 *
	 * @return null
	 *
	 * @throws Exception
	 */
	public function getSourceData()
	{
		$query = $this->_db->getQuery(true);

		$query->select('*')
			->from($this->getSourceTable());

		if ($this->_step->tbl_key != "")
		{
			$query->order($this->_step->tbl_key);
		}

		$start = (int) $this->_getStepCID();
		$limit = (int) $this->params->chunk_limit;

		// Setting the query
		$this->_db_old->setQuery($query, $start, $limit);

		try
		{
			$rows = $this->_db_old->loadAssocList();
		}
		catch (Exception $e)
		{
			throw new Exception($e->getMessage());
		}

		return $rows;
	}

	/**
	 * Get total of the rows of the table
	 *
	 * @access	public
	 * @return	int	The total of rows
	 */
	public function getTotal()
	{
		$query = $this->_db_old->getQuery(true);

		$query->select('COUNT(*)')
				->from($this->getSourceTable());

		// Setting the query
		$this->_db_old->setQuery($query);

		try
		{
			// $rows = $this->_db_old->loadAssocList();
			$total = $this->_db_old->loadResult();
		}
		catch (Exception $e)
		{
			throw new Exception($e->getMessage());
		}

		// return (int) count($rows);
		return (int) $total;
	}

	/**
	 * Check if table exists
	 *
	 * @param   string  $table  The table name
	 *
	 * @return string
	 */
	function tableExists($table)
	{
		$tables = array();
		$tables = $this->_db_old->getTableList();

		$table = $this->_db_old->getPrefix() . $table;

		return (in_array($table, $tables)) ? 'YES' : 'NO';
	}

	/**
	 * Get source table
	 *
	 * @return  string	The table name
	 */
	public function getSourceTable()
	{
		return '#__' . $this->_step->source;
	}

	/**
	 * Get destination table
	 *
	 * @return  string	The table name
	 */
	public function getDestinationTable()
	{
		return '#__' . $this->_step->destination;
	}

	/**
	 * Get key name
	 *
	 * @return  string	The table key name
	 */
	public function getKeyName()
	{
		if (empty($this->_tbl_key))
		{
			$table = $this->getSourceTable();

			$query = "SHOW KEYS FROM {$table} WHERE Key_name = 'PRIMARY'";
			$this->_db_old->setQuery($query);
			$keys = $this->_db_old->loadObjectList();

			return !empty($keys) ? $keys[0]->Column_name : '';
		}
		else
		{
			return $this->_tbl_key;
		}
	}

	/**
	 * Cleanup the data in the destination database.
	 *
	 * @return	void
	 *
	 * @since	0.5.1
	 * @throws	Exception
	 */
	protected function cleanDestinationData($table = false)
	{
		// Get the table
		if ($table == false)
		{
			$table = $this->getDestinationTable();
		}

		if ($this->canDrop)
		{
			$query = "TRUNCATE TABLE {$table}";
			$this->_db->setQuery($query);
			$this->_db->query();
		}
		else
		{
			$query = "DELETE FROM {$table}";
			$this->_db->setQuery($query);
			$this->_db->query();
		}

		// Check for query error.
		$error = $this->_db->getErrorMsg();

		if ($error)
		{
			throw new Exception($error);
		}
	}
}
