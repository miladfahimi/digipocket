<?php

namespace FSPoster\App\Providers;

abstract class SocialNetwork
{
	abstract public static function callbackURL ();

	protected static function error ( $message = '' )
	{
		if ( empty( $message ) )
		{
			$message = esc_html__( 'An error occurred while processing your request! Please close the window and try again!' );
		}

		echo '<div>' . esc_html( $message ) . '</div>';

		?>
		<script type="application/javascript">
			if ( typeof window.opener.accountAdded === "function" )
			{
				window.opener.FSPoster.alert( "<?php echo esc_html( $message ); ?>" );
				window.close();
			}
		</script>
		<?php

		exit();
	}

	protected static function closeWindow ()
	{
		echo '<div>' . esc_html__( 'Loading...' ) . '</div>';

		?>
		<script type="application/javascript">
			if ( typeof window.opener.accountAdded === "function" )
			{
				window.opener.accountAdded();
				window.close();
			}
		</script>
		<?php

		exit;
	}
}