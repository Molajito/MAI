<?php
/**
 * @package     Molajo
 * @subpackage  Renderer
 * @copyright   Copyright (C) 2012 Amy Stephen. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('MOLAJO') or die;

/**
 * Theme
 *
 * @package     Molajo
 * @subpackage  Renderer
 * @since       1.0
 */
class MolajoThemeRenderer extends MolajoRenderer
{
    /**
     * __construct
     *
     * Class constructor.
     *
     * @param  string $name
     * @param  string $type
     * @param  array  $items (used for event processing renderers, only)
     *
     * @return  null
     * @since   1.0
     */
    public function __construct($name = null, $type = null, $items = null)
    {
        $this->name = $name;
        $this->type = $type;

        $this->parameters = new Registry;
        $this->parameters->set('suppress_no_results', 0);
    }

    /**
     * render
     *
     * Establishes language files and media for theme
     *
     * @param   $attributes <include:renderer attr1=x attr2=y attr3=z ... />
     *
     * @return  mixed
     * @since   1.0
     */
    public function process($attributes = array())
    {
        $this->_loadMetadata();
        $this->_loadLanguage();
        $this->_loadMedia();

        return;
    }

    /**
     * _loadMetadata
     *
     * Loads Metadata values into Molajo::Response array
     *
     * @return  null
     * @since   1.0
     */
    protected function _loadMetadata()
    {
        if (Molajo::Request()->get('status_error') == true) {
            Molajo::Responder()->setMetadata('metadata_title',
                Services::Language()->_('ERROR_FOUND'));
            Molajo::Responder()->setMetadata('metadata_description', '');
            Molajo::Responder()->setMetadata('metadata_keywords', '');
            Molajo::Responder()->setMetadata('metadata_robots', '');
            Molajo::Responder()->setMetadata('metadata_author', '');
            Molajo::Responder()->setMetadata('metadata_content_rights', '');
            Molajo::Responder()->setMetadata('source_last_modified', '');
        } else {

            Molajo::Responder()->setMetadata('metadata_title',
                Molajo::Request()->get('metadata_title'));
            Molajo::Responder()->setMetadata('metadata_description',
                Molajo::Request()->get('metadata_description'));
            Molajo::Responder()->setMetadata('metadata_keywords',
                Molajo::Request()->get('metadata_keywords'));
            Molajo::Responder()->setMetadata('metadata_robots',
                Molajo::Request()->get('metadata_robots'));
            Molajo::Responder()->setMetadata('metadata_author',
                Molajo::Request()->get('metadata_author'));
            Molajo::Responder()->setMetadata('metadata_content_rights',
                Molajo::Request()->get('metadata_content_rights'));
            Molajo::Responder()->response->last_modified =
                Molajo::Request()->get('source_last_modified');
        }
    }

    /**
     * _loadLanguage
     *
     * Loads Language Files for extension
     *
     * @return  null
     * @since   1.0
     */
    protected function _loadLanguage()
    {
        /** theme */
        ExtensionHelper::loadLanguage(
            MOLAJO_EXTENSIONS_THEMES . '/'
                . Molajo::Request()->get('theme_name')
        );
        /** pages view */
        ExtensionHelper::loadLanguage(
            Molajo::Request()->get('page_view_path')
        );
    }

    /**
     * _loadMedia
     *
     * Loads Media Files for Site, Application, User, and Theme
     *
     * @return  boolean  True, if the file has successfully loaded.
     * @since   1.0
     */
    protected function _loadMedia()
    {
        /**  Site */
        $this->_loadMediaPlus('',
            Services::Configuration()->get('media_priority_site', 100));

        /** Application */
        $this->_loadMediaPlus('/application' . MOLAJO_APPLICATION,
            Services::Configuration()->get('media_priority_application', 200));

        /** User */
        $this->_loadMediaPlus('/user' .
                Services::User()
                    ->get('id'),
            Services::Configuration()->get('media_priority_user', 300));

        /** Theme */
        $priority = Services::Configuration()->get('media_priority_theme', 600);
        $filePath = MOLAJO_EXTENSIONS_THEMES . '/' .
            Molajo::Request()->get('theme_name');
        $urlPath = MOLAJO_EXTENSIONS_THEMES_URL . '/' .
            Molajo::Request()->get('theme_name');
        $css = Molajo::Responder()->addStyleLinksFolder($filePath, $urlPath, $priority);
        $js = Molajo::Responder()->addScriptLinksFolder($filePath, $urlPath, $priority, 0);
        $js = Molajo::Responder()->addScriptLinksFolder($filePath, $urlPath, $priority, 1);

        /** Page */
        $priority = Services::Configuration()->get('media_priority_theme', 600);
        $filePath = Molajo::Request()->get('page_view_path');
        $urlPath = Molajo::Request()->get('page_view_path_url');
        $css = Molajo::Responder()->addStyleLinksFolder($filePath, $urlPath, $priority);
        $js = Molajo::Responder()->addScriptLinksFolder($filePath, $urlPath, $priority, 0);
        $js = Molajo::Responder()->addScriptLinksFolder($filePath, $urlPath, $priority, 1);

        return;
    }

    /**
     * _loadMediaPlus
     *
     * Loads Media Files for Site, Application, User, and Theme
     *
     * @return  boolean  True, if the file has successfully loaded.
     * @since   1.0
     */
    protected function _loadMediaPlus($plus = '', $priority = 500)
    {
        /** Site Specific: Application */
        $filePath = SITE_MEDIA_FOLDER . '/' . $plus;
        $urlPath = SITE_MEDIA_URL . '/' . $plus;
        $css = Molajo::Responder()->addStyleLinksFolder($filePath, $urlPath, $priority);
        $js = Molajo::Responder()->addScriptLinksFolder($filePath, $urlPath, $priority, 0);
        $defer = Molajo::Responder()->addScriptLinksFolder($filePath, $urlPath, $priority, 1);

        /** Site Specific: Site-wide */
        $filePath = SITE_MEDIA_FOLDER . $plus;
        $urlPath = SITE_MEDIA_URL . $plus;
        $css = Molajo::Responder()->addStyleLinksFolder($filePath, $urlPath, $priority);
        $js = Molajo::Responder()->addScriptLinksFolder($filePath, $urlPath, $priority, false);
        $defer = Molajo::Responder()->addScriptLinksFolder($filePath, $urlPath, $priority, 1);

        /** All Sites: Application */
        $filePath = SITES_MEDIA_FOLDER . '/' . MOLAJO_APPLICATION . $plus;
        $urlPath = SITES_MEDIA_URL . '/' . MOLAJO_APPLICATION . $plus;
        $css = Molajo::Responder()->addStyleLinksFolder($filePath, $urlPath, $priority);
        $js = Molajo::Responder()->addScriptLinksFolder($filePath, $urlPath, $priority, 0);
        $defer = Molajo::Responder()->addScriptLinksFolder($filePath, $urlPath, $priority, 1);

        /** All Sites: Site Wide */
        $filePath = SITES_MEDIA_FOLDER . $plus;
        $urlPath = SITES_MEDIA_URL . $plus;
        $css = Molajo::Responder()->addStyleLinksFolder($filePath, $urlPath, $priority);
        $js = Molajo::Responder()->addScriptLinksFolder($filePath, $urlPath, $priority, 0);
        $defer = Molajo::Responder()->addScriptLinksFolder($filePath, $urlPath, $priority, 1);

        return;
    }
}
