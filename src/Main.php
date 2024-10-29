<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @since      1.0.0
 *
 * @package    Admin_Panel_Background_Color
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Admin_Panel_Background_Color
 * @author     castellar120
 */
class Main
{
    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Loader    $loader    Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $plugin_name    The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $version    The current version of the plugin.
     */
    protected $version;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */

    public function __construct()
    {
        if (defined('PLUGIN_NAME_VERSION')) {
            $this->version = PLUGIN_NAME_VERSION;
        } else {
            $this->version = '1.0.0';
        }
        $this->plugin_name = 'admin-panel-colors';

        $this->loadDependencies();
        $this->setLocale();
        // Hooks
        $this->defineAdminHooks();
        $this->definePublicHooks();
    }

    /**
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function loadDependencies()
    {
        $this->loader = new Loader();
    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the I18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function setLocale()
    {
        $plugin_i18n = new I18n();

        $this->loader->addAction('plugins_loaded', $plugin_i18n, 'loadPluginTextdomain');
    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function defineAdminHooks()
    {
        $plugin_admin = new Admin($this->getPluginName(), $this->getVersion());

        $this->loader->addAction('admin_enqueue_scripts', $plugin_admin, 'enqueueScripts');

        $this->loader->addAction('admin_color_scheme_picker', $plugin_admin, 'addUserExtraFields');
        $this->loader->addAction('personal_options_update', $plugin_admin, 'saveUserExtraFields');
        $this->loader->addAction('edit_user_profile_update', $plugin_admin, 'saveUserExtraFields');

        $this->loader->addAction('admin_init', $plugin_admin, 'getCurrentUserId');
        $this->loader->addAction('admin_init', $plugin_admin, 'getCurrentBackground', 11);

        $this->loader->addAction('admin_head', $plugin_admin, 'ApplyCurrentCss' );

        $this->loader->addAction( 'wp_ajax_compileScssAjax', $plugin_admin, 'compileScssAjax' );

        $this->loader->addAction('admin_init', $plugin_admin, 'unsetSessionVars');

        remove_action( 'admin_color_scheme_picker', 'admin_color_scheme_picker' );

        //$this->loader->addAction('admin_head', $plugin_admin, 'unsetSessionVars');
    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function definePublicHooks()
    {
        $plugin_public = new PublicClass($this->getPluginName(), $this->getVersion());

        $this->loader->addAction('wp_enqueue_scripts', $plugin_public, 'enqueueScripts');
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run()
    {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */
    public function getPluginName()
    {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     1.0.0
     * @return    Loader    Orchestrates the hooks of the plugin.
     */
    public function getLoader()
    {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function getVersion()
    {
        return $this->version;
    }
}
