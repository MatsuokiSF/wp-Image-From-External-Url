<?php
/**
 * ImageFromExternalUrl/views/admin-post-upload-ui.php
 * 
 * Esta vista muestra la interfaz en el flujo de carga de medios para permitir
 * agregar imágenes externas sin importar el archivo.
 */

// Asegurar que este archivo no se llame directamente
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

?>
<div id="imagefromexternalurl-in-upload-ui">
    <div class="row1"><?php _e('or', 'imagefromexternalurl'); ?></div>
    <div class="row2">
        <?php if ('grid' === $media_library_mode): ?>
            <button id="imagefromexternalurl-show" class="button button-large">
                <?php _e('Add External Media without Import', 'imagefromexternalurl'); ?>
            </button>
            <?php include IMAGEFROMEXTERNALURL_PLUGIN_DIR . 'views/media-new-panel.php'; ?>
        <?php else: ?>
            <a class="button button-large" href="<?php echo esc_url(admin_url('upload.php?page=add-external-media-without-import')); ?>">
                <?php _e('Add External Media without Import', 'imagefromexternalurl'); ?>
            </a>
        <?php endif; ?>
    </div>
</div>
