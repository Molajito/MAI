<?php
/**
 * @package     Molajo
 * @subpackage  Renderer
 * @copyright   Copyright (C) 2012 Amy Stephen. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('MOLAJO') or die;

/**
 * MolajoTagRenderer
 *
 * @package     Molajo
 * @subpackage  Renderer
 * @since       1.0
 */
ClassTagRenderer extends Renderer
{
	/**
	 * Renders multiple modules script and returns the results as a string
	 *
	 * @param   string  $position  The position of the modules to render
	 * @param   array   $params    Associative array of values
	 * @param   string  $content   Module content
	 *
	 * @return  string  The output of the script
	 *
	 * @since   11.1
	 */
	public function render($position, $params = array(), $content = null)
	{
		$renderer = $this->_doc->loadRenderer('module');
		$buffer = '';

		foreach (JModuleHelper::getModules($position) as $mod)
		{
			$buffer .= $renderer->render($mod, $params, $content);
		}
		return $buffer;
	}
}
