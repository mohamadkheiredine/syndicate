<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ContentRolesPermissionsSeeder extends Seeder
{
    /**
     * Assigns permissions to the four roles added alongside RolesSeeder's
     * original three (super-admin, admin, developer). "Set A" below is
     * every module that exists in the old CMS's real admin sidebar
     * (syndicate-website-master's includes/left-panel.php, confirmed
     * exhaustively) - none of the newer, this-project-only modules
     * (Members Payment, Reporting, Documents, Income, Expenses, Yearly
     * Payment, Election Fees, Push Notifications, Home/Splash/Ad Sliders,
     * Achievements) are in that old sidebar, so they're excluded from all
     * three content-scoped roles below.
     *
     */
    public function run()
    {
        $setA = [
            'logo-edit',
            'banners-view', 'banners-create', 'banners-edit', 'banners-delete',
            'news-view', 'news-create', 'news-edit', 'news-delete',
            'syndicate_activities-view', 'syndicate_activities-create', 'syndicate_activities-edit', 'syndicate_activities-delete',
            'syndicate_offers-view', 'syndicate_offers-create', 'syndicate_offers-edit', 'syndicate_offers-delete',
            'syndicate_family-view', 'syndicate_family-create', 'syndicate_family-edit', 'syndicate_family-delete',
            'our_team-edit',
            'about_syndicate-edit',
            'terms_conditions-edit',
            'syndicate_advertisement-view', 'syndicate_advertisement-create', 'syndicate_advertisement-edit', 'syndicate_advertisement-delete',
            'syndicate_users-view', 'syndicate_users-create', 'syndicate_users-edit', 'syndicate_users-delete',
            'syndicate_users-activate', 'syndicate_users-reset_password', 'syndicate_users-export',
            'syndicate_others_advertisement-view', 'syndicate_others_advertisement-create', 'syndicate_others_advertisement-edit', 'syndicate_others_advertisement-delete',
            'cms_settings-edit',
        ];

        $setAPublish = [
            'banners-publish',
            'news-publish',
            'syndicate_activities-publish',
            'syndicate_offers-publish',
            'syndicate_family-publish',
            'our_team-publish',
            'about_syndicate-publish',
            'terms_conditions-publish',
            'syndicate_advertisement-publish',
            'syndicate_others_advertisement-publish',
        ];

        Role::findByName('management-admin', 'admin')
            ->givePermissionTo(array_merge(['dashboard-view'], $setA, $setAPublish));

        Role::findByName('content-super-admin', 'admin')
            ->givePermissionTo(array_merge(['dashboard-view'], $setA, $setAPublish));

        Role::findByName('content-admin', 'admin')
            ->givePermissionTo(array_merge(['dashboard-view'], $setA));

        Role::findByName('developer', 'admin')
            ->givePermissionTo(Permission::where('guard_name', 'admin')->pluck('name'));
    }
}
