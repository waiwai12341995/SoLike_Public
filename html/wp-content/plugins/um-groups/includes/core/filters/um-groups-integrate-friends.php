<?php if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * Invite People Query
 */
add_filter("um_groups_invite_front__search_query","um_groups_friends_invite_front_query", 10, 7 );
add_filter("um_groups_invite_front__main_query","um_groups_friends_invite_front_query", 10, 7 );
function um_groups_friends_invite_front_query( $main_query, $group_id, $invite_people, $search_in, $search_keyword, $paginate, $search_query_ids ){
	global $wpdb;

	if( "friends" == $invite_people  && class_exists("UM_Friends_API") ){
		
		$table_name = UM()->Groups()->setup()->db_groups_table;

		$user_id = get_current_user_id();

		$arr_users = array( );

		$friends = UM()->Friends_API()->api()->friends( $user_id );

		if( ! empty( $friends ) ){
			foreach( $friends as $k => $friend ){
				$arr_users[ ] = $friend["user_id1"];
			}
		}
		
		if( ! empty( $search_query_ids ) ){
			$arr_users = array_intersect( $search_query_ids,  $arr_users );
		}

		$search_in = " AND ID IN( ".implode(",", $arr_users )." ) ";	

		if( ! empty( $search_query_ids ) ){
			$main_query = $wpdb->prepare(
				"SELECT DISTINCT ID as invite_user_id  FROM {$wpdb->users} WHERE ID NOT IN( SELECT user_id1 FROM {$table_name} WHERE group_id = %d AND status NOT IN('pending_member_review') ) {$search_in} ORDER BY ID ASC {$paginate} ", 
				$group_id
			); 
		}else{
			$main_query = $wpdb->prepare(
				"SELECT DISTINCT ID as invite_user_id  FROM {$wpdb->users} WHERE ID NOT IN( SELECT user_id1 FROM {$table_name} WHERE group_id = %d  ) {$search_in} ORDER BY ID ASC {$paginate} ", 
				$group_id
			); 
		}

	}

	if( "friends_followers" == $invite_people && class_exists("UM_Friends_API") && class_exists("UM_Followers_API") ){
		
		$table_name = UM()->Groups()->setup()->db_groups_table;

		$user_id = get_current_user_id();

		$arr_users = array( );

		// Followers
		$followers = UM()->Followers_API()->api()->followers( $user_id );

		if( ! empty( $followers ) ){
			foreach( $followers as $k => $follower ){
				$arr_users[ ] = $follower["user_id2"];
			}
		}

		// Friends
		$friends = UM()->Friends_API()->api()->friends( $user_id );

		if( ! empty( $friends ) ){
			foreach( $friends as $k => $friend ){
				$arr_users[ ] = $friend["user_id1"];
			}
		}
		if( ! empty( $search_query_ids ) ){
			$arr_users = array_intersect( $search_query_ids,  $arr_users );
		}

		$arr_users = array_filter( $arr_users );

		$search_in = " AND ID IN( ".implode(",", $arr_users )." ) ";	

		if( ! empty( $search_query_ids ) ){
			$main_query = $wpdb->prepare(
				"SELECT DISTINCT ID as invite_user_id  FROM {$wpdb->users} WHERE ID NOT IN( SELECT user_id1 FROM {$table_name} WHERE group_id = %d AND status NOT IN('pending_member_review') ) {$search_in} ORDER BY ID ASC {$paginate} ", 
				$group_id
			); 
		}else{
			$main_query = $wpdb->prepare(
				"SELECT DISTINCT ID as invite_user_id  FROM {$wpdb->users} WHERE ID NOT IN( SELECT user_id1 FROM {$table_name} WHERE group_id = %d  ) {$search_in} ORDER BY ID ASC {$paginate} ", 
				$group_id
			); 
		}

	}

	return $main_query;
}