<?php
if ( ! defined( 'ABSPATH' ) ) 
exit; // Exit if accessed directly

// Admin Functionaity

class Status_exporter_Admin {

	public function __construct() {

	}

	/**
	 * Download in CSV
	 *
	 * @param array  $array
	 * @param string $filename
	 * @param string $delimiter
	 */

	public function status_export_csv_download( $array, $filename = 'export.csv', $delimiter = ',' ) {

		// tell the browser it's going to be a csv file
		header( 'Content-Type: text/csv' );

		// tell the browser we want to save it instead of displaying it
		header( 'Content-Disposition: attachment; filename="' . $filename . '";' );

		// clean output buffer
		ob_end_clean();

		// open raw memory as file so no temp files needed, you might run out of memory though
		$f = fopen( 'php://output', 'w' );

		// loop over the input array
		foreach ( $array as $line ) {
			// generate csv lines from the inner arrays
			fputcsv( $f, $line, $delimiter );
		}

		fclose( $f );

		// flush buffer
		ob_flush();

		// use exit to get rid of unexpected output afterward
		exit();
	}


	/**
	 * Plugin Status export Button Hook
	 *
	 * @param array $plugins
	 */

	public function status_export_action_link( $plugins ) {

		$export_link = wp_nonce_url( 'plugins.php?action=export-status' );
		?>

		<div class="wp-clearfix plugin-export-button">
			<a href="<?php echo esc_attr($export_link); ?>" class="page-title-action"><?php echo esc_html__( 'Export Plugins Status', 'status-exporter' ); ?></a>
		</div>

		<?php
	}

	/**
	 * Plugin Status export Button CSS
	 */

	public function status_export_style() {
		?>
		<style type="text/css">
			.plugin-export-button {
				margin : 20px 0 10px;
			}
		</style>
		<?php
	}

	/**
	 * Plugin Status export Action
	 */

	public function status_export_status_action() {

		if ( isset( $_GET['action'] ) && $_GET['action'] == 'export-status' ) {
			$all_plugins  = get_plugins();
			$plugin_array = $data = array();

			$data[] = array( 'Sr.No', 'Plugin Name', 'Version', 'Status', 'Time' );

			if ( ! empty( $all_plugins ) ) {
				$i = 1;
				foreach ( $all_plugins as $plugin_name => $value ) {

					$plugin_array['no']      = $i;
					$plugin_array['name']    = $value['Name'];
					$plugin_array['version'] = $value['Version'];

					if ( in_array( $plugin_name, apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
						$plugin_array['status'] = esc_html__( 'Active', 'status-exporter' );
					} else {
						$plugin_array['status'] = esc_html__( 'Active', 'status-exporter' );
					}

					$plugin_array['time'] = date( 'd-m-Y H:i:s' );

					$data[] = $plugin_array;

					$i = $i + 1;
				}
			}

			// Filter to change file name
			$file_name = apply_filters( 'status_export_csv_file_name', esc_html__( 'Plugin Status.csv', 'status-exporter' ) );

			// Filter to change csv delimiter
			$delimiter = apply_filters( 'status_export_csv_delimiter', ',' );

			// Export CSV
			$this->status_export_csv_download( $data, $file_name, $delimiter );
			
		}

	}

	/**
	 * Adding Hooks
	 *
	 * @package Status Exporter
	 * @since 1.0
	 */
	function add_hooks() {

		add_action( 'pre_current_active_plugins', array( $this, 'status_export_action_link' ), 1 );

		add_action( 'admin_footer-plugins.php', array( $this, 'status_export_style' ), 10 );

		add_action( 'admin_init', array( $this, 'status_export_status_action' ) );

	}

}
