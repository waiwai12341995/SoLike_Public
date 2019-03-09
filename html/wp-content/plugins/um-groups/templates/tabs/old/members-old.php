
<?php 

    $privacy = UM()->Groups()->api()->get_privacy_slug( get_the_ID() );
?>


<?php if(  UM()->Groups()->api()->can_manage_group( get_the_ID(), null, $privacy ) || um_groups_admin_all_access() ):?>
    <table id="um_groups_manage_members" class="display">
        <thead>
            <tr>
                <th style="width:35%"><?php _e("Name","um-groups");?></th>
                <th style="width:15%"><?php _e("Status","um-groups");?></th>
                <th style="width:15%"><?php _e("Group Role","um-groups");?></th>
                <th style="width:18%"><?php _e("Actions","um-groups");?></th>
                <th><?php _e("Hidden Status","um-groups");?></th>
                <th><?php _e("Hidden Role","um-groups");?></th>
                <th><?php _e("Hidden User Login","um-groups");?></th>
                <th><?php _e("Hidden User Email","um-groups");?></th>
                <th><?php _e("Hidden Timestamp","um-groups");?></th>
            </tr>
        </thead>
        <tbody>
            
        </tbody>
        <tfoot>
            <tr>
                <th><?php _e("Name","um-groups");?></th>
                <th><?php _e("Status","um-groups");?></th>
                <th><?php _e("Group Role","um-groups");?></th>
                <th><?php _e("Actions","um-groups");?></th>
                <th><?php _e("Hidden Status","um-groups");?></th>
                <th><?php _e("Hidden Role","um-groups");?></th>
                <th><?php _e("Hidden User Login","um-groups");?></th>
                <th><?php _e("Hidden User Email","um-groups");?></th>
                <th><?php _e("Hidden Timestamp","um-groups");?></th>
            </tr>
        </tfoot>
    </table>
<?php else: ?>
     <table id="um_groups_members" data-group-status="approved" class="display">
        <thead>
            <tr>
                <th></th>
            </tr>
        </thead>
        <tbody>
            
        </tbody>
        <tfoot>
            <tr>
                <th><?php _e("Name","um-groups");?></th>
            </tr>
        </tfoot>
    </table>
<?php endif; ?>