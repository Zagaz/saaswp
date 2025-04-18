<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://wpankit.com/
 * @since      1.7.0
 *
 * @package    hab_Hide_Admin_Bar_Based_On_User_Roles
 * @subpackage hab_Hide_Admin_Bar_Based_On_User_Roles/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    hab_Hide_Admin_Bar_Based_On_User_Roles
 * @subpackage hab_Hide_Admin_Bar_Based_On_User_Roles/admin
 * @author     Ankit Panchal <ankitmaru@live.in>
 */
class hab_Hide_Admin_Bar_Based_On_User_Roles_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.7.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.7.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version The version of this plugin.
	 *
	 * @since    1.7.0
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.7.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in hab_Hide_Admin_Bar_Based_On_User_Roles_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The hab_Hide_Admin_Bar_Based_On_User_Roles_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		if ( isset( $_GET['page'] ) && $_GET['page'] == 'hide-admin-bar-settings' ) {

			wp_enqueue_style( 'select2-css', plugin_dir_url( __FILE__ ) . 'css/select2.min.css', array(), $this->version, 'all' );

			wp_enqueue_style( 'ultimakit_bootstrap_main', plugin_dir_url( __FILE__ ) . 'css/bootstrap.min.css', array(), $this->version, 'all' );
			wp_enqueue_style( 'ultimakit_bootstrap_rtl', plugin_dir_url( __FILE__ ) . 'css/bootstrap.rtl.min.css', array(), $this->version, 'all' );
			// Enqueue toastr CSS.
			wp_enqueue_style( 'toastr-css', plugin_dir_url( __FILE__ ) . 'css/toastr.min.css', array(), $this->version, 'all' );
			
			wp_enqueue_style('dashicons');
                            
			wp_enqueue_style( 'tagsinput-css', plugin_dir_url( __FILE__ ) . 'css/jquery.tagsinput.min.css', array(), $this->version, 'all' );
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/main.css', array(), $this->version, 'all' );
			wp_enqueue_style( $this->plugin_name.'-admin', plugin_dir_url( __FILE__ ) . 'css/hide-admin-bar-based-on-user-roles-admin.css', array(), $this->version, 'all' );
			
		}

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.7.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in hab_Hide_Admin_Bar_Based_On_User_Roles_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The hab_Hide_Admin_Bar_Based_On_User_Roles_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		if ( isset( $_GET['page'] ) && $_GET['page'] == 'hide-admin-bar-settings' ) {

			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'ultimakit_bootstrap_bundle', plugin_dir_url( __FILE__ ) . 'js/bootstrap.bundle.min.js', array( 'jquery' ), $this->version, false );
			// Enqueue toastr.js.
			wp_enqueue_script( 'toastr-js', plugin_dir_url( __FILE__ ) . 'js/toastr.min.js', array( 'jquery' ), $this->version, true );

			wp_enqueue_script( 'tagsinput-js', plugin_dir_url( __FILE__ ) . 'js/jquery.tagsinput.min.js', array( 'jquery' ), $this->version, false );

			wp_enqueue_script(
				'silent-installer', 
				plugin_dir_url(__FILE__) . 'js/silent-installer.js', 
				array('jquery'), 
				'1.0', 
				true
			);
			
			wp_localize_script('silent-installer', 'silent_installer_vars', array(
				'ajaxurl' => admin_url('admin-ajax.php'),
				'nonce' => wp_create_nonce('silent_installer'),
				'installing_text' => __('Installing...', 'hide-admin-bar-based-on-user-roles'),
				'activated_text' => __('Installed & Activated!', 'hide-admin-bar-based-on-user-roles'),
				'error_text' => __('Installation Failed', 'hide-admin-bar-based-on-user-roles'),
				'already_installed' => __('Already Installed & Active', 'hide-admin-bar-based-on-user-roles'),
				'checking_status' => __('Checking plugin status...', 'hide-admin-bar-based-on-user-roles'),
				'downloading' => __('Downloading plugin...', 'hide-admin-bar-based-on-user-roles'),
				'installing' => __('Installing plugin...', 'hide-admin-bar-based-on-user-roles'),
				'activating' => __('Activating plugin...', 'hide-admin-bar-based-on-user-roles')
			));

			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/hide-admin-bar-based-on-user-roles-admin.js', array( 'jquery' ), $this->version, false );
			$args = array(
				'url'       => admin_url( 'admin-ajax.php' ),
				'hba_nonce' => wp_create_nonce( 'hba-nonce' ),
			);
			wp_localize_script( $this->plugin_name, 'ajaxVar', $args );

			
		}


	}


	public function generate_admin_menu_page() {

		add_options_page( __( 'Hide Admin Bar Settings', 'hide-admin-bar-based-on-user-roles' ), __( 'Hide Admin Bar Settings', 'hide-admin-bar-based-on-user-roles' ), 'manage_options', 'hide-admin-bar-settings', array(
			$this,
			'hide_admin_bar_settings'
		) );

	}

	public function hide_admin_bar_settings() {

		$settings      = get_option( "hab_settings" );
		$hab_reset_key = get_option( "hab_reset_key" );

		if ( ! empty( $hab_reset_key ) && isset( $_GET["reset_plugin"] ) && $_GET["reset_plugin"] == $hab_reset_key ) {
			update_option( "hab_settings", "" );
			update_option( "hab_reset_key", rand( 0, 999999999 ) );
			echo '<script>window.location.reload();</script>';
		}
		?>
		<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #6610F2;">
			<div class="container-fluid p-2">
				<a class="navbar-brand" href="#">
					<img src="<?php echo esc_url( plugin_dir_url( __FILE__ ).'images/hide-admin-bar-logo.svg' ); ?>"  class="d-inline-block align-top" alt="hide-admin-bar-based-on-user-roles-logo" width="225px">
					<div class="wpuk-version-info"><?php esc_html_e( 'Current version: ', 'hide-admin-bar-based-on-user-roles' ); ?><?php echo HIDE_ADMIN_BAR_BASED_ON_USER_ROLES; ?></div>
				</a>
				
				<div class="navbar-nav ml-auto">
					<a class="nav-item nav-link" target="_blank" href="https://wordpress.org/support/plugin/hide-admin-bar-based-on-user-roles/reviews/#new-post" style="color: #ffffff; margin-right: 20px"><?php esc_html_e( 'Leave Feedback', 'hide-admin-bar-based-on-user-roles' ); ?></a>
				</div>
			</div>
		</nav>

		<div class="wrap">
			<div class="container-fluid module-container">
				<div class="row">
					<?php
						$menu_active_class = '';
						$menu_active_class = 'active show';
					?>
					<!-- Nav tabs -->
					<ul class="nav nav-tabs" id="wpukTabs" role="tablist">
						<li class="nav-item" role="presentation">
							<a class="nav-link <?php echo $menu_active_class; ?>" id="hab-modules-tab" data-bs-toggle="tab" href="#hab-modules" role="tab" aria-controls="hab-modules" aria-selected="true"><?php esc_html_e( 'Settings', 'hide-admin-bar-based-on-user-roles' ); ?></a>
						</li>

						<li class="nav-item" role="presentation">
							<a class="nav-link" id="tools-tab" data-bs-toggle="tab" style="font-weight: 600; text-decoration: underline;" href="#tools" role="tab" aria-controls="tools" aria-selected="true"><?php esc_html_e( 'Powerful WordPress Tools', 'hide-admin-bar-based-on-user-roles' ); ?></a>
						</li>

						<li class="nav-item" role="presentation">
							<a class="nav-link" href="https://wordpress.org/support/plugin/hide-admin-bar-based-on-user-roles/" target="_blank"><?php esc_html_e( 'Help', 'hide-admin-bar-based-on-user-roles' ); ?></a>
						</li>
					</ul>
					<!-- Tab panes -->
					<div class="tab-content" id="wpukTabsContent">
						<div class="tab-pane fade show <?php echo $menu_active_class; ?>" id="hab-modules" role="tabpanel" aria-labelledby="modules-tab">
							<div class="row">
								<form class="form-sample">
									<div class="row">
										<div class="col-md-12">
											<div class="form-group row">
												<label class="col-sm-6 col-form-label"><?php esc_html_e( 'Hide Admin Bar for All Users', 'hide-admin-bar-based-on-user-roles' ); ?></label>
												<div class="col-sm-6">
													<?php
													$disableForAll = ( isset( $settings["hab_disableforall"] ) ) ? $settings["hab_disableforall"] : "";
													$checked       = ( $disableForAll == 'yes' ) ? "checked" : "";
													echo '<div class="icheck-square">
															<input tabindex="5" ' . $checked . ' type="checkbox" id="hide_for_all">
														</div>';
													?>
												</div>
											</div>
										</div>
									</div>
									<?php if ( $disableForAll == "no" || empty( $disableForAll ) ) { ?>
										<div class="row mt-3">
											<div class="col-md-12">
												<div class="form-group row">
													<label class="col-sm-6 col-form-label"><?php esc_html_e( 'Hide Admin Bar for All Guests Users', 'hide-admin-bar-based-on-user-roles' ); ?></label>
													<div class="col-sm-6">
														<?php
														$disableForAllGuests = ( isset( $settings["hab_disableforallGuests"] ) ) ? $settings["hab_disableforallGuests"] : "";
														$checkedGuests       = ( $disableForAllGuests == 'yes' ) ? "checked" : "";
														echo '<div class="icheck-square">
															<input tabindex="5" ' . $checkedGuests . ' type="checkbox" id="hide_for_all_guests">
														</div>';
														?>

													</div>
												</div>
											</div>
										</div>
										<div class="row mt-3">
											<div class="col-md-12">
												<div class="form-group row">
													<label class="col-sm-6 col-form-label"><?php esc_html_e( 'User Roles', 'hide-admin-bar-based-on-user-roles' ); ?>
														<br/><br/><?php esc_html_e( 'Hide admin bar for selected user roles.', 'hide-admin-bar-based-on-user-roles' ); ?>
													</label>
													<div class="col-sm-6">
														<?php
														global $wp_roles;
														$exRoles = ( isset( $settings["hab_userRoles"] ) ) ? $settings["hab_userRoles"] : "";
														$checked = '';

														$roles = $wp_roles->get_names();
														if ( is_array( $roles ) ) {
															foreach ( $roles as $key => $value ):
																if ( is_array( $exRoles ) ) {
																	$checked = ( in_array( $key, $exRoles ) ) ? "checked" : "";
																}

																echo '<div class="icheck-square">
																<input name="userRoles[]" ' . $checked . ' tabindex="5" type="checkbox" value="' . $key . '">&nbsp;&nbsp;' . $value . '
															</div>';
															endforeach;
														}
														?>

													</div>
												</div>
											</div>
										</div>
										<div class="row mt-3">
											<div class="col-md-12">
												<div class="form-group row">
													<label class="col-sm-6 col-form-label"><?php esc_html_e( 'Capabilities Blacklist', 'hide-admin-bar-based-on-user-roles' );
														echo '<br />';
														esc_html_e( 'Hide admin bar for selected user capabilities', 'hide-admin-bar-based-on-user-roles' ); ?></label>
													<div class="col-sm-6">
														<?php
														$caps = ( isset( $settings["hab_capabilities"] ) ) ? $settings["hab_capabilities"] : "";
														?>
														<div class="icheck-square">
															<textarea name="had_capabilities"
																	id="had_capabilities" rows="5" cols="50"><?php echo $caps; ?></textarea>
														</div>
													</div>
												</div>
											</div>
										</div>
									<?php } ?>
									<div class="row mt-3">
										<div class="col-md-12">
											<button type="button" class="btn btn-primary btn-fw"
													id="submit_roles"><?php esc_html_e( "Save Changes", 'hide-admin-bar-based-on-user-roles' ); ?></button>
										</div>
										<div class="col-md-12">
											<br/>
											<p><?php esc_html_e( "You can reset plugin settings by visiting this url without login to admin panel. Keep it safe.", 'hide-admin-bar-based-on-user-roles' ); ?>
												<br/><a href="<?php echo admin_url() . "options-general.php?page=hide-admin-bar-settings&reset_plugin=" . $hab_reset_key; ?>"
														target="_blank"><?php echo admin_url() . "options-general.php?page=hide-admin-bar-settings&reset_plugin=" . $hab_reset_key; ?></a>
											</p>
										</div>
									</div>
								</form>
								<script>
									if (jQuery('#had_capabilities').length) {
										jQuery('#had_capabilities').tagsInput({
											'width': '100%',
											'height': '75%',
											'interactive': true,
											'defaultText': '<?php _e('Add More', 'hide-admin-bar-based-on-user-roles'); ?>',
											'removeWithBackspace': true,
											'minChars': 0,
											'maxChars': 20, // if not provided there is no limit
											'placeholderColor': '#666666'
										});
									}
								</script>
							</div>
						</div> <!-- WordPress Tab End --->
						
						<div class="tab-pane fade" id="tools" role="tabpanel" aria-labelledby="tools-tab">
							<div class="row">
								<div class="ultimakit-promo w-100 my-4">
									<div class="card border-0 w-100">
										<div class="card-body p-4">
											<div class="row g-4 w-100 mx-0">
												<div class="col-lg-8">
													<div class="feature-content">
														<span class="badge bg-primary-subtle text-primary mb-2"><?php esc_html_e( '192+ Powerful Modules', 'hide-admin-bar-based-on-user-roles' ); ?></span>
														<h3 class="text-primary mb-3"><?php esc_html_e( 'UltimaKit For WP – All-in-One WordPress Toolkit for SEO, Customization, and Performance', 'hide-admin-bar-based-on-user-roles' ); ?></h3>
														<div class="features-list mb-4">
															<p class="text-secondary mb-3">
																<?php esc_html_e( 'Simplify your WordPress management with UltimaKit – the all-in-one toolkit that replaces 25+ plugins. Popular modules include:', 'hide-admin-bar-based-on-user-roles' ); ?>
															</p>
															<div class="module-highlights">
																<span class="module-tag"><?php esc_html_e( 'GDPR Compliance', 'hide-admin-bar-based-on-user-roles' ); ?></span>
																<span class="module-tag"><?php esc_html_e( 'Hide Admin Bar', 'hide-admin-bar-based-on-user-roles' ); ?></span>
																<span class="module-tag"><?php esc_html_e( 'Custom Post Types', 'hide-admin-bar-based-on-user-roles' ); ?></span>
																<span class="module-tag"><?php esc_html_e( 'SEO Tools', 'hide-admin-bar-based-on-user-roles' ); ?></span>
																<span class="module-tag"><?php esc_html_e( 'Post &amp; Page Order', 'hide-admin-bar-based-on-user-roles' ); ?></span>
																<span class="module-tag"><?php esc_html_e( 'Admin Activity Logger', 'hide-admin-bar-based-on-user-roles' ); ?></span>
																<span class="module-tag"><?php esc_html_e( 'Gravity Forms: Address Autocomplete', 'hide-admin-bar-based-on-user-roles' ); ?></span>
																<span class="module-tag"><?php esc_html_e( 'Gravity Forms: AI Analysis', 'hide-admin-bar-based-on-user-roles' ); ?></span>
																<span class="module-tag"><?php esc_html_e( 'Gravity Forms: Form Analytics(Most advanced analytics)', 'hide-admin-bar-based-on-user-roles' ); ?></span>
																<span class="module-tag"><?php esc_html_e( 'WooCommerce Modules', 'hide-admin-bar-based-on-user-roles' ); ?></span>
															</div>
														</div>
														<button href="#" data-plugin-slug="ultimakit-for-wp" class="install-plugin btn btn-primary btn-lg">
															<?php esc_html_e( 'Install UltimaKit Now', 'hide-admin-bar-based-on-user-roles' ); ?>
														</button>

														<div class="loader-wrapper">
															<div class="loader-bar"></div>
														</div>

														<div class="progress-steps">
															<div class="step" data-step="check">
																<i class="dashicons dashicons-search"></i>
																<?php esc_html_e( 'Checking plugin status...', 'hide-admin-bar-based-on-user-roles' ); ?>
															</div>
															<div class="step" data-step="download">
																<i class="dashicons dashicons-download"></i>
																<?php esc_html_e( 'Downloading plugin...', 'hide-admin-bar-based-on-user-roles' ); ?>
															</div>
															<div class="step" data-step="install">
																<i class="dashicons dashicons-admin-plugins"></i>
																<?php esc_html_e( 'Installing plugin...', 'hide-admin-bar-based-on-user-roles' ); ?>
															</div>
															<div class="step" data-step="activate">
																<i class="dashicons dashicons-yes"></i>
																<?php esc_html_e( 'Activating plugin...', 'hide-admin-bar-based-on-user-roles' ); ?>
															</div>
														</div>
														
														<a href="https://wpultimakit.com" target="_blank" class="btn btn-primary btn-lg">
															<?php esc_html_e( 'Learn More About UltimaKit', 'hide-admin-bar-based-on-user-roles' ); ?>
														</a>
													</div>
												</div>
												<div class="col-lg-4">
													<div class="stats-container">
														<div class="stat-item">
															<?php printf(esc_html__( '%1$s25+%2$s %3$sPlugins Replaced%2$s', 'hide-admin-bar-based-on-user-roles' ),'<span class="stat-number">','</span>','<span class="stat-label">'); ?>
														</div>
														<div class="stat-item">
															<?php printf(esc_html__( '%1$s192+%2$s %3$sPowerful Modules%2$s', 'hide-admin-bar-based-on-user-roles' ),'<span class="stat-number">','</span>','<span class="stat-label">'); ?>
														</div>
														<div class="stat-item">
															<?php printf(esc_html__( '%1$s20+%2$s %3$sWooCommerce Modules%2$s', 'hide-admin-bar-based-on-user-roles' ),'<span class="stat-number">','</span>','<span class="stat-label">'); ?>
														</div>
														<div class="stat-item">
															<?php printf(esc_html__( '%1$s15+%2$s %3$sGravity Forms Modules%2$s', 'hide-admin-bar-based-on-user-roles' ),'<span class="stat-number">','</span>','<span class="stat-label">'); ?>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div class="ultimakit-promo w-100 my-4">
									<div class="card border-0 w-100">
										<div class="card-body p-4">
											<div class="row g-4 w-100 mx-0">
												<div class="col-lg-8">
													<div class="feature-content">
														<span class="badge bg-primary-subtle text-primary mb-2"><?php esc_html_e( 'Smart Note-Taking for WordPress', 'hide-admin-bar-based-on-user-roles' ); ?></span>
														<h3 class="text-primary mb-3"><?php esc_html_e( 'Smart Note-Taking for WordPress', 'hide-admin-bar-based-on-user-roles' ); ?></h3>
														<div class="features-list mb-4">
															<p class="text-secondary mb-3">
															<?php esc_html_e( 'Enhance your WordPress experience with intelligent note-taking directly in your dashboard. Perfect for content creators, developers, and site managers!', 'hide-admin-bar-based-on-user-roles' ); ?>
															</p>
															<div class="module-highlights">
																<span class="module-tag"><?php esc_html_e( 'Quick Notes Dashboard', 'hide-admin-bar-based-on-user-roles' ); ?></span>
																<span class="module-tag"><?php esc_html_e( 'Rich Text Editor', 'hide-admin-bar-based-on-user-roles' ); ?></span>
																<span class="module-tag"><?php esc_html_e( 'Task Management', 'hide-admin-bar-based-on-user-roles' ); ?></span>
																<span class="module-tag"><?php esc_html_e( 'Post Draft Notes', 'hide-admin-bar-based-on-user-roles' ); ?></span>
																<span class="module-tag"><?php esc_html_e( 'Team Collaboration', 'hide-admin-bar-based-on-user-roles' ); ?></span>
																<span class="module-tag"><?php esc_html_e( 'Custom Categories', 'hide-admin-bar-based-on-user-roles' ); ?></span>
																<span class="module-tag"><?php esc_html_e( 'Markdown Support', 'hide-admin-bar-based-on-user-roles' ); ?></span>
																<span class="module-tag"><?php esc_html_e( 'File Attachments', 'hide-admin-bar-based-on-user-roles' ); ?></span>
															</div>
														</div>
														<button href="#" data-plugin-slug="noteflow" class="install-plugin btn btn-primary btn-lg">
															<?php esc_html_e( 'Install Noteflow Now', 'hide-admin-bar-based-on-user-roles' ); ?>
														</button>

														<div class="loader-wrapper">
															<div class="loader-bar"></div>
														</div>

														<div class="progress-steps">
															<div class="step" data-step="check">
																<i class="dashicons dashicons-search"></i>
																<?php esc_html_e( 'Checking plugin status...', 'hide-admin-bar-based-on-user-roles' ); ?>
															</div>
															<div class="step" data-step="download">
																<i class="dashicons dashicons-download"></i>
																<?php esc_html_e( 'Downloading plugin...', 'hide-admin-bar-based-on-user-roles' ); ?>
															</div>
															<div class="step" data-step="install">
																<i class="dashicons dashicons-admin-plugins"></i>
																<?php esc_html_e( 'Installing plugin...', 'hide-admin-bar-based-on-user-roles' ); ?>
															</div>
															<div class="step" data-step="activate">
																<i class="dashicons dashicons-yes"></i>
																<?php esc_html_e( 'Activating plugin...', 'hide-admin-bar-based-on-user-roles' ); ?>
															</div>
														</div>

														<a href="https://wordpress.org/plugins/noteflow/" target="_blank" class="btn btn-primary btn-lg">
															<?php esc_html_e( 'Learn More About Noteflow', 'hide-admin-bar-based-on-user-roles' ); ?>
														</a>
													</div>
												</div>
												<div class="col-lg-4">
													<div class="stats-container">
														<div class="stat-item">
															<?php printf(esc_html__( '%1$s100%%%2$s %3$sFree Forever%2$s', 'hide-admin-bar-based-on-user-roles' ),'<span class="stat-number">','</span>','<span class="stat-label">'); ?>
														</div>
														<div class="stat-item">
															<?php printf(esc_html__( '%1$s5★%2$s %3$sUser Rating%2$s', 'hide-admin-bar-based-on-user-roles' ),'<span class="stat-number">','</span>','<span class="stat-label">'); ?>
														</div>
														<div class="stat-item">
															<?php printf(esc_html__( '%1$s1-Click%2$s %3$sQuick Notes%2$s', 'hide-admin-bar-based-on-user-roles' ),'<span class="stat-number">','</span>','<span class="stat-label">'); ?>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								
							</div>
						</div> <!-- WordPress Tab End --->
					
					</div>
					<!-- Duplicate the above block for each module you have -->
				</div>
			</div>
		</div>

		<?php
	}

	public function save_user_roles() {
		global $wpdb;

		if ( current_user_can( 'manage_options' ) && wp_verify_nonce( $_POST['hbaNonce'], 'hba-nonce' ) ) {

			$UserRoles      = $_REQUEST['UserRoles'];
			$caps           = sanitize_text_field( str_replace( "&nbsp;", "", $_REQUEST['caps'] ) );
			$disableForAll  = $_REQUEST['disableForAll'];
			$auto_hide_time = $_REQUEST['auto_hide_time'];
			$autoHideFlag   = $_REQUEST['autoHideFlag'];
			$forGuests      = $_REQUEST['forGuests'];

			$settings                      = array();
			$settings['hab_disableforall'] = $disableForAll;

			if ( $disableForAll == 'no' ) {
				$settings['hab_userRoles']           = $UserRoles;
				$settings['hab_capabilities']        = $caps;
				$settings['hab_auto_hide_time']      = $auto_hide_time;
				$settings['hab_auto_hide_flag']      = $autoHideFlag;
				$settings['hab_disableforallGuests'] = $forGuests;
			}
			update_option( "hab_settings", $settings );
			echo "Success";
		} else {
			echo "Failed";
		}
		wp_die();
	}

	public function upgrader_process_complete() {
	}

	public function enqueue_silent_installer() {
        
    }

    public function check_plugin_status() {
        // Check nonce
        if (!check_ajax_referer('silent_installer', 'nonce', false)) {
            wp_send_json_error(array('message' => 'Invalid security token.'));
        }

        // Check user capabilities
        if (!current_user_can('install_plugins')) {
            wp_send_json_error(array('message' => 'You do not have permission to install plugins.'));
        }

        $plugin_slug = sanitize_text_field($_POST['plugin_slug']);
        
        if (empty($plugin_slug)) {
            wp_send_json_error(array('message' => 'Plugin slug is required.'));
        }

        require_once ABSPATH . 'wp-admin/includes/plugin.php';
        
        $all_plugins = get_plugins();
        $plugin_base_file = false;
        
        foreach ($all_plugins as $file => $plugin) {
            if (strpos($file, $plugin_slug . '/') === 0) {
                $plugin_base_file = $file;
                break;
            }
        }

        wp_send_json_success(array(
            'installed' => !empty($plugin_base_file),
            'active' => !empty($plugin_base_file) && is_plugin_active($plugin_base_file)
        ));
    }

    public function handle_silent_install_plugin() {
        // Check nonce
        if (!check_ajax_referer('silent_installer', 'nonce', false)) {
            wp_send_json_error(array('message' => 'Invalid security token.'));
        }

        // Check user capabilities
        if (!current_user_can('install_plugins')) {
            wp_send_json_error(array('message' => 'You do not have permission to install plugins.'));
        }

        $plugin_slug = sanitize_text_field($_POST['plugin_slug']);
        
        if (empty($plugin_slug)) {
            wp_send_json_error(array('message' => 'Plugin slug is required.'));
        }

        // Include required files
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
        require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
        require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
        require_once ABSPATH . 'wp-admin/includes/class-plugin-upgrader.php';

        // Check if plugin is already installed
        $installed_plugins = get_plugins();
        $plugin_base_file = false;

        foreach ($installed_plugins as $file => $plugin) {
            if (strpos($file, $plugin_slug . '/') === 0) {
                $plugin_base_file = $file;
                break;
            }
        }

        // If plugin is not installed, install it
        if (!$plugin_base_file) {
            try {
                // Get plugin info
                $api = plugins_api('plugin_information', array(
                    'slug' => $plugin_slug,
                    'fields' => array(
                        'short_description' => false,
                        'sections' => false,
                        'requires' => false,
                        'rating' => false,
                        'ratings' => false,
                        'downloaded' => false,
                        'last_updated' => false,
                        'added' => false,
                        'tags' => false,
                        'compatibility' => false,
                        'homepage' => false,
                        'donate_link' => false,
                    ),
                ));

                if (is_wp_error($api)) {
                    wp_send_json_error(array('message' => $api->get_error_message()));
                }

                $upgrader = new Plugin_Upgrader(new WP_Ajax_Upgrader_Skin());
                $install_result = $upgrader->install($api->download_link);

                if (is_wp_error($install_result)) {
                    wp_send_json_error(array('message' => $install_result->get_error_message()));
                }

                $plugin_base_file = $upgrader->plugin_info();

            } catch (Exception $e) {
                wp_send_json_error(array('message' => $e->getMessage()));
            }
        }

        // Activate the plugin
        if ($plugin_base_file) {
            try {
                $activation_result = activate_plugin($plugin_base_file);
                
                if (is_wp_error($activation_result)) {
                    wp_send_json_error(array('message' => $activation_result->get_error_message()));
                }

                wp_send_json_success(array(
                    'message' => 'Plugin installed and activated successfully',
                    'plugin_file' => $plugin_base_file
                ));

            } catch (Exception $e) {
                wp_send_json_error(array('message' => $e->getMessage()));
            }
        } else {
            wp_send_json_error(array('message' => 'Plugin installation failed.'));
        }
    }
}
