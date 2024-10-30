<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


?>

<h2><?php _e('Welcome to Menu Restrict Plugin   '); ?></h2>


<?php 

/* Getting dropdown valu from datbase  */
$menuSelectedValue = get_option('vg-menu-restrict-name');

if (!empty($menuSelectedValue)) {
$ssplitMenu = explode('_', $menuSelectedValue);

        $vgmenuId  = $ssplitMenu[0];
        $vgmenuName = $ssplitMenu[1];
}

// Getting All nav menus list
$nav_menus = wp_get_nav_menus();


/**
 *
 * List all menu dropdown
 *
 * @date  10th July 2017
 * @since VG Menu Restrict 1.0
 *
 */
?>
<form name="menuRestrict" method="POST" action="">
    <select id="menu-to-restrict" name="menu-to-restrict" required>
        <option value="">&mdash; Select a Menu &mdash;</option>
        <?php foreach ( $nav_menus as $menu ) :

            $optionValue = esc_attr( $menu->term_id ).'_'.$menu->name;

         ?>
            <option value="<?php echo $optionValue; ?>" <?php selected($optionValue, $menuSelectedValue);?>>
                <?php echo esc_html( wp_html_excerpt( $menu->name, 40, '&hellip;' ) ); ?>
            </option>
        <?php endforeach; ?>
    </select>
    <?php submit_button( 'Submit', 'primary', 'menu-restrict-submit', false ); ?>
</form>



<?php 

/**
 *
 * Delete the menu record on click on remove button
 *
 * @date  19th July 2017
 * @since VG Menu Restrict 1.0
 *
 */

if(isset($_REQUEST['del'])){

    $getDelRequest  = sanitize_text_field($_REQUEST['del']);

    delete_option( $getDelRequest );
    wp_redirect( VG_MR_ADMIN_PAGE  );  exit;

}


/**
 *
 * If any menu record exits then only display table format
 *
 * @date  19th July 2017
 * @since VG Menu Restrict 1.0
 *
 */

if(!empty($vgmenuName)){
  
?>

<div class="selected-Menu">
<table class="menuRestrict-table">   
    <tr><th>Menu Name</th><th>Action</th></tr>
     <tr><td><?php echo $vgmenuName;?></td><td><a onclick="return confirm('Are you sure want to delete this menu record?');" href="<?php echo VG_MR_ADMIN_PAGE.'&del=vg-menu-restrict-name'; ?>">Remove</a></td></tr>
</table>
</div>

<?php } ?>