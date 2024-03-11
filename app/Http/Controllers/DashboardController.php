<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        if (
            auth()->user()->hasRole('admin') ||
            auth()->user()->hasRole('manager')
        ) {
            $data = $this->getCounts();

            dd($data);
        }
    }

    function getUniqueIds($model, $relation, $location_ids)
    {
        return array_unique(
            $model::whereIn('id', $location_ids)
                ->with($relation)
                ->get()
                ->pluck($relation)
                ->flatten()
                ->pluck('id')
                ->toArray(),
        );
    }

    public function getCounts()
    {
        $is_admin = auth()->user()->hasRole('admin');
        $is_manager = auth()->user()->hasRole('manager');

        $entities = ['drivers', 'clients', 'vehicles', 'transporters', 'imports', 'log_sheets', 'invoices'];
        $counts = [];

        if ($is_manager) {
            $location_ids_if_manager = auth()->user()->manager->locations->pluck('id')->toArray();

            foreach ($entities as $entity) {
                ${$entity . '_ids'} = $this->getUniqueIds("\App\Models\Location", $entity, $location_ids_if_manager);
            }

            $delivered_invoice_ids = \App\Models\Invoice::whereIn('location_id', $location_ids_if_manager)
                ->where('delivery_status', 'delivered')
                ->pluck('id')
                ->toArray();
            $pending_invoice_ids = \App\Models\Invoice::whereIn('location_id', $location_ids_if_manager)
                ->where('delivery_status', 'pending')
                ->pluck('id')
                ->toArray();
            $cancelled_invoice_ids = \App\Models\Invoice::whereIn('location_id', $location_ids_if_manager)
                ->where('delivery_status', 'cancelled')
                ->pluck('id')
                ->toArray();
        }

        foreach ($entities as $entity) {
            // Capitalize the first letter
            $model_name = ucfirst($entity);
            // Capitalize the first letter after an underscore
            $model_name = preg_replace_callback('/_([a-z])/', function ($matches) {
                return strtoupper($matches[1]);
            }, $model_name);
            // Change the word from plural to singular
            $model_name = rtrim($model_name, 's');

            $model_name = "\App\Models\\" . $model_name;
            // Route name has - instead of _
            $route_name = str_replace('_', '-', $entity);
            $counts[$entity] = [
                'title' => ucfirst($entity),
                'count' => $is_manager ? count(${$entity . '_ids'}) : $model_name::count(),
                'url' => route('admin.' . $route_name . '.index'),
                'data' => null,
                'color' => 'bg-gray-100',
            ];
        }


        $days = [1, 2, 3, 4, 5, 6, [7, 13], 14];
        $pending_invoices_count = [];

        foreach ($days as $day) {
            $title = is_array($day) ? 'One week' : $day . ' Days';
            if (is_array($day)) {
                $count = $is_manager
                    ? \App\Models\Invoice::whereIn('id', $pending_invoice_ids)
                    ->whereBetween('date', [
                        Carbon::now()->subDays($day[1])->toDateString(),
                        Carbon::now()->subDays($day[0])->toDateString(),
                    ])
                    ->count()
                    : \App\Models\Invoice::whereBetween('date', [
                        Carbon::now()->subDays($day[1])->toDateString(),
                        Carbon::now()->subDays($day[0])->toDateString(),
                    ])->count();
            } else {
                $count = $is_manager
                    ? \App\Models\Invoice::whereIn('id', $pending_invoice_ids)
                    ->where('date', '>=', Carbon::now()->subDays($day)->toDateString())
                    ->count()
                    : \App\Models\Invoice::where('date', '>=', Carbon::now()->subDays($day)->toDateString())->count();
            }

            $pending_invoices_count[] = [
                'title' => $title,
                'count' => $count,
                'url' => route('admin.invoices.pending', ['days' => $day, 'title' => $title]),
                'data' => null,
                'color' => 'bg-yellow-100 ',
            ];
        }

        $counts['invoices']['pending_invoices'] = $pending_invoices_count;

        return $counts;
    }
}
