<?php
/**
 * Displays the class schedule.
 *
 * @since   0.1.0
 * @package CrossFit
 */

// Don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

$classes = array();
$times   = array();

$class_posts = get_posts( array(
	'post_type'   => 'class',
	'numberposts' => - 1,
) );

if ( $class_posts ) {

	foreach ( $class_posts as $class ) {

		$day  = get_post_meta( $class->ID, '_day', true );
		$time = get_post_meta( $class->ID, '_time', true );
		$fire = get_post_meta( $class->ID, '_fire', true );

		$classes[ $day ][ $time ] = array(
			'ID'   => $class->ID,
			'fire' => $fire ? true : false,
		);

		if ( ! in_array( $time, $times ) ) {
			$times[] = $time;
		}
	}

	sort( $times, SORT_NUMERIC );
}

if ( $classes ) :

	$weekdays = array(
		'Sunday',
		'Monday',
		'Tuesday',
		'Wednesday',
		'Thursday',
		'Friday',
		'Saturday',
	);
	?>
	<div class="crossfit-schedule row">
		<?php foreach ($weekdays as $i => $day) : ?>
		<div class="day <?php echo strtolower( $day ); ?>">
			<h3 class="day-name">
				<?php echo $day; ?>
			</h3>

			<?php
			foreach ( $times as $time ) :
				$class = isset( $classes[ strtolower( $day ) ][ $time ] ) ? $classes[ strtolower( $day ) ][ $time ] : false;
				?>
				<div class="class <?php echo $class ? '' : 'blank'; echo $class['fire'] ? 'fire' : ''; ?>">

					<?php

					if ( $class && is_admin() ) {
						?>
						<a href="<?php echo get_delete_post_link( $class['ID'] ); ?>" onclick="return confirm('Delete class?');">
							<?php
							echo date( 'g:iA', strtotime( $time ) );
							echo $class['fire'] ? '&nbsp;(fire)' : '';
							echo '<span class="dashicons dashicons-trash"></span>';
							?>
						</a>
						<?php
					} elseif ( $class ) {

						echo date( 'g:iA', strtotime( $time ) );
						echo $class['fire'] ? '&nbsp;<span class="fa fa-fire"></span>' : '';
					} else {
						echo '&nbsp;';
					}
					?>
				</div>
			<?php endforeach; ?>
		</div>
		<?php endforeach; ?>
	</div>
<?php
endif;