<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            array('name' => 'dashboard-view', 'guard_name' => 'admin', 'created_at' => now(), 'updated_at' => now()),

            // Maintenance
            array('name' => 'maintenance-edit', 'guard_name' => 'admin', 'created_at' => now(), 'updated_at' => now()),

            // Notifications
            array('name' => 'push_notifications-create', 'guard_name' => 'admin', 'created_at' => now(), 'updated_at' => now()),
            array('name' => 'email_notifications-create', 'guard_name' => 'admin', 'created_at' => now(), 'updated_at' => now()),
            array('name' => 'sms_notifications-create', 'guard_name' => 'admin', 'created_at' => now(), 'updated_at' => now()),
            array('name' => 'whatsapp_notifications-create', 'guard_name' => 'admin', 'created_at' => now(), 'updated_at' => now()),

            // Roles
            array('name' => 'roles-view', 'guard_name' => 'admin', 'created_at' => now(), 'updated_at' => now()),
            array('name' => 'roles-create', 'guard_name' => 'admin', 'created_at' => now(), 'updated_at' => now()),
            array('name' => 'roles-edit', 'guard_name' => 'admin', 'created_at' => now(), 'updated_at' => now()),
            array('name' => 'roles-delete', 'guard_name' => 'admin', 'created_at' => now(), 'updated_at' => now()),

            // Admins
            array('name' => 'admins-view', 'guard_name' => 'admin', 'created_at' => now(), 'updated_at' => now()),
            array('name' => 'admins-create', 'guard_name' => 'admin', 'created_at' => now(), 'updated_at' => now()),
            array('name' => 'admins-edit', 'guard_name' => 'admin', 'created_at' => now(), 'updated_at' => now()),
            array('name' => 'admins-delete', 'guard_name' => 'admin', 'created_at' => now(), 'updated_at' => now()),
            array('name' => 'admins-block', 'guard_name' => 'admin', 'created_at' => now(), 'updated_at' => now()),

            // Social Media
            array('name' => 'social_media-view', 'guard_name' => 'admin', 'created_at' => now(), 'updated_at' => now()),
            array('name' => 'social_media-create', 'guard_name' => 'admin', 'created_at' => now(), 'updated_at' => now()),
            array('name' => 'social_media-edit', 'guard_name' => 'admin', 'created_at' => now(), 'updated_at' => now()),
            array('name' => 'social_media-delete', 'guard_name' => 'admin', 'created_at' => now(), 'updated_at' => now()),
            array('name' => 'social_media-publish', 'guard_name' => 'admin', 'created_at' => now(), 'updated_at' => now()),
            array('name' => 'social_media-order', 'guard_name' => 'admin', 'created_at' => now(), 'updated_at' => now()),

            // Fixed Sections
            array('name' => 'fixed_sections-view', 'guard_name' => 'admin', 'created_at' => now(), 'updated_at' => now()),
            array('name' => 'fixed_sections-create', 'guard_name' => 'admin', 'created_at' => now(), 'updated_at' => now()),
            array('name' => 'fixed_sections-edit', 'guard_name' => 'admin', 'created_at' => now(), 'updated_at' => now()),
            array('name' => 'fixed_sections-delete', 'guard_name' => 'admin', 'created_at' => now(), 'updated_at' => now()),

            // Settings
            array('name' => 'settings-edit', 'guard_name' => 'admin', 'created_at' => now(), 'updated_at' => now()),
            array('name' => 'cms_settings-edit', 'guard_name' => 'admin', 'created_at' => now(), 'updated_at' => now()),

            // Permissions
            array('name' => 'permissions-view', 'guard_name' => 'admin', 'created_at' => now(), 'updated_at' => now()),
            array('name' => 'permissions-create', 'guard_name' => 'admin', 'created_at' => now(), 'updated_at' => now()),
            array('name' => 'permissions-edit', 'guard_name' => 'admin', 'created_at' => now(), 'updated_at' => now()),
            array('name' => 'permissions-delete', 'guard_name' => 'admin', 'created_at' => now(), 'updated_at' => now()),

            // Users
            array('name' => 'users-view', 'guard_name' => 'admin', 'created_at' => now(), 'updated_at' => now()),
            array('name' => 'users-create', 'guard_name' => 'admin', 'created_at' => now(), 'updated_at' => now()),
            array('name' => 'users-edit', 'guard_name' => 'admin', 'created_at' => now(), 'updated_at' => now()),
            array('name' => 'users-delete', 'guard_name' => 'admin', 'created_at' => now(), 'updated_at' => now()),
            array('name' => 'users-blocked', 'guard_name' => 'admin', 'created_at' => now(), 'updated_at' => now()),

            // Syndicate Users
            array('name' => 'syndicate_users-view', 'guard_name' => 'admin', 'created_at' => now(), 'updated_at' => now()),
            array('name' => 'syndicate_users-create', 'guard_name' => 'admin', 'created_at' => now(), 'updated_at' => now()),
            array('name' => 'syndicate_users-edit', 'guard_name' => 'admin', 'created_at' => now(), 'updated_at' => now()),
            array('name' => 'syndicate_users-delete', 'guard_name' => 'admin', 'created_at' => now(), 'updated_at' => now()),
            array('name' => 'syndicate_users-activate', 'guard_name' => 'admin', 'created_at' => now(), 'updated_at' => now()),
            array('name' => 'syndicate_users-reset_password', 'guard_name' => 'admin', 'created_at' => now(), 'updated_at' => now()),
            array('name' => 'syndicate_users-export', 'guard_name' => 'admin', 'created_at' => now(), 'updated_at' => now()),

            // Countries
            array('name' => 'countries-view', 'guard_name' => 'admin', 'created_at' => now(), 'updated_at' => now()),
            array('name' => 'countries-create', 'guard_name' => 'admin', 'created_at' => now(), 'updated_at' => now()),
            array('name' => 'countries-edit', 'guard_name' => 'admin', 'created_at' => now(), 'updated_at' => now()),
            array('name' => 'countries-delete', 'guard_name' => 'admin', 'created_at' => now(), 'updated_at' => now()),
            array('name' => 'countries-publish', 'guard_name' => 'admin', 'created_at' => now(), 'updated_at' => now()),

            // FAQs
            array('name' => 'faqs-view', 'guard_name' => 'admin', 'created_at' => now(), 'updated_at' => now()),
            array('name' => 'faqs-create', 'guard_name' => 'admin', 'created_at' => now(), 'updated_at' => now()),
            array('name' => 'faqs-edit', 'guard_name' => 'admin', 'created_at' => now(), 'updated_at' => now()),
            array('name' => 'faqs-delete', 'guard_name' => 'admin', 'created_at' => now(), 'updated_at' => now()),
            array('name' => 'faqs-publish', 'guard_name' => 'admin', 'created_at' => now(), 'updated_at' => now()),
            array('name' => 'faqs-order', 'guard_name' => 'admin', 'created_at' => now(), 'updated_at' => now()),

            // Support Categories
            array('name' => 'support_categories-view', 'guard_name' => 'admin', 'created_at' => now(), 'updated_at' => now()),
            array('name' => 'support_categories-create', 'guard_name' => 'admin', 'created_at' => now(), 'updated_at' => now()),
            array('name' => 'support_categories-edit', 'guard_name' => 'admin', 'created_at' => now(), 'updated_at' => now()),
            array('name' => 'support_categories-delete', 'guard_name' => 'admin', 'created_at' => now(), 'updated_at' => now()),

            // Support (Contact Forms)
            array('name' => 'support-view', 'guard_name' => 'admin', 'created_at' => now(), 'updated_at' => now()),
            array('name' => 'support-delete', 'guard_name' => 'admin', 'created_at' => now(), 'updated_at' => now()),

            // Simulation
            array('name' => 'simulation-create', 'guard_name' => 'admin', 'created_at' => now(), 'updated_at' => now()),
        ];

        foreach ($data as $row) {
            \Spatie\Permission\Models\Permission::create($row);
        }
    }
}
