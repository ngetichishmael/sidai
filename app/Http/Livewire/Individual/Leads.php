<?php

namespace App\Http\Livewire\Individual;

use App\Charts\SalesTargetChart;
use App\Models\SalesTarget;
use Livewire\Component;

class Leads extends Component
{
    public $arraySLabel = [];
    public $arraySTargets = [];
    public $arraySAchieved = [];
    public $label;

    public function render()
    {
        $this->arraySLabel = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        $this->arraySTargets = array_fill(0, 12, 0); // Initialize array with zeroes
        $this->arraySAchieved = array_fill(0, 12, 0); // Initialize array with zeroes

        $salestargetEx = SalesTarget::selectRaw('MONTH(created_at) as month, SUM(SalesTarget) as total_sales_target, SUM(AchievedSalesTarget) as total_sales_achieved')
            ->groupBy('month')
            ->get();

        foreach ($salestargetEx as $br) {
            // Subtract 1 because month starts from 1 and array index starts from 0
            $this->arraySTargets[$br->month - 1] = $br->total_sales_target;
            $this->arraySAchieved[$br->month - 1] = $br->total_sales_achieved;
        }

        $this->label = "Sales Target";
        $salestarget = new SalesTargetChart();
        $salestarget->labels($this->arraySLabel);
        $salestarget->dataset($this->label, 'bar', $this->arraySTargets)->options([
            "responsive" => true,
            'color' => "#94DB9D",
            'backgroundColor' => "#009dde",
            "borderWidth" => 2,
            "borderRadius" => 5,
            "borderSkipped" => false,
        ]);
        $salestarget->dataset('Achieved', 'bar', $this->arraySAchieved)->options([
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

        return view('livewire.individual.leads', [
            'salestarget' => $salestarget,
        ]);
    }
}
