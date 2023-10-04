<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

use App\Models\Matricula;
use App\Models\Curso;
use App\Models\Turma;
use App\Models\Aluno;

class MatriculaController extends Controller
{
    /**
     * Carrega a listagem de dados
     */
    public function index()
    {
        $matriculas = Matricula::all();

        return view('matricula.list')->with(['matriculas'=>$matriculas]);
    }

    /**
     * Carrega o formulário
     */
    public function create()
    {
        $cursos = Curso::orderBy('nome')->get();
        $turmas = Turma::orderBy('nome')->get();
        $alunos = Aluno::orderBy('nome')->get();

        return view('matricula.form')->with([
            'cursos'=> $cursos,
            'turmas'=> $turmas,
            'alunos'=> $alunos,
        ]);
    }

    /**
     * Salva os dados do formulário
     */
    public function store(Request $request)
    {

        $request->validate([
            'curso_id'=>'required',
            'turma_id'=>'required',
            'aluno_id'=>'required',
            'data_matricula'=>'required|date'
        ],[
            'curso_id.required'=>" :attribute é obrigatorio!",
            'turma_id.required'=>" :attribute é obrigatorio!",
            'aluno_id.required'=>" :attribute é obrigatorio!",
            'data_matricula.required'=>" :attribute é obrigatorio!",
        ]);

        $dados = ['curso_id'=> $request->curso_id,
        'turma_id'=> $request->turma_id,
        'aluno_id'=> $request->aluno_id,
        'data_matricula'=>$request->data_matricula,
        ];

        Matricula::create($dados); //ou  $request->all()

        return redirect('matricula')->with('success', "Cadastrado com sucesso!");
    }

    /**
     * Carrega o formulário para edição
     */
    public function edit($id)
    {
        $matricula = Matricula::find($id);
        $cursos = Curso::orderBy('nome')->get();
        $turmas = Turma::orderBy('nome')->get();
        $alunos = Aluno::orderBy('nome')->get();

        return view('matricula.form')->with([
            'matricula'=> $matricula,
            'cursos'=> $cursos,
            'turmas' => $turmas,
            'alunos' => $alunos,
        ]);
    }

    /**
     * Atualiza os dados do formulário
     */
    public function update(Request $request, Matricula $matricula)
    {
        $request->validate([
            'curso_id'=>'required',
            'turma_id'=>'required',
            'aluno_id'=>'required',
            'data_matricula'=>'required|date'
        ],[
            'curso_id.required'=>" :attribute é obrigatorio!",
            'turma_id.required'=>" :attribute é obrigatorio!",
            'aluno_id.required'=>" :attribute é obrigatorio!",
            'data_matricula.required'=>" :attribute é obrigatorio!",
        ]);

        $dados = ['curso_id'=> $request->curso_id,
        'turma_id'=> $request->turma_id,
        'aluno_id'=> $request->aluno_id,
        'data_matricula'=>$request->data_matricula,
        ];

        Matricula::updateOrCreate(
            ['id'=>$request->id],
            $dados);

        return redirect('matricula')->with('success', "Atualizado com sucesso!");

    }

    /**
     * Remove o registro do banco de dados
     */
    public function destroy($id)
    {
        $matricula = Matricula::findOrFail($id);

        $matricula->delete();

        return redirect('matricula')->with('success', "Removido com sucesso!");
    }
    /**
     * pesquisa e filtra o registro do banco de dados
     */
    public function search(Request $request)
    {
        if(!empty($request->valor)){
            $matriculas = Matricula::where(
                $request->tipo,
                 'like' ,
                "%". $request->valor."%"
                )->get();
        } else {
            $matriculas = Matricula::all();
        }

        return view('matricula.list')->with(['matriculas'=> $matriculas]);
    }

    /**
     * cria relatório para gerar em pdf
     */
    public function report() {
        // select * from matricula order by nome
        $matriculas = Matricula::orderBy('nome')->get();

        $data = [
            'title'=>"Relatório Listagem de Matriculas",
            // 'date'=>date('d/m/Y'),
            'matriculas' => $matriculas,
        ];

        $pdf = PDF::loadView('matricula.report', $data);

        return $pdf->download("listagem_matriculas.pdf");
    }

    /**
     * cria gráfico
     */
    public function chart(GraficoMatricula $matricula, MatriculasNotas $matriculaNotas){
        // return view('matricula.chart',['chart'=>$chart->build]);  OU
        return view('matricula.chart')->with([
            'matricula'=>$matricula->build(),
            'matriculaNotas'=>$matriculaNotas->build()
        ]);
    }


}
