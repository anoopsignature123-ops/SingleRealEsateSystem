<?php

namespace App\Http\Controllers;

use App\Models\Associate;
use App\Models\CustomerBooking;
use App\Models\CustomerPayment;
use Illuminate\Http\Request;

class AssociateTreeController extends Controller
{
    public function index(Request $request)
    {
        $associateId = trim((string) $request->associate_id);
        $rootAssociate = Associate::query()->with('rank')
            ->when(
                $associateId !== '',
                fn($query) => $query->where('associate_id', $associateId),
                fn($query) => $query->whereNull('under_place_id')
            )->first();
        if ($rootAssociate) {
            $this->prepareTree($rootAssociate, true);
        }
        return view('associate-tree.index', compact('rootAssociate'));
    }

    private function prepareTree(Associate $associate, bool $isRoot = false): void
    {
        $associate->loadMissing(['rank', 'children.rank']);
        $associate->setAttribute('tree_stats', $this->buildStatsFor($associate));
        $associate->setAttribute('is_tree_root', $isRoot);
        $associate->children->each(
            function (Associate $child) {
                $this->prepareTree($child);
            }
        );
    }
    private function buildStatsFor(Associate $associate): array
    {
        $selfStats = $this->businessStatsForAssociateIds(collect([$associate->id]));
        $downlineIds = collect($associate->getDownlineIds())->filter()->unique()->values();
        $teamStats = $this->businessStatsForAssociateIds($downlineIds);
        return [
            'self_business' => $selfStats['business'],
            'team_business' => $teamStats['business'],
            'total_business' => $selfStats['business'] + $teamStats['business'],
            'plot_area' => $selfStats['area'],
            'team_area' => $teamStats['area'],
            'total_area' => $selfStats['area'] + $teamStats['area'],
            'direct_count' => $associate->children->count(),
            'downline_count' => $downlineIds->count(),
        ];
    }

    private function businessStatsForAssociateIds(
        $associateIds
    ): array {
        $associateIds = collect($associateIds)->filter()->unique()->values();

        if ($associateIds->isEmpty()) {
            return ['business' => 0, 'area' => 0];
        }

        $bookingIds = CustomerBooking::query()
            ->whereIn('associate_id', $associateIds)->pluck('id');
        if ($bookingIds->isEmpty()) {
            return ['business' => 0, 'area' => 0];
        }

        $payments = CustomerPayment::query()
            ->with(['plotSaleDetail.plotDetail'])->whereIn('customer_booking_id', $bookingIds)
            ->where('booking_status', 'booked')
            ->whereIn('payment_status', ['paid', 'cleared'])
            ->whereHas(
                'plotSaleDetail',
                function ($query) {
                    $query->where('status', 'active');
                }
            )->get();

        $plotSales = $payments->pluck('plotSaleDetail')->filter()->unique('id');
        return [
            'business' => (float) $payments->sum('paid_amount'),
            'area' => (float) $plotSales->sum(
                function ($plotSale) {
                    return $plotSale->plot_area ?? $plotSale->plotDetail?->plot_area ?? 0;
                }
            ),
        ];
    }
}