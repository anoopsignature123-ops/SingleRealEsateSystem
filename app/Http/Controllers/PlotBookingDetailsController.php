<?php

namespace App\Http\Controllers;

use App\Models\Block;
use App\Models\CustomerBooking;
use App\Models\PlotDetail;
use App\Models\Project;
use App\Services\ExcelExportService;
use Illuminate\Http\Request;

class PlotBookingDetailsController extends Controller
{
    protected $excelExportService;

    public function __construct(ExcelExportService $excelExportService)
    {
        $this->excelExportService = $excelExportService;
    }

    public function getCustomerDetails($id)
    {
        $customer = CustomerBooking::with('primaryDetail')->find($id);

        return response()->json(['name' => $customer?->primaryDetail?->name]);
    }

    public function getProjectBlocks($projectId)
    {
        $blocks = Block::where('project_id', $projectId)->get();

        return response()->json($blocks);
    }

    public function getBlockPlcTypes($blockId)
    {
        $types = PlotDetail::with('plotType')->where('block_id', $blockId)->get()->pluck('plotType')
            ->unique('id')->values();

        return response()->json($types);
    }

    public function index(Request $request)
    {
        $query = CustomerBooking::with([
            'primaryDetail',
            'associate',
            'plotSaleDetail.project',
            'plotSaleDetail.block',
            'plotSaleDetail.plotDetail.plotType',
            'payments',
        ]);
        if ($request->customer_id) {
            $query->where('id', $request->customer_id);
        }
        if ($request->customer_name) {
            $query->whereHas('primaryDetail',
                function ($q) use ($request) {
                    $q->where('name', 'like', '%'.$request->customer_name.'%');
                }
            );
        }
        if ($request->project_id) {
            $query->whereHas('plotSaleDetail',
                function ($q) use ($request) {
                    $q->where('project_id', $request->project_id);
                }
            );
        }
        if ($request->block_id) {
            $query->whereHas('plotSaleDetail',
                function ($q) use ($request) {
                    $q->where('block_id', $request->block_id);
                }
            );
        }
        if ($request->plot_type_id) {
            $query->whereHas('plotSaleDetail.plotDetail',
                function ($q) use ($request) {
                    $q->where('plot_type_id', $request->plot_type_id);
                }
            );
        }
        if ($request->plan_type) {
            $query->whereHas('payments',
                function ($q) use ($request) {
                    $q->where('plan_type', $request->plan_type);
                }
            );
        }
        if ($request->payment_mode) {
            $query->whereHas('payments',
                function ($q) use ($request) {
                    $q->where('payment_mode', $request->payment_mode);
                }
            );
        }
        if ($request->from_date) {
            $query->whereDate('created_at', '>=',
                $request->from_date
            );
        }
        if ($request->to_date) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }
        $bookings = $query->latest()->get();
        $customerIds = CustomerBooking::get();
        $projects = Project::get();

        return view('reports.plot-booking-details.index',
            compact('bookings', 'customerIds', 'projects')
        );
    }

    public function export(Request $request)
    {
        $query = CustomerBooking::with([
            'primaryDetail',
            'associate',
            'plotSaleDetail.project',
            'plotSaleDetail.block',
            'plotSaleDetail.plotDetail.plotType',
            'payments',
        ]);

        // Filters
        if ($request->customer_id) {
            $query->where('id', $request->customer_id);
        }

        if ($request->project_id) {
            $query->whereHas('plotSaleDetail', function ($q) use ($request) {
                $q->where('project_id', $request->project_id);
            });
        }

        if ($request->block_id) {
            $query->whereHas('plotSaleDetail', function ($q) use ($request) {
                $q->where('block_id', $request->block_id);
            });
        }

        if ($request->from_date) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->to_date) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $bookings = $query->latest()->get();

        return $this->excelExportService->export(
            $bookings,
            'plot-booking-details-report',
            [
                'Booking ID',
                'Agent ID / Name',
                'Customer Name',
                'Project Name',
                'Plot No',
                'Plot Rate / Area',
                'Plot Cost',
                'Other Charges',
                'Discount',
                'Final Amount',
                'Paid Amount',
                'Installment Amount',
                'Booking Date',
                'Plan Type',
            ],
            function ($booking) {
                $plotSale = $booking->plotSaleDetail;
                $payment = $booking->payment;
                $paidAmount = $booking->payments->sum('booking_amount');
                $installmentAmount = 0;
                if (($payment?->plan_type ?? '') == 'emi_plan' && ($payment?->emi_months ?? 0) > 0) {
                    $installmentAmount = $payment->net_payable_amount / $payment->emi_months;
                }

                return [
                    $booking->booking_code ?? 'N/A', ($booking->associate_code ?? 'N/A').' / '.
                    ($booking->associate_name ?? 'N/A'),
                    $booking->primaryDetail?->name ?? 'N/A',
                    $plotSale?->project?->name ?? 'N/A',
                    $plotSale?->plotDetail?->plot_number ?? 'N/A',
                    ($plotSale?->plot_rate ?? 0).' / '.
                    ($plotSale?->plot_area ?? 0),
                    $plotSale?->plot_cost ?? 0,
                    $plotSale?->other_charges ?? 0,
                    $plotSale?->coupon_discount ?? 0,
                    $plotSale?->total_plot_cost ?? 0,
                    $paidAmount,
                    $installmentAmount,
                    $plotSale?->booking_date ?? 'N/A',
                    ucfirst(str_replace('_', ' ', $payment?->plan_type ?? 'N/A')),
                ];
            }
        );
    }
}
