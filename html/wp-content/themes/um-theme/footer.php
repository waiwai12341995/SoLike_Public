<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package um-theme
 */

global $defaults;
?>
</div>
</div><!-- site-content -->
</div><!-- Row -->
<?php do_action( 'um_theme_before_footer' ); ?>
	<footer id="colophon" class="site-footer" role="contentinfo">
		<?php
			// Elementor `footer` location
			if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'footer' ) ) {
				/**
				* Functions hooked in to um_theme_footer action
				*
				* @hooked um_theme_footer_widgets - 10
				* @hooked um_theme_footer_bottom_content - 20
				*/
				do_action( 'um_theme_footer' );
			}
		?>
	</footer>
<?php do_action( 'um_theme_after_footer' ); ?>
<a href="#0" class="scrollToTop"><li class="fas fa-chevron-up" style="display: inline-block;"></li></a>
</div><!-- site-content -->

<?php wp_footer(); ?>
</body>
</html>
