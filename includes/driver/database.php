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
	 * @var null
	 */
	public $_conditions = null;

	/**
	 * Constructor
	 *
	 * @param JTransportStep $step
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

		$type = 'core';

		if (!empty($step->type))
		{
			$type = $step->type;
		}

		JTransportHelper::requireClass($name, $type, $class);

		// @@ Fix bug using PHP < 5.2.3 version
		$this->_conditions = call_user_func($class . '::getConditionsHook');

		$db_config = array();
		$db_config['driver'] = $this->params->old_dbtype;
		$db_config['host'] = $this->params->old_hostname;
		$db_config['user'] = $this->params->old_username;
		$db_config['password'] = $this->params->old_password;
		$db_config['database'] = $this->params->old_db;
		$db_config['prefix'] = $this->params->old_dbprefix;

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
		// Get the conditions
		$conditions = $this->getConditionsHook();

		// Process the conditions
		$query = $this->_processQuery($conditions, true);

		// Setting the query
		$this->_db_old->setQuery($query);

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
		// Get the conditions
		$conditions = $this->getConditionsHook();

		// Process the conditions
		$query = $this->_processQuery($conditions);

		// Get Total
		$this->_db_old->setQuery($query);

		try
		{
			$total = $this->_db_old->loadAssocList();
		}
		catch (Exception $e)
		{
			throw new Exception($e->getMessage());
		}

		return (int) count($total);
	}

	/**
	 * Process the conditions
	 *
	 * @access	public
	 * @return	array	The conditions ready to be added to query
	 *
	 * @since  3.1.0
	 */
	public function _processQuery( $conditions, $pagination = false )
	{
		// Create a new query object.
		$query = $this->_db->getQuery(true);

		// Getting the SELECT clause
		$select = isset($conditions['select']) ? $conditions['select'] : '*';
		$select = trim(preg_replace('/\s+/', ' ', $select));

		// Getting the TABLE and AS clause
		$table = isset($conditions['as']) ? "{$this->getSourceTable()} AS {$conditions['as']}" : $this->getSourceTable();

		// Building the query
		$query->select($select);
		$query->from(trim($table));

		// Setting the join[s] into the query
		if (isset($conditions['join']))
		{
			$count = count($conditions['join']);

			for ($i = 0; $i < $count; $i++)
			{
				$query->join('LEFT', $conditions['join'][$i]);
			}
		}

		// Setting the where[s] into the query
		if (isset($conditions['where']))
		{
			$count = count($conditions['where']);

			for ($i = 0; $i < $count; $i++)
			{
				$query->where(trim($conditions['where'][$i]));
			}
		}

		// Setting the where[s] into the query
		if (isset($conditions['where_or']))
		{
			$count = count($conditions['where_or']);

			for ($i = 0; $i < $count; $i++)
			{
				$query->where(trim($conditions['where_or'][$i]), 'OR');
			}
		}

		// Setting the GROUP BY into the query
		if (isset($conditions['group_by']))
		{
			$query->group(trim($conditions['group_by']));
		}

		// Process the ORDER clause
		$key = $this->getKeyName();

		if (!empty($key))
		{
			$order = isset($conditions['order']) ? $conditions['order'] : "{$key} ASC";
			$query->order($order);
		}

		// Pagination
		if ($pagination === true)
		{
			$chunk_limit = (int) $this->params->chunk_limit;
			$oid = (int) $this->_getStepID();

			$query->setLimit($chunk_limit, $oid);
		}

		return $query;
	}

	/*
	 *
	 * @return	void
	 * @since	3.0.0
	 * @throws	Exception
	 */
	public function getConditionsHook()
	{
		return $this->_conditions;
	}

	/**
 	* 
	* @param string $table The table name
	*/
	function tableExists($table)
	{
		$tables = array();
		$tables = $this->_db_old->getTableList();

		$table = $this->_db_old->getPrefix() . $table;

		return (in_array($table, $tables)) ? 'YES' : 'NO';
	}

	/**
	 * @return  string	The table name
	 *
	 * @since   3.0
	 */
	public function getSourceTable()
	{
		return '#__' . $this->_step->source;
	}

	/**
	 * @return  string	The table name
	 *
	 * @since   3.0
	 */
	public function getDestinationTable()
	{
		return '#__' . $this->_step->destination;
	}

	/**
	 * @return  string	The table key name
	 *
	 * @since   3.0
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
