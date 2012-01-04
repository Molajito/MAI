<?php
/**
 * @version        $Id: default.php 20196 2011-01-09 02:40:25Z ian $
 * @package        Joomla.Site
 * @subpackage    search
 * @copyright    Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license        GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
?>
<form action="<?php echo MolajoRouteHelper::_('index.php');?>" method="post">
    <div class="search<?php echo $view_class_suffix ?>">
<?php
            $output = '<label for="mod-search-searchword">' . $label . '</label><input name="searchword" id="mod-search-searchword" maxlength="' . $maxlength . '"  class="inputbox' . $view_class_suffix . '" type="text" size="' . $width . '" value="' . $text . '"  onblur="if (this.value==\'\') this.value=\'' . $text . '\';" onfocus="if (this.value==\'' . $text . '\') this.value=\'\';" />';

    if ($button) :
        if ($imagebutton) :
            $button = '<input type="image" value="' . $button_text . '" class="button' . $view_class_suffix . '" src="' . $img . '" onclick="this.form.searchword.focus();"/>';
        else :
            $button = '<input type="submit" value="' . $button_text . '" class="button' . $view_class_suffix . '" onclick="this.form.searchword.focus();"/>';
        endif;
    endif;

    switch ($button_pos) :
        case 'top' :
            $button = $button . '<br />';
            $output = $button . $output;
            break;

        case 'bottom' :
            $button = '<br />' . $button;
            $output = $output . $button;
            break;

        case 'right' :
            $output = $output . $button;
            break;

        case 'left' :
        default :
            $output = $button . $output;
            break;
    endswitch;

    echo $output;
    ?>
    <input type="hidden" name="task" value="search"/>
    <input type="hidden" name="option" value="search"/>
    </div>
</form>
