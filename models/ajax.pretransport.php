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
 */
class JTransportModelAjaxPreTransport extends JModelLegacy
{
	/**
	 * @throws RuntimeException
	 */
	public function preTransport()
	{
		// Getting the component parameter with global settings
		$params = JComponentHelper::getParams('com_jtransport');
		$params = $params->toObject();

		// Checking tables
		$tables = $this->_db->getTableList();

		// Check if the tables exists if not populate install.sql
		$tablesComp = array();
		$tablesComp[] = 'core_acl_aro';
		$tablesComp[] = 'extensions';
		$tablesComp[] = 'steps';

		foreach ($tablesComp as $table)
		{
			if (!in_array($this->_db->getPrefix() . 'jtransport_' . $table, $tables))
			{
				JTransportHelper::populateDatabase($this->_db, JPATH_COMPONENT_ADMINISTRATOR . '/sql/install.sql');
				break;
			}
		}

		// Check safe_mode_gid
		if (@ini_get('safe_mode_gid') && @ini_get('safe_mode'))
		{
			throw new Exception('COM_JTRANSPORT_ERROR_DISABLE_SAFE_GID');
		}

		// Check for bad configurations
		if ($params->transport_method == "webservice")
		{
			if (!isset($params->webservice_hostname) || !isset($params->webservice_username)
				|| !isset($params->webservice_password) || !isset($params->webservice_security_key) )
			{
				throw new Exception('COM_JTRANSPORT_ERROR_REST_CONFIG');
			}

			if ($params->webservice_hostname == 'http://www.example.org/' || $params->webservice_hostname == ''
				|| $params->webservice_username == '' || $params->webservice_password == '' || $params->webservice_security_key == '')
			{
				throw new Exception('COM_JTRANSPORT_ERROR_REST_CONFIG');
			}
		}

		// Check for bad configurations
		if ($params->transport_method == "database")
		{
			if ($params->database_hostname == '' || $params->database_username == ''
				|| $params->database_db == '' || $params->database_dbprefix == '')
			{
				throw new Exception('COM_JTRANSPORT_ERROR_DATABASE_CONFIG');
			}
		}

		// Importing helper tags
		JLoader::import('cms.helper.tags');

		// Convert the params to array
		$core_transport = (array) $params;

		// Version of source joomla (J15 or J25)
		$core_version = $core_transport['core_version'];

		// Clean #__jtransport_steps table
		$query = "Truncate table #__jtransport_steps";
		$this->_db->setQuery($query)->execute();

		// Clean #__jtransport_core_acl_aro
		$query = "Truncate table #__jtransport_core_acl_aro";
		$this->_db->setQuery($query)->execute();

		// Xml file includes core steps
		$schemasPath = JPATH_COMPONENT_ADMINISTRATOR . "/includes/schemas";

		// Init session values
		$session = JFactory::getSession();

		$session->set('stepTotal', 0, 'jtransport');
		$session->set('laststep', '', 'jtransport');

		if ($core_version == 'J15') // J15 core
		{
			$xml_file = $schemasPath . "/joomla15/steps.xml";
		}
		else // J25 core
		{
			$xml_file = $schemasPath . "/joomla25/steps.xml";
		}

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

		// Save the steps in xml file into db
		JTransportHelper::populateSteps($xml_file);

		$query = $this->_db->getQuery(true);

		// Skipping the steps setted by user
		foreach ($core_transport as $k => $v)
		{
			$transport = substr($k, 0, 9);
			$name = substr($k, 10, 18);

			if ($transport == "transport")
			{
				if ($v == 1)
				{
					$query->clear();

					// Set all status to 2 and clear state
					$query->update('#__jtransport_steps')
						->set('status = 2')
						->where("name = '{$name}'");

					try
					{
						$this->_db->setQuery($query)->execute();
					}
					catch (RuntimeException $e)
					{
						throw new RuntimeException($e->getMessage());
					}

					$query->clear();

					if ($name == 'users')
					{
						$query->update('#__jtransport_steps')
							->set('status = 2');

						if ($core_version == 0)
						{
							$query->where('name = "arogroup" OR name = "usergroupmap" OR name = "aclaro"');
						}
						else
						{
							$query->where('name = "usergroups" OR name = "usergroupmap" OR name = "usernotes" OR name = "userprofiles"');
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

					if ($name == 'categories')
					{
						if ($core_version == 0)
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

			if ($k == 'skip_extensions')
			{
				if ($v == 1)
				{
					$query->clear();
					$query->update('#__jtransport_steps')
						->set('status = 2')
						->where('name = "extensions"');

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
} // End class

