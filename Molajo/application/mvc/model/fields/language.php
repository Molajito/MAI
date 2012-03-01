<?php
/**
 * @version     $id: filterLanguage.php
 * @package     Molajo
 * @subpackage  Filter
 * @copyright   Copyright (C) 2012 Amy Stephen. All rights reserved.
 * @license     GNU General Public License Version 2, or later http://www.gnu.org/licenses/gpl.html
 */
defined('MOLAJO') or die;

/**
 *  MolajoFieldLanguage
 *
 *  Language Filter Field Handling
 *
 * @package    Molajo
 * @subpackage Filter
 * @since      1.6
 */
ClassFieldLanguage extends MolajoField
{
    /**
     *  __construct
     *
     *  Set Fieldname and Filter with parent
     */
    public function __construct()
    {
        parent::__construct();
        parent::setName('language');
        parent::setFilter('string');

        parent::setSortable(true);
        parent::setCheckbox(false);
        parent::setDisplayType('integer');
    }

    /**
     *  getOptions
     *
     *  Returns Option Values
     */
    public function getOptions()
    {
        return MolajoHTML::_('contentlanguage.existing', true, true);
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

        /** validate to list **/
        $this->validateRequestValue();

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
        $validItems = $this->getOptions();

        /** loop thru **/
        $found = false;
        foreach ($validItems as $count => $validItem) {

            if ($this->value == $validItem->value) {
                $found = true;
                break;
            }

        }
        return $found;
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
            $query->select('a.language');
            $query->select('l.title AS language_title');
            $query->join('LEFT', '`#__languages` AS l ON l.lang_code = a.language');
        }

        if ((int)$value == 0) {
            return;
        }

        if (is_numeric($value) && $value > 0) {
            $query->where('a.language = ' . (int)$value);
        }
    }
}
