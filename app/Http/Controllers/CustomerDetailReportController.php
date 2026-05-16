<?php

namespace App\Http\Controllers;

use App\Models\CustomerBooking;
use App\Services\ExcelExportService;
use Illuminate\Http\Request;

class CustomerDetailReportController extends Controller
{
    protected $excelExportService;

    public function __construct(ExcelExportService $excelExportService)
    {
        $this->excelExportService = $excelExportService;
    }

    public function index(Request $request)
    {
        $query = CustomerBooking::with([
            'primaryDetail.correspondenceDetail',
            'parentCustomer',
        ])->whereNotNull('customer_id');

        // Name Filter
        if ($request->filled('name')) {

            $query->whereHas('primaryDetail', function ($q) use ($request) {

                $q->where(
                    'name',
                    'like',
                    '%'.$request->name.'%'
                );

            });

        }

        // Mobile Filter
        if ($request->filled('mobile')) {

            $query->whereHas(
                'primaryDetail.correspondenceDetail',
                function ($q) use ($request) {

                    $q->where(
                        'telephone_no',
                        'like',
                        '%'.$request->mobile.'%'
                    );

                }
            );

        }

        // Date Filter
        if ($request->filled('from_date')) {

            $query->whereDate(
                'created_at',
                '>=',
                $request->from_date
            );

        }

        if ($request->filled('to_date')) {

            $query->whereDate(
                'created_at',
                '<=',
                $request->to_date
            );

        }

        $customers = $query
            ->latest()
            ->get()
            ->groupBy('customer_id')
            ->map(function ($group) {

                $customer = $group->first();

                $customer->total_bookings = $group->count();

                return $customer;

            })
            ->values();

        return view(
            'reports.customer-detail.index',
            compact('customers')
        );
    }

    public function export(Request $request)
    {
        $query = CustomerBooking::with([
            'primaryDetail.correspondenceDetail',
            'parentCustomer',
        ]);

        // Same filters as index
        if ($request->name) {
            $query->whereHas('primaryDetail', function ($q) use ($request) {
                $q->where(
                    'name',
                    'like',
                    '%'.$request->name.'%'
                );
            });
        }

        if ($request->mobile) {
            $query->whereHas(
                'primaryDetail.correspondenceDetail',
                function ($q) use ($request) {
                    $q->where(
                        'telephone_no',
                        'like',
                        '%'.$request->mobile.'%'
                    );
                }
            );
        }

        if ($request->from_date) {
            $query->whereDate(
                'created_at',
                '>=',
                $request->from_date
            );
        }

        if ($request->to_date) {
            $query->whereDate(
                'created_at',
                '<=',
                $request->to_date
            );
        }

        $customers = $query
            ->whereNotNull('customer_id')
            ->latest()
            ->get()
            ->groupBy('customer_id')
            ->map(function ($group) {

                $customer = $group->first();

                $customer->total_bookings = $group->count();

                return $customer;
            })
            ->values();

        return $this->excelExportService->export(
            $customers,
            'customer-detail-report',
            [
                'Customer ID',
                'Reference Customer',
                'Customer Name',
                'Address',
                'Mobile',
                'Email',
                'Booking Status',
                'Created Date',
            ],
            function ($customer) {

                $primary = $customer->primaryDetail;

                $contact = $primary?->correspondenceDetail;

                $address =
                    $primary?->permanent_address ??
                    (
                        $primary?->city
                        ? $primary->city.', '.$primary->state
                        : 'N/A'
                    );

                $parentCode =
                    $customer->parentCustomer?->customer_code
                    ?? 'Self';

                return [
                    $customer->customer_code ?? 'N/A',
                    $parentCode,
                    $primary?->name ?? 'N/A',
                    $address,
                    $contact?->telephone_no ?? 'N/A',
                    $contact?->email ?? 'N/A',
                    'Booked '.$customer->total_bookings.' Plot',
                    $customer->created_at->format('d-m-Y'),
                ];
            }
        );
    }
}
