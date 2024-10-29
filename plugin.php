<?php
        /*
        Plugin Name: ActiveTrail Free Email Signup Form 
        Plugin URI: http://www.eshel.us/wordpress/activetrail-free-email-signup-form
        Description: ActiveTrail.com integration
        Version: 1.4
        Author: ActiveTrail.com
        Author URI: ActiveTrail.com
        Author Email: support@activetrail.com
        Contributors: mosheeshel, Activetrail
        Donate link: http://www.eshel.us/wordpress-donate/?plugin=activetrail-free-email-signup-form
        Tags: email, mailchimp, marketing, newsletter, plugin, signup, widget, activetrail, campaign, template, form, subscribe
        Requires at least: 3.0
        Tested up to: 3.5.1
        Stable tag: 1.4
        License: GPLv2
        */
    
    
        define('ACTIVE_TRAIL_NAME', 'activetrail-free-email-signup-form');
        define('ACTIVE_TRAIL_MENU', ACTIVE_TRAIL_NAME.'-general');
    
        /**
         * Dir constants
         */
        define('ACTIVE_TRAIL_DIR', dirname( __FILE__ ));
        define('ACTIVE_TRAIL_LOG_DIR', ACTIVE_TRAIL_DIR.'/logs');
        define('ACTIVE_TRAIL_LIB_DIR', ACTIVE_TRAIL_DIR.'/lib');
        define('ACTIVE_TRAIL_VIEWS_DIR', ACTIVE_TRAIL_DIR.'/views');
        define('ACTIVE_TRAIL_WIDGETS_DIR', ACTIVE_TRAIL_VIEWS_DIR.'/widgets');
        define('ACTIVE_TRAIL_LAYOUT_DIR', ACTIVE_TRAIL_VIEWS_DIR.'/layouts');
        define('ACTIVE_TRAIL_SECTIONS_DIR', ACTIVE_TRAIL_VIEWS_DIR.'/sections');
        define('ACTIVE_TRAIL_SHORTCODES_DIR', ACTIVE_TRAIL_VIEWS_DIR.'/shortcodes');
    
        /** Path to register link to activetrail.com **/
        define('ACTIVE_TRAIL_REGISTER_URI', 'http://www.activetrail.com/create-account/?utm_source=wp-plugin-install&utm_medium=wpplugin&utm_content=join-active&utm_campaign=eshel');
        /** Cache expire for groups - 3600 = hour, 86400 = day etc **/
       /* define('ACTIVE_TRAIL_CACHE_GROUPS', 86400);*/
       define('ACTIVE_TRAIL_CACHE_GROUPS', 30);
    
    
        class Active_Trail_Plugin {
    
            const PLUGIN = ACTIVE_TRAIL_NAME;
    
            public static $FORM_ID = 0;
            public static $NONCE_ACTION;
            public static $NONCE_FIELD;
            public static $PAGES = array();
            public static $LAYOUTS = array();
            public static $SESSION_SUBMIT_KEY = 'do_submit_signup_form';
    
            /**
             * @var bool
             */
            protected static $_CLIENT = FALSE;
    
            /**
             * @var Active_Trail
             * Singleton instance of Active_Trail class
             */
            protected static $_CLIENT_INSTANCE = FALSE;
    
            /**
             * Is the plugin configured?
             * @var bool
             */
            protected static $_CONFIGURED = FALSE;
    
            /**
             * @var array
             * Logger
             */
            protected static $_LOG = array();
    
            /**
             * @var bool
             * Checks if the user turned on logging
             */
            protected static $_LOGGING_ACTIVE = FALSE;
    
            /**
             * Shared config view data 
             */
            public $config_data = array();
    
            /**
             * Configures all needed options for plugin
             */
            public static function configure()
            {
                if ( ! self::$_CONFIGURED)
                {
                    self::do_action('before_configure');
    
                    self::$_CONFIGURED = TRUE;
    
                    self::$NONCE_ACTION = self::PLUGIN.'-NONCE';
    
                    self::$_LOGGING_ACTIVE = (bool) self::get_option('logging');
    
                    self::$PAGES = array
                    (
                       'general' => __('ActiveTrail Account', 'atl'),
                       //'signup_templates' => __('Signup Templates', 'atl'),
                       //'sync' => __('Groups', 'atl'),
                       //'logs' => __('Logs', 'atl'),
                       'shortcodes' => __('Create Signup Form', 'atl'),
                       'faq' => __('FAQ', 'atl'),
                    );
    
                    self::$LAYOUTS = array
                    (
                       1 => array
                       (
                           'label' => __('Only E-mail, label above', 'atl'),
                           'view' => ACTIVE_TRAIL_LAYOUT_DIR.'/email_block.php',
                       ),
                       2 => array
                       (
                           'label' => __('Only E-mail, inline', 'atl'),
                           'view' => ACTIVE_TRAIL_LAYOUT_DIR.'/email_inline.php',
                       ),
                       3 => array
                       (
                           'label' => __('First name, last name, e-mail, all inline', 'atl'),
                           'view' => ACTIVE_TRAIL_LAYOUT_DIR.'/more_fields_inline.php',
                       ),
                       4 => array
                       (
                           'label' => __('First name, last name, e-mail, labels inline', 'atl'),
                           'view' => ACTIVE_TRAIL_LAYOUT_DIR.'/more_fields_horizontal.php',
                       ),
                       5 => array
                       (
                           'label' => __('First name, last name, e-mail, labels above', 'atl'),
                           'view' => ACTIVE_TRAIL_LAYOUT_DIR.'/more_fields_block.php',
                       ),
                       /*
                       6 => array
                       (
                           'label' => __('Only E-mail, no custom style - use theme style', 'atl'),
                           'view' => ACTIVE_TRAIL_LAYOUT_DIR.'/email_block_no_style.php',
                       ),
    
                       7 => array
                       (
                           'label' => __('First name, last name, e-mail, no custom style - use theme style', 'atl'),
                           'view' => ACTIVE_TRAIL_LAYOUT_DIR.'/more_fields_block_no_style.php',
                       ),*/
    
                    );
    
    
    
                    self::do_action('after_configure');
                }
            }
    
            /**
             * Shutdown handler
             */
            public function shutdown()
            {
    
                 if ( ! self::$_LOGGING_ACTIVE) return;
    
                 if (self::$_LOG)
                 {
    
                     $filename = ACTIVE_TRAIL_LOG_DIR.'/'.date('Y-m-d', time()).'.txt';
                     if ( ! file_exists($filename))
                     {
                        $created = @file_put_contents($filename, 'Log file for date: '.date('Y-m-D', time())."\n");
                     }
                     else
                     { $created = TRUE; }
    
                     // cant create file, or file is not writeable
                     if ( ! $created OR ! is_writeable($filename)) return;
    
                     $handle = fopen($filename, 'a');
    
                     fwrite($handle, implode("\n", self::$_LOG));
                     fclose($handle);
    
                 }
    
            }
    
            /**
             * Logs text into the plugin log file
             */
            public static function log($text, $level = 'INFO')
            {
                if ( ! self::$_LOGGING_ACTIVE) return;
    
                if (is_array($text))
                {
                    foreach($text as $t)
                    {
                        self::log($t, $level);
                    }
                }
                else {
    
                    self::$_LOG[] = "LOG [$level] [".date('d.m.y H:i:s', time())." | ".$_SERVER['REQUEST_URI']."]: ".($text)."\n";
                }
    
            }
    
            /**
             * Factory method for Active_Trail
             * @var Active_Trail
             * @return Active_Trail
             */
            public static function new_client($user, $password, $location = "")
            {
                  if ( ! self::$_CLIENT)
                  {
                       require ACTIVE_TRAIL_LIB_DIR.'/ActiveTrail_Class.php';
                       self::$_CLIENT = TRUE;
                  }
    
                  return new Active_Trail($user, $password, $location);
            }
    
            /**
             * Returns singleton of default config for client
             * @var Active_Trail
             * @return Active_Trail
             */
            public static function client()
            {
    
                if ( ! self::$_CLIENT_INSTANCE)
                { 
                    self::$_CLIENT_INSTANCE = self::new_client(self::get_option('user'), self::get_option('password'));
                }
                return self::$_CLIENT_INSTANCE;
            }
    
            /**
             * Returns set of available groups
             * @return mixed
             */
            public static function get_groups()
            {
                $k = 'cached_groups';
                if (isset($_REQUEST['clear_group_cache'])) { self::delete_option($k); }
                 
                $d = self::get_option($k);
                if (!$d OR $d['timestamp'] < time())
                {
                    $data = self::client()->GetGroups();
                    self::set_option($k, array('data' => $data, 'timestamp' => time() + ACTIVE_TRAIL_CACHE_GROUPS));
                }
                else { $data = $d['data']; }
    
                return (array)$data->GetGroupsResult->WebGroup;
            }
    
    
            /**
             * plugins_url namespace
             * @return string
             */
            public static function plugins_url($url)
            {
                return plugins_url(self::PLUGIN.'/'.$url);
            }
    
            /*--------------------------------------------*
             * Constructor
             *--------------------------------------------*/
    
            /**
             * Initializes the plugin by setting localization, filters, and administration functions.
             */
            function __construct() {
    
                self::configure();
                if ( ! session_id())
                {
                    session_start();
                }
    
                load_plugin_textdomain('atl', false, ACTIVE_TRAIL_DIR . '/lang' );
    
                // Register admin styles and scripts
                add_action( 'admin_print_styles', array( &$this, 'register_admin_styles' ) );
                add_action( 'admin_enqueue_scripts', array( &$this, 'register_admin_scripts' ) );
    
                // Register site styles and scripts
                add_action('wp_head', array($this, 'set_js_vars'));
                add_action( 'wp_enqueue_scripts', array( &$this, 'register_plugin_styles' ) );
                add_action( 'wp_enqueue_scripts', array( &$this, 'register_plugin_scripts' ) );
    
                add_action('wp_ajax_active_trail_general', array($this, 'admin_ajax'));
    
                add_action('wp_ajax_active_trail_general_frontend', array($this, 'public_ajax'));
                add_action('wp_ajax_nopriv_active_trail_general_frontend', array($this, 'public_ajax'));
                add_action('shutdown', array($this, 'shutdown'));
    
    
    
    
                add_shortcode(self::PLUGIN.'_signup_form', array($this, 'shortcode_signup_form'));
    
                register_activation_hook( __FILE__, array( &$this, 'activate' ) );
                register_deactivation_hook( __FILE__, array( &$this, 'deactivate' ) );
    
                add_action('init', array($this, 'init'));
    
                if (is_admin())
                {
                     include ACTIVE_TRAIL_LIB_DIR.'/ActiveTHelper.php';
    
                }
    
    
            } // end constructor
    
            /**
             * Init function 
             */
            public function init()
            {
                add_action('admin_menu', array( $this, 'add_admin_pages' ));
                add_action('wp_head', array($this, 'set_js_vars'));
    
                self::do_action('after_init');
            }
    
            /**
             * Adds global js variable setup
             */
            public function set_js_vars()
            {
                $config = array
                (
                    'PLUGIN_NAME' => self::PLUGIN,
                    'BACKEND_AJAX' => admin_url('admin-ajax.php?action=active_trail_general'),
                    'FRONTEND_AJAX' => admin_url('admin-ajax.php?action=active_trail_general_frontend'),
                    'REQUIRED_FIELD' => __('This field is required', 'atl'),
                    'IS_REQUIRED' => __('is required', 'atl')
                );
    
                echo '<script type="text/javascript">
                      _activetrail_config = '.json_encode($config).';
                     </script>';
            }
    
            /**
             * Prints variable contents with <pre></pre> tags
             */
            public static function print_r($data)
            {
                return '<pre>'.print_r($data, true).'</pre>';
            }
    
            /**
             * For later use
             */	
            public function public_ajax()
            {
    
                if ( ! session_id())
                {
                    session_start();
                }
    
                $data = array();
    
                if ($_POST)
                {
                    $signup = self::post_data('form_id');
                    if ($signup['signup'])
                    {
                        $attrs = $_SESSION[self::$SESSION_SUBMIT_KEY][$signup];
                        $data['html'] = false;
                        if ($attrs)
                        {
                            $html = self::do_signup_form($attrs['attrs'] + array('__ajax_submit' => $signup), $attrs['content']);
                            $data['html'] = $html;
                        }
    
    
                    }
    
                }
    
                exit(json_encode($data));
            }
    
            /**
             * For later use 
             */	
            public function admin_ajax()
            {
                 if ($_POST['shortcodes'])
                 {
                     $this->config_page_shortcodes();
                 }
                 exit;
            }
    
            /**
             * Returns file timestamp from plugin directory
             */
            public function filemtime($file)
            {
                return filemtime(ACTIVE_TRAIL_DIR.'/'.$file);
            }
    
            /**
             * Hook for adding admin pages
             */
            public function add_admin_pages()
            {
                 if ( function_exists('add_submenu_page') ) {
    
                    add_menu_page('ActiveTrail', 'ActiveTrail', 'manage_options', ACTIVE_TRAIL_MENU, array($this, 'config_page'));
    
                    $pages = self::$PAGES;
                    unset($pages['general']);
    
                    foreach ($pages as $k => $t)
                    {
                        add_submenu_page(ACTIVE_TRAIL_MENU,
                          $t,
                          $t, 
                          'manage_options', 
                          self::get_page($k), 
                          array( $this, 'config_page_'.$k)); 
    
                    }
    
                 }
            }
    
            /**
             * Builds segment for plugin namespace
             */
            public static function get_page($segment)
            {
                return self::PLUGIN.'-'.$segment;
            }
    
            /**
             * Wrapper function for admin_url under plugin namespace
             */
            public static function admin_url($segment)
            {
                return admin_url('admin.php?page='.self::get_page($segment));
            }
    
            /**
             * Subpages
             */
            public function config_page_signup_templates()
            {
                  $this->config_page($_GET['page']);
            }
    
            public function config_page_sync()
            {
    
                  $this->config_page($_GET['page']);
            }
    
            public function config_page_faq()
            {
                  $this->config_page($_GET['page']);
            }
    
            public function config_page_logs()
            {
                  $this->config_page($_GET['page']);
            }
    
            /**
             * Subpages
             */
            public function config_page_shortcodes()
            {
                  $this->config_data['groups'] = $groups =  Active_Trail_Plugin::get_groups();
                  if ($_POST)
                  {
                     $_SESSION[$session_key] = array();
    
                     $wr = self::grab_shortcode_defaults($_POST);
                     $success = esc_attr(@$wr['msg_success_general']);
                     $error = esc_attr(@$wr['msg_error_general']);
    
                     $gids = $_POST['group_id'];
                     if ( ! $gids)
                     {
                        //$t = current($groups);
                        $gids = array(-1);
                     }
    
                     $this->config_data['scode'] = '['.self::shortcode('signup_form').' msg_error_general="'.$error.'"  msg_success_general="'.$success.'" ajax="'.(isset($_POST['ajax']) ? 0 : 1).'" verified="'.($_POST['as_verified'] ? '1' : '0').'" button_text="'.esc_html($_POST['button_txt']).'" group="'.implode(',', (array) $gids).'" layout="'.$_POST['layout'].'" name="'.esc_html($_POST['title']).'"]'.esc_html($_POST['text']).'[/'.self::shortcode('signup_form').']';
                  }
    
                  $this->config_page($_GET['page']);
            }
    
            /**
             * Checks if user submitted a form with correct nonce (if verify_nonce is true)
             * @return bool
             */
            public function is_post($verify_nonce = TRUE)
            {
                    if ($_POST)
                    {
                        if ($verify_nonce)
                        {
                            if ($this->check_nonce($_POST))
                            {
                                return TRUE;
                            }
                        }
                        else
                        {
                            return TRUE;
                        }
                    }
    
                    return FALSE;
            }
    
            /**
             * Base config page
             */ 
            public function config_page($section = NULL)
            {
    
                 if ( ! $section)
                 {
                    $section = 'general';
                 }
                 else {
    
                    $section = str_replace(self::PLUGIN.'-', '', $section);
                 }
    
                 $creds = array
                 (
                    'user' => self::get_option('user'),
                    'password' => self::get_option('password')
                 );
    
                 $logging = self::get_option('logging');
    
                 if ($this->is_post() AND $_POST['general'])
                 {
                     if ($_POST['save_data']) 
                     {
    
                         self::set_option('user', $u = $_POST['user']);
                         self::set_option('password', $p = $_POST['password']);
                         self::set_option('logging', (int) isset($_POST['logging']));
                         $creds = $_POST;
                         $success = array(__('Configuration saved.', 'atl'), 'success');
    
                         $cls = self::client()->login_success;
                         
                         if ($cls)
                         {
                            $success = array(__('Login <b>success</b>. Configuration is done!', 'atl'), 'success');
                         }
                         else
                         {
                            $success = array(__('Login <b>failed</b>. It seems that your username/password combination is not correct. Please try again.', 'atl'), 'error');
                         }
                     }
                 }
    
                 $titles = self::$PAGES;
                 $title = $titles[$section];
    
                 echo self::render('admin', array('data' => get_defined_vars()) + $this->config_data);
            }
    
    
            /**
             * Renders plugin view
             * @return void or string depends on echo bool
             */
            public static function render($file, $data = NULL, $echo = TRUE)
            {
                if ($data)
                extract($data);
    
                if ( ! strpos($file, '.php'))
                {
                    $file .= '.php';
                }
    
                if ($echo)
                {
                    ob_start();
                }
    
                include(ACTIVE_TRAIL_DIR.'/views/'.$file);
    
                if ($echo)
                {
                    return ob_get_clean();
                }
    
            }
    
            /**
             * Fired when the plugin is activated.
             *
             * @params	$network_wide	True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog 
             */
            public function activate( $network_wide ) {
    
            } // end activate
    
            /**
             * Fired when the plugin is deactivated.
             *
             * @params	$network_wide	True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog 
             */
            public function deactivate( $network_wide ) {
    
            } // end deactivate
    
            /**
             * Registers and enqueues admin-specific styles.
             */
            public function register_admin_styles() {
    
                $f = 'css/admin.css';
                wp_register_style(self::PLUGIN.'-admin-styles', self::plugins_url($f.'?v='.self::filemtime($f)) );
                wp_enqueue_style(self::PLUGIN.'-admin-styles' );
    
            } // end register_admin_styles
    
            /**
             * Registers and enqueues admin-specific JavaScript.
             */	
            public function register_admin_scripts() {
    
                $f = 'js/admin.js';
                $f2 = 'css/btrap/js/bootstrap.min.js';
    
                wp_register_script($general = self::PLUGIN.'-admin-script', self::plugins_url($f.'?v='.self::filemtime($f)) );
                wp_register_script($btrap = self::PLUGIN.'-admin-btrapjs', self::plugins_url($f2.'?v='.self::filemtime($f2)) );
    
                wp_enqueue_script($general);
                wp_enqueue_script($btrap);
    
            } // end register_admin_scripts
    
            /**
             * Registers and enqueues plugin-specific styles.
             */
            public function register_plugin_styles() {
    
                $f = 'css/display.css'; 
    
                wp_register_style(self::PLUGIN.'-plugin-styles', self::plugins_url($f.'?v='.self::filemtime($f)) );
                wp_enqueue_style(self::PLUGIN.'-plugin-styles' );
    
                $current_theme_stylesheet_url = get_bloginfo('stylesheet_directory');
                $custom_url = $current_theme_stylesheet_url.'/activetrail-custom.css';
                $current_theme_stylesheet_dir = get_stylesheet_directory();
                $custom = $current_theme_stylesheet_dir.'/activetrail-custom.css';
    
    
                if ( file_exists($custom))
                {
                    wp_register_style(self::PLUGIN.'-plugin-styles-custom-css', $custom_url.'?v='.filemtime($custom) );
                    wp_enqueue_style(self::PLUGIN.'-plugin-styles-custom-css');
                }
    
            } // end register_plugin_styles
    
            /**
             * Registers and enqueues plugin-specific scripts.
             */
            public function register_plugin_scripts() {
    
                $f = 'js/display.js';
                wp_register_script(self::PLUGIN.'-plugin-script', self::plugins_url($f.'?v='.self::filemtime($f)) );
                wp_enqueue_script(self::PLUGIN.'-plugin-script' );
    
            } // end register_plugin_scripts
    
            /*--------------------------------------------*
             * Core Functions
             *---------------------------------------------*/
    
            /**
             * Sets option under plugin namespace
             */ 
            public static function set_option($key, $value)
            {
                return update_option(self::PLUGIN.'_'.$key, $value);
            }
    
            /**
             * deletes option under plugin namespace
             */
            public static function delete_option($key)
            {
                return delete_option(self::PLUGIN.'_'.$key);
            }
    
            /**
             * Gets option under plugin namespace
             */
            public static function get_option($key)
            {
                return get_option(self::PLUGIN.'_'.$key);
            }
    
            /**
             * Checks if the nonce is valid
             */
            public static function check_nonce($post)
            {
                return wp_verify_nonce(@$post['_n'], self::$NONCE_ACTION);
            }
    
            /**
             * Gets security nonce for forms
             */
            public static function get_nonce()
            {
                if (self::$NONCE_FIELD)
                {
                    return self::$NONCE_FIELD;
                }
    
                return self::$NONCE_FIELD = wp_nonce_field(self::$NONCE_ACTION, "_n", true , FALSE);
            }
    
            /**
             * Namespace for do_action
             */
            public static function do_action($action, $args = array())
            {
    
                  do_action(self::PLUGIN.'_'.$action, $args);
                  self::log(self::PLUGIN.'_'.$action);
            }
    
            /**
             * Returns shortcode name under plugin namespace
             */
            public static function shortcode($name)
            {
                return self::PLUGIN.'_'.$name;
            }
    
            /**
             * Converts multidimensional array into stdClass object
             */
            public static function arrayToStdClass($array) {
                if(!is_array($array)) {
                    return $array;
                }
    
                $object = new stdClass();
                if (is_array($array) && count($array) > 0) {
                  foreach ($array as $name=>$value) {
                     $name =  (trim($name));
                     if (!empty($name)) {
                        $object->$name = self::arrayToStdClass($value);
                     }
                  }
                  return $object; 
                }
                else {
                  return FALSE;
                }
            }
    
            /**
             * Handles signup form submission
             * @return array response, is_subscribed?
             */
            public static function do_submit_signup_form()
            {
                if ( ! session_id())
                {
                    session_start();
                }
    
                $grab = self::post_data(array('email', 'form_id', 'signup', 'first_name', 'last_name'));
    
                $session_key = self::$SESSION_SUBMIT_KEY;
                $config = @$_SESSION[$session_key][$grab['form_id']];

                $errors = array();
                $success = array();
                $is_error = 0;
                $subscribed = false;
    
                if ( ! $config)
                return array('response' => $result, 'no_config' => 1, 'is_error' => $is_error, 'success' => $success, 'errors' => $errors, 'status' => $subscribed);
               
                $resultArray = array();
                $attrs = $config['attrs'];
                $grpArr = explode(',', $attrs['group']);
                $checkEmailExist = TRUE;
                foreach($grpArr as  $index => $value)
                {           
                    $resultArray = self::do_submit_signup_form_helper($grab,$session_key ,$config,$errors,$success,$is_error,$subscribed,$attrs,$value,$checkEmailExist);
                    if ($resultArray['is_error'])
                        break;
                    $checkEmailExist  = FALSE;
                }
                $checkEmailExist = TRUE;

                return $resultArray;
            }
    
            public static function do_submit_signup_form_helper($grab,$session_key ,$config,$errors,$success,$is_error,$subscribed,$attrs,$value,$checkEmailExist)
            {                   
                self::do_action('before_submit_signup_form');
                $result = FALSE;

                if ($_POST)
                {
                    if ($grab['signup'])
                    {
    
                        if ( ! is_email($grab['email']))
                        {
                            $errors['email'] = __('Not a valid e-mail address.', 'atl');
                        }
                        else
                        {
                            $data = new stdClass();
                            $data->email = $grab['email'];
    
                            $is_subscribed = self::client()->GetCustomerByEmail($data);
    
                            if (is_object($is_subscribed) && $checkEmailExist)
                            {
                                if (isset($is_subscribed->GetCustomerByEmailResult))
                                {
                                    if ($is_subscribed->GetCustomerByEmailResult->Email)
                                    {
                                        $errors['email'] = __('This e-mail address already exists on our lists.', 'atl');
                                    }
                                }
                            }
                        }
    
    
                        if (empty($errors))
                        {
                            $grpValue = array();
                            $grpValue['int'] = $value;
                                //$grpArr = explode(',', $attrs['group']);
                                $data = array
                                (
                                    'webcustomer' => array
                                    (
                                            'Email' => $grab['email'],
                                    ),
                                    // 'groups' =>  $sendGroup, //$sendGroup, //array($attrs['group'], 'int'),//)1,2),                            
                                    'groups' =>  $grpValue,
                                    'mailinglists' => array(0)
                                );
    
                                if ($f = $grab['first_name'])
                                {
                                    $data['webcustomer']['FirstName'] = $f;
                                }
    
                                if ($f = $grab['last_name'])
                                {
                                    $data['webcustomer']['LastName'] = $f;
                                }
                                                                                                  
                            $data = self::arrayToStdClass($data);
                                
                            if ($attrs['verified'])
                            {
                                $result = self::client()->ImportCustomerAsConfirmed($data);   
                            }
                            else
                            {                                  
                                $result = self::client()->ImportCustomer($data);
                            }
                        }
                    }
                }
    
                self::do_action('after_submit_signup_form');
    
                $subscribed = NULL;
    
                if (is_object($result))
                {
    
                    if ($res = $result->ImportCustomerAsConfirmedResult)
                    {
    
                    }
                    else 
                    {
                        $res = $result->ImportCustomerResult;
                    }
    
                    if ($res AND $res->Result == 'success')
                    {
                        $subscribed = TRUE;
                        $success['email'] = __('You have successfully subscribed to our list.', 'atl');
                        if ($attrs['msg_success_general'])
                        {
                            $success['email'] = $attrs['msg_success_general'];
                        }
    
                    } else {
                        $subscribed = FALSE; 
                        self::log(print_r($result, true), 'ERROR');
                        $errors['email'] = ($config['msg_error_general'] ? $config['msg_error_general'] : __('We could not insert you to our mailing list because of server error. Please contact our support or try again later.', 'atl')); }
    
                }
    
                if (empty($errors)) 
                $is_error = false;
                else $is_error = true;
                
                return array('response' => $result, 'is_error' => $is_error, 'success' => $success, 'errors' => $errors, 'status' => $subscribed);
            }
            
            public static function arr_get($arr, $key, $default = NULL)
            {
                if (isset($arr[$key]))
                {
                    return $arr[$key];
                }
    
                return $default;
            }
    
            public static function check_arr_get($arr, $key, $default = NULL)
            {
                $get = self::arr_get($arr, $key, $default);
                if ($get)
                {
                    return $get;
                }
                return $default;
            }
    
            public static function grab_shortcode_defaults($attrs)
            {
    
                $c = $attrs;
                $c['layout'] = self::check_arr_get($c, 'layout', 1);
                $c['button_text'] = self::check_arr_get($c, 'button_text', __('Subscribe', 'atl'));
                $c['msg_success_general'] = self::check_arr_get($c, 'msg_success_general', 'Thank you for subscribing!');
                $c['msg_error_general'] = self::check_arr_get($c, 'msg_error_general', 'An error occurred!');
    
                return $c;
            }
    
            /**
             * Function used by widgets and shortcodes
             * @return string
             */
            public static function do_signup_form($attrs, $content = '')
            {
    
                $attrs = self::grab_shortcode_defaults($attrs);
    
                if ( ! array_key_exists($attrs['layout'], self::$LAYOUTS))
                {
                    $attrs['layout'] = 1;
                }
    
    
                if ( ! isset($attrs['__ajax_submit']))
                {
                     $form_id = self::$FORM_ID = self::$FORM_ID + 1;
                }
                else
                {
                     $form_id = $attrs['__ajax_submit'];
                }
    
    
                $session_key = self::$SESSION_SUBMIT_KEY;
    
                if ( ! is_array(@$_SESSION[$session_key])) $_SESSION[$session_key] = array();
    
                $_SESSION[$session_key][$form_id] = array('attrs' => $attrs, 'content' => $content);
    
    
                self::do_action('before_do_signup_form');
    
                $grab = self::post_data(array('form_id'));
    
                if (@$grab['form_id'] == $form_id)
                {
                    $result = self::do_submit_signup_form();
                }
                else
                {
                    $result = array('response' => 0, 'no_config' => 1, 'is_error' => 0, 'success' => array(), 'status' => 0,  'errors' => array());
                }
    
    
                $layout = self::show_layout($attrs['layout'], self::$FORM_ID);
    
                ob_start();
    
                include ACTIVE_TRAIL_SHORTCODES_DIR.'/signup_form.php';
    
                $contents = ob_get_clean();
    
                self::do_action('after_do_signup_form');
    
                return str_replace(array("\n", "\n\n"), array('', ''), $contents);
            }
    
            /**
             * Gets values from $_POST under plugin namespace
             * @return mixed
             */
            public static function post_data($keys)
            {
                if ( ! is_array($keys))
                {
                    return @$_POST[self::PLUGIN.'_'.$keys];
                }
                else {
    
                        $data = array();
                        foreach ($keys as $t)
                        {
                            $data[$t] = self::post_data($t);
                        }
                }
    
                return $data;
            }
    
            /**
             * Handles shortcode for signup form
             */
            public function shortcode_signup_form($attrs, $content = '')
            {
                return self::do_signup_form($attrs, $content);
            }
    
            /**
             * Helper for showing layout
             */
            public static function show_layout($id, $fid)
            {
                if ( ! file_exists(self::$LAYOUTS[$id]['view']))
                {
                    return 'Layout ID: '.esc_html($id).' does not exist!';
                }
    
                $dt = array();
    
                if ($fid == self::post_data('form_id')) : 
                foreach ($_POST as $k => $v)
                {
                    $dt[str_replace(ACTIVE_TRAIL_NAME.'_', '', $k)] = $v;
                }
                endif;
    
                ob_start();
                include self::$LAYOUTS[$id]['view'];
                return ob_get_clean();
            }
    
    
        } // end class
    
        function active_trail_start()
        {
            if ( ! isset($GLOBALS['ActiveTrailPlugin']))
            $GLOBALS['ActiveTrailPlugin'] = new Active_Trail_Plugin();
        }
        add_action('plugins_loaded', 'active_trail_start');
    
    
        class Widget_Active_Trail_Sign_Up extends WP_Widget {
    
            const WIDGET_ID = 'active-trail-signup-widget';
            const WIDGET_NAME = 'ActiveTrail Signup Form';
    
            /*--------------------------------------------------*/
            /* Constructor
            /*--------------------------------------------------*/
    
            /**
             * The widget constructor. Specifies the classname and description, instantiates
             * the widget, loads localization files, and includes necessary scripts and
             * styles.
             */
            public function __construct() {
    
                parent::__construct(
                    self::WIDGET_ID,
                    __( self::WIDGET_NAME, 'atl' ),
                    array(
                        'classname'		=>	self::WIDGET_ID-'-class',
                        'description'	=>	__( 'ActiveTrail Signup Form.', 'atl' )
                    )
                );
    
    
            } // end constructor
    
            /*--------------------------------------------------*/
            /* Widget API Functions
            /*--------------------------------------------------*/
    
            /**
             * Outputs the content of the widget.
             *
             * @args			The array of form elements
             * @instance		The current instance of the widget
             */
            public function widget( $args, $instance ) {
    
                extract( $args, EXTR_SKIP );
    
                echo $before_widget;
    
                include( ACTIVE_TRAIL_WIDGETS_DIR. '/sign_up/widget.php' );
    
                echo $after_widget;
    
            } // end widget
    
            /**
             * Processes the widget's options to be saved.
             *
             * @new_instance	The previous instance of values before the update.
             * @old_instance	The new instance of values to be generated via the update.
             */
            public function update( $new_instance, $old_instance ) {
    
    
                $instance = $new_instance;
    
                // Note that this 'Title' is just an example
                $instance['group'] = implode(',', $new_instance['groups']);
    
                return $instance;
    
            } // end widget
    
            /**
             * Generates the administration form for the widget.
             *
             * @instance	The array of keys and values for the widget.
             */
            public function form( $instance ) {
    
                $instance = wp_parse_args(
                    (array) $instance
                );
    
    
                // Display the admin form
                include(ACTIVE_TRAIL_WIDGETS_DIR. '/sign_up/admin.php' );	
    
            } // end form
    
    
        } // end class
    
        add_action( 'widgets_init', create_function('', 'register_widget("Widget_Active_Trail_Sign_Up");' ));
