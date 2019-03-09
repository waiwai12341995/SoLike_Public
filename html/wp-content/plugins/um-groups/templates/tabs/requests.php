
<?php

$privacy = UM()->Groups()->api()->get_privacy_slug( get_the_ID() );

$args = UM()->Groups()->api()->get_members( get_the_ID(), 'requests' );

$args['group_id'] = get_the_ID();

$args = apply_filters('um_groups_user_lists_args', $args );
$args = apply_filters('um_groups_user_lists_args__requests', $args );

echo "<div class='um-groups-wrapper'>";
    
 	UM()->Groups()->api()->get_template("list-users", $args );

echo "</div>";


