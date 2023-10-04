<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;

class GraficoAluno
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build(): \ArielMejiaDev\LarapexCharts\DonutChart
    {
        return $this->chart->donutChart()
            ->setTitle('Notas dos alunos')
            ->setSubtitle('Turma 2')
            ->addData([2, 7, 4, 8, 8])
            ->setLabels(['Aluno1', 'Aluno2', 'Aluno3', 'Aluno4', 'Aluno5']);
    }
}
