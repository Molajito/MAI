<?php
/**
 * @package    Molajo
 * @copyright  2012 Amy Stephen. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */
namespace Molajo\MVC\Controller;

use Molajo\Service\Services;

defined('MOLAJO') or die;

/**
 * Entry
 *
 * As the name might suggest, the Entry Controller is the entry point for all Controller requests.
 * The class merely allows all processing to enter a common gateway and then flow
 * through the class structure to the intended method.
 *
 * There are two basic process flows to the Controller within the Molajo Application:
 *
 * 1. The first is directly related to processing the request and using the MVC
 *     architecture to either render output or execute the task action.
 *
 *   -> For rendering, the Parser and Includer gather data needed and execute
 *         the Controller task to activate the MVC.
 *
 *   -> For task actions, the Controller task is initiated in the Application class.
 *
 *  The Controller then interacts with the Model for data requests.
 *
 * 2. The second logic flow routes support queries originating in Service and Helper
 *  route through the Model Service class which essentially acts as a Controller
 *  to gather information and then invoke the Model, as needed.
 *
 * @package     Molajo
 * @subpackage  Model
 * @since       1.0
 */
Class EntryController extends DisplayController
{
	/**
	 * Constructor.
	 *
	 * @since  1.0
	 */
	public function __construct()
	{
		return parent::__construct();
	}
}
