<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use App\Models\CategoriaAluno;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

// Imports relatórios
use App\Charts\GraficoAluno;
use App\Charts\AlunosNotas;
use App\Charts\grafico;

class AlunoController extends Controller
{
    /**
     * Carrega a listagem de dados
     */
    public function index()
    {
        /*
        ******** visualizar quais turmas o aluno está relacionado:
        $alunos = Aluno::with('turmas')->get();
        dd($alunos);
        ********/
        $alunos = Aluno::all();
        return view('aluno.list')->with(['alunos'=> $alunos]);
    }

    /**
     * Carrega o formulário
     */
    public function create()
    {
        $categorias = CategoriaAluno::orderBy('nome')->get();

        return view('aluno.form')->with(['categorias'=> $categorias]);
    }

    /**
     * Salva os dados do formulário
     */
    public function store(Request $request)
    {

        $request->validate([
            'nome'=>'required|max:100',
            'cpf'=>'required|max:14'
        ],[
            'nome.required'=>"O :attribute é obrigatorio!",
            'nome.max'=>" Só é permitido 120 caracteres no :attribute !",
            'cpf.required'=>"O :attribute é obrigatorio!",
            'cpf.max'=>" Só é permitido 14 caracteres no :attribute !",
        ]);

        $dados = ['nome'=> $request->nome,
        'data_nascimento'=> $request->data_nascimento,
        'cpf'=> $request->cpf,
        'email'=> $request->email,
        'telefone'=>$request->telefone,
        'categoria_aluno_id'=>$request->categoria_aluno_id,
        ];

        $imagem = $request->file('imagem');
        //verifica se existe imagem no formulário
    if($imagem){
        $nome_arquivo =
        date('YmdHis').'.'.$imagem->getClientOriginalExtension();

        $diretorio = "imagem/aluno/";
        //salva imagem em uma pasta do sistema
        $imagem->storeAs($diretorio,$nome_arquivo,'public');

        $dados['imagem'] = $diretorio.$nome_arquivo;
    }


        Aluno::create($dados); //ou  $request->all()

        return redirect('aluno')->with('success', "Cadastrado com sucesso!");
    }

    /**
     * Carrega apenas 1 registro da tabela
     */
    public function show(Aluno $aluno)
    {
        //
    }

    /**
     * Carrega o formulário para edição
     */
    public function edit($id)
    {
        $aluno = Aluno::find($id); //select * from aluno where id = $id

        $categorias = CategoriaAluno::orderBy('nome')->get();

        return view('aluno.form')->with([
            'aluno'=> $aluno,
            'categorias' => $categorias]);
    }

    /**
     * Atualiza os dados do formulário
     */
    public function update(Request $request, Aluno $aluno)
    {
        $request->validate([
            'nome'=>'required|max:100',
            'cpf'=>'required|max:14'
        ],[
            'nome.required'=>"O :attribute é obrigatorio!",
            'nome.max'=>" Só é permitido 120 caracteres no :attribute !",
            'cpf.required'=>"O :attribute é obrigatorio!",
            'cpf.max'=>" Só é permitido 14 caracteres no :attribute !",
        ]);

        $dados = ['nome'=> $request->nome,
            'data_nascimento'=> $request->data_nascimento,
            'cpf'=> $request->cpf,
            'email'=> $request->email,
            'telefone'=>$request->telefone,
            'categoria_aluno_id'=>$request->categoria_aluno_id,
            'imagem'=>$request->imagem,
        ];

        $imagem = $request->file('imagem');
        //verifica se existe imagem no formulário
        if($imagem){
            $nome_arquivo =
            date('YmdHis').'.'.$imagem->getClientOriginalExtension();

            $diretorio = "imagem/aluno/";
            //salva imagem em uma pasta do sistema
            $imagem->storeAs($diretorio,$nome_arquivo,'public');

            $dados['imagem'] = $diretorio.$nome_arquivo;
        }

        Aluno::updateOrCreate(
            ['id'=>$request->id],
            $dados);

        return redirect('aluno')->with('success', "Atualizado com sucesso!");

    }

    /**
     * Remove o registro do banco de dados
     */
    public function destroy($id)
    {
        $aluno = Aluno::findOrFail($id);

        $aluno->delete();

        return redirect('aluno')->with('success', "Removido com sucesso!");
    }
    /**
     * pesquisa e filtra o registro do banco de dados
     */
    public function search(Request $request)
    {
        if(!empty($request->valor)){
            $alunos = Aluno::where(
                $request->tipo,
                 'like' ,
                "%". $request->valor."%"
                )->get();
        } else {
            $alunos = Aluno::all();
        }

        return view('aluno.list')->with(['alunos'=> $alunos]);
    }

    /**
     * cria relatório para gerar em pdf
     */
    public function report() {
        // select * from aluno order by nome
        $alunos = Aluno::orderBy('nome')->get();

        $data = [
            'title'=>"Relatório Listagem de Alunos",
            // 'date'=>date('d/m/Y'),
            'alunos' => $alunos,
        ];

        $pdf = PDF::loadView('aluno.report', $data);

        return $pdf->download("listagem_alunos.pdf");
    }

    /**
     * cria gráfico
     */
    public function chart(GraficoAluno $aluno, AlunosNotas $alunoNotas){
        // return view('aluno.chart',['chart'=>$chart->build]);  OU
        return view('aluno.chart')->with([
            'aluno'=>$aluno->build(),
            'alunoNotas'=>$alunoNotas->build()
        ]);
    }


}
