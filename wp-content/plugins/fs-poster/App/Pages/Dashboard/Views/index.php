<?php

namespace FSPoster\App\Pages\Dashboard\Views;

use FSPoster\App\Providers\Pages;

defined( 'ABSPATH' ) or exit;
?>

<script>
	fspConfig.comparison = {
		data: <?php echo json_encode( $fsp_params[ 'report3' ][ 'data' ] ); ?>,
		labels: <?php echo json_encode( $fsp_params[ 'report3' ][ 'labels' ] ); ?>
	}

	fspConfig.accComparison = {
		data: <?php echo json_encode( $fsp_params[ 'report4' ][ 'data' ] ); ?>,
		labels: <?php echo json_encode( $fsp_params[ 'report4' ][ 'labels' ] ); ?>
	}
</script>

<div class="fsp-row">
	<div class="fsp-col-12">
		<div class="fsp-dashboard-stats fsp-row">
			<div class="fsp-dashboard-stats-col fsp-col-12 fsp-col-md-6 fsp-col-lg-3">
				<img class="fsp-dashboard-stats-icon" src="<?php echo Pages::asset( 'Dashboard', 'img/fsp-icon-share.svg' ); ?>">
				<div>
					<span class="fsp-dashboard-stats-text"><?php echo (int) $fsp_params[ 'sharesThisMonth' ][ 'c' ]; ?></span>
					<span class="fsp-dashboard-stats-subtext"><?php echo esc_html__( 'Shares in this month', 'fs-poster' ); ?></span>
				</div>
			</div>
			<div class="fsp-dashboard-stats-col fsp-col-12 fsp-col-md-6 fsp-col-lg-3">
				<img class="fsp-dashboard-stats-icon" src="<?php echo Pages::asset( 'Dashboard', 'img/fsp-icon-pointer.svg' ); ?>">
				<div>
					<span class="fsp-dashboard-stats-text"><?php echo (int) $fsp_params[ 'hitsThisMonth' ][ 'c' ]; ?></span>
					<span class="fsp-dashboard-stats-subtext"><?php echo esc_html__( 'Clicks in this month', 'fs-poster' ); ?></span>
				</div>
			</div>
			<div class="fsp-dashboard-stats-col fsp-col-12 fsp-col-md-6 fsp-col-lg-3">
				<img class="fsp-dashboard-stats-icon" src="<?php echo Pages::asset( 'Dashboard', 'img/fsp-icon-people.svg' ); ?>">
				<div>
					<span class="fsp-dashboard-stats-text"><?php echo (int) $fsp_params[ 'accounts' ][ 'c' ]; ?></span>
					<span class="fsp-dashboard-stats-subtext"><?php echo esc_html__( 'Total accounts', 'fs-poster' ); ?></span>
				</div>
			</div>
			<div class="fsp-dashboard-stats-col fsp-col-12 fsp-col-md-6 fsp-col-lg-3">
				<img class="fsp-dashboard-stats-icon" src="<?php echo Pages::asset( 'Dashboard', 'img/fsp-icon-calendar.svg' ); ?>">
				<div>
					<span class="fsp-dashboard-stats-text"><?php echo (int) $fsp_params[ 'hitsThisMonthSchedule' ][ 'c' ]; ?></span>
					<span class="fsp-dashboard-stats-subtext"><?php echo esc_html__( 'Clicks from schedules', 'fs-poster' ); ?></span>
				</div>
			</div>
		</div>
	</div>
	<div class="fsp-dashboard-graphs fsp-col-12 fsp-col-md-6">
		<div class="fsp-card">
			<div class="fsp-card-title">
				<?php echo esc_html__( 'Shared posts count', 'fs-poster' ); ?>
				<select id="fspReports_sharesTypes" class="fsp-select2-single">
					<option value="dayly"><?php echo esc_html__( 'Daily', 'fs-poster' ); ?></option>
					<option value="monthly"><?php echo esc_html__( 'Monthly', 'fs-poster' ); ?></option>
					<option value="yearly"><?php echo esc_html__( 'Annually', 'fs-poster' ); ?></option>
				</select>
			</div>
			<div class="fsp-card-body fsp-p-20">
				<canvas id="fspReports_sharesChart"></canvas>
			</div>
		</div>
	</div>
	<div class="fsp-dashboard-graphs fsp-col-12 fsp-col-md-6">
		<div class="fsp-card">
			<div class="fsp-card-title">
				<?php echo esc_html__( 'Clicks count', 'fs-poster' ); ?>
				<select id="fspReports_clicksTypes" class="fsp-select2-single">
					<option value="dayly"><?php echo esc_html__( 'Daily', 'fs-poster' ); ?></option>
					<option value="monthly"><?php echo esc_html__( 'Monthly', 'fs-poster' ); ?></option>
					<option value="yearly"><?php echo esc_html__( 'Annually', 'fs-poster' ); ?></option>
				</select>
			</div>
			<div class="fsp-card-body fsp-p-20">
				<canvas id="fspReports_clicksChart"></canvas>
			</div>
		</div>
	</div>
	<div class="fsp-dashboard-graphs fsp-col-12 fsp-col-md-6">
		<div class="fsp-card">
			<div class="fsp-card-title">
				<?php echo esc_html__( 'Social networks comparison (by clicks)', 'fs-poster' ); ?>
				<div></div>
			</div>
			<div class="fsp-card-body fsp-p-20">
				<canvas id="fspReports_comparisonChart"></canvas>
			</div>
		</div>
	</div>
	<div class="fsp-dashboard-graphs fsp-col-12 fsp-col-md-6">
		<div class="fsp-card">
			<div class="fsp-card-title">
				<?php echo esc_html__( 'Accounts comparison (by clicks)', 'fs-poster' ); ?>
				<div></div>
			</div>
			<div class="fsp-card-body fsp-p-20">
				<canvas id="fspReports_accComparisonChart"></canvas>
			</div>
		</div>
	</div>
</div>