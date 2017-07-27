<?php

RFG_Setup::get_instance();

class RFG_Setup {

	/**
	 * @var
	 */
	protected static $_instance;

	/**
	 * @var string
	 */
	public static $_options_key = 'rfg_role_groups';

	/**
	 * Only make one instance of the RFG_Setup
	 *
	 * @return RFG_Setup
	 */
	public static function get_instance() {
		if ( ! self::$_instance instanceof RFG_Setup ) {
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
     * Note that this does the opposite of what the original plugin does.
     *
	 * @param $groups
	 *
	 * @return array
	 */
	public function filter_org_groups( $groups ) {
		$user = wp_get_current_user();
		if ( empty( $user->roles[0] ) ) {
            // PUBLIC
            $role = '__public__';
		} elseif ( $user->roles[0]  === 'administrator' ){
            // Early exit. Admins can see all fields.
            return $groups;
        } else {
            $role = $user->roles[0];
        }
        $keep_groups = rfg_get_role_groups( $role );
        // Always display the base group
        $keep_groups[] = 1;
		foreach( $groups as $id => $group ) {
			if ( ! in_array( $group->id, $keep_groups ) ) {
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
			'Role Field Groups',
			'Groups Display',
			'manage_options',
			'role-field-groups',
			array( $this, 'create_admin_page' )
		);
	}

	/**
	 * Options page callback
	 */
	public function create_admin_page() {
		include( RFG_PATH . 'views/settings.php' );
	}

	public function save_settings() {
		if ( empty( $_POST['rfg_save_nonce'] ) || ! wp_verify_nonce( $_POST['rfg_save_nonce'], 'rfg_save' ) ) {
			return;
		}

		$roles = array();

		if ( empty( $_POST['rfg'] ) ) {
			update_option( self::$_options_key, $roles );
			return;
		}
		foreach( (array) $_POST['rfg'] as $role => $groups ) {
			$roles[ sanitize_title( $role ) ] = array_map( 'absint', array_keys( $groups ) );
		}

		update_option( self::$_options_key, $roles );

	}

}

/**
 * @return mixed|void
 */
function rfg_get_role_map() {
	return get_option( RFG_Setup::$_options_key, array() );
}

/**
 * Return array of groups to hide
 *
 * @param $role
 *
 * @return array
 */
function rfg_get_role_groups( $role ) {
	$map = rfg_get_role_map();
	if ( empty( $map[ $role ] ) ) {
		return array();
	}

	return $map[ $role ];
}