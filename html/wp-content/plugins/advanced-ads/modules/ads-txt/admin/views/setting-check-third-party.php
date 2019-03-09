<div id="advads-ads-txt-check-tp">
    <button class="button" type="button" id="advads-check-ads-txt"><?php esc_html_e( 'Check for existing ads.txt file', 'advanced-ads' ); ?></button>
	<span <?php if ( ! $tpe ) { echo 'style="display: none;"'; } ?> class="advads-error-message" id="advads-ads-txt-tpe"><?php
	printf( esc_html__( 'Another ads.txt already exists: %s', 'advanced-ads' ), $link ); ?></span>
	<span <?php if ( $tpe ) { echo 'style="display: none;"'; } ?> id="advads-ads-txt-tpne"><?php esc_html_e( 'No conflicting ads.txt file found', 'advanced-ads' ); ?></span>
	<span id="advads-ads-txt-tp-error" class="advads-error-message"></span>
</div>
