<?php
/**
 * JTransport
 *
 * @author vdkhai
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * JTransport utility class for transportation
 *
 */
class JTransport
{
	/**
	 * @var      
	 * @since  3.0
	 */
	public $params = null;

	/**
	 * @var      
	 * @since  3.0
	 */
	public $ready = true;

	/**
	 * @var      
	 * @since  3.0
	 */
	public $_db = null;

	/**
	 * @var
	 * @since  3.0
	 */
	public $_driver = null;

	/**
	 * @var      
	 * @since  3.0
	 */
	public $_version = null;

	/**
	 * @var      
	 * @since  3.0
	 */
	public $_total = null;

	/**
	 * @var	array
	 * @since  3.0
	 */
	protected $_step = null;

	/**
	 * @var bool Can drop
	 * @since	0.4.
	 */
	public $canDrop = false;

	/**
	 * Constructor
	 *
	 * @param   JTransportStep  $step  Step
	 */
	function __construct(JTransportStep $step = null)
	{
		// Set the current step
		$this->_step = $step;

		// JLoader::import('legacy.component.helper');
		JLoader::import('cms.version.version');

		// Getting the params and Joomla version web and cli
		$params = JComponentHelper::getParams('com_jtransport');
		$this->params = $params->toObject();

		// Getting the J! version
		$version = new JVersion;
		$this->_version = $version->RELEASE;

		// Creating dabatase instance for this installation
		$this->_db = JFactory::getDBO();

		// Getting the driver
		JLoader::register('JTransportDriver', JPATH_COMPONENT_ADMINISTRATOR . '/includes/jtransport.driver.class.php');

		if ($this->_step instanceof JTransportStep)
		{
			$this->_step->table = $this->getSourceTable();

			// Initialize the driver
			$this->_driver = JTransportDriver::getInstance($step);
		}

		// Getting the total
		if (!empty($step->source))
		{
			$this->_total = JTransportHelper::getTotal($step);
		}

		// Set time limit to 0
		/*if (!@ini_get('safe_mode'))
		{
			if (!empty($this->params->timelimit))
			{
				set_time_limit(0);
			}
		}*/

		// Make sure we can see all errors.
		if (!empty($this->params->php_error_report))
		{
			error_reporting(E_ALL);
			@ini_set('display_errors', 1);
		}

		// MySQL grants check
		$query = "SHOW GRANTS FOR CURRENT_USER";
		$this->_db->setQuery($query);
		$list = $this->_db->loadRowList();
		$grant = isset($list[1][0]) ? $list[1][0] : $list[0][0];
		$grant = empty($list[1][0]) ? $list[0][0] : $list[1][0];

		if (strpos($grant, 'DROP') == true || strpos($grant, 'ALL') == true)
		{
			$this->canDrop = true;
		}
	}

	/**
	 * Get instance of JTransport
	 *
	 * @param   JTransportStep  $step  Step
	 *
	 * @return bool
	 *
	 * @throws RuntimeException
	 */
	static function getInstance(JTransportStep $step = null)
	{
		$class = '';

		if ($step == null)
		{
			return false;
		}

		// Getting the class name
		if (isset($step->class))
		{
			$class = $step->class;
		}

		// Require the correct file
		JTransportHelper::requireClass($step->name, $step->type, $step->class);

		// If the class still doesn't exist we have nothing left to do but throw an exception.  We did our best.
		if (!class_exists($class))
		{
			$class = 'JTransport';
		}

		// Create our new JTransport connector based on the options given.
		try
		{
			$instance = new $class($step);
		}
		catch (RuntimeException $e)
		{
			throw new RuntimeException(sprintf('Unable to load JTransport object: %s', $e->getMessage()));
		}

		return $instance;
	}

	/**
	 * The public entry point for the class.
	 *
	 * @return	boolean
	 */
	public function transport()
	{
		try
		{
			// Get data of the table from source db and save to destination db
			$this->setDestinationData();
		}
		catch (Exception $e)
		{
			throw new Exception($e->getMessage());
		}

		return true;
	}

	/**
	 * Sets the data in the destination database.
	 *
	 * @param   bool  $rows  Rows
	 *
	 * @return bool|void
	 *
	 * @throws Exception
	 */
	protected function setDestinationData($rows = false)
	{
		$name = $this->_step->_getStepName();

		// Get the source data.
		if ($rows === false)
		{
			$rows = $this->_driver->getSourceData();
		}
		else
		{
			return false;
		}

		$method = $this->params->transport_method;

		if ($method == 'database' || $this->_step->type != 'core')
		{
			if (method_exists($this, 'databaseHook'))
			{
				$rows = $this->databaseHook($rows);
			}
		}

		if ($this->_step->first == true && $this->_step->cid == 0)
		{
			// Calling the structure modificator hook
			$structureHook = 'structureHook_' . $name;

			if (method_exists($this, $structureHook))
			{
				try
				{
					$this->$structureHook();
				}
				catch (Exception $e)
				{
					throw new Exception($e->getMessage());
				}
			}
		}

		// Calling the data modificator hook
		$dataHookFunc = 'dataHook_' . $name;

		// If method exists call the custom dataHook
		if (method_exists($this, $dataHookFunc))
		{
			try
			{
				$rows = $this->$dataHookFunc($rows);
			}
			catch (Exception $e)
			{
				throw new Exception($e->getMessage());
			}
		}
		else // If method not exists call the default dataHook
		{
			try
			{
				$rows = $this->dataHook($rows);
			}
			catch (Exception $e)
			{
				throw new Exception($e->getMessage());
			}
		}

		if ($rows !== false)
		{
			try
			{
				$this->ready = $this->insertData($rows);
			}
			catch (Exception $e)
			{
				throw new Exception($e->getMessage());
			}
		}

		// Load the step object
		$this->_step->_load();

		if ($this->getTotal() == $this->_step->cid)
		{
			$this->ready = $this->afterHook();
		}

		if ($this->_step->name == $this->_step->laststep
			&& $this->_step->cache == 0
			&& $this->getTotal() == $this->_step->cid)
		{
			$this->ready = $this->afterAllStepsHook();
		}

		return $this->ready;
	}

	/**
	 * Insert data
	 *
	 * @param   array|object  $rows  Rows
	 *
	 * @return bool
	 *
	 * @throws Exception
	 */
	protected function insertData($rows)
	{
		$table = $this->getDestinationTable();

		if (is_array($rows))
		{
			foreach ($rows as $row)
			{
				if ($row != false)
				{
					// Convert the array into an object.
					$row = (object) $row;

					try
					{
						$this->_db->insertObject($table, $row);
					}
					catch (Exception $e)
					{
						throw new Exception($e->getMessage());
					}
				}

				$this->_step->_nextCID();
			}
		}
		elseif (is_object($rows))
		{
			if ($rows != false)
			{
				try
				{
					$this->_db->insertObject($table, $rows);
				}
				catch (Exception $e)
				{
					throw new Exception($e->getMessage());
				}
			}
		}

		return !empty($this->_step->error) ? false : true;
	}

	/**
	 * @return array
	 */
	public static function getConditionsHook()
	{
		$conditions = array();
		$conditions['where'] = array();

		// Do customisation of the params field here for specific data.
		return $conditions;
	}

	/**
	 * Fake method of dataHook if it not exists
	 *
	 * @param   array  $rows  Rows
	 *
	 * @return mixed
	 */
	public function dataHook($rows)
	{
		// Do customisation of the params field here for specific data.
		return $rows;
	}

	/**
	 * Fake method after hooks
	 *
	 * @return bool
	 */
	public function afterHook()
	{
		return true;
	}

	/**
	 * Hook to do custom migration after all steps
	 *
	 * @return	boolean Ready
	 *
	 * @since	1.1.0
	 */
	protected function afterAllStepsHook()
	{
		return true;
	}

	/**
	 * Get the table structure
	 *
	 * @return bool
	 */
	public function getTableStructure()
	{
		// Getting the source table
		$table = $this->getSourceTable();

		// Getting the structure
		if ($this->params->transport_method == 'database')
		{
			$result = $this->_driver->_db_old->getTableCreate($table);
			$structure = str_replace($this->_driver->_db_old->getPrefix(), "#__", "{$result[$table]} ;\n\n");
		}
		elseif ($this->params->method == 'rest')
		{
			$structure = $this->_driver->requestRest("tablestructure", str_replace('#__', '', $table));
		}

		// Create only if not exists
		$structure = str_replace('CREATE TABLE', 'CREATE TABLE IF NOT EXISTS', $structure);

		// Replacing the table name from xml
		$replaced_table = $this->replaceTable($table);

		if ($replaced_table != $table)
		{
			$structure = str_replace($table, $replaced_table, $structure);
		}

		// Inserting the structure to new site
		$this->_db->setQuery($structure);
		$this->_db->query();

		return true;
	}

	/**
	 * Replace table name
	 *
	 * @param         $table      Table
	 * @param   null  $structure  Structure
	 *
	 * @return mixed
	 */
	protected function replaceTable($table, $structure = null)
	{
		$replaced_table = $table;

		// Replace table name from xml
		$replace = explode("|", $this->_step->replace);

		if (count($replace) > 1)
		{
			$replaced_table = str_replace($replace[0], $replace[1], $table);
		}

		return $replaced_table;
	}

	/**
	 * @return  string	The destination table key name
	 *
	 * @since   3.0
	 */
	public function getDestKeyName()
	{
		$table = $this->getDestinationTable();

		$query = "SHOW KEYS FROM {$table} WHERE Key_name = 'PRIMARY'";
		$this->_db->setQuery($query);
		$keys = $this->_db->loadObjectList();

		return !empty($keys) ? $keys[0]->Column_name : '';
	}

	/**
	 * @return  bool	Check if the value exists in the table
	 *
	 * @since   3.0
	 */
	public function valueExists($row, $fields)
	{
		$table = $this->getSourceTable();
		$key = $this->getDestKeyName();
		$value = $row->$key;

		$conditions = array();

		foreach ($fields as $field)
		{
			$conditions[] = "{$field} = {$row->$field}";
		}

		$where = count($conditions) ? 'WHERE ' . implode(' AND ', $conditions) : '';

		$query = "SELECT `{$key}` FROM {$table} {$where} LIMIT 1";
		$this->_db->setQuery($query);
		$exists = $this->_db->loadResult();

		return empty($exists) ? false : true;
	}

	/**
	 * TODO: Replace this function: get the new id directly
	 * Internal function to get original database prefix
	 *
	 * @return	an original database prefix
	 *
	 * @since	0.5.3
	 * @throws	Exception
	 */
	public function getMapList($table = 'categories', $section = false, $custom = false)
	{
		// Getting the categories id's
		$query = "SELECT *"
					. " FROM #__redmigrator_{$table}";

		if ($section !== false)
		{
			$query .= " WHERE section = '{$section}'";
		}

		if ($custom !== false)
		{
			$query .= " WHERE {$custom}";
		}

		$this->_db->setQuery($query);
		$data = $this->_db->loadObjectList('old');

		// Check for query error.
		$error = $this->_db->getErrorMsg();

		if ($error)
		{
			throw new Exception($error);

			return false;
		}

		return $data;
	}

	/**
	 * Internal function to get original database prefix
	 *
	 * @return	an original database prefix
	 *
	 * @since	0.5.3
	 * @throws	Exception
	 */
	public function getMapListValue($table = 'categories', $section = false, $custom = false)
	{
		// Getting the categories id's
		$query = "SELECT new"
					. " FROM #__jtransport_{$table}";

		if ($section !== false)
		{
			if ($section == 'categories')
			{
				$query .= " WHERE (section REGEXP '^[\-\+]?[[:digit:]]*\.?[[:digit:]]*$' OR section = 'com_section')";
			}
			else
			{
				$query .= " WHERE section = '{$section}'";
			}
		}

		if ($custom !== false)
		{
			if ($section !== false)
			{
				$query .= " AND {$custom}";
			}
			else
			{
				$query .= " WHERE {$custom}";
			}
		}

		$this->_db->setQuery($query);
		$data = $this->_db->loadResult();

		// Check for query error.
		$error = $this->_db->getErrorMsg();

		if ($error)
		{
			throw new Exception($error);

			return false;
		}

		return $data;
	}

	/**
	 * Converts the params fields into a JSON string.
	 *
	 * @param	string	$params	The source text definition for the parameter field.
	 *
	 * @return	string	A JSON encoded string representation of the parameters.
	 *
	 * @since	0.4.
	 * @throws	Exception from the convertParamsHook.
	 */
	protected function convertParams($params, $hook = true)
	{
		$temp	= new JRegistry($params);
		$object	= $temp->toObject();

		// Fire the hook in case this parameter field needs modification.
		if ($hook === true)
		{
			$this->convertParamsHook($object);
		}

		return json_encode($object);
	}

	/**
	 * A hook to be able to modify params prior as they are converted to JSON.
	 *
	 * @param	object	$object	A reference to the parameters as an object.
	 *
	 * @return	void
	 *
	 * @since	0.4.
	 * @throws	Exception
	 */
	protected function convertParamsHook($object)
	{
		// Do customisation of the params field here for specific data.
	}

	/**
	 * Internal function to get the component settings
	 *
	 * @return	an object with global settings
	 *
	 * @since	0.5.7
	 */
	public function getParams()
	{
		return $this->params;
	}

	/**
	 * Get total of the rows of the table
	 *
	 * @access	public
	 * @return	int	The total of rows
	 */
	public function getTotal()
	{
		return $this->_total;
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
     * Get field list of destination table
     *
     * @return mixed
     */
    protected function _getFieldList()
    {
        $this->_db->getTableColumns($this->getDestinationTable());

        $columns = $this->_db->loadColumn();

        return $columns;
    }

    /**
     * Remove source table's fields not in destination table
     *
     * @param   array  $row  Source row
     */
    protected function _removeUnusedFields(&$row)
    {
        // Destination table's fields
        $arrFieldList = $this->_getFieldList();

        // Remove fields not exist in destination table
        foreach ($row as $key => $value)
        {
            if (!in_array($key, $arrFieldList))
            {
                unset($row[$key]);
            }
        }
    }
} // End class
