<?php
/**
 * @package   Molajo
 * @copyright 2012 Amy Stephen. All rights reserved.
 * @license   GNU General Public License Version 2, or later http://www.gnu.org/licenses/gpl.html
 */
namespace Molajo\Service\Services\Error;

use Molajo\Service\Services;

defined('MOLAJO') or die;

/**
 * Error
 *
 * @package     Molajo
 * @subpackage  Service
 * @since       1.0
 */
Class ErrorService
{
	/**
	 * @static
	 * @var    object
	 * @since  1.0
	 */
	protected static $instance;

	/**
	 * @static
	 * @return  bool|object
	 * @since   1.0
	 */
	public static function getInstance()
	{
		if (empty(self::$instance)) {
			self::$instance = new ErrorService();
		}
		return self::$instance;
	}

	/**
	 * 500 Set routing for an error condition
	 *
	 * @param   $code
	 * @param   null|string $message
	 *
	 * @return  null
	 *
	 * @since   1.0
	 */
	public function set($code, $message = 'Internal server error')
	{
		Services::Registry()->set('Request', 'error_status', true);
		Services::Registry()->set('Request', 'mvc_controller', 'display');
		Services::Registry()->set('Request', 'action', 'display');
		Services::Registry()->set('Request', 'mvc_model', 'messages');

		/** default error theme and page */
		Services::Registry()->set('Request', 'theme_id',
			Services::Registry()->get('Configuration', 'error_theme_id', 0)
		);
		Services::Registry()->set('Request', 'page_view_id',
			Services::Registry()->get('Configuration', 'error_page_view_id', 0)
		);

		/** set header status, message and override default theme/page, if needed */
		if ($code == 503) {
			$this->error503();

		} else if ($code == 403) {
			$this->error403();

		} else if ($code == 404) {
			$this->error404();

		} else {

			Services::Response()
				->setHeader('Status', '500 Internal server error', true);

			Services::Message()
				->set($message, MESSAGE_TYPE_ERROR, 500);
		}
	}

	/**
	 * 503 Offline
	 *
	 * @return  null
	 *
	 * @since   1.0
	 */
	protected function error503()
	{
		Services::Response()
			->setStatusCode(503);

		Services::Message()
			->set(
				Services::Registry()->get('Configuration', 'offline_message',
				'This site is not available.<br /> Please check back again soon.'
			),
			MESSAGE_TYPE_WARNING,
			503
		);

		Services::Registry()->set('Request', 'theme_id',
			Services::Registry()->get('Configuration', 'offline_theme_id', 0)
		);

		Services::Registry()->set('Request', 'page_view_id',
			Services::Registry()->get('Configuration', 'offline_page_view_id', 0)
		);
	}

	/**
	 * 403 Not Authorised
	 *
	 * @return  null
	 *
	 * @since   1.0
	 */
	protected function error403()
	{
		Services::Response()
			->setStatusCode(403);

		Services::Message()
			->set(
				Services::Registry()->get('Configuration', 'error_403_message', 'Not Authorised.'),
				MESSAGE_TYPE_ERROR,
				403
			);
	}

	/**
	 * 404 Page Not Found
	 *
	 * @return  null
	 *
	 * @since   1.0
	 */
	protected function error404()
	{
		Services::Response()
			->setStatusCode(404);

		Services::Message()
			->set(
				Services::Registry()->get('Configuration', 'error_404_message', 'Page not found.'),
				MESSAGE_TYPE_ERROR,
				404
			);

		return;
	}
}
