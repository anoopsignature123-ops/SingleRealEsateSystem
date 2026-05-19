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
                'name' => 'Customer List',
                'slug' => 'customer-list',
                'parent_slug' => 'plot-booking',
                'route_name' => 'admin.customer-list.index',
                'active_routes' => 'admin.customer-list.index',
                'icon' => 'bi bi-list-ul',
            ],

            [
                'name' => 'Edit Plot Booking',
                'slug' => 'edit-plot-booking',
                'parent_slug' => 'plot-booking',
                'route_name' => 'admin.edit-plot-booking.index',
                'active_routes' => 'admin.edit-plot-booking.index',
                'icon' => 'bi bi-pencil-square',
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

            // Parent Module
            [
                'name' => 'Payment',
                'slug' => 'payment',
                'icon' => 'bi bi-wallet2',
            ],

            // Child Modules

            [
                'name' => 'Receipt Reprint',
                'slug' => 'receipt-reprint',
                'parent_slug' => 'payment',
                'route_name' => 'admin.receipt-reprint.index',
                'active_routes' => 'admin.receipt-reprint*',
                'icon' => 'bi bi-receipt',
            ],

            [
                'name' => 'One Time Payment',
                'slug' => 'one-time-payment',
                'parent_slug' => 'payment',
                'route_name' => 'admin.one-time-payment.index',
                'active_routes' => 'admin.one-time-payment*',
                'icon' => 'bi bi-cash',
            ],

            [
                'name' => 'EMI Payment',
                'slug' => 'emi-payment',
                'parent_slug' => 'payment',
                'route_name' => 'admin.emi-payment.index',
                'active_routes' => 'admin.emi-payment*',
                'icon' => 'bi bi-calendar-check',
            ],

            [
                'name' => 'Multiple Cheque Clearance',
                'slug' => 'multiple-cheque-clearance',
                'parent_slug' => 'payment',
                'route_name' => 'admin.multiple-cheque-clearance.index',
                'active_routes' => 'admin.multiple-cheque-clearance*',
                'icon' => 'bi bi-bank',
            ],

            [
                'name' => 'Edit Payment Detail',
                'slug' => 'payment-edit',
                'parent_slug' => 'payment',
                'route_name' => 'admin.edit-payment-details.index',
                'active_routes' => 'admin.edit-payment-details*',
                'icon' => 'bi bi-pencil-square',
            ],

            [
                'name' => 'Update EMI Date',
                'slug' => 'update-emi-date',
                'parent_slug' => 'payment',
                'route_name' => 'admin.update-emi-date.index',
                'active_routes' => 'admin.update-emi-date*',
                'icon' => 'bi bi-calendar-event',
            ],

            [
                'name' => 'Associate Advance',
                'slug' => 'associate-advance',
                'parent_slug' => 'payment',
                'route_name' => 'admin.associate-advances.index',
                'active_routes' => 'admin.associate-advances.*',
                'icon' => 'bi bi-person-badge',
            ],

            [
                'name' => 'Generate EMI',
                'slug' => 'generate-emi',
                'parent_slug' => 'payment',
                'route_name' => 'admin.generate-emi.index',
                'active_routes' => 'admin.generate-emi*',
                'icon' => 'bi bi-gear',
            ],

            // Parent Module
            [
                'name' => 'Report',
                'slug' => 'report',
                'icon' => 'bi bi-clipboard-data',
            ],

            // Child Modules
            [
                'name' => 'Agent Details',
                'slug' => 'agent-detail-report',
                'parent_slug' => 'report',
                'route_name' => 'admin.agent-detail-report.index',
                'active_routes' => 'admin.agent-detail-report*',
                'icon' => 'bi bi-person-badge',
            ],

            [
                'name' => 'Customer Details',
                'slug' => 'customer-details-report',
                'parent_slug' => 'report',
                'route_name' => 'admin.customer-details-report.index',
                'active_routes' => 'admin.customer-details-report*',
                'icon' => 'bi bi-people',
            ],
            [
                'name' => 'EMI Due Status',
                'slug' => 'emi-due-status-report',
                'parent_slug' => 'report',
                'route_name' => 'admin.emi-due-status-report.index',
                'active_routes' => 'admin.emi-due-status-report*',
                'icon' => 'bi bi-calendar-x',
            ],

            [
                'name' => 'EMI Payment Dues',
                'slug' => 'emi-payment-dues-report',
                'parent_slug' => 'report',
                'route_name' => 'admin.emi-due-date-report.index',
                'active_routes' => 'admin.emi-due-date-report*',
                'icon' => 'bi bi-cash-stack',
            ],

            [
                'name' => 'One Time Payment Dues',
                'slug' => 'one-time-payment-dues-report',
                'parent_slug' => 'report',
                'route_name' => 'admin.one-time-payment-dues-report.index',
                'active_routes' => 'admin.one-time-payment-dues-report*',
                'icon' => 'bi bi-wallet2',
            ],

            [
                'name' => 'Plot Booking Details',
                'slug' => 'plot-booking-details-report',
                'parent_slug' => 'report',
                'route_name' => 'admin.plot-booking-details-report.index',
                'active_routes' => 'admin.plot-booking-details-report*',
                'icon' => 'bi bi-house-check',
            ],

            [
                'name' => 'EMI Payment Details',
                'slug' => 'emi-payment-details-report',
                'parent_slug' => 'report',
                'route_name' => 'admin.emi-payment-details-report.index',
                'active_routes' => 'admin.emi-payment-details-report*',
                'icon' => 'bi bi-calendar-check',
            ],

            [
                'name' => 'Full Payment Details',
                'slug' => 'full-payment-details-report',
                'parent_slug' => 'report',
                'route_name' => 'admin.full-payment-details-report.index',
                'active_routes' => 'admin.full-payment-details-report*',
                'icon' => 'bi bi-credit-card',
            ],

            [
                'name' => 'Registerd Plot Details',
                'slug' => 'registered-plot-details-report',
                'parent_slug' => 'report',
                'route_name' => 'admin.registered-plot-details-report.index',
                'active_routes' => 'admin.registered-plot-details-report*',
                'icon' => 'bi bi-file-earmark-check',
            ],

            [
                'name' => 'Without Registerd Plot Report',
                'slug' => 'without-registered-plot-report',
                'parent_slug' => 'report',
                'route_name' => 'admin.without-registered-plot-report.index',
                'active_routes' => 'admin.without-registered-plot-report*',
                'icon' => 'bi bi-file-earmark-x',
            ],

            [
                'name' => 'Associate Direct Report',
                'slug' => 'associate-direct-report',
                'parent_slug' => 'report',
                'route_name' => 'admin.associate-direct-report.index',
                'active_routes' => 'admin.associate-direct-report*',
                'icon' => 'bi bi-diagram-2',
            ],

            [
                'name' => 'Associate Chain Report',
                'slug' => 'associate-chain-report',
                'parent_slug' => 'report',
                'route_name' => 'admin.associate-chain-report.index',
                'active_routes' => 'admin.associate-chain-report*',
                'icon' => 'bi bi-diagram-3',
            ],

            [
                'name' => 'Associate Business Report',
                'slug' => 'associate-business-report',
                'parent_slug' => 'report',
                'route_name' => 'admin.associate-business-report.index',
                'active_routes' => 'admin.associate-business-report*',
                'icon' => 'bi bi-graph-up',
            ],

            [
                'name' => 'Cancel Plot Booking Report',
                'slug' => 'cancel-plot-booking-report',
                'parent_slug' => 'report',
                'route_name' => 'admin.cancel-plot-booking-report.index',
                'active_routes' => 'admin.cancel-plot-booking-report*',
                'icon' => 'bi bi-x-circle',
            ],

            [
                'name' => 'Customer Ledger',
                'slug' => 'customer-ledger-report',
                'parent_slug' => 'report',
                'route_name' => 'admin.customer-ledger-report.index',
                'active_routes' => 'admin.customer-ledger-report*',
                'icon' => 'bi bi-journal-text',
            ],
            [
                'name' => 'Payment Collection & Dues Summary',
                'slug' => 'payment-collection-dues-summary-report',
                'parent_slug' => 'report',
                'route_name' => 'admin.payment-collection-dues-summary-report.index',
                'active_routes' => 'admin.payment-collection-dues-summary-report*',
                'icon' => 'bi bi-cash-stack',
            ],

            [
                'name' => 'Dues Installment Report',
                'slug' => 'dues-installment-report',
                'parent_slug' => 'report',
                'route_name' => 'admin.dues-installment-report.index',
                'active_routes' => 'admin.dues-installment-report*',
                'icon' => 'bi bi-calendar2-check',
            ],

            [
                'name' => 'Cheque Details',
                'slug' => 'cheque-details-report',
                'parent_slug' => 'report',
                'route_name' => 'admin.cheque-details-report.index',
                'active_routes' => 'admin.cheque-details-report*',
                'icon' => 'bi bi-bank',
            ],

            [
                'name' => 'EMI Dues Summary',
                'slug' => 'emi-dues-summary-report',
                'parent_slug' => 'report',
                'route_name' => 'admin.emi-dues-summary-report.index',
                'active_routes' => 'admin.emi-dues-summary-report*',
                'icon' => 'bi bi-wallet2',
            ],

            [
                'name' => 'Daily Collection Report',
                'slug' => 'daily-collection-report',
                'parent_slug' => 'report',
                'route_name' => 'admin.daily-collection-report.index',
                'active_routes' => 'admin.daily-collection-report*',
                'icon' => 'bi bi-calendar-day',
            ],

            [
                'name' => 'Daily Dues Report',
                'slug' => 'daily-dues-report',
                'parent_slug' => 'report',
                'route_name' => 'admin.daily-dues-report.index',
                'active_routes' => 'admin.daily-dues-report*',
                'icon' => 'bi bi-calendar-x',
            ],

            [
                'name' => 'Agent Summary Details',
                'slug' => 'agent-summary-details-report',
                'parent_slug' => 'report',
                'route_name' => 'admin.agent-summary-details-report.index',
                'active_routes' => 'admin.agent-summary-details-report*',
                'icon' => 'bi bi-person-lines-fill',
            ],

            [
                'name' => 'New Booking Payment Details',
                'slug' => 'new-booking-payment-details-report',
                'parent_slug' => 'report',
                'route_name' => 'admin.new-booking-payment-details-report.index',
                'active_routes' => 'admin.new-booking-payment-details-report*',
                'icon' => 'bi bi-house-add',
            ],

            [
                'name' => 'Associate Team New Booking Details',
                'slug' => 'associate-team-new-booking-details-report',
                'parent_slug' => 'report',
                'route_name' => 'admin.associate-team-new-booking-details-report.index',
                'active_routes' => 'admin.associate-team-new-booking-details-report*',
                'icon' => 'bi bi-people-fill',
            ],

            [
                'name' => 'Bounced Cheque Details',
                'slug' => 'bounced-cheque-details-report',
                'parent_slug' => 'report',
                'route_name' => 'admin.bounced-cheque-details-report.index',
                'active_routes' => 'admin.bounced-cheque-details-report*',
                'icon' => 'bi bi-x-circle',
            ],

            [
                'name' => 'Associate Advance Report',
                'slug' => 'associate-advance-report',
                'parent_slug' => 'report',
                'route_name' => 'admin.associate-advance-report.index',
                'active_routes' => 'admin.associate-advance-report*',
                'icon' => 'bi bi-currency-rupee',
            ],

            [
                'name' => 'Cancel Plot Report',
                'slug' => 'cancel-plot-report',
                'parent_slug' => 'report',
                'route_name' => 'admin.cancel-plot-report.index',
                'active_routes' => 'admin.cancel-plot-report*',
                'icon' => 'bi bi-file-earmark-x',
            ],

            [
                'name' => 'Monthly EMI Report',
                'slug' => 'monthly-emi-report',
                'parent_slug' => 'report',
                'route_name' => 'admin.monthly-emi-report.index',
                'active_routes' => 'admin.monthly-emi-report*',
                'icon' => 'bi bi-calendar3',
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
