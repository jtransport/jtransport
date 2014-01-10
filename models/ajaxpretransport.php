<?php
/**
 * JTransport
 *
 * @author vdkhai
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

JLoader::register('JTransport', JPATH_COMPONENT_ADMINISTRATOR . '/includes/jtransport.class.php');
JLoader::register('JTransportDriver', JPATH_COMPONENT_ADMINISTRATOR . '/includes/jtransport.driver.class.php');
JLoader::register('JTransportStep', JPATH_COMPONENT_ADMINISTRATOR . '/includes/jtransport.step.class.php');

/**
 * JTransport Model
 *
 * @since  1.0.1
 */
class JTransportModelAjaxPreTransport extends JModelLegacy
{
	/**
	 * Use's config params
	 *
	 * @var null
	 */
	private $params = null;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		// Getting the user's config params
		$params = JComponentHelper::getParams('com_jtransport');
		$this->params = $params->toObject();

		parent::__construct();
	}

	/**
	 * Pre Transport
	 *
	 * @return none
	 */
	public function preTransport()
	{
		// User's config params
		$params = $this->params;

		// Check safe_mode_gid
		if (@ini_get('safe_mode_gid') && @ini_get('safe_mode'))
		{
			throw new Exception('COM_JTRANSPORT_ERROR_DISABLE_SAFE_GID');
		}

		// Check for bad configurations
		if ($params->transport_method == "webservice")
		{
			if ($params->webservice_hostname == 'http://www.example.org/' || $params->webservice_hostname == ''
				|| $params->webservice_username == '' || $params->webservice_password == ''
				|| $params->webservice_security_key == '')
			{
				throw new Exception('COM_JTRANSPORT_ERROR_WEBSERVICE_CONFIG');
			}
		}

		// Check for bad configurations
		if ($params->transport_method == "database")
		{
			if ($params->database_hostname == '' || $params->database_username == ''
				|| $params->database_name == '' || $params->table_prefix == ''
				|| $params->chunk_limit == '')
			{
				throw new Exception('COM_JTRANSPORT_ERROR_DATABASE_CONFIG');
			}
		}

		// Clean tables
		$this->cleanTables();

		// Don't transport joomla core
		if ($params->core_version != "nocore")
		{
			// Set default for sessions
			$this->initSessions();

			// Init Joomla Core
			$this->initJoomlaCore();
		}

		// Init 3rd extensions
		$this->init3rdExtensions();
	}

	/**
	 * Init sessions
	 *
	 * @return none
	 */
	public function initSessions()
	{
		// Init session values
		$session = JFactory::getSession();

		$session->set('stepTotal', 0, 'jtransport');
		$session->set('laststep', '', 'jtransport');

		// Map section old id to new id
		$session->set('arrSections', array(), 'jtransport');

		// Map category old id to new id
		$session->set('arrCategories', array(), 'jtransport');

		// Category items have parent item after itself in db
		$session->set('arrCategoriesSwapped', array(), 'jtransport');

		// Map content old id to new id
		$session->set('arrContent', array(), 'jtransport');

		// Map user old id to new id
		$session->set('arrUsers', array(), 'jtransport');

		// Map usergroup old id to new id
		$session->set('arrUsergroups', array(), 'jtransport');

		// Usergroup items have parent item after itself in db
		$session->set('arrUsergroupsSwapped', array(), 'jtransport');

		// Map menu old id to new id
		$session->set('arrMenu', array(), 'jtransport');

		// Menu items have parent item after itself in db
		$session->set('arrMenuSwapped', array(), 'jtransport');

		// Map module old id to new id
		$session->set('arrModules', array(), 'jtransport');

		// Map banner old id to new id
		$session->set('arrBanners', array(), 'jtransport');
	}

	/**
	 * Clean tables
	 *
	 * @return none
	 */
	public function cleanTables()
	{
		// Clean #__jtransport_steps table
		$query = "Truncate table #__jtransport_steps";
		$this->_db->setQuery($query)->execute();

		// Clean #__jtransport_core_acl_aro
		$query = "Truncate table #__jtransport_core_acl_aro";
		$this->_db->setQuery($query)->execute();
	}

	/**
	 * Init Joomla Core
	 *
	 * @return none
	 */
	public function initJoomlaCore()
	{
		// User's config param
		$params = $this->params;

		// Convert the params to array
		$core_transport = (array) $params;

		// Version of source joomla (J15 or J25)
		$core_version = $core_transport['core_version'];

		// Xml file includes core steps
		$schemasPath = JPATH_COMPONENT_ADMINISTRATOR . "/includes/schemas";

		if ($core_version == "j15") // J15 core
		{
			$xml_file = $schemasPath . "/joomla15/steps.xml";
		}
		else // $core_version == "j25"
		{
			$xml_file = $schemasPath . "/joomla25/steps.xml";
		}

		// Save the steps in xml file into db
		JTransportHelper::populateSteps($xml_file);

		$query = $this->_db->getQuery(true);

		// Set steps status inputted by user
		foreach ($core_transport as $k => $v)
		{
			$transport = substr($k, 0, 9);
			$name = substr($k, 10, 18);

			if ($transport == "transport")
			{
				if ($v == 0)
				{
					// Clear previous query
					$query->clear();

					// Set all status to 2 and clear state
					$query->update("#__jtransport_steps")
							->set("status = 2")
							->where("name = '" . $name . "'");

					try
					{
						$this->_db->setQuery($query)->execute();
					}
					catch (RuntimeException $e)
					{
						throw new RuntimeException($e->getMessage());
					}

					// Clear previous query
					$query->clear();

					// Set state if transport users
					if ($name == 'users')
					{
						$query->update('#__jtransport_steps')
								->set('status = 2');

						if ($core_version == "j15")
						{
							$query->where('name = "arogroup"', 'OR')
									->where('name = "usergroupmap"', 'OR')
									->where('name = "aclaro"');
						}
						else // $core_version == "j25"
						{
							$query->where('name = "usergroups"', 'OR')
									->where('name = "usergroupmap"', 'OR')
									->where('name = "usernotes"', 'OR')
									->where('name = "userprofiles"');
						}

						try
						{
							$this->_db->setQuery($query)->execute();
						}
						catch (RuntimeException $e)
						{
							throw new RuntimeException($e->getMessage());
						}
					}

					// Set state if transport categories
					if ($name == 'categories')
					{
						if ($core_version == "j15")
						{
							$query->update('#__jtransport_steps')
									->set('status = 2')
									->where('name = "sections"');

							try
							{
								$this->_db->setQuery($query)->execute();
							}
							catch (RuntimeException $e)
							{
								throw new RuntimeException($e->getMessage());
							}
						}
					}
				}
			}
		}
	}

	/**
	 * Init 3rd Extension
	 *
	 * @return none
	 */
	public function init3rdExtensions()
	{
		// Getting the plugins list
		$query = $this->_db->getQuery(true);
		$query->select("*");
		$query->from("#__extensions");
		$query->where("type = 'plugin'");
		$query->where("folder = 'jtransport'");
		$query->where("enabled = 1");

		// Setting the query and getting the result
		$this->_db->setQuery($query);
		$plugins = $this->_db->loadObjectList();

		// Do some custom post processing on the list.
		foreach ($plugins as $plugin)
		{
			// Remove database or 3rd extensions if exists
			$uninstall_script = JPATH_PLUGINS . "/jtransport/{$plugin->element}/sql/uninstall.utf8.sql";
			JTransportHelper::populateDatabase($this->_db, $uninstall_script);

			// Install blank database of new 3rd extensions
			$install_script = JPATH_PLUGINS . "/jtransport/{$plugin->element}/sql/install.utf8.sql";
			JTransportHelper::populateDatabase($this->_db, $install_script);

			// Looking for xml files
			$files = (array) JFolder::files(JPATH_PLUGINS . "/jtransport/{$plugin->element}/extensions", '\.xml$', true, true);

			// Populate xml to db
			foreach ($files as $xmlfile)
			{
				if (!empty($xmlfile))
				{
					JTransportHelper::populateSteps($xmlfile);
				}
			}
		}
	}
}

