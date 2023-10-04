<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;
use App\Models\Aluno;

class AlunosNotas
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build(): \ArielMejiaDev\LarapexCharts\HorizontalBar
    {
        $qtdAlunos = Aluno::all()->count();
        // dd($qtdAlunos);

        return $this->chart->horizontalBarChart()
            ->setTitle('Los Angeles vs Miami.')
            ->setSubtitle('Wins during season 2021.')
            ->setColors(['#0000FF', '#FFC107', '#D32F2F'])
            ->addData('Qtd Aluno', [$qtdAlunos])
            ->addData('San Francisco', [6, 9, 3, 4, 10, 8])
            ->addData('Boston', [7, 3, 8, 2, 6, 4])
            ->setXAxis(['Qtd Aluno','January', 'February', 'March', 'April', 'May', 'June']);
    }
}
