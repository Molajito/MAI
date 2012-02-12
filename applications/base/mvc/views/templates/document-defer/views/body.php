<?php
/**
 * @package     Molajo
 * @subpackage  Views
 * @copyright   Copyright (C) 2012 Amy Stephen. All rights reserved.
 * @license     GNU General Public License Version 2, or later http://www.gnu.org/licenses/gpl.html
 */
defined('MOLAJO') or die;

/**
 *  Builds recursive aspects of the header considering HTML5
 *
 *  Note: Avoid horizontal space outside of the PHP sections
 *      because it will be reflected in the header section
 */

/** javascript_links */
if ($this->row->type == 'javascript_links'):
?>
    <script src="<?php echo $this->row->url; ?>" <?php if ((int)Services::Configuration()->get('html5', 1) == 0): ?> type="<?php echo $this->row->mimetype; ?>"<?php endif; ?><?php if (trim($this->row->defer) != ''): ?>defer="defer" <?php endif; ?><?php if (trim($this->row->async) != ''): ?>async="async" <?php endif; ?>/></script>
    <?php

    /** stylesheet_declarations */
    elseif ($this->row->type == 'script_declarations'):
    ?>
        <script<?php if ((int)Services::Configuration()->get('html5', 1) == 0): ?> type="<?php echo $this->row->mimetype; ?>" <?php endif; ?>>
        <?php
            if ($this->row->mimetype == 'text/html') :
            else : ?>
            <![CDATA[
        <?php
            endif;
            echo $this->row->content;
            if ($this->row->mimetype == 'text/html') :
            else : ?>
            ]]>
        <?php
            endif; ?>
        </script>
    <?php
endif;
