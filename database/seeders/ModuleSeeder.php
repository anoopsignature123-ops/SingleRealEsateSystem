<?php

namespace Database\Seeders;

use App\Models\Module;
use Illuminate\Database\Seeder;

class ModuleSeeder extends Seeder
{
    public function run(): void
    {
        $modules = [

            [
                'name' => 'Dashboard',
                'slug' => 'dashboard',
                'route_name' => 'admin.dashboard',
                'active_routes' => 'admin.dashboard*',
                'icon' => 'bi bi-speedometer2',
            ],

            [
                'name' => 'Role Management',
                'slug' => 'roles',
                'route_name' => 'admin.roles.index',
                'active_routes' => 'admin.roles.*',
                'icon' => 'bi bi-shield-lock',
            ],

            [
                'name' => 'User / Staff Management',
                'slug' => 'users',
                'route_name' => 'admin.users.index',
                'active_routes' => 'admin.users.*',
                'icon' => 'bi bi-people',
            ],

            [
                'name' => 'Master',
                'slug' => 'master',
                'icon' => 'bi bi-grid',
            ],

            [
                'name' => 'Company Profile',
                'slug' => 'company-profile',
                'parent_slug' => 'master',
                'route_name' => 'admin.company.index',
                'active_routes' => 'admin.company.*',
                'icon' => 'bi bi-building',
            ],

            [
                'name' => 'Project',
                'slug' => 'project-management',
                'parent_slug' => 'master',
                'route_name' => 'admin.projects.index',
                'active_routes' => 'admin.projects.*',
                'icon' => 'bi bi-folder',
            ],

            [
                'name' => 'Block',
                'slug' => 'blocks',
                'parent_slug' => 'master',
                'route_name' => 'admin.blocks.index',
                'active_routes' => 'admin.blocks.*',
                'icon' => 'bi bi-grid-3x3-gap',
            ],

            [
                'name' => 'Plot Type',
                'slug' => 'plot-types',
                'parent_slug' => 'master',
                'route_name' => 'admin.plot-types.index',
                'active_routes' => 'admin.plot-types.*',
                'icon' => 'bi bi-diagram-3',
            ],

            [
                'name' => 'Plot Detail',
                'slug' => 'plot-details',
                'parent_slug' => 'master',
                'route_name' => 'admin.plot-details.index',
                'active_routes' => 'admin.plot-details.*',
                'icon' => 'bi bi-card-list',
            ],

            [
                'name' => 'PLC / Development Rate',
                'slug' => 'plc-development-rate',
                'parent_slug' => 'master',
                'route_name' => 'admin.plot-rates.index',
                'active_routes' => 'admin.plot-rates.*,'.
                    'admin.plc-rates.*,'.
                    'admin.developments.*',
                'icon' => 'bi bi-currency-rupee',
            ],

            [
                'name' => 'Project Manipulation',
                'slug' => 'project-manipulation',
                'parent_slug' => 'master',
                'route_name' => 'admin.project.manipulation.index',
                'active_routes' => 'admin.project.manipulation.*',
                'icon' => 'bi bi-sliders',
            ],

            [
                'name' => 'Rank / Designation',
                'slug' => 'rank-designation',
                'parent_slug' => 'master',
                'route_name' => 'admin.designations.index',
                'active_routes' => 'admin.designations.*',
                'icon' => 'bi bi-award',
            ],

            [
                'name' => 'Associate',
                'slug' => 'associate',
                'icon' => 'bi bi-person-lines-fill',
            ],

            [
                'name' => 'Associate Tree',
                'slug' => 'associate-tree',
                'parent_slug' => 'associate',
                'route_name' => 'admin.associate-tree',
                'active_routes' => 'admin.associate-tree*',
                'icon' => 'bi bi-diagram-3',
            ],

            [
                'name' => 'Add Associate',
                'slug' => 'add-associate',
                'parent_slug' => 'associate',
                'route_name' => 'admin.associate.create',
                'active_routes' => 'admin.associate.create*',
                'icon' => 'bi bi-person-plus',
            ],

            [
                'name' => 'Associate Details',
                'slug' => 'associate-details',
                'parent_slug' => 'associate',
                'route_name' => 'admin.associate.index',
                'active_routes' => 'admin.associate.index,admin.associate.edit,admin.associate.show',
                'icon' => 'bi bi-person-vcard',
            ],

            [
                'name' => 'Direct Associate',
                'slug' => 'direct-associate',
                'parent_slug' => 'associate',
                'route_name' => 'admin.direct-associate',
                'active_routes' => 'admin.direct-associate*',
                'icon' => 'bi bi-people-fill',
            ],

            [
                'name' => 'Downline Associate',
                'slug' => 'downline-associate',
                'parent_slug' => 'associate',
                'route_name' => 'admin.associate-downline',
                'active_routes' => 'admin.associate-downline*',
                'icon' => 'bi bi-diagram-2-fill',
            ],

            [
                'name' => 'Promotion Report',
                'slug' => 'promotion-report',
                'parent_slug' => 'associate',
                'route_name' => 'admin.promotion.report',
                'active_routes' => 'admin.promotion.report*',
                'icon' => 'bi bi-graph-up-arrow',
            ],

            [
                'name' => 'Plot Booking / Sales',
                'slug' => 'plot-booking',
                'icon' => 'bi bi-house-check',
            ],

            [
                'name' => 'Customer Booking',
                'slug' => 'customer-booking',
                'parent_slug' => 'plot-booking',
                'route_name' => 'admin.customer-booking.index',
                'active_routes' => 'admin.customer-booking*',
                'icon' => 'bi bi-people-fill',
            ],

            [
                'name' => 'Plot Registry',
                'slug' => 'plot-registry',
                'parent_slug' => 'plot-booking',
                'route_name' => 'admin.plot-registry.index',
                'active_routes' => 'admin.plot-registry*',
                'icon' => 'bi bi-journal-check',
            ],

            [
                'name' => 'Cancel Booking',
                'slug' => 'cancel-booking',
                'parent_slug' => 'plot-booking',
                'route_name' => 'admin.cancel-booking.index',
                'active_routes' => 'admin.cancel-booking*',
                'icon' => 'bi bi-x-circle',
            ],

            [
                'name' => 'Plot Transfer',
                'slug' => 'plot-transfer',
                'parent_slug' => 'plot-booking',
                'route_name' => 'admin.plot-transfer.index',
                'active_routes' => 'admin.plot-transfer*',
                'icon' => 'bi bi-arrow-left-right',
            ],

            [
                'name' => 'Allotement & Agreement Letter',
                'slug' => 'booking-letter',
                'parent_slug' => 'plot-booking',
                'route_name' => 'admin.booking-letter.index',
                'active_routes' => 'admin.booking-letter*',
                'icon' => 'bi bi-file-earmark-text',
            ],

            [
                'name' => 'Edit Payment Details',
                'slug' => 'edit-payment-details',
                'parent_slug' => 'plot-booking',
                'route_name' => 'admin.edit-payment-details.index',
                'active_routes' => 'admin.edit-payment-details*',
                'icon' => 'bi bi-cash-stack',
            ],

        ];
        foreach ($modules as $module) {
            $parentId = null;
            if (isset($module['parent_slug'])) {
                $parent = Module::where('slug', $module['parent_slug'])->first();
                $parentId = $parent?->id;
            }
            unset($module['parent_slug']);
            $module['parent_id'] = $parentId;
            Module::updateOrCreate(['slug' => $module['slug']], $module);
        }
    }
}
