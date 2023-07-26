<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LeadsTargets;
use App\Models\OrdersTarget;
use App\Models\SalesTarget;
use App\Models\VisitsTarget;

class TargetsUIController extends Controller
{
    public $targets = [
        'sales' => [
            'target' => 'SalesTarget',
            'achieved' => 'AchievedSalesTarget',
            'label' => 'Sales',
            'color' => '#33691E',
        ],
        'leads' => [
            'target' => 'LeadsTarget',
            'achieved' => 'AchievedLeadsTarget',
            'label' => 'Leads',
            'color' => '#01579B',
        ],
        'visit' => [
            'target' => 'VisitsTarget',
            'achieved' => 'AchievedVisitsTarget',
            'label' => 'Visits',
            'color' => '#FF8F00',
        ],
        'order' => [
            'target' => 'OrdersTarget',
            'achieved' => 'AchievedOrdersTarget',
            'label' => 'Orders',
            'color' => '#E53935',
        ],
    ];
    public function getTarget($type)
    {
        // Determine the model based on the selected type
        switch ($type) {
            case 'sales':
                $model = SalesTarget::class;
                break;
            case 'leads':
                $model = LeadsTargets::class;
                break;
            case 'visit':
                $model = VisitsTarget::class;
                break;
            case 'order':
                $model = OrdersTarget::class;
                break;
            default:
                // If the type is not recognized, return an empty response or an error message.
                return response()->json(['error' => 'Invalid target type'], 404);
        }
        $targetData = $this->targets[$type];

        $targetColumn = $targetData['target'];
        $achievedColumn = $targetData['achieved'];
        $achievedLabel = $targetData['label'];
        $color = $targetData['color'];

        $data = $model::selectRaw('MONTH(created_at) as month, SUM(' . $targetColumn . ') as total_target, SUM(' . $achievedColumn . ') as total_achieved')
            ->groupBy('month')
            ->get();
        $labels = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        $targets = array_fill(0, 12, 0);
        $achieved = array_fill(0, 12, 0);

        foreach ($data as $entry) {
            // Subtract 1 because the month starts from 1 and array index starts from 0
            $monthIndex = (int) $entry->month - 1;
            $targets[$monthIndex] = $entry->total_target;
            $achieved[$monthIndex] = $entry->total_achieved;
        }

        // Return the data as JSON
        return response()->json([
            'labels' => $labels,
            'targets' => $targets,
            'achieved' => $achieved,
            'label' => $achievedLabel,
            'color' => $color,
        ]);
    }
}
