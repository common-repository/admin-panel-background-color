<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @since      1.0.0
 *
 * @package    Admin_Panel_Background_Color
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * @package    Admin_Panel_Background_Color
 * @author     castellar120
 */
class Admin
{
    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * First default color.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $color1    First default color.
     */
    private $color1;

    /**
     * Second default color.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $color2    Second default color.
     */
    private $color2;

    /**
     * Third default color.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $color3    Third default color.
     */
    private $color3;

    /**
     * Fourth default color.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $color4    Fourth default color.
     */
    private $color4;


    /**
     * Initialize the class and set its properties.
     *
     * @since     1.0.0
     * @param     string    $plugin_name       The name of this plugin.
     * @param     string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

        $this->color1 = '#23282d';
        $this->color2 = '#a0a5aa';
        $this->color3 = '#1981b2';
        $this->color4 = '#d54e21';

        $this->getCurrentBackground();
    }

    /**
     * The current background color.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $activeBackgroundColor    The current background color.
     */
    private $activeBackgroundColor;

    /**
     * The current user id.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $currentUserId    The current user id.
     */
    private $currentUserId;

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     * @access    public
     */
    public function enqueueScripts()
    {
        // Register the script
        wp_register_script($this->plugin_name, plugins_url() . '/admin-panel-background-color/admin/js/admin.js', array('jquery', 'colorpicker/js', 'wp-color-picker'), $this->version, false);

        // Localize the script with new data
        $varArray = array(
            'activeBackground' => __($this->$activeBackgroundColor, 'abcolor'),
            'pluginsUrl' => plugins_url(),
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
            'color1' => $this->getUserMeta($this->getCurrentUserId(), 'abcolor_color1', $this->color1),
            'color2' => $this->getUserMeta($this->getCurrentUserId(), 'abcolor_color2', $this->color2),
            'color3' => $this->getUserMeta($this->getCurrentUserId(), 'abcolor_color3', $this->color3),
            'color4' => $this->getUserMeta($this->getCurrentUserId(), 'abcolor_color4', $this->color4),
        );
        wp_localize_script($this->plugin_name, 'phpVars', $varArray);

        // Enqueued script with localized data.
        wp_enqueue_script($this->plugin_name);


        wp_enqueue_script('colorpicker/js', plugins_url() . '/admin-panel-background-color/admin/js/HSV-HEX-Color-Picker-jQuery/jquery.colorpicker.js', array( 'jquery' ), $this->version, false);
    }

    /**
     * Check if user is on his/her own profile page.
     *
     * @since     1.0.0
     * @access    public
     * @return    bool    TRUE if user is on his/her own profile page, false otherwise.
     */
    public function isOnOwnProfile()
    {
        $url = $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

        return strpos($url,'wp-admin/profile.php');
    }

    /**
     * Hide default admin color schemes.
     *
     * @since     1.0.0
     * @access    public
     */
    public function hideDefaultColorSchemes()
    {
        global $_wp_admin_css_colors;
        $_wp_admin_css_colors = 0;
    }

    /**
     * Create options for adding new color scheme.
     *
     * @since     1.0.0
     * @access    public
     * @todo      use template
     */
    public function addUserExtraFields($user_id)
    {
        if($this->isOnOwnProfile()) {
?>
        <h3><?php _e("Create custom color scheme", "abcolor"); ?></h3>
        <table>
            <tr>
                <input name="abcolor_color1" type="text" id="abcolor_color1" attr-field="abcolor_color1"/>
            </tr>
            <tr>
                <input name="abcolor_color2" type="text" id="abcolor_color2" attr-field="abcolor_color2"/>
            </tr>
            <tr>
                <input name="abcolor_color3" type="text" id="abcolor_color3" attr-field="abcolor_color3"/>
            </tr>
            <tr>
                <input name="abcolor_color4" type="text" id="abcolor_color4" attr-field="abcolor_color4"/>
            </tr>
        </table>
        <br>
        <h3><?php _e("Select background color of admin panel", "abcolor"); ?></h3>

        <table class="add-scheme">
            <tr>
                <div id="colorpicker">
                </div>
            </tr>
            <tr>
                <th><label for="abc-background-color"><?php _e("Color", "abcolor"); ?></label></th>
                <td>
                    <input type="text" name="abc-background-color" id="abc-background-color" attr-field="#abc-background-color" value="" class="regular-text" /><br />
                    <span class="description"><?php _e("Please enter hex code of new admin background color."); ?></span>
                </td>
            </tr>
        </table>
<?php
        }
    }

    /**
     * Save new background color.
     *
     * @since     1.0.0
     * @access    public
     */
    public function saveUserExtraFields($user_id)
    {
        if (!current_user_can('edit_user', $user_id)) {
            return false;
        }

        global $wpdb;

        $cuid = get_current_user_id();

        $newBackground = $_POST['abc-background-color'];

        update_user_meta($cuid, 'admin_color_background', $newBackground);


        $abcolor_color1 = $_POST['abcolor_color1'];
        $abcolor_color2 = $_POST['abcolor_color2'];
        $abcolor_color3 = $_POST['abcolor_color3'];
        $abcolor_color4 = $_POST['abcolor_color4'];


        if (!empty($abcolor_color1)) {
            update_user_meta($cuid, 'abcolor_color1', $abcolor_color1);
        }

        if (!empty($abcolor_color2)) {
            update_user_meta($cuid, 'abcolor_color2', $abcolor_color2);
        }

        if (!empty($abcolor_color3)) {
            update_user_meta($cuid, 'abcolor_color3', $abcolor_color3);
        }

        if (!empty($abcolor_color4)) {
            update_user_meta($cuid, 'abcolor_color4', $abcolor_color4);
        }

        $newCss = $this->compileScss(array('color1' => $abcolor_color1, 'color2' => $abcolor_color2, 'color3' => $abcolor_color3, 'color4' => $abcolor_color4));

        update_user_meta($cuid, 'abcolor_css', $newCss);
    }

    /**
     * Get user meta but with default value.
     *
     * @since     1.0.0
     * @access    public
     */
    public function getUserMeta(int $user_id, string $key = '', $default = false)
    {
        $userMeta = get_user_meta($user_id, $key, true);

        if (!$userMeta) {
           $userMeta = $default;
        }

        return $userMeta;
    }

    /**
     * Compile scss.
     *
     * @since     1.0.0
     * @access    public
     */
    public function compileScss($colors)
    {
        $scss = new scssc();

        if (empty($colors['color1'])) {
            $color1 = $this->getUserMeta($this->getCurrentUserId(), 'abcolor_color1', $this->color1);
        } else {
            $color1 = $colors['color1'];
        }

        if (empty($colors['color2'])) {
            $color2 = $this->getUserMeta($this->getCurrentUserId(), 'abcolor_color2', $this->color2);
        } else {
            $color2 = $colors['color2'];
        }

        if (empty($colors['color3'])) {
            $color3 = $this->getUserMeta($this->getCurrentUserId(), 'abcolor_color3', $this->color3);
        } else {
            $color3 = $colors['color3'];
        }

        if (empty($colors['color4'])) {
            $color4 = $this->getUserMeta($this->getCurrentUserId(), 'abcolor_color4', $this->color4);
        } else {
            $color4 = $colors['color4'];
        }

         $tempsass = $scss->compile('
            $base-color:' . $color1 .';
            $icon-color:' . $color2 .';
            $highlight-color:' . $color3 .';
            $notification-color:' . $color4 .';

            // assign default value to all undefined variables


            // core variables

            $text-color: #fff !default;
            $base-color: #23282d !default;
            $icon-color: hsl( hue( $base-color ), 7%, 95% ) !default;
            $highlight-color: #0073aa !default;
            $notification-color: #d54e21 !default;


            // global

            $body-background: #f1f1f1 !default;

            $link: #0073aa !default;
            $link-focus: lighten( $link, 10% ) !default;

            $button-color: $highlight-color !default;
            $form-checked: $highlight-color !default;


            // admin menu & admin-bar

            $menu-text: $text-color !default;
            $menu-icon: $icon-color !default;
            $menu-background: $base-color !default;

            $menu-highlight-text: $text-color !default;
            $menu-highlight-icon: $text-color !default;
            $menu-highlight-background: $highlight-color !default;

            $menu-current-text: $menu-highlight-text !default;
            $menu-current-icon: $menu-highlight-icon !default;
            $menu-current-background: $menu-highlight-background !default;

            $menu-submenu-text: mix( $base-color, $text-color, 30% ) !default;
            $menu-submenu-background: darken( $base-color, 7% ) !default;
            $menu-submenu-background-alt: desaturate( lighten( $menu-background, 7% ), 7% ) !default;

            $menu-submenu-focus-text: $highlight-color !default;
            $menu-submenu-current-text: $text-color !default;

            $menu-bubble-text: $text-color !default;
            $menu-bubble-background: $notification-color !default;
            $menu-bubble-current-text: $text-color !default;
            $menu-bubble-current-background: $menu-submenu-background !default;

            $menu-collapse-text: $menu-icon !default;
            $menu-collapse-icon: $menu-icon !default;
            $menu-collapse-focus-text: $text-color !default;
            $menu-collapse-focus-icon: $menu-highlight-icon !default;

            $adminbar-avatar-frame: lighten( $menu-background, 7% ) !default;
            $adminbar-input-background: lighten( $menu-background, 7% ) !default;

            $adminbar-recovery-exit-text: $menu-bubble-text !default;
            $adminbar-recovery-exit-background: $menu-bubble-background !default;
            $adminbar-recovery-exit-background-alt: mix(black, $adminbar-recovery-exit-background, 10%) !default;

            $menu-customizer-text: mix( $base-color, $text-color, 40% ) !default;

            /*
             * Button mixin- creates 3d-ish button effect with correct
             * highlights/shadows, based on a base color.
             */
            @mixin button( $button-color, $text-color: #fff ) {
                background: $button-color;
                border-color: darken( $button-color, 10% ) darken( $button-color, 15% ) darken( $button-color, 15% );
                color: $text-color;
                box-shadow: 0 1px 0 darken( $button-color, 15% );
                text-shadow: 0 -1px 1px darken( $button-color, 15% ),
                    1px 0 1px darken( $button-color, 15% ),
                    0 1px 1px darken( $button-color, 15% ),
                    -1px 0 1px darken( $button-color, 15% );

                &:hover,
                &:focus {
                    background: lighten( $button-color, 3% );
                    border-color: darken( $button-color, 15% );
                    color: $text-color;
                    box-shadow: 0 1px 0 darken( $button-color, 15% );
                }

                &:focus {
                    box-shadow: inset 0 1px 0 darken( $button-color, 10% ),
                                0 0 2px 1px #33b3db;
                }

                &:active,
                &.active,
                &.active:focus,
                &.active:hover {
                    background: darken( $button-color, 10% );
                    border-color: darken( $button-color, 15% );
                    box-shadow: inset 0 2px 0 darken( $button-color, 15% );
                }

                &[disabled],
                &:disabled,
                &.button-primary-disabled,
                &.disabled {
                    color: hsl( hue( $button-color ), 10%, 80% ) !important;
                    background: darken( $button-color, 8% ) !important;
                    border-color: darken( $button-color, 15% ) !important;
                    text-shadow: none !important;
                }

                &.button-hero {
                    box-shadow: 0 2px 0 darken( $button-color, 15% ) !important;
                    &:active {
                        box-shadow: inset 0 3px 0 darken( $button-color, 15% ) !important;
                    }
                }

            }

            body {
                background: $body-background;
            }


            /* Links */

            a {
                color: $link;

                &:hover,
                &:active,
                &:focus {
                    color: $link-focus;
                }
            }

            #media-upload a.del-link:hover,
            div.dashboard-widget-submit input:hover,
            .subsubsub a:hover,
            .subsubsub a.current:hover {
                color: $link-focus;
            }


            /* Forms */

            input[type=checkbox]:checked:before {
                color: $form-checked;
            }

            input[type=radio]:checked:before {
                background: $form-checked;
            }

            .wp-core-ui input[type="reset"]:hover,
            .wp-core-ui input[type="reset"]:active {
                color: $link-focus;
            }


            /* Core UI */

            .wp-core-ui {
                .button-primary {
                    @include button( $button-color );
                }

                .wp-ui-primary {
                    color: $text-color;
                    background-color: $base-color;
                }
                .wp-ui-text-primary {
                    color: $base-color;
                }

                .wp-ui-highlight {
                    color: $menu-highlight-text;
                    background-color: $menu-highlight-background;
                }
                .wp-ui-text-highlight {
                    color: $menu-highlight-background;
                }

                .wp-ui-notification {
                    color: $menu-bubble-text;
                    background-color: $menu-bubble-background;
                }
                .wp-ui-text-notification {
                    color: $menu-bubble-background;
                }

                .wp-ui-text-icon {
                    color: $menu-icon;
                }
            }


            /* List tables */

            .wrap .add-new-h2:hover, /* deprecated */
            .wrap .page-title-action:hover {
                color: $menu-text;
                background-color: $menu-background;
            }

            .view-switch a.current:before {
                color: $menu-background;
            }

            .view-switch a:hover:before {
                color: $menu-bubble-background;
            }


            /* Admin Menu */

            #adminmenuback,
            #adminmenuwrap,
            #adminmenu {
                background: $menu-background;
            }

            #adminmenu a {
                color: $menu-text;
            }

            #adminmenu div.wp-menu-image:before {
                color: $menu-icon;
            }

            #adminmenu a:hover,
            #adminmenu li.menu-top:hover,
            #adminmenu li.opensub > a.menu-top,
            #adminmenu li > a.menu-top:focus {
                color: $menu-highlight-text;
                background-color: $menu-highlight-background;
            }

            #adminmenu li.menu-top:hover div.wp-menu-image:before,
            #adminmenu li.opensub > a.menu-top div.wp-menu-image:before {
                color: $menu-highlight-icon;
            }


            /* Active tabs use a bottom border color that matches the page background color. */

            .about-wrap .nav-tab-active,
            .nav-tab-active,
            .nav-tab-active:hover {
                background-color: $body-background;
                border-bottom-color: $body-background;
            }


            /* Admin Menu: submenu */

            #adminmenu .wp-submenu,
            #adminmenu .wp-has-current-submenu .wp-submenu,
            #adminmenu .wp-has-current-submenu.opensub .wp-submenu,
            .folded #adminmenu .wp-has-current-submenu .wp-submenu,
            #adminmenu a.wp-has-current-submenu:focus + .wp-submenu {
                background: $menu-submenu-background;
            }

            #adminmenu li.wp-has-submenu.wp-not-current-submenu.opensub:hover:after {
                border-right-color: $menu-submenu-background;
            }

            #adminmenu .wp-submenu .wp-submenu-head {
                color: $menu-submenu-text;
            }

            #adminmenu .wp-submenu a,
            #adminmenu .wp-has-current-submenu .wp-submenu a,
            .folded #adminmenu .wp-has-current-submenu .wp-submenu a,
            #adminmenu a.wp-has-current-submenu:focus + .wp-submenu a,
            #adminmenu .wp-has-current-submenu.opensub .wp-submenu a {
                color: $menu-submenu-text;

                &:focus, &:hover {
                    color: $menu-submenu-focus-text;
                }
            }


            /* Admin Menu: current */

            #adminmenu .wp-submenu li.current a,
            #adminmenu a.wp-has-current-submenu:focus + .wp-submenu li.current a,
            #adminmenu .wp-has-current-submenu.opensub .wp-submenu li.current a {
                color: $menu-submenu-current-text;

                &:hover, &:focus {
                    color: $menu-submenu-focus-text;
                }
            }

            ul#adminmenu a.wp-has-current-submenu:after,
            ul#adminmenu > li.current > a.current:after {
                border-right-color: $body-background;
            }

            #adminmenu li.current a.menu-top,
            #adminmenu li.wp-has-current-submenu a.wp-has-current-submenu,
            #adminmenu li.wp-has-current-submenu .wp-submenu .wp-submenu-head,
            .folded #adminmenu li.current.menu-top {
                color: $menu-current-text;
                background: $menu-current-background;
            }

            #adminmenu li.wp-has-current-submenu div.wp-menu-image:before,
            #adminmenu a.current:hover div.wp-menu-image:before,
            #adminmenu li.wp-has-current-submenu a:focus div.wp-menu-image:before,
            #adminmenu li.wp-has-current-submenu.opensub div.wp-menu-image:before,
            #adminmenu li:hover div.wp-menu-image:before,
            #adminmenu li a:focus div.wp-menu-image:before,
            #adminmenu li.opensub div.wp-menu-image:before,
            .ie8 #adminmenu li.opensub div.wp-menu-image:before {
                color: $menu-current-icon;
            }


            /* Admin Menu: bubble */

            #adminmenu .awaiting-mod,
            #adminmenu .update-plugins {
                color: $menu-bubble-text;
                background: $menu-bubble-background;
            }

            #adminmenu li.current a .awaiting-mod,
            #adminmenu li a.wp-has-current-submenu .update-plugins,
            #adminmenu li:hover a .awaiting-mod,
            #adminmenu li.menu-top:hover > a .update-plugins {
                color: $menu-bubble-current-text;
                background: $menu-bubble-current-background;
            }


            /* Admin Menu: collapse button */

            #collapse-button {
                color: $menu-collapse-text;
            }

            #collapse-button:hover,
            #collapse-button:focus {
                color: $menu-submenu-focus-text;
            }

            /* Admin Bar */

            #wpadminbar {
                color: $menu-text;
                background: $menu-background;
            }

            #wpadminbar .ab-item,
            #wpadminbar a.ab-item,
            #wpadminbar > #wp-toolbar span.ab-label,
            #wpadminbar > #wp-toolbar span.noticon {
                color: $menu-text;
            }

            #wpadminbar .ab-icon,
            #wpadminbar .ab-icon:before,
            #wpadminbar .ab-item:before,
            #wpadminbar .ab-item:after {
                color: $menu-icon;
            }

            #wpadminbar:not(.mobile) .ab-top-menu > li:hover > .ab-item,
            #wpadminbar:not(.mobile) .ab-top-menu > li > .ab-item:focus,
            #wpadminbar.nojq .quicklinks .ab-top-menu > li > .ab-item:focus,
            #wpadminbar.nojs .ab-top-menu > li.menupop:hover > .ab-item,
            #wpadminbar .ab-top-menu > li.menupop.hover > .ab-item {
                color: $menu-submenu-focus-text;
                background: $menu-submenu-background;
            }

            #wpadminbar:not(.mobile) > #wp-toolbar li:hover span.ab-label,
            #wpadminbar:not(.mobile) > #wp-toolbar li.hover span.ab-label,
            #wpadminbar:not(.mobile) > #wp-toolbar a:focus span.ab-label {
                color: $menu-submenu-focus-text;
            }

            #wpadminbar:not(.mobile) li:hover .ab-icon:before,
            #wpadminbar:not(.mobile) li:hover .ab-item:before,
            #wpadminbar:not(.mobile) li:hover .ab-item:after,
            #wpadminbar:not(.mobile) li:hover #adminbarsearch:before {
                color: $menu-highlight-icon;
            }


            /* Admin Bar: submenu */

            #wpadminbar .menupop .ab-sub-wrapper {
                background: $menu-submenu-background;
            }

            #wpadminbar .quicklinks .menupop ul.ab-sub-secondary,
            #wpadminbar .quicklinks .menupop ul.ab-sub-secondary .ab-submenu {
                background: $menu-submenu-background-alt;
            }

            #wpadminbar .ab-submenu .ab-item,
            #wpadminbar .quicklinks .menupop ul li a,
            #wpadminbar .quicklinks .menupop.hover ul li a,
            #wpadminbar.nojs .quicklinks .menupop:hover ul li a {
                color: $menu-submenu-text;
            }

            #wpadminbar .quicklinks li .blavatar,
            #wpadminbar .menupop .menupop > .ab-item:before {
                color: $menu-icon;
            }

            #wpadminbar .quicklinks .menupop ul li a:hover,
            #wpadminbar .quicklinks .menupop ul li a:focus,
            #wpadminbar .quicklinks .menupop ul li a:hover strong,
            #wpadminbar .quicklinks .menupop ul li a:focus strong,
            #wpadminbar .quicklinks .ab-sub-wrapper .menupop.hover > a,
            #wpadminbar .quicklinks .menupop.hover ul li a:hover,
            #wpadminbar .quicklinks .menupop.hover ul li a:focus,
            #wpadminbar.nojs .quicklinks .menupop:hover ul li a:hover,
            #wpadminbar.nojs .quicklinks .menupop:hover ul li a:focus,
            #wpadminbar li:hover .ab-icon:before,
            #wpadminbar li:hover .ab-item:before,
            #wpadminbar li a:focus .ab-icon:before,
            #wpadminbar li .ab-item:focus:before,
            #wpadminbar li .ab-item:focus .ab-icon:before,
            #wpadminbar li.hover .ab-icon:before,
            #wpadminbar li.hover .ab-item:before,
            #wpadminbar li:hover #adminbarsearch:before,
            #wpadminbar li #adminbarsearch.adminbar-focused:before {
                color: $menu-submenu-focus-text;
            }

            #wpadminbar .quicklinks li a:hover .blavatar,
            #wpadminbar .quicklinks li a:focus .blavatar,
            #wpadminbar .quicklinks .ab-sub-wrapper .menupop.hover > a .blavatar,
            #wpadminbar .menupop .menupop > .ab-item:hover:before,
            #wpadminbar.mobile .quicklinks .ab-icon:before,
            #wpadminbar.mobile .quicklinks .ab-item:before {
                color: $menu-submenu-focus-text;
            }

            #wpadminbar.mobile .quicklinks .hover .ab-icon:before,
            #wpadminbar.mobile .quicklinks .hover .ab-item:before {
                color: $menu-icon;
            }


            /* Admin Bar: search */

            #wpadminbar #adminbarsearch:before {
                color: $menu-icon;
            }

            #wpadminbar > #wp-toolbar > #wp-admin-bar-top-secondary > #wp-admin-bar-search #adminbarsearch input.adminbar-input:focus {
                color: $menu-text;
                background: $adminbar-input-background;
            }

            /* Admin Bar: recovery mode */

            #wpadminbar #wp-admin-bar-recovery-mode {
                color: $adminbar-recovery-exit-text;
                background-color: $adminbar-recovery-exit-background;
            }

            #wpadminbar #wp-admin-bar-recovery-mode .ab-item,
            #wpadminbar #wp-admin-bar-recovery-mode a.ab-item {
                color: $adminbar-recovery-exit-text;
            }

            #wpadminbar .ab-top-menu > #wp-admin-bar-recovery-mode.hover >.ab-item,
            #wpadminbar.nojq .quicklinks .ab-top-menu > #wp-admin-bar-recovery-mode > .ab-item:focus,
            #wpadminbar:not(.mobile) .ab-top-menu > #wp-admin-bar-recovery-mode:hover > .ab-item,
            #wpadminbar:not(.mobile) .ab-top-menu > #wp-admin-bar-recovery-mode > .ab-item:focus {
                color: $adminbar-recovery-exit-text;
                background-color: $adminbar-recovery-exit-background-alt;
            }

            /* Admin Bar: my account */

            #wpadminbar .quicklinks li#wp-admin-bar-my-account.with-avatar > a img {
                border-color: $adminbar-avatar-frame;
                background-color: $adminbar-avatar-frame;
            }

            #wpadminbar #wp-admin-bar-user-info .display-name {
                color: $menu-text;
            }

            #wpadminbar #wp-admin-bar-user-info a:hover .display-name {
                color: $menu-submenu-focus-text;
            }

            #wpadminbar #wp-admin-bar-user-info .username {
                color: $menu-submenu-text;
            }


            /* Pointers */

            .wp-pointer .wp-pointer-content h3 {
                background-color: $highlight-color;
                border-color: darken( $highlight-color, 5% );
            }

            .wp-pointer .wp-pointer-content h3:before {
                color: $highlight-color;
            }

            .wp-pointer.wp-pointer-top .wp-pointer-arrow,
            .wp-pointer.wp-pointer-top .wp-pointer-arrow-inner,
            .wp-pointer.wp-pointer-undefined .wp-pointer-arrow,
            .wp-pointer.wp-pointer-undefined .wp-pointer-arrow-inner {
                border-bottom-color: $highlight-color;
            }


            /* Media */

            .media-item .bar,
            .media-progress-bar div {
                background-color: $highlight-color;
            }

            .details.attachment {
                box-shadow:
                    inset 0 0 0 3px #fff,
                    inset 0 0 0 7px $highlight-color;
            }

            .attachment.details .check {
                background-color: $highlight-color;
                box-shadow: 0 0 0 1px #fff, 0 0 0 2px $highlight-color;
            }

            .media-selection .attachment.selection.details .thumbnail {
                box-shadow: 0 0 0 1px #fff, 0 0 0 3px $highlight-color;
            }


            /* Themes */

            .theme-browser .theme.active .theme-name,
            .theme-browser .theme.add-new-theme a:hover:after,
            .theme-browser .theme.add-new-theme a:focus:after {
                background: $highlight-color;
            }

            .theme-browser .theme.add-new-theme a:hover span:after,
            .theme-browser .theme.add-new-theme a:focus span:after {
                color: $highlight-color;
            }

            .theme-section.current,
            .theme-filter.current {
                border-bottom-color: $menu-background;
            }

            body.more-filters-opened .more-filters {
                color: $menu-text;
                background-color: $menu-background;
            }

            body.more-filters-opened .more-filters:before {
                color: $menu-text;
            }

            body.more-filters-opened .more-filters:hover,
            body.more-filters-opened .more-filters:focus {
                background-color: $menu-highlight-background;
                color: $menu-highlight-text;
            }

            body.more-filters-opened .more-filters:hover:before,
            body.more-filters-opened .more-filters:focus:before {
                color: $menu-highlight-text;
            }

            /* Widgets */

            .widgets-chooser li.widgets-chooser-selected {
                background-color: $menu-highlight-background;
                color: $menu-highlight-text;
            }

            .widgets-chooser li.widgets-chooser-selected:before,
            .widgets-chooser li.widgets-chooser-selected:focus:before {
                color: $menu-highlight-text;
            }

            /* Responsive Component */

            div#wp-responsive-toggle a:before {
                color: $menu-icon;
            }

            .wp-responsive-open div#wp-responsive-toggle a {
                // ToDo: make inset border
                border-color: transparent;
                background: $menu-highlight-background;
            }

            .wp-responsive-open #wpadminbar #wp-admin-bar-menu-toggle a {
                background: $menu-submenu-background;
            }

            .wp-responsive-open #wpadminbar #wp-admin-bar-menu-toggle .ab-icon:before {
                color: $menu-icon;
            }

            /* TinyMCE */

            .mce-container.mce-menu .mce-menu-item:hover,
            .mce-container.mce-menu .mce-menu-item.mce-selected,
            .mce-container.mce-menu .mce-menu-item:focus,
            .mce-container.mce-menu .mce-menu-item-normal.mce-active,
            .mce-container.mce-menu .mce-menu-item-preview.mce-active {
                background: $highlight-color;
            }
        ');

        return $tempsass;
    }

    /**
    * Return compiled sass for ajax call.
    *
    * @since     1.0.0
    * @access    public
    */
    public function compileScssAjax() {


        if (!empty($_REQUEST['c1'])) {
            $_SESSION['abcolor1'] = $_REQUEST['c1'];;
        }

        if (!empty($_REQUEST['c2'])) {
            $_SESSION['abcolor2'] = $_REQUEST['c2'];;
        }

        if (!empty($_REQUEST['c3'])) {
            $_SESSION['abcolor3'] = $_REQUEST['c3'];;
        }

        if (!empty($_REQUEST['c4'])) {
            $_SESSION['abcolor4'] = $_REQUEST['c4'];;
        }

        if (empty($_SESSION['abcolor1'])) {
            $abcolor_new_color1 = $this->getUserMeta($this->getCurrentUserId(), 'abcolor_color1', $this->color1);
        } else {
            $abcolor_new_color1 = $_SESSION['abcolor1'];
        }

        if (empty($_SESSION['abcolor2'])) {
            $abcolor_new_color2 = $this->getUserMeta($this->getCurrentUserId(), 'abcolor_color2', $this->color2);
        } else {
            $abcolor_new_color2 = $_SESSION['abcolor2'];
        }

        if (empty($_SESSION['abcolor3'])) {
            $abcolor_new_color3 = $this->getUserMeta($this->getCurrentUserId(), 'abcolor_color3', $this->color3);
        } else {
            $abcolor_new_color3 = $_SESSION['abcolor3'];
        }

        if (empty($_SESSION['abcolor4'])) {
            $abcolor_new_color4 = $this->getUserMeta($this->getCurrentUserId(), 'abcolor_color4', $this->color4);
        } else {
            $abcolor_new_color4 = $_SESSION['abcolor4'];
        }

        echo $this->compileScss(array('color1' => $abcolor_new_color1, 'color2' => $abcolor_new_color2, 'color3' => $abcolor_new_color3, 'color4' => $abcolor_new_color4));

        wp_die(); // this is required to terminate immediately and return a proper response
    }

    /**
    * Unset session variables
    *
    * @since     1.0.0
    * @access    public
    */
    public function unsetSessionVars() {
        if (strpos ($_SERVER ['REQUEST_URI'] , 'wp-admin/admin-ajax.php?action=compileScssAjax' )==FALSE) {
            error_log($_SERVER ['REQUEST_URI']);
            unset ($_SESSION["abcolor1"]);
            unset ($_SESSION["abcolor2"]);
            unset ($_SESSION["abcolor3"]);
            unset ($_SESSION["abcolor4"]);
        }
    }

    /**
    * Get current abcolor admin css.
    *
    * @since     1.0.0
    * @access    public
    */
    public function getCurrentCss(){
        return get_user_meta($this->getCurrentUserId(), 'abcolor_css', true);
    }

    /**
    * Apply plugins CSS.
    *
    * @since     1.0.0
    * @access    public
    */
    public function ApplyCurrentCss(){
        echo '<style class="abcolor_style">' . $this->getCurrentCss() . '</style>';
    }

    /**
     * Get current user ID.
     *
     * @since     1.0.0
     * @access    public
     */
    public function getCurrentUserId()
    {
        $this->$currentUserId = get_current_user_id();
        return get_current_user_id();
    }

    /**
     * Get currently active background color.
     *
     * @since     1.0.0
     * @access    protected
     */
    public function getCurrentBackground()
    {
        $this->$activeBackgroundColor = get_user_meta($this->$currentUserId, 'admin_color_background', true);
    }
}
