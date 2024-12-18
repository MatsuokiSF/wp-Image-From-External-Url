<?php
/**
 * //ImageFromExternalUrl/includes/class-imagefromexternalurl-plugin.php
 * 
 */

namespace ImageFromExternalUrl;

require_once IMAGEFROMEXTERNALURL_PLUGIN_DIR . 'includes/class-imagefromexternalurl-admin.php';
require_once IMAGEFROMEXTERNALURL_PLUGIN_DIR . 'includes/class-imagefromexternalurl-ajax.php';
require_once IMAGEFROMEXTERNALURL_PLUGIN_DIR . 'includes/class-imagefromexternalurl-media-handler.php';
require_once IMAGEFROMEXTERNALURL_PLUGIN_DIR . 'includes/functions-utils.php';

class ImageFromExternalUrl_Plugin {
    protected $admin;
    protected $ajax;
    protected $media_handler;

    public function init() {
        $this->media_handler = new ImageFromExternalUrl_Media_Handler();
        $this->admin = new ImageFromExternalUrl_Admin( $this->media_handler );
        $this->ajax = new ImageFromExternalUrl_Ajax( $this->media_handler );

        // Ajuste para que wp_attachment_is_image funcione con imágenes externas
        add_filter('get_attached_file', [$this, 'filter_attached_file'], 10, 2);
    }

    public function filter_attached_file($file, $attachment_id) {
        if (empty($file)) {
            $post = get_post($attachment_id);
            if (get_post_type($post) === 'attachment') {
                return $post->guid;
            }
        }
        return $file;
    }
}
