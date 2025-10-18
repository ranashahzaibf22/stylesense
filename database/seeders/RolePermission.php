<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermission extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {

        $permission = array(

            //Counter
            ['name' => 'view-Counter', 'value' => 'Counter'],
            ['name' => 'create-Counter', 'value' => 'Counter'],
            ['name' => 'edit-Counter', 'value' => 'Counter'],
            ['name' => 'delete-Counter', 'value' => 'Counter'],


            //Role
            ['name' => 'view-Role', 'value' => 'Role'],
            ['name' => 'create-Role', 'value' => 'Role'],
            ['name' => 'edit-Role', 'value' => 'Role'],
            ['name' => 'delete-Role', 'value' => 'Role'],

            //Administator
            ['name' => 'view-Administator', 'value' => 'Administator'],
            ['name' => 'create-Administator', 'value' => 'Administator'],
            ['name' => 'edit-Administator', 'value' => 'Administator'],
            ['name' => 'delete-Administator', 'value' => 'Administator'],

            //Show
            ['name' => 'view-Show', 'value' => 'Show'],
            ['name' => 'create-Show', 'value' => 'Show'],
            ['name' => 'edit-Show', 'value' => 'Show'],
            ['name' => 'delete-Show', 'value' => 'Show'],

            //Event
            ['name' => 'view-Event', 'value' => 'Event'],
            ['name' => 'create-Event', 'value' => 'Event'],
            ['name' => 'edit-Event', 'value' => 'Event'],
            ['name' => 'delete-Event', 'value' => 'Event'],


            //designer
            ['name' => 'view-Designer', 'value' => 'Designer'],
            ['name' => 'create-Designer', 'value' => 'Designer'],
            ['name' => 'edit-Designer', 'value' => 'Designer'],
            ['name' => 'delete-Designer', 'value' => 'Designer'],

             //Seating-Plan
            ['name' => 'view-Seating-Plan', 'value' => 'Seating Plan'],
            ['name' => 'create-Seating-Plan', 'value' => 'Seating Plan'],
            ['name' => 'edit-Seating-Plan', 'value' => 'Seating Plan'],
            ['name' => 'delete-Seating-Plan', 'value' => 'Seating Plan'],

             //Seating-Plan
            ['name' => 'view-cms', 'value' => 'CMS'],
            ['name' => 'create-cms', 'value' => 'CMS'],
            ['name' => 'edit-cms', 'value' => 'CMS'],
            ['name' => 'delete-cms', 'value' => 'CMS'],

            //Model-Staff
            ['name' => 'view-Model-Staff', 'value' => 'Models & back stage'],
            ['name' => 'create-Model-Staff', 'value' => 'Models & back stage'],
            ['name' => 'edit-Model-Staff', 'value' => 'Models & back stage'],
            ['name' => 'delete-Model-Staff', 'value' => 'Models & back stage'],


            //Seat
            ['name' => 'view-Seat', 'value' => 'Seat'],
            ['name' => 'create-Seat', 'value' => 'Seat'],
            ['name' => 'edit-Seat', 'value' => 'Seat'],
            ['name' => 'delete-Seat', 'value' => 'Seat'],


            //Pending RSVP
            ['name' => 'view-Pending-Rsvp', 'value' => 'Pending-Rsvp'],
            ['name' => 'action-Pending-Rsvp', 'value' => 'Pending-Rsvp'],

            //Alerts and Notification
            ['name' => 'view-notifications', 'value' => 'Alerts-&-Notification'],
            ['name' => 'action-notifications', 'value' => 'Alerts-&-Notification'],

            //Rejected RSVP
            ['name' => 'view-Rejected-Rsvp', 'value' => 'Rejected-Rsvp'],
            ['name' => 'action-Rejected-Rsvp', 'value' => 'Rejected-Rsvp'],

            //RSVP List
            ['name' => 'view-Rsvp-List', 'value' => 'Rsvp-List'],
            ['name' => 'view-Rsvp-email', 'value' => 'Rsvp-List'],
            ['name' => 'view-Rsvp-phone', 'value' => 'Rsvp-List'],
            ['name' => 'action-Rsvp-List', 'value' => 'Rsvp-List'],


            //Waiting List
            ['name' => 'view-Waiting-List', 'value' => 'Waiting-List'],
            ['name' => 'action-Waiting-List', 'value' => 'Waiting-List'],

            //Pre Approved List
            ['name' => 'view-Pre-Approved-List', 'value' => 'Pre-Approved-List'],
            ['name' => 'action-Pre-Approved-List', 'value' => 'Pre-Approved-List'],


            //Event Report
            ['name' => 'view-Event-Report', 'value' => 'Event-Report'],
            ['name' => 'action-Event-Report', 'value' => 'Event-Report'],

            //Person Report
            ['name' => 'view-Person-Report', 'value' => 'Person-Report'],
            ['name' => 'action-Person-Report', 'value' => 'Person-Report'],


            //sub Event Report
            ['name' => 'view-sub-event-report', 'value' => 'Sub-Event-Report'],
            ['name' => 'action-sub-event-report', 'value' => 'Sub-Event-Report'],

            //Rsvp Report
            ['name' => 'view-Rsvp-Report', 'value' => 'Rsvp-Report'],
            ['name' => 'action-Rsvp-Report', 'value' => 'Rsvp-Report'],

            //Event History
            ['name' => 'view-Event-History', 'value' => 'Event-History'],
            ['name' => 'action-Event-History', 'value' => 'Event-History'],
            
            //Promo-Code
            ['name' => 'view-Promo-Code', 'value' => 'Promo-Code'],
            ['name' => 'action-Promo-Code', 'value' => 'Promo-Code'],

            //Projects
            ['name' => 'view-Projects', 'value' => 'Projects'],


            //subscribe email
            ['name' => 'view-subscribe-email', 'value' => 'subscribe-email'],
            ['name' => 'action-subscribe-email', 'value' => 'subscribe-email'],

            //setting
            ['name' => 'view-setting', 'value' => 'setting'],

            //app-access
            ['name' => 'app-access', 'value' => 'App-Access'],


        );

        foreach ($permission as $per) {
            $assign[] = $per['name'];
            Permission::create($per);

        }

        $super_admin = Role::create(['name' => 'Super-Admin']);
        $super_admin->givePermissionTo($assign);

        $user = User::find(1);
        $user->assignRole('Super-Admin');

    }
}
