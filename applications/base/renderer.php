<?php
/**
 * @package     Molajo
 * @subpackage  Base
 * @copyright   Copyright (C) 2012 Amy Stephen. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('MOLAJO') or die;

/**
 * Renderer
 *
 * @package     Molajo
 * @subpackage  Renderer
 * @since       1.0
 */
class MolajoRenderer
{
    /**
     * $name
     *
     * @var    string
     * @since  1.0
     */
    protected $name = null;

    /**
     * $type
     *
     * @var    string
     * @since  1.0
     */
    protected $type = null;

    /**
     * $tag
     *
     * @var    string
     * @since  1.0
     */
    protected $tag = null;

    /**
     * $attributes
     *
     * Extracted in Parser Class from Theme/Rendered output
     * <include:extension statement attr1=x attr2=y attrN="and-so-on" />
     *
     * @var    array
     * @since  1.0
     */
    protected $attributes = array();

    /**
     * $extension_required
     *
     * Some renderers (ex. head, messages, defer), do not require
     * an extension for further processing. In those cases, this
     * indicator is set to false.
     *
     * @var    bool
     * @since  1.0
     */
    protected $extension_required = true;

    /**
     * $task_request
     *
     * Building this object is the primary purpose of the Renderers
     * classes. It contains the instructions needed for the MVC to
     * fulfill this renderer request
     *
     * @var    object
     * @since  1.0
     */
    protected $task_request;

    /**
     * $parameters
     *
     * Parameters merged using default sequence
     *
     * @var    object
     * @since  1.0
     */
    protected $parameters;

    /**
     * $rendered_output
     *
     * Rendered output resulting from MVC processing
     *
     * @var    object
     * @since  1.0
     */
    protected $rendered_output;

    /**
     * $items
     *
     * Used only for event processing and will be passed into the
     * MVC to serve as the Model data source
     *
     * @var    object
     * @since  1.0
     */
    protected $items;

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
        $this->items = $items;

        $this->parameters = new Registry;
        $this->parameters->set('extension_suppress_no_results', 0);
    }

    /**
     * process
     *
     * Determines the criteria associated with this renderer request
     *
     * Imports extension classes, if existing
     *
     * Loads page metadata (only invoked for Theme Renderer)
     *
     * Loads language files for extension.
     *
     * Loads media files for extension
     *
     * Calls the controller->task which processes renderer request
     *
     * Captures rendered output for possible post-processing
     *
     * Returns rendered output to Molajo::Parser to use in place of <include:renderer />
     *
     * @param   $attributes <include:renderer attr1=x attr2=y attr3=z ... />
     *
     * @return  mixed
     * @since   1.0
     */
    public function process($attributes = array())
    {
        /** attributes from <include:renderer */
        $this->attributes = $attributes;

        /** initializes and populates the MVC request */
        $this->_setRenderCriteria();
        if ($this->extension_required === true) {
            if ($this->get('extension_instance_id', 0) == 0) {
                return $this->set('status_found', false);
            }
        }

        /** extension MVC classes are loaded */
        $this->_importClasses();

        /** load Metadata (theme renderer, only) */
        $this->_loadMetadata();

        /** extension language files */
        $this->_loadLanguage();

        /** css and js media files for extension and related entities */
        $this->_loadMedia();

        /** instantiate MVC and render output */
        $this->rendered_output = $this->_invokeMVC();

        /** used by events to update $items, if necessary */
        $this->_postMVCProcessing();

        return $this->rendered_output;
    }

    /**
     * get
     *
     * Returns a property of the Task Request object
     * or the default value if the property is not set.
     *
     * @param   string  $key
     * @param   mixed   $default
     *
     * @since   1.0
     */
    public function get($key, $default = null)
    {
        return $this->task_request->get($key, $default);
    }

    /**
     * set
     *
     * Modifies a property of the Task Request object,
     * creating it if it does not already exist.
     *
     * @param   string  $key
     * @param   mixed   $value
     *
     * @since   1.0
     */
    public function set($key, $value = null)
    {
        //echo 'Set '.$key.' '.$value.'<br />';
        return $this->task_request->set($key, $value);
    }

    /**
     * _setRenderCriteria
     *
     * Initialize the request object for MVC values
     *
     * @return  bool
     * @since   1.0
     */
    protected function _setRenderCriteria()
    {
        /** creates mvc object and initializes settings */
        $this->_initializeRequest();

        /** establish values needed for MVC */
        $this->_getAttributes();

        /** retrieves extension and populates related mvc object values */
        $this->_getExtension();

        if ($this->extension_required === true) {
            if ($this->get('extension_instance_id', 0) == 0) {
                return $this->set('status_found', false);
            }
        }

        /** retrieves MVC defaults for application */
        $this->_getApplicationDefaults();

        /** lazy load paths for view files */
        $this->_setPaths();

        return $this->set('status_found', true);
    }

    /**
     * _initializeRequest
     *
     * Initialize the request object for MVC values
     *
     * @return  null
     * @since   1.0
     */
    protected function _initializeRequest()
    {
        $this->task_request = new Registry();

        /** extension */
        $this->set('extension_instance_id', 0);
        $this->set('extension_instance_name', '');
        $this->set('extension_asset_type_id', 0);
        $this->set('extension_asset_id', 0);
        $this->set('extension_view_group_id', 0);
        $this->set('extension_custom_fields', array());
        $this->set('extension_metadata', array());
        $this->set('extension_parameters', array());
        $this->set('extension_path', '');
        $this->set('extension_type', $this->name);
        if ($this->type == 'request') {
            $this->set('extension_primary', true);
        } else {
            $this->set('extension_primary', false);
        }
        $this->set('extension_folder', '');
        $this->set('extension_event_type', '');

        /** view */
        $this->set('template_view_id', 0);
        $this->set('template_view_name', '');
        $this->set('template_view_css_id', '');
        $this->set('template_view_css_class', '');
        $this->set('template_view_asset_type_id',
            MOLAJO_ASSET_TYPE_EXTENSION_TEMPLATE_VIEW);
        $this->set('template_view_asset_id', 0);
        $this->set('template_view_path', '');
        $this->set('template_view_path_url', '');

        /** wrap */
        $this->set('wrap_view_id', 0);
        $this->set('wrap_view_name', '');
        $this->set('wrap_view_css_id', '');
        $this->set('wrap_view_css_class', '');
        $this->set('wrap_view_asset_type_id',
            MOLAJO_ASSET_TYPE_EXTENSION_WRAP_VIEW);
        $this->set('wrap_view_asset_id', 0);
        $this->set('wrap_view_path', '');
        $this->set('wrap_view_path_url', '');

        /** mvc parameters */
        $this->set('controller', '');
        $this->set('task', '');
        $this->set('model', '');
        $this->set('table', '');
        $this->set('id', 0);
        $this->set('category_id', 0);
        $this->set('suppress_no_results', false);

        /** for event processing renderers, only */
        $this->set('event_items', 0);

        return;
    }

    /**
     * _getAttributes
     *
     *  Retrieve request information needed to execute extension
     *
     * @return  bool
     * @since   1.0
     */
    protected function _getAttributes()
    {
        foreach ($this->attributes as $name => $value) {

            if ($name == 'name'
                || $name == 'title'
            ) {
                $this->set('extension_instance_name', $value);


            } else if ($name == 'tag') {
                $this->tag = $value;


            } else if ($name == 'template') {
                $this->set('template_view_name', $value);

            } else if ($name == 'template_view_css_id'
                || $name == 'template_view_id'
            ) {
                $this->set('template_view_css_id', $value);

            } else if ($name == 'template_view_css_class'
                || $name == 'view_class'
            ) {
                $this->set('template_view_css_class', $value);


            } else if ($name == 'wrap') {
                $this->set('wrap_view_name', $value);

            } else if ($name == 'wrap_view_css_id'
                || $name == 'wrap_view_id'
            ) {
                $this->set('wrap_view_css_id', $value);

            } else if ($name == 'wrap_view_css_class'
                || $name == 'wrap_view_class'
            ) {
                $this->set('wrap_view_css_class', $value);
            }
            //todo: amy merge other parameters into $this->parameters $this->set('other_parameters') = $other_parameters;
        }

        return true;
    }

    /**
     * _getExtension
     *
     * Retrieve extension information using either the ID or the name
     *
     * @return bool
     * @since 1.0
     */
    protected function _getExtension()
    {
        $results = ExtensionHelper::getExtensionRequestObject($this->task_request);

        if ($results === false) {
            return false;
        }

        $this->task_request = $results;
        return $this->set('status_found', true);
    }

    /**
     *  _getApplicationDefaults
     *
     *  Retrieve default values, if not provided by extension
     *
     * @return  bool
     * @since   1.0
     */
    protected function _getApplicationDefaults()
    {
        if ((int)$this->get('template_view_id', 0) == 0) {
            $this->set('template_view_id',
                ViewHelper::getViewDefaults('view',
                    $this->get('model'),
                    $this->get('task', ''),
                    (int)$this->get('id', 0))
            );
        }

        /** wrap */
        if ((int)$this->get('wrap_view_id', 0) == 0) {
            $this->set('wrap_view_id',
                ViewHelper::getViewDefaults('wrap',
                    $this->get('model'),
                    $this->get('task', ''),
                    (int)$this->get('id', 0))
            );
        }
        return true;
    }

    /**
     *  _setPaths
     *
     *  Lazy load extension files
     *
     * @return  null
     * @since   1.0
     */
    protected function _setPaths()
    {
        /** template view */

        /** retrieve id or name */
        if ((int)$this->get('template_view_id', 0) == 0) {
            $this->set('template_view_id',
                ExtensionHelper::getInstanceID(
                    MOLAJO_ASSET_TYPE_EXTENSION_TEMPLATE_VIEW,
                    $this->get('template_view_name'),
                    'templates'
                )
            );
        } else {
            $this->set('template_view_name',
                ExtensionHelper::getInstanceTitle(
                    $this->get('template_view_id')
                )
            );
        }

        /** retrieve paths */
        $tc = new MolajoViewHelper(
            $this->get('template_view_name'),
            'templates',
            $this->get('extension_instance_name'),
            $this->get('extension_type'),
            ' ',
            $this->get('theme_name')
        );
        $this->set('template_view_path', $tc->view_path);
        $this->set('template_view_path_url', $tc->view_path_url);

        /** wrap view */

        /** retrieve id or name */
        if ((int)$this->get('wrap_view_id', 0) == 0) {
            $this->set('wrap_view_id',
                ExtensionHelper::getInstanceID(
                    MOLAJO_ASSET_TYPE_EXTENSION_WRAP_VIEW,
                    $this->get('wrap_view_name'),
                    'wraps'
                )
            );
        } else {
            $this->set('wrap_view_name',
                ExtensionHelper::getInstanceTitle(
                    $this->get('wrap_view_id')
                )
            );
        }

        /** retrieve paths */
        $wc = new MolajoViewHelper(
            $this->get('wrap_view_name'),
            'wraps',
            $this->get('extension_instance_name'),
            $this->get('extension_type'),
            ' ',
            $this->get('theme_name')
        );
        $this->set('wrap_view_path', $wc->view_path);
        $this->set('wrap_view_path_url', $wc->view_path_url);

        return true;
    }

    /**
     * _importClasses
     *
     * imports extension classes and files
     *
     * @return  null
     * @since   1.0
     */
    protected function _importClasses()
    {
    }

    /**
     * _loadMetadata
     *
     * Theme Renderer use, only
     *
     * @return  null
     * @since   1.0
     */
    protected function _loadMetadata()
    {
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
        return ExtensionHelper::loadLanguage(
            $this->get('extension_path')
        );
    }

    /**
     * _loadMedia
     *
     * Loads Media CSS and JS files for extension and related content
     *
     * @return  null
     * @since   1.0
     */
    protected function _loadMedia()
    {
    }

    /**
     * _invokeMVC
     *
     * Instantiate the Controller and fire off the task, returns rendered output
     *
     * @return mixed
     */
    protected function _invokeMVC()
    {
        /** model */
        $model = (string)$this->_setModel();
        $this->set('model', $model);

        /** controller */
        $cc = (string)$this->_setController();
        $this->set('controller', $cc);

        /** task */
        $task = (string)$this->get('task', 'display');
        $this->set('task', $task);

        $this->_verifyMVC(true);

        /** verify all values required are available */
        if ($this->get('status_found') === false) {
            return $this->get('status_found');
        }

        /** instantiate controller  */
        $controller = new $cc($this->task_request, $this->parameters);

        /** execute task: display, edit, or add  */
        return $controller->$task();
    }

    /**
     * _setModel
     *
     * Set the name of the Model
     *
     * @return  string
     * @since   1.0
     */
    protected function _setModel()
    {
        /** 1. Specifically Named Model */
        if ($this->get('model', '') == '') {
        } else {
            $mc = (string)ucfirst($this->get('model'));
            if (class_exists($mc)) {
                return $mc;
            }
        }

        /** 2. Extension Name (+ non-component name, if appropriate) + Model */
        $mc = (string)ucfirst($this->get('extension_instance_name'));
        $mc = str_replace(array('-', '_'), '', $mc);

        if ($this->get('extension_type') == 'component') {
        } else {
            $mc .= ucfirst(trim($this->get('extension_type', 'Module')));
        }
        $mc .= 'Model';
        if (class_exists($mc)) {
            return $mc;
        }

        /** 3. Molajo + Task Name + Model */
        if ($this->get('model', '') == '') {
        } else {
            $mc = 'Molajo' .
                (string)ucfirst(strtolower($this->get('model')) .
                        'Model'
                );
            if (class_exists($mc)) {
                return $mc;
            }
        }

        /** 4. Base Class (no query) */
        return 'MolajoModel';
    }

    /**
     * _setController
     *
     * Set the name of the Controller
     *
     * @return  string
     * @since   1.0
     */
    protected function _setController()
    {
        /** 1. Specifically Named Controller */
        if ($this->get('controller', '') == '') {
        } else {
            $cc = (string)ucfirst($this->get('controller'));
            if (class_exists($cc)) {
                return $cc;
            }
        }

        /** 2. Extension Name (+ Module, if appropriate) + Controller */
        $cc = (string)ucfirst($this->get('extension_instance_name'));
        $cc = str_replace(array('-', '_'), '', $cc);
        if ($this->get('extension_type') == 'component') {
        } else {
            $cc .= ucfirst(trim($this->get('extension_type', 'Module')));
        }
        $cc .= 'Controller';
        if (class_exists($cc)) {
            return $cc;
        }

        /** 3. Molajo + Task Name + Controller */
        if ($this->get('controller', '') == '') {
            $cc = 'Molajo' .
                (string)ucfirst(strtolower($this->get('task', 'display')) .
                        'Controller'
                );
            if (class_exists($cc)) {
                return $cc;
            }
        }

        /** 4. Base Class (no query) */
        return 'MolajoController';
    }

    /**
     * _verifyMVC
     * @return bool
     */
    protected function _verifyMVC($onlyErrors = true)
    {
        $test1 = class_exists($this->get('controller'));
        $test2 = method_exists($this->get('controller'), $this->get('task'));
        $test3 = class_exists($this->get('controller'));
        if ($this->get('template_view_id', 0) == 0) {
            $test4 = 0;
        } else {
            $test4 = 1;
        }
        if ($this->get('wrap_view_id', 0) == 0) {
            $test5 = 0;
        } else {
            $test5 = 1;
        }


        if (($test1 + $test2 + $test3 + $test4 + $test5) < 5) {
            $this->set('status_found', false);
        }

        if (($this->get('status_found') === false)
            || $onlyErrors === false
        ) {
            echo 'Error Count: ' . (5 - ($test1 + $test2 + $test3 + $test4 + $test5)) . '<br />';
            echo 'Controller ' . $this->get('controller') . '<br />';
            echo 'Model ' . $this->get('model') . '<br />';
            echo 'Task ' . $this->get('task') . '<br />';
            echo 'Template View ' . $this->get('template_view_id') . '<br />';
            echo 'Wrap View ' . $this->get('wrap_view_id') . '<br />';
            echo '<pre>';
            var_dump($this->task_request);
            echo '</pre>';
        }
    }

    /**
     * _postMVCProcessing
     * @return bool
     */
    protected function _postMVCProcessing ()
    {

    }
}
