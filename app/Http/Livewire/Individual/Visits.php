<?php

namespace App\Http\Livewire\Individual;

use App\Charts\VisitsTargetChart;
use App\Models\VisitsTarget;
use Livewire\Component;

class Visits extends Component
{
    public $arrayVLabel = [];
    public $arrayVTargets = [];
    public $arrayVAchieved = [];
    public $label;

    public function render()
    {
        $this->arrayVLabel = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        $this->arrayVTargets = array_fill(0, 12, 0); // Initialize array with zeroes
        $this->arrayVAchieved = array_fill(0, 12, 0); // Initialize array with zeroes

        $visitstargetEx = VisitsTarget::selectRaw('MONTH(created_at) as month, SUM(VisitsTarget) as total_visits_target, SUM(AchievedVisitsTarget) as total_visits_achieved')
            ->groupBy('month')
            ->get();

        foreach ($visitstargetEx as $br) {
            // Subtract 1 because month starts from 1 and array index starts from 0
            $this->arrayVTargets[$br->month - 1] = $br->total_visits_target;
            $this->arrayVAchieved[$br->month - 1] = $br->total_visits_achieved;
        }

        $this->label = "Visits Target";
        $visitstarget = new VisitsTargetChart();
        $visitstarget->labels($this->arrayVLabel);
        $visitstarget->dataset($this->label, 'bar', $this->arrayVTargets)->options([
            "responsive" => true,
            'color' => "#94DB9D",
            'backgroundColor' => "#009dde",
            "borderWidth" => 2,
            "borderRadius" => 5,
            "borderSkipped" => false,
        ]);
        $visitstarget->dataset('Achieved', 'bar', $this->arrayVAchieved)->options([
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

        return view('livewire.individual.visits', [
            'visitstarget' => $visitstarget,
        ]);
    }
}
