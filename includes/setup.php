<?php

CFG_Setup::get_instance();

class CFG_Setup {

	/**
	 * @var
	 */
	protected static $_instance;

	/**
	 * @var string
	 */
	public static $_options_key = 'cfg_conditional_groups';

	/**
	 * Only make one instance of the CFG_Setup
	 *
	 * @return CFG_Setup
	 */
	public static function get_instance() {
		if ( ! self::$_instance instanceof CFG_Setup ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Add Hooks and Actions
	 */
	protected function __construct() {
		add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'save_settings'   ) );

		add_filter( 'bp_xprofile_get_groups', array( $this, 'filter_org_groups' ) );
	}

	/**
	 * Customize the XProfile Field Groups.
	 *
	 * @param $groups
	 *
	 * @return array
	 */
	public function filter_org_groups( $groups ) {
		$user = get_user_by( 'id', bp_displayed_user_id() );

		if ( empty( $user->roles[0] ) ) {
			return $groups;
		}

		$role = $user->roles[0];

		$remove_groups = cfg_get_role_groups( $role );


		foreach( $groups as $id => $group ) {
			if ( in_array( $group->id, $remove_groups ) ) {
				unset( $groups[ $id ] );
			}
		}

		// re-key array
		$groups = array_values( $groups );

		return $groups;
	}

	/**
	 * Add options page
	 */
	public function add_plugin_page() {
		add_submenu_page(
			'users.php',
			'Conditional Field Groups',
			'Conditional Groups',
			'manage_options',
			'conditional-field-groups',
			array( $this, 'create_admin_page' )
		);
	}

	/**
	 * Options page callback
	 */
	public function create_admin_page() {
		include( CFG_PATH . 'views/settings.php' );
	}

	public function save_settings() {
		if ( empty( $_POST['cfg_save_nonce'] ) || ! wp_verify_nonce( $_POST['cfg_save_nonce'], 'cfg_save' ) ) {
			return;
		}

		$conditionals = array();

		if ( empty( $_POST['cfg'] ) ) {
			update_option( self::$_options_key, $conditionals );
			return;
		}

		foreach( (array) $_POST['cfg'] as $role => $groups ) {
			$conditionals[ sanitize_title( $role ) ] = array_map( 'absint', array_keys( $groups ) );
		}

		update_option( self::$_options_key, $conditionals );

	}

}

/**
 * @return mixed|void
 */
function cfg_get_conditional_map() {
	return get_option( CFG_Setup::$_options_key, array() );
}

/**
 * Return array of groups to hide
 *
 * @param $role
 *
 * @return array
 */
function cfg_get_role_groups( $role ) {
	$map = cfg_get_conditional_map();

	if ( empty( $map[ $role ] ) ) {
		return array();
	}

	return $map[ $role ];
}