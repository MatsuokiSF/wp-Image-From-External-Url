<?php
/**
 * //ImageFromExternalUrl/views/media-new-panel.php
 * 
 * Este archivo es el panel que permite ingresar URLs de imágenes externas y campos opcionales
 * como ancho, alto y MIME type. Puede ser incluido desde distintas vistas para proveer la interfaz
 * correspondiente al usuario.
 */

// Asegurar que no se acceda directamente
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div id="imagefromexternalurl-media-new-panel">
    <label id="imagefromexternalurl-urls-label"><?php _e('Add medias from URLs', 'imagefromexternalurl'); ?></label>
    <textarea id="imagefromexternalurl-urls" rows="10" name="urls" required placeholder="<?php _e("Please fill in the media URLs.\nMultiple URLs are supported, one per line.", 'imagefromexternalurl'); ?>"></textarea>
    <div id="imagefromexternalurl-hidden" style="display: none;">
        <div>
            <span id="imagefromexternalurl-error"></span>
            <?php _e('Please fill in the following properties manually. If you leave them blank or 0, the plugin will try to resolve them automatically.', 'imagefromexternalurl'); ?>
        </div>
        <div id="imagefromexternalurl-properties">
            <label><?php _e('Width', 'imagefromexternalurl'); ?></label>
            <input id="imagefromexternalurl-width" name="width" type="number">
            <label><?php _e('Height', 'imagefromexternalurl'); ?></label>
            <input id="imagefromexternalurl-height" name="height" type="number">
            <label><?php _e('MIME Type', 'imagefromexternalurl'); ?></label>
            <input id="imagefromexternalurl-mime-type" name="mime-type" type="text">
        </div>
    </div>
    <div id="imagefromexternalurl-buttons-row">
        <span class="spinner"></span>
        <input type="hidden" name="action" value="add_external_media_without_import">
        <input type="button" id="imagefromexternalurl-clear" class="button" value="<?php _e('Clear', 'imagefromexternalurl'); ?>">
        <input type="submit" id="imagefromexternalurl-add" class="button button-primary" value="<?php _e('Add', 'imagefromexternalurl'); ?>">
        <input type="button" id="imagefromexternalurl-cancel" class="button" value="<?php _e('Cancel', 'imagefromexternalurl'); ?>">
    </div>
</div>
