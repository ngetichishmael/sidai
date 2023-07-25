<?php

namespace App\Http\Livewire\Individual;

use App\Charts\OrdersTargetChart;
use App\Models\OrdersTarget;
use Livewire\Component;

class Orders extends Component
{
    public $arrayOLabel = [];
    public $arrayOTargets = [];
    public $arrayOAchieved = [];
    public $label;

    public function render()
    {
        $this->arrayOLabel = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        $this->arrayOTargets = array_fill(0, 12, 0); // Initialize array with zeroes
        $this->arrayOAchieved = array_fill(0, 12, 0); // Initialize array with zeroes

        $orderstargetEx = OrdersTarget::selectRaw('MONTH(created_at) as month, SUM(OrdersTarget) as total_orders_target, SUM(AchievedOrdersTarget) as total_orders_achieved')
            ->groupBy('month')
            ->get();

        foreach ($orderstargetEx as $br) {
            // Subtract 1 because month starts from 1 and array index starts from 0
            $this->arrayOTargets[$br->month - 1] = $br->total_orders_target;
            $this->arrayOAchieved[$br->month - 1] = $br->total_orders_achieved;
        }

        $this->label = "Orders Target";
        $orderstarget = new OrdersTargetChart();
        $orderstarget->labels($this->arrayOLabel);
        $orderstarget->dataset($this->label, 'bar', $this->arrayOTargets)->options([
            "responsive" => true,
            'color' => "#94DB9D",
            'backgroundColor' => "#009dde",
            "borderWidth" => 2,
            "borderRadius" => 5,
            "borderSkipped" => false,
        ]);
        $orderstarget->dataset('Achieved', 'bar', $this->arrayOAchieved)->options([
            "responsive" => true,
            'color' => [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)',
            ],
            'backgroundColor' => '#07ed41',
            "borderWidth" => 2,
            "borderRadius" => 5,
            "borderSkipped" => false,
        ]);

        return view('livewire.individual.orders', [
            'orderstarget' => $orderstarget,
        ]);
    }
}
