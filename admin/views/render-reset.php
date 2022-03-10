<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


?>

<div class="wrap">

    <div id="icon-themes" class="icon32"></div>
    <h2>Troubleshoot Facebook for WooCommerce</h2>

	<?php $active_tab = ( isset( $_GET['tab'] ) ) ? sanitize_text_field( $_GET['tab'] ) : 'delete-options'; ?>

    <h2 class="nav-tab-wrapper">
        <a href="?page=troubleshoot-facebook-for-woocommerce&tab=delete-options"
           class="nav-tab <?php echo 'delete-options' === $active_tab ? 'nav-tab-active' : ''; ?>">
            Delete options
        </a>

        <a href="?page=troubleshoot-facebook-for-woocommerce&tab=connection-test"
           class="nav-tab <?php echo 'connection-test' === $active_tab ? 'nav-tab-active' : ''; ?>">
            Connection test
        </a>

    </h2>

	<?php if ( 'delete-options' === $active_tab ) : ?>

        <h3>Delete all WooCommerce for Facebook options</h3>
        <h4>This is only for when you want to completely reset your connection with Facebook, after all else has
            failed.</h4>

        <h4>You have <?php echo esc_html( count( $options ) ); ?> Facebook for WooCommerce options stored in your
            database.</h4>


        <form action="/wp-admin/admin-post.php" method="post">

            <input type="hidden" name="reset" value="true">
			<?php wp_nonce_field( 'deletefacebook_' . get_current_user_id() ); ?>
            <input type="hidden" name="action" value="delete_facebook_options">

			<?php
			echo submit_button(
				$text = "Delete all options",
				$type = 'delete button-primary',
				null,
				$wrap = true,
				array( 'style' => 'background: #d63638;border: #d63638;' )
			);
			?>

        </form>

        <div class="postbox">
            <div class="inside">
                <h2>Previously stored Facebook configuration data</h2>
                <table class="widefat">
                    <tbody>
					<?php
					$i = 0;
					foreach ( $options

					as $option ): ?>
                    <tr <?php echo ( $i % 2 == 0 ) ? 'class="alternate"' : ''; ?> >
                        <td class="row-title">

                    <?php echo esc_textarea( $option ); ?>

                        </td>
                        <td>

                    <?php
                    $option_value = get_option( $option, '' );
                    echo esc_textarea( $option_value ); ?>

                        </td>
						<?php $i ++;
						endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

	<?php endif; ?>
	<?php if ( 'connection-test' === $active_tab ) : ?>

		<?php
		/**
		 * Connection test requires Facebook for WooCommerce to be active
		 */
		if ( ! is_plugin_active( 'facebook-for-woocommerce/facebook-for-woocommerce.php' ) ): ?>

            <form method="post" action="options.php">

				<?php settings_fields( 'facebook_connection_test' ); ?>
				<?php do_settings_sections( 'troubleshoot-facebook-connection-test' ); ?>
				<?php echo submit_button( 'Save proxy settings' ); ?>

            </form>

		<?php endif; ?>

	<?php endif; ?>

</div>

