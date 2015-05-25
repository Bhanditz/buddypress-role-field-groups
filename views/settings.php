<?php
global $wp_roles;

$roles = $wp_roles->role_names;

// remove BBPress roles
unset( $roles['bbp_keymaster'] );
unset( $roles['bbp_moderator'] );
unset( $roles['bbp_participant'] );
unset( $roles['bbp_spectator'] );
unset( $roles['bbp_blocked'] );

$roles = array_reverse( $roles );

$map    = cfg_get_conditional_map();
$groups = bp_xprofile_get_groups();

// don't allow the base group to be removed from any profile. Bad things happen
foreach( $groups as $id => $group ) {
	if ( 1 == $group->id ) {
		unset( $groups[ $id ] );
	}
} ?>
<style>
	table {
		border-collapse: collapse;
	}

	td, th {
		min-width:  100px;
		border:     1px solid #ccc;
		padding:    15px;
		text-align: center;
	}

	.container {
		position: relative;
	}

	.groups {
		background: #F1F1F1;
		height:     100%;
		position:   absolute;
		width:      131px;
		top:        0;
		left:       0;
	}

	.groups table {
		position: absolute;
		bottom:   0;
		left:     0;
	}

	.checkboxes {
		max-width: 100%;
		overflow:  scroll;
	}

	td.group {
		width:      50px;
		background: #F1F1F1;
		line-height: 20px;
	}

	th {
		background: #F1F1F1;
	}
</style>

<div class="wrap">
	<h2>BuddPress Conditional Profile Groups</h2>

	<p>Check the boxes below to hide the BuddyPress Profile Group for the corresponding user role.</p>

	<?php if ( empty( $groups ) ) : ?>
		<p>There are no Profile Field Groups</p>
	<?php else : ?>
		<form method="post" action="">

			<?php wp_nonce_field( 'cfg_save', 'cfg_save_nonce' ); ?>
			<?php submit_button(); ?>

			<div class="container">
				<div class="groups">
					<table>
						<tbody>
						<?php foreach ( $groups as $group ) : ?>
							<tr>
								<td class="group"><?php echo $group->name; ?></td>
							</tr>
						<?php endforeach; ?>
						</tbody>
					</table>
				</div>

				<div class="checkboxes">
					<table>
						<thead>
						<tr>
							<th>&nbsp;</th>
							<?php foreach ( $roles as $role ) : ?>
								<th><?php echo $role; ?></th>
							<?php endforeach; ?>
						</tr>
						</thead>

						<tbody>
						<?php foreach ( $groups as $group ) : ?>
							<tr>
								<td class="group"><?php echo $group->name; ?></td>

								<?php foreach ( $roles as $id => $role ) : ?>
									<td>
										<input type="checkbox" <?php checked( in_array( $group->id, cfg_get_role_groups( $id ) ) ); ?> name="cfg[<?php echo $id; ?>][<?php echo $group->id; ?>]" />
									</td>
								<?php endforeach; ?>
							</tr>
						<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>

			<?php submit_button(); ?>

		</form>

	<?php endif; ?>

</div>