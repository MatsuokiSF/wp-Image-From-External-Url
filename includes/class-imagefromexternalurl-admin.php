<?php
/**
 * //ImageFromExternalUrl/includes/class-imagefromexternalurl-admin.php
 * 
 */

namespace ImageFromExternalUrl;

class ImageFromExternalUrl_Admin {
    protected $media_handler;

    public function __construct($media_handler) {
        $this->media_handler = $media_handler;
        add_action('admin_menu', [$this, 'add_submenu']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_assets']);
        add_action('post-plupload-upload-ui', [$this, 'render_post_upload_ui']);
        add_action('post-html-upload-ui', [$this, 'render_post_upload_ui']);
        add_action('admin_post_add_external_media_without_import', [$this, 'handle_admin_post']);
    }

    public function add_submenu() {
        add_submenu_page(
            'upload.php', 
            __('Add External Media without Import', 'imagefromexternalurl'), 
            __('Add External Media without Import', 'imagefromexternalurl'), 
            'manage_options', 
            'add-external-media-without-import', 
            [$this, 'render_submenu_page']
        );
    }

    public function enqueue_assets() {
        $style = 'imagefromexternalurl-css';
        wp_register_style($style, IMAGEFROMEXTERNALURL_PLUGIN_URL . 'assets/css/ImageFromExternalUrl.css');
        wp_enqueue_style($style);
    
        $script = 'imagefromexternalurl-js';
        wp_register_script($script, IMAGEFROMEXTERNALURL_PLUGIN_URL . 'assets/js/ImageFromExternalUrl.js', ['jquery'], null, true);
        wp_enqueue_script($script);
    }    

    public function render_post_upload_ui() {
        $media_library_mode = get_user_option('media_library_mode', get_current_user_id());
        include IMAGEFROMEXTERNALURL_PLUGIN_DIR . 'views/admin-post-upload-ui.php';
    }

    public function render_submenu_page() {
        echo "Test submenu page";
        include IMAGEFROMEXTERNALURL_PLUGIN_DIR . 'views/admin-submenu-page.php';
    }
    

    public function handle_admin_post() {
        $info = $this->media_handler->add_external_media_without_import();
        $redirect_url = 'upload.php';
        $urls = $info['urls'];

        if (!empty($urls)) {
            $redirect_url = add_query_arg([
                'page' => 'add-external-media-without-import',
                'urls' => urlencode($urls),
                'error' => urlencode($info['error']),
                'width' => urlencode($info['width']),
                'height' => urlencode($info['height']),
                'mime-type' => urlencode($info['mime-type'])
            ], $redirect_url);
        }

        wp_redirect(admin_url($redirect_url));
        exit;
    }
}
