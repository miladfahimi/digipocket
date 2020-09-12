<?php

namespace FSPoster\App\Pages\Base\Views;

use FSPoster\App\Providers\Date;
use FSPoster\App\Providers\Pages;
use FSPoster\App\Providers\Helper;

defined( 'ABSPATH' ) or exit;
?>

<div class="fsp-row">
	<div class="fsp-col-12 fsp-title">
		<div class="fsp-title-text">
			<?php echo esc_html__( 'Schedules', 'fs-poster' ); ?>
			<span id="fspSchedulesCount" class="fsp-title-count"><?php echo count( $fsp_params[ 'schedules' ] ); ?></span>
		</div>
		<div class="fsp-title-button">
			<button id="fspRemoveSelected" class="fsp-button fsp-is-red fsp-hide">
				<i class="far fa-trash-alt"></i>
				<span><?php echo esc_html__( 'DELETE', 'fs-poster' ); ?></span>
				<span id="fspSelectedCount" class="fsp-schedule-selected-count">(<span></span>)</span>
			</button>
			<a href="?page=fs-poster-schedules&view=calendar" class="fsp-button fsp-is-info">
				<i class="far fa-calendar-alt"></i><span><?php echo esc_html__( 'CALENDAR', 'fs-poster' ); ?></span>
			</a>
			<button class="fsp-button fsp-is-danger" data-load-modal="add_schedule" id="createNewScheduleBtn">
				<i class="fas fa-plus"></i><span><?php echo esc_html__( 'SCHEDULE', 'fs-poster' ); ?></span>
			</button>
			<a href="https://www.fs-poster.com/documentation/how-to-set-up-a-cron-job-on-wordpress-fs-poster-wp-plugin" target="_blank" class="fsp-button fsp-is-red fsp-tooltip" data-title="<?php echo esc_html__( 'If you want the Schedule module to work on time, please configure Cron Job on your website. Click the button to learn more.', 'fs-poster' ); ?>">
				<i class="far fa-calendar-times"></i><span><?php echo esc_html__( 'HAVE ISSUES?', 'fs-poster' ); ?></span>
			</a>
		</div>
	</div>
	<div class="fsp-col-12 fsp-schedules">
		<?php foreach ( $fsp_params[ 'schedules' ] as $schedule_info )
		{
			$status_btn = 'danger';

			if ( $schedule_info[ 'status' ] === 'finished' )
			{
				$status_btn = 'success';
			}
			else if ( $schedule_info[ 'status' ] === 'paused' )
			{
				$status_btn = 'warning';
			}

			$categoryFilter = (int) $schedule_info[ 'category_filter' ];

			if ( empty( $categoryFilter ) )
			{
				$categoryFiltersTxt = '';
			}
			else
			{
				$getCategNames      = get_term( $categoryFilter );
				$categoryFiltersTxt = ' , Category filter: <u>' . htmlspecialchars( $getCategNames->name ) . '</u>';
			}

			$addTxt = ( isset( $names_array1[ $schedule_info[ 'post_sort' ] ] ) ? ' , Order post by: ' . '<u>' . $names_array1[ $schedule_info[ 'post_sort' ] ] . '</u>' : '' );
			$addTxt .= ( isset( $names_array2[ $schedule_info[ 'post_date_filter' ] ] ) ? ' , Select posts added in: ' . '<u>' . $names_array2[ $schedule_info[ 'post_date_filter' ] ] . '</u>' : '' );

			$post_ids = $schedule_info[ 'save_post_ids' ];
			$post_ids = empty( $post_ids ) ? [] : explode( ',', $post_ids );

			$nextPostDate = $schedule_info[ 'status' ] === 'active' ? Date::dateTime( $schedule_info[ 'next_execute_time' ] ) : '-';
			?>
			<div data-id="<?php echo $schedule_info[ 'id' ]; ?>" class="fsp-schedule">
				<div class="fsp-schedule-checkbox-container">
					<input data-id="<?php echo $schedule_info[ 'id' ]; ?>" type="checkbox" class="fsp-form-checkbox fsp-schedule-checkbox">
				</div>
				<div class="fsp-schedule-icon">
					<i class="fas fa-rocket"></i>
				</div>
				<div class="fsp-schedule-title">
					<div class="fsp-schedule-title-text">
						<?php echo esc_html( Helper::cutText( $schedule_info[ 'title' ], 55 ) ); ?>
						<?php if ( ! empty( $schedule_info[ 'sleep_time_start' ] ) && ! empty( $schedule_info[ 'sleep_time_end' ] ) ) { ?>
							<i class="fas fa-moon fsp-tooltip" data-title="Sleep times: <?php echo Date::time( $schedule_info[ 'sleep_time_start' ] ) . ' - ' . Date::time( $schedule_info[ 'sleep_time_end' ] ); ?>"></i>
						<?php } ?>
					</div>
					<div class="fsp-schedule-title-subtext">
						<?php if ( count( $post_ids ) == 1 )
						{
							echo esc_html__( 'Post ID:', 'fs-poster' ) . ' ' . reset( $post_ids ) . ' ';
							echo strpos( esc_html( get_permalink( reset( $post_ids ) ) ), 'fs_post' ) > -1 ? ', ' . esc_html__( 'Scheduled on DIRECT SHARE', 'fs-poster' ) : '( <a href="' . esc_html( get_permalink( reset( $post_ids ) ) ) . '" target="_blank">' . esc_html( Helper::cutText( get_the_title( reset( $post_ids ) ) ) ) . '</a> )';
						}
						else
						{
							echo esc_html__( 'Post type:', 'fs-poster' ) .' <u>' . esc_html( ucfirst( $schedule_info[ 'post_type_filter' ] ) ) . '</u>' . $categoryFiltersTxt . $addTxt;
						} ?>
					</div>
				</div>
				<div class="fsp-schedule-dates">
					<?php if ( count( $post_ids ) === 1 && $schedule_info[ 'post_freq' ] === 'once' ) { ?>
						<div class="fsp-schedule-dates-row">
							<div class="fsp-schedule-dates-date">
								<i class="far fa-calendar-alt"></i> <?php echo Date::dateTime( $schedule_info[ 'start_date' ] . ' ' . $schedule_info[ 'share_time' ] ); ?>
							</div>
						</div>
					<?php } else { ?>
						<div class="fsp-schedule-dates-row">
							<div class="fsp-schedule-dates-label">
								<?php echo esc_html__( 'Start date', 'fs-poster' ); ?>
							</div>
							<div class="fsp-schedule-dates-date">
								<i class="far fa-calendar-alt"></i> <?php echo Date::dateTime( $schedule_info[ 'start_date' ] . ' ' . $schedule_info[ 'share_time' ] ); ?>
							</div>
						</div>
						<div class="fsp-schedule-dates-row">
							<div class="fsp-schedule-dates-label">
								<?php echo esc_html__( 'Next post', 'fs-poster' ); ?>
							</div>
							<div class="fsp-schedule-dates-date">
								<i class="far fa-calendar-alt"></i> <?php echo $nextPostDate; ?>
							</div>
						</div>
					<?php } ?>
				</div>
				<div class="fsp-schedule-interval">
					<i class="fas fa-sync"></i> <?php echo( count( $post_ids ) == 1 && $schedule_info[ 'post_freq' ] === 'once' ? 'no interval' : ( $schedule_info[ 'interval' ] % 1440 == 0 ? ( $schedule_info[ 'interval' ] / 1440 ) . ' day(s)' : ( $schedule_info[ 'interval' ] % 60 == 0 ? ( $schedule_info[ 'interval' ] / 60 ) . ' hour(s)' : $schedule_info[ 'interval' ] . ' minute(s)' ) ) ); ?>
				</div>
				<div class="fsp-schedule-status-container">
					<span class="fsp-status fsp-is-<?php echo $status_btn; ?>">
						<?php echo esc_html( $schedule_info[ 'status' ] ); ?>
					</span>
					<?php if ( $schedule_info[ 'status' ] === 'active' ) { ?>
						<button type="button" class="fsp-button fsp-is-info fsp-tooltip fsp-change-schedule" data-id="<?php echo $schedule_info[ 'id' ]; ?>" data-title="<?php echo esc_html__( 'Pause shares', 'fs-poster' ); ?>">
							<i class="fa fa-pause"></i></button>
					<?php } else if ( $schedule_info[ 'status' ] === 'paused' ) { ?>
						<button type="button" class="fsp-button fsp-is-info fsp-tooltip fsp-change-schedule" data-id="<?php echo $schedule_info[ 'id' ]; ?>" data-title="<?php echo esc_html__( 'Resume shares', 'fs-poster' ); ?>">
							<i class="fa fa-play"></i></button>
					<?php } ?>
				</div>
				<div class="fsp-schedule-controls">
					<div class="fsp-schedule-control" data-title="<?php echo esc_html__( 'Logs', 'fs-poster' ); ?>" data-load-modal="posts_list" data-parameter-schedule_id="<?php echo $schedule_info[ 'id' ]; ?>" data-fullscreen="true">
						<i class="fas fa-list"></i>
						<span class="fsp-schedule-control-text"><?php echo (int) $schedule_info[ 'shares_count' ]; ?></span>
					</div>
					<?php if ( $schedule_info[ 'status' ] != 'finished' ) { ?>
						<div class="fsp-schedule-control" data-load-modal="edit_schedule" data-parameter-schedule_id="<?php echo $schedule_info[ 'id' ]; ?>">
							<i class="fas fa-pencil-alt"></i>
						</div>
					<?php } else { ?>
						<div class="fsp-schedule-control" data-title="<?php echo esc_html__( 'Re-schedule', 'fs-poster' ); ?>" data-load-modal="edit_schedule" data-parameter-schedule_id="<?php echo $schedule_info[ 'id' ]; ?>">
							<i class="fas fa-sync"></i>
						</div>
					<?php } ?>
					<div data-id="<?php echo $schedule_info[ 'id' ]; ?>" class="fsp-schedule-control fsp-delete-schedule">
						<i class="far fa-trash-alt"></i>
					</div>
				</div>
			</div>

		<?php } ?>
		<div class="fsp-card fsp-emptiness <?php echo empty( $fsp_params[ 'schedules' ] ) ? '' : 'fsp-hide'; ?>">
			<div class="fsp-emptiness-image">
				<img src="<?php echo Pages::asset( 'Base', 'img/empty.svg' ); ?>">
			</div>
			<div class="fsp-emptiness-text">
				<?php echo esc_html__( 'There haven\'t been created any schedules yet.', 'fs-poster' ); ?>
			</div>
		</div>
	</div>
</div>