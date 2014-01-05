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

/**
 * Upgrade class for 3rd party extensions
 *
 * This class search for extensions to be migrated
 *
 * @since  0.4.5
 */
class JTransportCheckExtensions extends JTransportExtensions
{
	/**
	 * count adapters
	 * @var int
	 * @since 1.1.0
	 */
	public $count = 0;

	protected $extensions = array();

	/**
	 * Upgrade
	 *
	 * @return bool
	 */
	public function upgrade()
	{
		if (!$this->_processExtensions())
		{
			return false;
		}

		return true;
	}

	/**
	 * Get the raw data for this part of the upgrade.
	 *
	 * @return	array	Returns a reference to the source data array.
	 *
	 * @since 1.1.0
	 * @throws	Exception
	 */
	protected function _processExtensions()
	{
		JLoader::import('joomla.filesystem.folder');

		$types = array(
			'/^com_(.+)$/e',		/*Com_componentname*/
			'/^mod_(.+)$/e',		/*Mod_modulename*/
			'/^plg_(.+)_(.+)$/e',	/*Plg_folder_pluginname*/
			'/^tpl_(.+)$/e');		/*Tpl_templatename*/

		$classes = array(
			"'JTransportComponent'.ucfirst('\\1')",				/*JTransportComponentComponentname*/
			"'JTransportModule'.ucfirst('\\1')",					/*JTransportModuleModulename*/
			"'JTransportPlugin'.ucfirst('\\1').ucfirst('\\2')",	/*JTransportPluginPluginname*/
			"'JTransportTemplate'.ucfirst('\\1')");				/*JTransportTemplateTemplatename*/

		// Getting the plugins list
		$query = $this->_db->getQuery(true);
		$query->select('*');
		$query->from('#__extensions');
		$query->where("type = 'plugin'");
		$query->where("folder = 'redmigrator'");
		$query->where("enabled = 1");

		// Setting the query and getting the result
		$this->_db->setQuery($query);
		$plugins = $this->_db->loadObjectList();

		// Do some custom post processing on the list.
		foreach ($plugins as $plugin)
		{
			// Remove database or 3rd extensions if exists
			$uninstall_script = JPATH_PLUGINS . "/redmigrator/{$plugin->element}/sql/uninstall.utf8.sql";
			JTransportHelper::populateDatabase($this->_db, $uninstall_script);

			// Install blank database of new 3rd extensions
			$install_script = JPATH_PLUGINS . "/redmigrator/{$plugin->element}/sql/install.utf8.sql";
			JTransportHelper::populateDatabase($this->_db, $install_script);

			// Looking for xml files
			$files = (array) JFolder::files(JPATH_PLUGINS . "/redmigrator/{$plugin->element}/extensions", '\.xml$', true, true);

			foreach ($files as $xmlfile)
			{
				if (!empty($xmlfile))
				{
					$element = JFile::stripExt(basename($xmlfile));

					if (array_key_exists($element, $this->extensions))
					{
						// Read xml definition file
						$xml = simplexml_load_file($xmlfile);

						// Getting the php file
						if (!empty($xml->installer->file[0]))
						{
							$phpfile = JPATH_ROOT . '/' . trim($xml->installer->file[0]);
						}

						if (empty($phpfile))
						{
							$default_phpfile = JPATH_PLUGINS . "/redmigrator/{$plugin->element}/extensions/{$element}.php";
							$phpfile = file_exists($default_phpfile) ? $default_phpfile : null;
						}

						// Getting the class
						if (!empty($xml->installer->class[0]))
						{
							$class = trim($xml->installer->class[0]);
						}

						if (empty($class))
						{
							$class = preg_replace($types, $classes, $element);
						}

						// Saving the extensions and migrating the tables
						if (!empty($phpfile) || !empty($xmlfile))
						{
							// Adding tables to migrate
							if (!empty($xml->tables[0]))
							{
								$count = count($xml->tables[0]->table);

								for ($i = 0; $i < $count; $i++)
								{
									$table = new StdClass;
									$attributes = $xml->tables->table[$i]->attributes();
									$table->name = (string) $xml->tables->table[$i];
									$table->title = (string) $attributes->title;
									$table->tbl_key = (string) $attributes->tbl_key;
									$table->source = (string) $xml->tables->table[$i];
									$table->destination = (string) $attributes->destination;
									$table->type = (string) $attributes->type;
									$table->class = (string) $attributes->class;

									if (!$this->_db->insertObject('#__redmigrator_steps', $table))
									{
										throw new Exception($this->_db->getErrorMsg());
									}
								}
							}
						} /*End if*/
					} /*End if*/
				} /*End if*/

				unset($class);
				unset($phpfile);
				unset($xmlfile);
			} /*End foreach*/
		} /*End foreach*/

		return true;
	}
} /*End class*/
