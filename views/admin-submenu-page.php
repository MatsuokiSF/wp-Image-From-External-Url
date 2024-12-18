<?php
/**
 * //ImageFromExternalUrl/views/admin-submenu-page.php
 * 
 * Esta vista se utiliza para mostrar una página de submenú en el panel de administración,
 * bajo el menú "Medios". Permite al usuario agregar imágenes externas proporcionando sus URLs.
 * 
 * Al enviar el formulario, la acción `admin_post_add_external_media_without_import` 
 * procesará las URLs y agregará las imágenes a la librería de WordPress.
 */

// Asegurar que no se acceda directamente
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<div class="wrap">
    <h1><?php _e('Add External Media without Import', 'imagefromexternalurl'); ?></h1>
    <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">
        <?php 
        // Incluir el panel que contiene el textarea para URLs y los campos opcionales
        include IMAGEFROMEXTERNALURL_PLUGIN_DIR . 'views/media-new-panel.php'; ?>
    </form>
</div>
