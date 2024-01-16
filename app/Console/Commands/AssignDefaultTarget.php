<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AssignDefaultTarget extends Command
{
    protected $signature = 'assign:default-target';
    protected $description = 'Assign default target of 100 with end-of-month deadline to users';

    public function __construct()
    {
        parent::__construct();
    }
    public function handle()
    {
        $now = Carbon::now();
        $endOfMonth = $now->endOfMonth()->toDateString();
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();
        $weekdays = 0;
        while ($startDate->lte($endDate)) {
            if ($startDate->isWeekday()) {
                $weekdays++;
            }
            $startDate->addDay();
        }

        $users = DB::table('users')
            ->whereNotIn('account_type', ['Admin', 'Customer'])
            ->select('user_code')
            ->get();

        // Monthly targets
        $totalSalesTarget = 2080000; // Ksh 2,080,000
        $totalVisitsTarget = 25 * $weekdays;
        $totalLeadsTarget = 15 * $weekdays;
        $totalOrdersTarget = 4 * $weekdays;

        $targetTables = [
            'leads_targets' => [
                'table' => "leads_targets",
                'target' => "LeadsTarget",
                'achieved' => 'AchievedLeadsTarget',
                'monthly' => $totalLeadsTarget,
            ],
            'orders_targets' => [
                'table' => "orders_targets",
                'target' => "OrdersTarget",
                'achieved' => 'AchievedOrdersTarget',
                'monthly' => $totalOrdersTarget,
            ],
            'sales_targets' => [
                'table' => "sales_targets",
                'target' => "SalesTarget",
                'achieved' => 'AchievedSalesTarget',
                'monthly' => $totalSalesTarget,
            ],
            'visits_targets' => [
                'table' => "visits_targets",
                'target' => "VisitsTarget",
                'achieved' => 'AchievedVisitsTarget',
                'monthly' => $totalVisitsTarget,
            ],
        ];

        foreach ($users as $user) {
            $userCode = $user->user_code;

            foreach ($targetTables as $targetTable) {
                // Attempt to fetch the most recent target for the user from the respective table
                $lastTarget = DB::table($targetTable['table'])
                    ->where('user_code', $userCode)
                    ->orderByDesc('created_at')
                    ->first();

                $targetValue = $lastTarget ? $lastTarget->{$targetTable['target']} : $targetTable['monthly'];

                $newTarget = [
                    'user_code' => $userCode,
                    $targetTable['target'] => $targetValue,
                    $targetTable['achieved'] => '0',
                    'Deadline' => $endOfMonth,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];

                // Insert a new target row in the respective table
                DB::table($targetTable['table'])->insert($newTarget);
            }
        }

        info('Default or last targets with end-of-month deadline assigned to users successfully.');
        $this->info('Default or last targets with end-of-month deadline assigned to users successfully.');
    }

}
