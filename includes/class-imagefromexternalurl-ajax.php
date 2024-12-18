<?php
namespace ImageFromExternalUrl;

class ImageFromExternalUrl_Ajax {
    protected $media_handler;

    public function __construct($media_handler) {
        $this->media_handler = $media_handler;
        add_action('wp_ajax_add_external_media_without_import', [$this, 'handle_ajax_request']);
    }

    public function handle_ajax_request() {
        $info = $this->media_handler->add_external_media_without_import();
        $attachments = [];

        foreach ($info['attachment_ids'] as $attachment_id) {
            $attachment = wp_prepare_attachment_for_js($attachment_id);
            if ($attachment) {
                $attachments[] = $attachment;
            } else {
                $error = "There's an attachment inserted but couldn't be retrieved.";
            }
        }

        $info['attachments'] = $attachments;
        if (isset($error)) {
            $info['error'] = isset($info['error']) ? $info['error'] . "\n$error" : $error;
        }

        wp_send_json_success($info);
    }
}
