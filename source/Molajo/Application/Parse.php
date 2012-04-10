<?php
/**
 * @package   Molajo
 * @copyright 2012 Amy Stephen. All rights reserved.
 * @license   GNU General Public License Version 2, or later http://www.gnu.org/licenses/gpl.html
 */
namespace Molajo\Application;

defined('MOLAJO') or die;

/**
 * Parse
 *
 * @package     Molajo
 * @subpackage  Parse
 * @since       1.0
 */
Class Parse extends Molajo
{
    /**
     * $instance
     *
     * Parse static instance
     *
     * @var    object
     * @since  1.0
     */
    protected static $instance;

    /**
     * $sequence
     *
     * System defined order for processing includes
     * stored in the sequence.xml file
     *
     * @var array
     * @since 1.0
     */
    protected $sequence = array();

    /**
     * $final
     *
     * Indicator of final processing for includes
     * (which means clean up of unfound includes can take place)
     *
     * @var boolean
     * @since 1.0
     */
    protected $final = false;

    /**
     * $include_request
     *
     * Include Statement Includer requests extracted from the
     * theme (initially) and then the rendered output
     *
     * @var array
     * @since 1.0
     */
    protected $include_request = array();

    /**
     * $rendered_output
     *
     * Collects output rendered by MVC after Includer Processing
     *
     * @var string
     * @since 1.0
     */
    protected $rendered_output = array();

    /**
     * $includes
     *
     * Parsing process retrieves include statements from the theme and rendered output
     *
     * @var string
     * @since 1.0
     */
    protected $includes = array();

    /**
     * getInstance
     *
     * @return bool|object
     * @since  1.0
     */
    public static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new Parse();
        }
        return self::$instance;
    }

    /**
     * __construct
     *
     * @return boolean
     * @since  1.0
     */
    public function __construct()
    {
        return $this;
    }

    /**
     * process
     *
     * Load sequence.xml file contents into array for determining processing order
     *
     * Invoke Theme Includer to load page metadata, and theme language and media resources
     *
     * Retrieve Theme and Page View to initiate the iterative process of parsing rendered output
     * for <include:type/> statements and then looping through all include requests
     *
     * When no more <include:type/> statements are found in the rendered output,
     * process sets the Responder body and completes
     *
     * @param     null     $sequenceXML
     * @param     null     $finalXML
     * @return     string
     * @since     1.0
     */
    public function process($sequenceXML = null, $finalXML = null)
    {
        /**
         *  Body Includers: processed recursively until no more <include: found
         *      for the set of includes defined in the includes-page.xml
         */
        $formatXML = '';
        if (Services::Filesystem()->fileExists($sequenceXML)) {
            $formatXML = $sequenceXML;
        } else {
            $formatXML = MOLAJO_CONFIGURATION_FOLDER. '/includes-page.xml';
        }

        if (Services::Filesystem()->fileExists($formatXML)) {
        } else {
            //error
            return false;
        }

        $sequence = simplexml_load_file($formatXML);
        foreach ($sequence->include as $next) {
            $this->sequence[] = (string)$next;
        }

        /** Theme Parameters */
        Services::Registry()->create('theme');

        $themeParameters = Services::Registry()->get('Request\\theme_parameters');
        var_dump($themeParameters);
        die;
        Services::Registry()->loadArray('theme',
            array(
                'theme' => Services::Registry()->get('Request\\theme_name'),
                'theme_path' => Services::Registry()->get('Request\\theme_path') . '/' . 'index.php',
                'page' => Services::Registry()->get('Request\\page_view_include'),
                'parameters' => Services::Registry()->get('Request\\theme_parameters')
            )
        );
        echo 'done';
        $helperFile = Services::Registry()->get('Request\\theme_path')
            . '/helpers/theme.php';

        if (file_exists($helperFile)) {
            require_once $helperFile;

            $helperClass = 'Molajo' .
                ucfirst(Services::Registry()->get('Request\\theme_name'))
                . 'ThemeHelper';
        }

        /** Before Event */
        // Services::Dispatcher()->notify('onBeforeRender');

        $this->final = false;
        $body = $this->_renderLoop();

        /**
         *  Final Includers: Now, the theme, head, messages, and defer includes run
         *      and any cleanup of unfound <include values can take place
         */
        $formatXML = '';
        if (Services::Filesystem()->fileExists($finalXML)) {
            $formatXML = $finalXML;
        } else {
            $formatXML = MOLAJO_CONFIGURATION_FOLDER . '/includes-final.xml';
        }
        if (Services::Filesystem()->fileExists($formatXML)) {
        } else {
            //error
            return false;
        }

        $this->sequence = array();
        $sequence = simplexml_load_file($formatXML);
        foreach ($sequence->include as $next) {
            if ($next == 'message') {
                $messages = Services::Message()->get();
                if (count($messages) == 0) {
                } else {
                    $this->sequence[] = (string)$next;
                }
            } else {
                $this->sequence[] = (string)$next;
            }
        }

        /** theme: load template media and language files */
        if (class_exists('IncluderTheme')) {
            $rc = new IncluderTheme ('theme');
            $results = $rc->process();

        } else {
            echo 'failed include = ' . 'IncluderTheme' . '<br />';
            // ERROR
        }

        $this->final = true;
        $body = $this->_renderLoop($body);

        /** after rendering */
        //        Services::Dispatcher()->notify('onAfterRender');

        return $body;
    }

    /**
     *  _renderLoop
     *
     * Parse the Theme and Page View, and then rendered output, for
     *  <include:type statements
     *
     * @return string  $body     Rendered output for the Response Head and Body
     * @since  1.0
     */
    protected function _renderLoop($body = null)
    {
        /** initial run: start with theme and page */
        if ($body == null) {
            ob_start();
            require $this->parameters->get('theme_path');
            $this->rendered_output = ob_get_contents();
            ob_end_clean();
        } else {
            /* final run (for page head): start with rendered body */
            $this->rendered_output = $body;
        }

        /** process all input for include: statements  */
        $complete = false;
        $loop = 0;
        while ($complete === false) {

            $loop++;

            $this->_parseIncludeRequests();

            if (count($this->include_request) == 0) {
                break;
            } else {
                $this->rendered_output = $this->_callIncluder();
            }

            if ($loop > MOLAJO_STOP_LOOP) {
                break;
            }
            continue;
        }
        return $this->rendered_output;
    }

    /**
     * _parseIncludeRequests
     *
     * Parse the theme (first) and then rendered output (subsequent calls)
     * in search of include statements
     *
     * @return  array
     * @since   1.0
     */
    protected function _parseIncludeRequests()
    {
        $matches = array();
        $this->include_request = array();
        $i = 0;

        preg_match_all('#<include:(.*)\/>#iU',
            $this->rendered_output,
            $matches
        );

        if (count($matches) == 0) {
            return;
        }

        foreach ($matches[1] as $includeStatement) {

            $parts = array();
            $parts = explode(' ', $includeStatement);
            $includerType = '';

            foreach ($parts as $part) {

                /** 1st part is the Includer Command */
                if ($includerType == '') {
                    $includerType = $part;
                    $this->include_request[$i]['name'] = $includerType;
                    $this->include_request[$i]['replace'] = $includeStatement;

                    /** Includer Attributes */
                } else {
                    $attributes = str_replace('"', '', $part);

                    if (trim($attributes) == '') {
                    } else {

                        /** Associative array of attributes */
                        $pair = array();
                        $pair = explode('=', $attributes);
                        $this->include_request[$i]['attributes'][$pair[0]] = $pair[1];
                    }
                }
            }
            $i++;
        }
    }

    /**
     * _callIncluder
     *
     * Invoke extension-specific includer for include statement
     *
     * @return  string rendered output
     * @since   1.0
     */
    protected function _callIncluder()
    {
        $replace = array();
        $with = array();

        /** 1. process extension includers in order defined by sequence.xml */
        foreach ($this->sequence as $sequence) {

            /** 2. if necessary, split includer name and type     */
            /** (ex. request:component and defer:head)            */
            if (stripos($sequence, ':')) {
                $includeName = substr($sequence, 0, strpos($sequence, ':'));
                $includerType = substr($sequence, strpos($sequence, ':') + 1, 999);
            } else {
                $includeName = $sequence;
                $includerType = $sequence;
            }

            /** 3. loop thru parsed include requests for match */
            for ($i = 0; $i < count($this->include_request); $i++) {

                $parsedRequests = $this->include_request[$i];

                if ($includeName == $parsedRequests['name']) {

                    /** 4. place attribute pairs into variable */
                    if (isset($parsedRequests['attributes'])) {
                        $attributes = $parsedRequests['attributes'];
                    } else {
                        $attributes = array();
                    }

                    /** 5. store the "replace this" value */
                    $replace[] = "<include:" . $parsedRequests['replace'] . "/>";

                    /** 6. call the includer class */
                    $class = 'Includer' . ucfirst($includerType);
                    if (class_exists($class)) {
                        $rc = new $class ($includerType, $includeName);
                    } else {
                        echo 'failed includer = ' . $class . '<br />';
                        die;
                        // ERROR
                    }

                    /** 7. render output and store results as "replace with" */
                    $with[] = $rc->process($attributes);
                }
            }
        }

        /** 8. replace it */
        $this->rendered_output = str_replace($replace, $with, $this->rendered_output);

        /** 9. make certain all <include:xxx /> literals are removed on final */
        if ($this->final === true) {
            $replace = array();
            $with = array();
            for ($i = 0; $i < count($this->include_request); $i++) {
                $replace[] = "<include:" . $this->include_request[$i]['replace'] . "/>";
                $with[] = '';
            }

            $this->rendered_output = str_replace($replace, $with, $this->rendered_output);
        }

        return $this->rendered_output;
    }
}
