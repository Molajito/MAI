<?php
/**
 * @version     $id: filtercreatedby.php
 * @package     Molajo
 * @subpackage  Filter
 * @copyright   Copyright (C) 2012 Amy Stephen. All rights reserved.
 * @license     GNU General Public License Version 2, or later http://www.gnu.org/licenses/gpl.html
 */
defined('MOLAJO') or die;

/**
 *  MolajoFieldCreated_by
 *
 *  Created_by Filter Field Handling
 *
 * @package    Molajo
 * @subpackage Filter
 * @since      1.6
 */
ClassFieldCreatedBy extends MolajoField
{
    /**
     *  __construct
     *
     *  Set Fieldname and Filter with parent
     */
    public function __construct()
    {
        parent::__construct();
        parent::setName('created_by');
        parent::setFilter('integer');

    }

    /**
     *  getOptions
     *
     *  Returns Option Values
     */
    public function getOptions()
    {
        $class = ucfirst(JRequest::getCmd('DefaultView')) . 'Model' . ucfirst(JRequest::getCmd('DefaultView'));
        $authorModel = new $class();
        return $authorModel->getAuthors();
    }

    /**
     *  getValue
     *
     *  Returns Selected Value
     */
    public function getValue()
    {
        parent::getValue();

        if ($this->value == null) {
            return false;
        }

        /** return filtered and validated value **/
        return $this->value;
    }

    /**
     *  validateRequestValue
     *
     *  Returns Selected Value
     */
    public function validateRequestValue()
    {

    }

    /**
     *  getQueryInformation
     *
     *  Returns Formatted Where clause for Query
     */
    public function getQueryInformation($query, $value, $selectedState, $onlyWhereClause = false)
    {
        if ($onlyWhereClause) {
        } else {
            $query->select('author.id AS author_id');
            $query->select('a.created_by AS created_by');
            $query->select('author.name AS author_name');
            $query->select('author.email AS author_email');
            $query->join('LEFT', '#__users AS author ON author.id = a.created_by');
        }

        if ((int)$value == 0) {
            return;
        }
        $query->where('a.created_by = ' . (int)$value);
    }

    /**
     *  render
     *
     *  sets formatting and content parameters
     */
    public function render($view, $item, $itemCount)
    {
        if ($view == 'admin') {
            $render = array();
            $render['link_value'] = false;
            $render['class'] = 'nowrap';
            $render['valign'] = 'top';
            $render['align'] = 'left';
            $render['sortable'] = true;
            $render['checkbox'] = false;
            $render['data_type'] = 'string';
            $render['column_name'] = 'author_name';
            $render['print_value'] = $item->author_name;

            return $render;
        }
    }
}
