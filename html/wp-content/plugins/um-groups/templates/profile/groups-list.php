<?php
/**
 * @Template: Groups Directory
 */
?>

<div class="um">
<?php 
do_action('um_groups_directory_tabs',			$arr_settings );
do_action('um_groups_directory', 				$arr_settings );
do_action('um_groups_directory_footer', 		$arr_settings );
?>
</div>
