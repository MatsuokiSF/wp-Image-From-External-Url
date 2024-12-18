<?php
namespace ImageFromExternalUrl;

class ImageFromExternalUrl_Media_Handler {

    private function sanitize_and_validate_input() {
        if (!isset($_POST['urls'])) {
            return ['error' => __('No URLs provided.', 'imagefromexternalurl')];
        }

        $raw_urls = explode("\n", $_POST['urls']);
        $urls = array_map('trim', $raw_urls);
        $urls = array_map('esc_url_raw', $urls);

        $width = isset($_POST['width']) ? sanitize_text_field($_POST['width']) : '';
        $height = isset($_POST['height']) ? sanitize_text_field($_POST['height']) : '';
        $mime_type = isset($_POST['mime-type']) ? sanitize_mime_type($_POST['mime-type']) : '';

        $width_int = !empty($width) ? intval($width) : 0;
        $height_int = !empty($height) ? intval($height) : 0;

        if (( !empty($width) && $width_int <= 0 ) || ( !empty($height) && $height_int <= 0 )) {
            return ['error' => __('Width and height must be non-negative integers.', 'imagefromexternalurl')];
        }

        return [
            'urls' => $urls,
            'width' => $width_int,
            'height' => $height_int,
            'mime-type' => $mime_type
        ];
    }

    public function add_external_media_without_import() {
        $info = $this->sanitize_and_validate_input();

        if (isset($info['error'])) {
            return $info;
        }

        $urls = $info['urls'];
        $width = $info['width'];
        $height = $info['height'];
        $mime_type = $info['mime-type'];

        $attachment_ids = [];
        $failed_urls = [];

        foreach ($urls as $url) {
            $width_of_the_image = $width;
            $height_of_the_image = $height;
            $mime_type_of_the_image = $mime_type;

            if (empty($width_of_the_image) || empty($height_of_the_image)) {
                $image_size = @getimagesize($url);
                if (empty($image_size)) {
                    $failed_urls[] = $url;
                    continue;
                }
                $width_of_the_image = empty($width) ? $image_size[0] : $width;
                $height_of_the_image = empty($height) ? $image_size[1] : $height;
                $mime_type_of_the_image = empty($mime_type) ? $image_size['mime'] : $mime_type_of_the_image;
            } elseif (empty($mime_type_of_the_image)) {
                $response = wp_remote_head($url);
                if (is_array($response) && isset($response['headers']['content-type'])) {
                    $mime_type_of_the_image = $response['headers']['content-type'];
                } else {
                    continue;
                }
            }

            $filename = wp_basename($url);
            $attachment = [
                'guid' => $url,
                'post_mime_type' => $mime_type_of_the_image,
                'post_title' => preg_replace('/\.[^.]+$/', '', $filename),
            ];

            $attachment_id = wp_insert_attachment($attachment);
            if ($attachment_id) {
                $attachment_metadata = [
                    'width' => $width_of_the_image,
                    'height' => $height_of_the_image,
                    'file' => $filename,
                    'sizes' => ['full' => [
                        'width' => $width_of_the_image,
                        'height' => $height_of_the_image,
                        'file' => $filename
                    ]]
                ];
                wp_update_attachment_metadata($attachment_id, $attachment_metadata);
                $attachment_ids[] = $attachment_id;
            } else {
                $failed_urls[] = $url;
            }
        }

        $info['attachment_ids'] = $attachment_ids;
        $failed_urls_string = implode("\n", $failed_urls);
        $info['urls'] = $failed_urls_string;

        if (!empty($failed_urls_string)) {
            $info['error'] = __('Failed to get info of the image(s).', 'imagefromexternalurl');
        }

        return $info;
    }
}
