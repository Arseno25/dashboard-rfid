<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user();

        $orderQuery = Order::query()->where('user_id', $user->id);

        $stats = [
            'total_orders' => (clone $orderQuery)->count(),
            'total_spent' => (clone $orderQuery)->sum('total') ?? 0,
            'pending_payments' => (clone $orderQuery)
                ->whereIn('payment_status', ['pending', 'waiting', 'challenge'])
                ->count(),
            'completed_orders' => (clone $orderQuery)
                ->where('payment_status', 'paid')
                ->count(),
        ];

        $recentOrders = (clone $orderQuery)
            ->latest()
            ->with('items')
            ->take(5)
            ->get();

        $upcomingOrder = (clone $orderQuery)
            ->where(function ($query) {
                $query->whereIn('payment_status', ['pending', 'waiting', 'challenge'])
                    ->orWhereIn('status', ['pending', 'processing']);
            })
            ->latest()
            ->first();

        return view('account.dashboard', [
            'user' => $user,
            'stats' => $stats,
            'recentOrders' => $recentOrders,
            'upcomingOrder' => $upcomingOrder,
        ]);
    }
}
