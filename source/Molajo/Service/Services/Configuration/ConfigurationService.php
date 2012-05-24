<?php
/**
 * @package   Molajo
 * @copyright 2012 Amy Stephen. All rights reserved.
 * @license   GNU General Public License Version 2, or later http://www.gnu.org/licenses/gpl.html
 */
namespace Molajo\Service\Services\Configuration;

use Molajo\Application;
use Molajo\Service\Services;

defined('MOLAJO') or die;

/**
 * Configuration
 *
 * @package     Molajo
 * @subpackage  Service
 * @since       1.0
 */
Class ConfigurationService
{
	/**
	 * Static instance
	 *
	 * @var    object
	 * @since  1.0
	 */
	protected static $instance;

	/**
	 * getInstance
	 *
	 * @static
	 * @return bool|object
	 * @since  1.0
	 */
	public static function getInstance($configuration_file = null)
	{
		if (empty(self::$instance)) {
			self::$instance = new ConfigurationService($configuration_file);
		}

		return self::$instance;
	}

	/**
	 * Retrieve Site and Application data, set constants and paths
	 *
	 * @return  object
	 * @since   1.0
	 */
	public function __construct($configuration_file = null)
	{
		/** Retrieve Site Data */
		$this->getSite($configuration_file);

		/** Retrieve Application Data */
		$this->getApplication();

		/** Defines, etc., with site paths */
		$this->setSitePaths();

		/** return */
		return $this;
	}

	/**
	 * Retrieve site configuration object from ini file
	 *
	 * @param string $configuration_file optional
	 *
	 * @return  object
	 * @throws  \Exception
	 * @since   1.0
	 */
	public function getSite($configuration_file = null)
	{
		/** File Configuration */
		if ($configuration_file === null) {
			$configuration_file = SITE_FOLDER_PATH . '/configuration.php';
		}
		$configuration_class = 'SiteConfiguration';

		if (file_exists($configuration_file)) {
			require_once $configuration_file;

		} else {
			throw new \Exception('Fatal error - Site Configuration File does not exist', 100);
		}

		if (class_exists($configuration_class)) {
			$siteData = new $configuration_class();

		} else {
			throw new \Exception('Fatal error - Configuration Class does not exist', 100);
		}

		foreach ($siteData as $key => $value) {
			Services::Registry()->set('Configuration', $key, $value);
		}

		/** Retrieve Sites Data from DB */
		$m = Application::Controller()->connect('Sites', 'Table');
		$m->model->set('id', (int)SITE_ID);

		$items = $m->getData('load');
		if ($items === false) {
			throw new \RuntimeException ('Site getSite() query problem');
		}

		Services::Registry()->set('Configuration', 'site_id', (int)$items['id']);
		Services::Registry()->set('Configuration', 'site_catalog_type_id', (int)$items['catalog_type_id']);
		Services::Registry()->set('Configuration', 'site_name', $items['name']);
		Services::Registry()->set('Configuration', 'site_path', $items['path']);
		Services::Registry()->set('Configuration', 'site_base_url', $items['base_url']);
		Services::Registry()->set('Configuration', 'site_description', $items['description']);

		return true;
	}

	/**
	 * Establish media, cache, log, etc., locations for site for application use
	 *
	 * Called out of the Configurations Class construct - paths needed in startup process for other services
	 *
	 * @return mixed
	 * @since  1.0
	 */
	public function setSitePaths()
	{
		if (defined('SITE_NAME')) {
		} else {
			define('SITE_NAME',
			Services::Registry()->get('Configuration', 'site_name', SITE_ID));
		}

		if (defined('SITE_CACHE_FOLDER')) {
		} else {
			define('SITE_CACHE_FOLDER',
			Services::Registry()->get('Configuration', 'cache_path', SITE_FOLDER_PATH . '/cache'));
		}

		if (defined('SITE_LOGS_FOLDER')) {
		} else {
			define('SITE_LOGS_FOLDER', SITE_FOLDER_PATH . '/'
				. Services::Registry()->get('Configuration', 'logs_path', SITE_FOLDER_PATH . '/logs'));
		}

		/** following must be within the web document folder */
		if (defined('SITE_MEDIA_FOLDER')) {
		} else {
			define('SITE_MEDIA_FOLDER', SITE_FOLDER_PATH . '/'
				. Services::Registry()->get('Configuration', 'media_path', SITE_FOLDER_PATH . '/media'));
		}
		if (defined('SITE_MEDIA_URL')) {
		} else {
			define('SITE_MEDIA_URL', BASE_URL
				. Services::Registry()->get('Configuration', 'media_url', BASE_URL . 'sites/' . SITE_ID . '/media'));
		}

		/** following must be within the web document folder */
		if (defined('SITE_TEMP_FOLDER')) {
		} else {
			define('SITE_TEMP_FOLDER', SITE_FOLDER_PATH . '/'
				. Services::Registry()->get('Configuration', 'temp_path', SITE_FOLDER_PATH . '/temp'));
		}
		if (defined('SITE_TEMP_URL')) {
		} else {
			define('SITE_TEMP_URL', BASE_URL
				. Services::Registry()->get('Configuration', 'temp_url', BASE_URL . 'sites/' . SITE_ID . '/temp'));
		}

		return true;
	}

	/**
	 * Get the application data and store it in the registry, combine with site data for configuration
	 *
	 * @return  boolean
	 * @since   1.0
	 */
	protected function getApplication()
	{

		if (APPLICATION == 'installation') {

			Services::Registry()->set('Configuration', 'application_id', 0);
			Services::Registry()->set('Configuration', 'application_catalog_type_id', CATALOG_TYPE_BASE_APPLICATION);
			Services::Registry()->set('Configuration', 'application_name', APPLICATION);
			Services::Registry()->set('Configuration', 'application_description', APPLICATION);
			Services::Registry()->set('Configuration', 'application_path', APPLICATION);

		} else {

			try {

				$m = Application::Controller()->connect('Applications');
				$m->model->set('id_name', APPLICATION);

				$items = $m->getData('load');
				if ($items === false) {
					throw new \RuntimeException ('Application getApplication() query problem');
				}

				Services::Registry()->set('Configuration', 'application_id', (int)$items['id']);
				Services::Registry()->set('Configuration', 'application_catalog_type_id',
					(int)$items['catalog_type_id']);
				Services::Registry()->set('Configuration', 'application_name', $items['name']);
				Services::Registry()->set('Configuration', 'application_path', $items['path']);
				Services::Registry()->set('Configuration', 'application_description', $items['description']);

				/** Combine Application and Site Parameters into Configuration */
				$parameters = Services::Registry()->get('ApplicationsParameters');
				foreach ($parameters as $key => $value) {
					Services::Registry()->set('Configuration', $key, $value);
				}

				/** Dynamic configuration info: base URLs for Site and Application */
				Services::Registry()->set('Configuration', 'site_base_url', BASE_URL);
				$path = Services::Registry()->get('Configuration', 'application_path', '');
				Services::Registry()->set('Configuration', 'application_base_url', BASE_URL . $path);

			} catch (\Exception $e) {
				echo 'Application will die. Exception caught in Configuration: ', $e->getMessage(), "\n";
				die;
			}
		}

		if (defined('APPLICATION_ID')) {
		} else {
			define('APPLICATION_ID', Services::Registry()->get('Configuration', 'application_id'));
		}

		return $this;
	}

	/**
	 * getFile processes all XML configuration files for the application
	 *
	 * Usage:
	 * Services::Configuration()->getFile('defines', 'Application');
	 *
	 * @return  mixed object or void
	 * @since   1.0
	 * @throws  \RuntimeException
	 */
	public static function getFile($file, $type = 'Application')
	{
//echo 'File: ' . $file . ' Type: ' . $type . '<br />';

		if ($type == 'Application' || $type == 'Language') {

		} else {
			$registryName = ucfirst(strtolower($file)) . ucfirst(strtolower($type));
			$exists = Services::Registry()->exists($registryName);
			if ($exists === true) {
				return $registryName;
			}
		}

		if (strtolower($type) == 'application') {
			$path_and_file = CONFIGURATION_FOLDER . '/Application/' . $file . '.xml';

		} else if (strtolower($type) == 'language') {
			$path_and_file = $file . '/' . 'Manifest.xml';

		} else if (strtolower($type) == 'table') {

			if (file_exists(EXTENSIONS_COMPONENTS . '/options/' . $file . '.xml')) {
				$path_and_file = EXTENSIONS_COMPONENTS . '/Options/' . $file . '.xml';

			} else if ($file == 'Themes') {

				if (file_exists(Services::Registry()->get('Parameters', 'theme_path') . '/Options/Theme.xml')) {
					$path_and_file = Services::Registry()->get('Parameters', 'theme_path') . '/Options/Theme.xml';
				} else {
					$path_and_file = CONFIGURATION_FOLDER . '/Table/' . $file . '.xml';
				}

			} else if ($file == 'PageViews' || $file == 'TemplateViews' || $file == 'WrapViews') {

				$path_and_file = CONFIGURATION_FOLDER . '/Table/' . $file . '.xml';

			} else {

				$path_and_file = CONFIGURATION_FOLDER . '/Table/' . $file . '.xml';
			}

		} else if (strtolower($type) == 'item') { // Primary Component Data

			if (file_exists(EXTENSIONS_COMPONENTS . '/' . $file . '/Options/Item.xml')) {
				$path_and_file = EXTENSIONS_COMPONENTS . '/' . $file . '/Options/Item.xml';
			} else {
				$path_and_file = CONFIGURATION_FOLDER . '/Table/' . $file . '.xml';
			}

		} elseif (strtolower($type) == 'list') {

			if (file_exists(EXTENSIONS_COMPONENTS . '/' . $file . '/Options/List.xml')) {
				$path_and_file = EXTENSIONS_COMPONENTS . '/' . $file . '/Options/List.xml';

			} else {
				$path_and_file = CONFIGURATION_FOLDER . '/Table/' . $file . '.xml';
			}

		} elseif (strtolower($type) == 'module') {

			$path_and_file = EXTENSIONS_MODULES . '/' . $file . '/Manifest.xml';

		} else {
			$path_and_file = $type . '/' . $file . '.xml';
		}

		if (file_exists($path_and_file)) {
		} else {
			echo 'DOES NOT EXIST ' . $file . ' ' . $type . '<br />' . $path_and_file . '<br />';
			echo 'File not found: ' . $path_and_file;
			throw new \RuntimeException('File not found: ' . $path_and_file);
		}

		try {
			$xml = simplexml_load_file($path_and_file);

		} catch (\Exception $e) {
			throw new \RuntimeException ('Failure reading XML File: ' . $path_and_file . ' ' . $e->getMessage());
		}

		if (strtolower($type) == 'application') {
			return $xml;

		} else if (strtolower($type) == 'language') {
			return $xml;

		} else if (strtolower($type) == 'module') {
			return ConfigurationService::processModuleTable($file, $type, $path_and_file, $xml);

		} else {
			//error ?
			return ConfigurationService::processTableFile($file, $type, $path_and_file, $xml);
		}
	}

	/**
	 * Retrieves base Model Registry data and stores it to the datasource registry
	 *
	 * @static
	 * @param  $registryName
	 * @param  $xml
	 * @return mixed
	 */
	public static function setModelRegistry($registryName, $xml)
	{
		Services::Registry()->set($registryName, 'model_name', (string)$xml['name']);
		Services::Registry()->set($registryName, 'table_name', (string)$xml['table']);

		$value = (string)$xml['primary_key'];
		if ($value == '') {
			$value = 'id';
		}
		Services::Registry()->set($registryName, 'primary_key', $value);

		$value = (string)$xml['name_key'];
		if ($value == '') {
			$value = 'title';
		}
		Services::Registry()->set($registryName, 'name_key', $value);

		$value = (string)$xml['primary_prefix'];
		if ($value == '') {
			$value = 'a';
		}
		Services::Registry()->set($registryName, 'primary_prefix', $value);

		$value = (int)$xml['get_customfields'];
		if ($value == 0 || $value == 1 || $value == 2) {
		} else {
			$value = 1;
		}
		Services::Registry()->set($registryName, 'get_customfields', $value);

		$value = (int)$xml['get_item_children'];
		if ($value == 0) {
		} else {
			$value = 1;
		}
		Services::Registry()->set($registryName, 'get_item_children', $value, '0');

		$value = (int)$xml['use_special_joins'];
		if ($value == 0) {
		} else {
			$value = 1;
		}
		Services::Registry()->set($registryName, 'use_special_joins', $value, '0');

		$value = (int)$xml['check_view_level_access'];
		if ($value == 0) {
		} else {
			$value = 1;
		}
		Services::Registry()->set($registryName, 'check_view_level_access', $value, '1');

		if ($value == 1) {
		} else {
			$value = 0;
		}
		Services::Registry()->set($registryName, 'check_published', $value, '1');

		$value = (string)$xml['data_source'];
		if ($value == '') {
			$value = 'JDatabase';
		}
		Services::Registry()->set($registryName, 'data_source', $value, 'JDatabase');

		return;
	}

	/**
	 * processModuleTable extracts XML configuration data for Modules and populates Registry
	 *
	 * @static
	 * @param $file
	 * @param string $type
	 * @param $path_and_file
	 * @param $xml
	 *
	 * @return  string Name of registry
	 * @since   1.0
	 * @throws  \RuntimeException
	 */
	public static function processModuleTable($file, $type = 'Module', $path_and_file, $xml)
	{
		$xml_string = '';
		$registryName = ucfirst(strtolower($file)) . ucfirst(strtolower($type));

		$exists = Services::Registry()->exists($registryName);
		if ($exists === true) {
			return $registryName;
		}

		/** Model */
		if (isset($xml->config->include['model'])) {
			$include = (string)$xml->config->include['model'];

			if ($xml_string == '') {
				$xml_string = file_get_contents($path_and_file);
			}

			$replace_this = '<include model="' . $include . '"/>';

			$xml_string = ConfigurationService::replaceIncludeStatement(
				$include, $file, $replace_this, $xml_string
			);

			$xml = simplexml_load_string($xml_string);
		}

		/** Set Model Properties */
		ConfigurationService::setModelRegistry($registryName, $xml->config->model);
		Services::Registry()->set($registryName, 'model_name', $registryName);

		/** Fields  */
		$known = array('name', 'type', 'null', 'default', 'length', 'customtype', 'file', 'char',
			'minimum', 'maximum', 'identity', 'shape', 'size', 'unique', 'values');

		$include = '';
		if (isset($xml->config->fields->include['name'])) {
			$include = (string)$xml->config->fields->include['name'];
		}
		if ($include == '') {
		} else {

			if ($xml_string == '') {
				$xml_string = file_get_contents($path_and_file);
			}

			$replace_this = '<include name="' . $include . '"/>';

			$xml_string = ConfigurationService::replaceIncludeStatement(
				$include, $file, $replace_this, $xml_string
			);
			$xml = simplexml_load_string($xml_string);
		}

		if (isset($xml->config->fields->field)) {

			$fields = $xml->config->fields->field;
			$fieldArray = array();

			foreach ($fields as $field) {

				$attributes = get_object_vars($field);
				$fieldAttributes = ($attributes["@attributes"]);
				$fieldAttributesArray = array();

				while (list($key, $value) = each($fieldAttributes)) {

					if (in_array($key, $known)) {
					} else {
						echo 'Field attribute not known ' . $key . ' for ' . $file . '<br />';
					}
					$fieldAttributesArray[$key] = $value;
				}
				$fieldArray[] = $fieldAttributesArray;
			}

			Services::Registry()->set($registryName, 'fields', $fieldArray);
		}

		/** Joins */

		$include = '';
		if (isset($xml->config->joins->include['name'])) {
			$include = (string)$xml->config->joins->include['name'];
		}
		if ($include == '') {
		} else {

			if ($xml_string == '') {
				$xml_string = file_get_contents($path_and_file);
			}
			$replace_this = '<include name="' . $include . '"/>';

			$xml_string = ConfigurationService::replaceIncludeStatement(
				$include, $file, $replace_this, $xml_string
			);
			$xml = simplexml_load_string($xml_string);
		}

		if (isset($xml->config->joins->join)) {
			$jXML = $xml->config->joins->join;

			$jArray = array();
			foreach ($jXML as $joinItem) {

				$joinVars = get_object_vars($joinItem);
				$joinAttributes = ($joinVars["@attributes"]);
				$joinAttributesArray = array();

				$joinAttributesArray['table'] = (string)$joinAttributes['table'];
				$joinAttributesArray['alias'] = (string)$joinAttributes['alias'];
				$joinAttributesArray['select'] = (string)$joinAttributes['select'];
				$joinAttributesArray['jointo'] = (string)$joinAttributes['jointo'];
				$joinAttributesArray['joinwith'] = (string)$joinAttributes['joinwith'];

				$jArray[] = $joinAttributesArray;
			}

			Services::Registry()->set($registryName, 'Joins', $jArray);
		}

		/** Parameters  */
		$include = '';
		if (isset($xml->config->parameters->include['name'])) {
			$include = (string)$xml->config->parameters->include['name'];
		}
		if ($include == '') {
		} else {

			if ($xml_string == '') {
				$xml_string = file_get_contents($path_and_file);
			}
			$replace_this = '<include name="' . $include . '"/>';

			$xml_string = ConfigurationService::replaceIncludeStatement(
				$include, $file, $replace_this, $xml_string
			);
			$xml = simplexml_load_string($xml_string);
		}

		/** Retrieve Field Attributes for each field */
		$fieldArray = array();

		if (isset($xml->config->parameters->field)) {
			foreach ($xml->config->parameters->field as $key1 => $value1) {

				$attributes = get_object_vars($value1);
				$fieldAttributes = ($attributes["@attributes"]);
				$fieldAttributesArray = array();

				while (list($key2, $value2) = each($fieldAttributes)) {

					if (in_array($key2, $known)) {
					} else {
						echo 'Field attribute not known ' . $key2 . ':' . $value2 . ' for ' . $file . '<br />';
					}
					$fieldAttributesArray[$key2] = $value2;
				}

				$fieldArray[] = $fieldAttributesArray;
			}
		}

		Services::Registry()->set($registryName, 'Parameters', $fieldArray);

		Services::Registry()->set($registryName, 'CustomFieldGroups', array('Parameters'));

		return $registryName;
	}

	/**
	 * processTableFile extracts XML configuration data for Tables/Models and populates Registry
	 * Returns the name of the registry
	 *
	 * NOTE: Until this gets fixed, right now, the replacement does not take place for includes
	 * if there is a space following the name value.
	 *
	 * This works: <include name="ThisRocks"/>
	 * This does not works: <include name="ThisSucks" />
	 *
	 * @static
	 * @param $file
	 * @param string $type
	 * @param $path_and_file
	 * @param $xml
	 *
	 * @return  string
	 * @since   1.0
	 * @throws  \RuntimeException
	 */
	public static function processTableFile($file, $type = 'Table', $path_and_file, $xml)
	{
		/** Table only: Process Include Code */
		$xml_string = '';

		$registryName = ucfirst(strtolower($file)) . $type;

//echo 'In processTableFile creating Registry: ' . $registryName . ' for file: ' . $path_and_file . '<br />';

		/** If the registry already exists, return it, otherwise create it */
		$exists = Services::Registry()->exists($registryName);
		if ($exists === true) {
			return $registryName;
		}

		Services::Registry()->createRegistry($registryName);

		/** Set Model Properties */
		ConfigurationService::setModelRegistry($registryName, $xml);

		/** Body - No registry */

		$include = '';
		if (isset($xml->model->body->include['name'])) {
			$include = (string)$xml->model->body->include['name'];
		}
		if ($include == '') {
		} else {
			$replace_this = '<include name="' . $include . '"/>';
			if ($xml_string == '') {
				$xml_string = file_get_contents($path_and_file);
			}
			$xml_string = ConfigurationService::replaceIncludeStatement(
				$include, $file, $replace_this, $xml_string
			);
			$xml = simplexml_load_string($xml_string);
		}

		/** Fields  */
		$known = array('name', 'type', 'null', 'default', 'length', 'customtype', 'file', 'char',
			'minimum', 'maximum', 'identity', 'shape', 'size', 'unique', 'values');

		$include = '';
		if (isset($xml->table->item->fields->include['name'])) {
			$include = (string)$xml->table->item->fields->include['name'];
		}
		if ($include == '') {
		} else {

			if ($xml_string == '') {
				$xml_string = file_get_contents($path_and_file);
			}

			$replace_this = '<include name="' . $include . '"/>';

			$xml_string = ConfigurationService::replaceIncludeStatement(
				$include, $file, $replace_this, $xml_string
			);
			$xml = simplexml_load_string($xml_string);
		}

		if (isset($xml->table->item->fields->field)) {

			$fields = $xml->table->item->fields->field;
			$fieldArray = array();

			foreach ($fields as $field) {

				$attributes = get_object_vars($field);
				$fieldAttributes = ($attributes["@attributes"]);
				$fieldAttributesArray = array();

				while (list($key, $value) = each($fieldAttributes)) {

					if (in_array($key, $known)) {
					} else {
						echo 'Field attribute not known ' . $key . ' for ' . $file . '<br />';
					}
					$fieldAttributesArray[$key] = $value;
				}
				$fieldArray[] = $fieldAttributesArray;
			}

			Services::Registry()->set($registryName, 'fields', $fieldArray);
		}

		/** Joins */

		$include = '';
		if (isset($xml->table->item->joins->include['name'])) {
			$include = (string)$xml->table->item->joins->include['name'];
		}
		if ($include == '') {
		} else {

			if ($xml_string == '') {
				$xml_string = file_get_contents($path_and_file);
			}
			$replace_this = '<include name="' . $include . '"/>';

			$xml_string = ConfigurationService::replaceIncludeStatement(
				$include, $file, $replace_this, $xml_string
			);
			$xml = simplexml_load_string($xml_string);
		}

		if (isset($xml->table->item->joins->join)) {
			$jXML = $xml->table->item->joins->join;

			$jArray = array();
			foreach ($jXML as $joinItem) {

				$joinVars = get_object_vars($joinItem);
				$joinAttributes = ($joinVars["@attributes"]);
				$joinAttributesArray = array();

				$joinAttributesArray['table'] = (string)$joinAttributes['table'];
				$joinAttributesArray['alias'] = (string)$joinAttributes['alias'];
				$joinAttributesArray['select'] = (string)$joinAttributes['select'];
				$joinAttributesArray['jointo'] = (string)$joinAttributes['jointo'];
				$joinAttributesArray['joinwith'] = (string)$joinAttributes['joinwith'];

				$jArray[] = $joinAttributesArray;
			}

			Services::Registry()->set($registryName, 'Joins', $jArray);
		}

		/** Foreign Keys */

		$include = '';
		if (isset($xml->table->item->foreignkeys->include['name'])) {
			$include = (string)$xml->table->item->foreignkeys->include['name'];
		}
		if ($include == '') {
		} else {
			if ($xml_string == '') {
				$xml_string = file_get_contents($path_and_file);
			}
			$replace_this = '<include name="' . $include . '"/>';
			$xml_string = ConfigurationService::replaceIncludeStatement(
				$include, $file, $replace_this, $xml_string
			);
			$xml = simplexml_load_string($xml_string);
		}

		if (isset($xml->table->item->foreignkeys->foreignkey)) {

			$fks = $xml->table->item->foreignkeys->foreignkey;
			$fkArray = array();
			foreach ($fks as $fk) {

				$attributes = get_object_vars($fk);
				$fkAttributes = ($attributes["@attributes"]);
				$fkAttributesArray = array();

				$fkAttributesArray['name'] = $fkAttributes['name'];
				$fkAttributesArray['source_id'] = $fkAttributes['source_id'];
				$fkAttributesArray['source_model'] = $fkAttributes['source_model'];
				$fkAttributesArray['required'] = $fkAttributes['required'];

				$fkArray[] = $fkAttributesArray;
			}
			Services::Registry()->set($registryName, 'foreignkeys', $fkArray);
		}

		/** Children */

		$include = '';
		if (isset($xml->table->item->children->include['name'])) {
			$include = (string)$xml->table->item->children->include['name'];
		}
		if ($include == '') {
		} else {
			if ($xml_string == '') {
				$xml_string = file_get_contents($path_and_file);
			}
			$replace_this = '<include name="' . $include . '"/>';
			$xml_string = ConfigurationService::replaceIncludeStatement(
				$include, $file, $replace_this, $xml_string
			);
			$xml = simplexml_load_string($xml_string);
		}

		if (isset($xml->table->item->children->child)) {

			$cs = $xml->table->item->children->child;
			$csArray = array();
			foreach ($cs as $c) {

				$chVars = get_object_vars($c);
				$chAttributes = ($chVars["@attributes"]);
				$chkAttributesArray = array();

				$chkAttributesArray['name'] = $chAttributes['name'];
				$chkAttributesArray['join'] = $chAttributes['join'];

				$csArray[] = $chkAttributesArray;
			}
			Services::Registry()->set($registryName, 'children', $csArray);
		}

		/** Triggers */
		$include = '';
		if (isset($xml->table->item->triggers->include['name'])) {
			$include = (string)$xml->table->item->triggers->include['name'];
		}

		if ($include == '') {
		} else {
			if ($xml_string == '') {
				$xml_string = file_get_contents($path_and_file);
			}
			$replace_this = '<include name="' . $include . '"/>';
			$xml_string = ConfigurationService::replaceIncludeStatement(
				$include, $file, $replace_this, $xml_string
			);
			$xml = simplexml_load_string($xml_string);
		}

		if (isset($xml->table->item->triggers->trigger)) {
			$triggers = $xml->table->item->triggers->trigger;
			$triggersArray = array();
			foreach ($triggers as $trigger) {
				$t = get_object_vars($trigger);
				$tAttr = ($t["@attributes"]);
				$triggersArray[] = $tAttr['name'];
			}
			Services::Registry()->set($registryName, 'triggers', $triggersArray);
		}

		/** Customfields */
		$fieldTypes = 0;
		while ($fieldTypes < 99) {

			/** Process one field type at a time ex. parameters, metadata, customfield */
			if (isset($xml->table->item->customfields->customfield[$fieldTypes])) {
			} else {
				$fieldTypes = 9999;
				break;
			}

			$done = false;
			while ($done == false) {

				/** Process one include code statement at a time per fieldtype */
				if (isset($xml->table->item->customfields->customfield[$fieldTypes]->include['name'])) {
					$include = (string)$xml->table->item->customfields->customfield[$fieldTypes]->include['name'];

					if ($xml_string == '') {
						$xml_string = file_get_contents($path_and_file);
					}

					$replace_this = '<include name="' . $include . '"/>';

					$xml_string = ConfigurationService::replaceIncludeStatement(
						$include, $file, $replace_this, $xml_string
					);

					$xml = simplexml_load_string($xml_string);
				} else {
					$done = true;
				}
			}
			$fieldTypes++;
		}

		/** Now that all include code has been retrieved, process custom fields */
		if (isset($xml->table->item->customfields)) {
			ConfigurationService::getCustomFields(
				$xml->table->item->customfields,
				$file,
				$known,
				$registryName
			);
		}

		/** I THINK THIS WILL BE REMOVED Component Customfields (OR WE'LL USE THIS FOR QUERIES) */

		$include = 'x';
		$i = 0;
		while ($i < 99) {

			if (isset($xml->table->component->customfields->customfield[$i])) {
			} else {
				$i = 9999;
				break;
			}

			if (isset($xml->table->component->customfields->customfield[$i]->include['name'])) {
				$include = (string)$xml->table->component->customfields->customfield[$i]->include['name'];

				if ($xml_string == '') {
					$xml_string = file_get_contents($path_and_file);
				}

				$replace_this = '<include name="' . $include . '"/>';

				$xml_string = ConfigurationService::replaceIncludeStatement(
					$include, $file, $replace_this, $xml_string
				);

				$xml = simplexml_load_string($xml_string);
			}
			$i++;
		}

		if (isset($xml->table->component->customfields)) {
			ConfigurationService::getCustomFields(
				$xml->table->component->customfields,
				$file,
				$known,
				$registryName
			);
		}

		return $registryName;
	}

	/**
	 * replaceIncludeStatement retrieves the specified include file and substitutes the contents
	 * for the include statement
	 *
	 * @static
	 * @return  object
	 * @since   1.0
	 * @throws  \RuntimeException
	 */
	public static function replaceIncludeStatement($include, $file, $replace_this, $xml_string)
	{
//echo 'In replace: ' . $include . ' ' . $file . ' ' . $replace_this . ' ' . $xml_string . ' <br />';
		$path_and_file = CONFIGURATION_FOLDER . '/include/' . $include . '.xml';

		if (file_exists($path_and_file)) {
		} else {
			throw new \RuntimeException('Include file not found: ' . $path_and_file);
		}

		try {
			$with_this = file_get_contents($path_and_file);
			return str_replace($replace_this, $with_this, $xml_string);

		} catch (\Exception $e) {
			throw new \RuntimeException (
				'Failure reading XML Include file: ' . $path_and_file . ' ' . $e->getMessage()
			);
		}
	}

	/**
	 * processTableFile extracts XML configuration data for Tables/Models and populates Registry
	 *
	 * @static
	 * @param $xml
	 * @param $file
	 * @param $known - list of valid field attributes
	 * @param $registryName
	 *
	 * @return  object
	 * @since   1.0
	 * @throws  \RuntimeException
	 */
	public static function getCustomFields($xml, $file, $known, $registryName)
	{
		$i = 0;
		$continue = true;
		$customFieldsArray = array();

		while ($continue == true) {

			if (isset($xml->customfield[$i]->field)) {
				$customfield = $xml->customfield[$i];
			} else {
				$continue = false;
				break;
			}

			$name = '';

			/** Specific Field Name  */
			if (isset($customfield['name'])) {
				$name = (string)$customfield['name'];
			}

			/** Retrieve Field Attributes for each field */
			$fieldArray = array();
			foreach ($customfield->field as $key1 => $value1) {

				$attributes = get_object_vars($value1);
				$fieldAttributes = ($attributes["@attributes"]);
				$fieldAttributesArray = array();

				while (list($key2, $value2) = each($fieldAttributes)) {

					if (in_array($key2, $known)) {
					} else {
						echo 'Field attribute not known ' . $key2 . ':' . $value2 . ' for ' . $file . '<br />';
					}

					$fieldAttributesArray[$key2] = $value2;
				}

				$fieldArray[] = $fieldAttributesArray;
			}
			Services::Registry()->set($registryName, $name, $fieldArray);

			/** Track Registry names for all customfields */
			$exists = Services::Registry()->exists($registryName, 'CustomFieldGroups');

			if ($exists === true) {
				$temp = Services::Registry()->get($registryName, 'CustomFieldGroups');
			} else {
				$temp = array();
			}

			if (is_array($temp)) {
			} else {
				if ($temp == '') {
					$temp = array();
				} else {

					$hold = $temp;
					$temp = array();
					$temp[] = $hold;
				}
			}

			$temp[] = $name;

			Services::Registry()->set($registryName, 'CustomFieldGroups', array_unique($temp));

			$i++;
		}

		return;
	}
}
