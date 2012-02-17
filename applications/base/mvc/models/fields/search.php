<?php
/**
 * @version     $id: filterSearch.php
 * @package     Molajo
 * @subpackage  Filter
 * @copyright   Copyright (C) 2012 Amy Stephen. All rights reserved.
 * @license     GNU General Public License Version 2, or later http://www.gnu.org/licenses/gpl.html
 */
defined('MOLAJO') or die;

/**
 *  MolajoFieldSearch
 *
 *  Search Filter Field Handling
 *
 * @package    Molajo
 * @subpackage Filter
 * @since      1.6
 */
class MolajoFieldSearch extends MolajoField
{
    /**
     *  __construct
     *
     *  Set Fieldname and Filter with parent
     */
    public function __construct()
    {
        parent::__construct();
        parent::setName('search');
        parent::setFilter('string');

        parent::setSortable(false);
        parent::setCheckbox(false);
        parent::setDisplayType('string');
    }

    /**
     *  getOptions
     *
     *  Returns Option Values
     */
    public function getOptions()
    {
        $this->valueDateModel = JModel::getInstance('Model' . ucfirst(JRequest::getCmd('DefaultView')), ucfirst(JRequest::getCmd('DefaultView')), array('ignore_request' => true));
        return $this->valueDateModel->getMonthsPublish();
    }

    /**
     *  getValue
     *
     *  Returns Selected Value
     */
    public function getValue()
    {
        /** retrieve and filter selected value **/
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
        $db = Services::DB();
        if ($value == null || trim($value) == '') {
            return;
        }

        if (stripos($value, 'id:')) {
            $where = 'a.id = ' . (int)substr(trim($value), 3);

        } else if (stripos(trim($value), 'author:')) {
            $authorname = $db->q('%' . $db->getEscaped(substr($value, 7), true) . '%');
            $where = 'author.name LIKE ' . $db->q(trim($authorname)) . ' OR author.username LIKE ' . $db->q(trim($authorname));

        } else {
            $title = $db->q('%' . $db->getEscaped(trim($value)) . '%');
            $where = 'a.title LIKE ' . $title . ' OR a.alias LIKE ' . $db->q(trim($title));
        }
        $query->where($where);
    }
}
