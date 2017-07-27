<?php
global $wp_roles;

$roles = $wp_roles->role_names;

// remove BBPress roles
unset( $roles['bbp_keymaster'] );
unset( $roles['bbp_moderator'] );
unset( $roles['bbp_participant'] );
unset( $roles['bbp_spectator'] );
unset( $roles['bbp_blocked'] );
// Administrators can see all fields
unset( $roles['administrator'] );
// Pseudo-role for users who are not logged in
$roles['__public__'] = 'PUBLIC';

$roles = array_reverse( $roles );

$map    = rfg_get_role_map();
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
		top:      0;
		left:     0;
	}

	.groups table th {
		border:   none;
        padding:  15px 15px 16px 15px;
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
	<h2>BuddPress Role Profile Groups</h2>

        <p>Check the boxes below to <b>show</b> the BuddyPress Profile Group to the corresponding user role when they view the site.</p>
        <b>Note:</b>
        <ol>
            <li>Administrators can see all groups.</li>
            <li>The &quot;PUBLIC&quot; role refers to viewers of the site who are not logged in, i.e. the general publlic</li>                                             <li>The &quot;Base&quot; group will always be displayed.</li>
        </ol>

	<?php if ( empty( $groups ) ) : ?>
		<p>There are no Profile Field Groups</p>
	<?php else : ?>
		<form method="post" action="">

			<?php wp_nonce_field( 'rfg_save', 'rfg_save_nonce' ); ?>
			<?php submit_button(); ?>

			<div class="container">
				<div class="groups">
					<table>
						<tbody>
						<tr><th>&nbsp</th></tr>
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
										<input type="checkbox" <?php checked( in_array( $group->id, rfg_get_role_groups( $id ) ) ); ?> name="rfg[<?php echo $id; ?>][<?php echo $group->id; ?>]" />
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