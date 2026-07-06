<?php

namespace App\Http\Middleware;

use App\Models\Module;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class CheckModulePermission
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login');
        }

        if ($user->hasRole('Admin')) {
            return $next($request);
        }

        $routeName = $request->route()?->getName();

        if (!$routeName) {
            abort(403, 'Route name not found.');
        }

        $skipRoutes = [
            'profile',
            'profile.update',
            'change-password',
            'change-password.update',
            'logout',
        ];

        if (in_array($routeName, $skipRoutes, true)) {
            return $next($request);
        }

        $extraRouteMap = [
            'get.project.data' => 'plot-details-list',
            'plot-details.export' => 'plot-details-list',
            'get.project.blocks' => 'plot-rates-list',

            'get.sponsor.ranks' => 'associate-create-list',
            'associate.index' => 'associate-details-list',
            'associate.create' => 'associate-create-list',
            'associate.store' => 'associate-create-list',
            'associate.show' => 'associate-details-list',
            'associate.edit' => 'associate-details-list',
            'associate.update' => 'associate-details-list',
            'associate.destroy' => 'associate-details-list',
            'associate.export' => 'associate-details-list',

            'direct-associate.export' => 'direct-associate-list',
            'associate-downline.export' => 'associate-downline-list',

            'get-customer-details' => 'emi-payment-details-report-list',
            'get.cities' => 'farmers-list',

            'customer-booking.get-blocks' => 'customer-booking-list',
            'customer-booking.get-plots' => 'customer-booking-list',

            'ledger.project.blocks' => 'customer-ledger-report-list',
            'ledger.block.customers' => 'customer-ledger-report-list',
            'ledger.customer.plots' => 'customer-ledger-report-list',
            'ledger.plot.booking' => 'customer-ledger-report-list',
            'customer-ledger-report.export' => 'customer-ledger-report-list',
        ];

        if (isset($extraRouteMap[$routeName])) {
            return $this->checkPermission($user, $extraRouteMap[$routeName], $next, $request);
        }

        $module = Module::query()->get()
            ->first(function ($module) use ($routeName) {
                if ($module->route_name === $routeName) {
                    return true;
                }

                $patterns = collect(explode(',', (string) $module->active_routes))
                    ->map(fn($item) => trim($item))->filter()->values();

                return $patterns->contains(
                    fn($pattern) => Str::is($pattern, $routeName)
                );
            });

        if (!$module) {
            abort(403, 'Permission mapping not found for this route: ' . $routeName);
        }

        $permission = $module->slug . '-list';

        return $this->checkPermission($user, $permission, $next, $request);
    }
    private function checkPermission($user, string $permission, Closure $next, Request $request): Response
    {
        if ($user->can($permission)) {
            return $next($request);
        }

        $referer = $request->headers->get('referer');

        /*
         * Agar user direct URL type/paste karke open karega,
         * to referer empty hoga. Is case me 403 show karo.
         */
        if (!$referer) {
            abort(403, 'You do not have permission to access this page.');
        }

        /*
         * Agar same app ke andar se refresh/navigation hua hai,
         * aur permission remove ho chuki hai,
         * to first allowed module par redirect karo.
         */
        $redirectUrl = $this->firstAllowedRoute($user);

        if ($redirectUrl) {
            return redirect()->to($redirectUrl)
                ->with('warning', 'Your permission has been changed. You have been redirected to an allowed section.');
        }

        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->withErrors(['email' => 'No permission assigned to your account.']);
    }

    private function firstAllowedRoute($user): ?string
    {
        $modules = Module::whereNotNull('route_name')->whereNotNull('slug')->orderBy('sort_order')->orderBy('id')->get();
        foreach ($modules as $module) {
            $permission = $module->slug . '-list';
            if ($user->can($permission) && Route::has($module->route_name)) {
                return route($module->route_name);
            }
        }
        return null;
    }
}