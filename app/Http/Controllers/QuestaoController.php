<?php

namespace SimuladoENADE\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use SimuladoENADE\Validator\QuestaoValidator;
use SimuladoENADE\Validator\ValidationException;
use Illuminate\Support\Facades\Response;

class QuestaoController extends Controller {
	public function adicionar(Request $request){
		try{
			QuestaoValidator::Validate($request->all()); 
			
			$alternativas = $request->input('alternativa');

			$questao = new \SimuladoENADE\Questao();
			$questao->enunciado = $request->input('enunciado');
			$questao->alternativa_correta = $request->input('alternativa_correta');
			$questao->dificuldade = $request->input('dificuldade');
			$questao->disciplina_id = $request->input('disciplina_id');

			$questao->alternativa_a = $alternativas[0];
			$questao->alternativa_b = $alternativas[1];
			$questao->alternativa_c = $alternativas[2];
			$questao->alternativa_d = $alternativas[3];
			$questao->alternativa_e = $alternativas[4];

			$questao->save();

			return redirect("listar/questao")->with('success', \SimuladoENADE\FlashMessage::cadastroSuccess());

		} catch(ValidationException $ex){
			return redirect("cadastrar/questao")->withErrors($ex->getValidator())->withInput();
		}
	}

	public function cadastrar(){
		$cursos = \SimuladoENADE\Curso::all();
		#dd($cursos);
		$disciplinas = \SimuladoENADE\Disciplina::where('curso_id', '=', \Auth::user()->curso_id)->orderBy('nome')->get(); 
		return view('/QuestaoView/cadastrarQuestao', ['disciplinas' => $disciplinas, 'cursos' => $cursos]); 
	}
	
	public function listar(){
		$curso_id = \Auth::user()->curso_id;

		$questaos = \SimuladoENADE\Questao::select('*', \DB::raw('questaos.id as qstid'))
			->join('disciplinas', 'questaos.disciplina_id', '=', 'disciplinas.id')
			->where('curso_id', '=', \Auth::user()->curso_id)
			->orderBy('nome')
			->orderBy('dificuldade')
			->get();

		$questoes_discursivas = \SimuladoENADE\QuestaoDiscursiva::select('*', \DB::raw('questao_discursivas.id as qstid'))
			->join('disciplinas', 'questao_discursivas.disciplina_id', '=', 'disciplinas.id')
			->where('curso_id', '=', \Auth::user()->curso_id)
			->orderBy('nome')
			->orderBy('dificuldade')
			->get();


		$disciplinas = \SimuladoENADE\Disciplina::where('curso_id', '=', \Auth::user()->curso_id)->get();

		return view('/QuestaoView/listaQuestao', ['questaos' => $questaos, 'questoes_discursivas' => $questoes_discursivas, 'disciplinas' => $disciplinas]);
	}

	public function listarQstDisciplina(Request $request){
		$curso_id = \Auth::user()->curso_id;

		$questaos = \SimuladoENADE\Questao::select('*', \DB::raw('questaos.id as qstid'))
			->join('disciplinas', 'questaos.disciplina_id', '=', 'disciplinas.id')
			->where('disciplina_id', '=', $request->id)
			->orderBy('nome')
			->orderBy('dificuldade')
			->get();

		$questoes_discursivas = \SimuladoENADE\QuestaoDiscursiva::select('*', \DB::raw('questao_discursivas.id as qstid'))
			->join('disciplinas', 'questao_discursivas.disciplina_id', '=', 'disciplinas.id')
			->where('disciplina_id', '=', $request->id)
			->orderBy('nome')
			->orderBy('dificuldade')
			->get();

		$disciplinas = \SimuladoENADE\Disciplina::where('curso_id', '=', \Auth::user()->curso_id)->get();

		return view('/QuestaoView/listaQuestao', ['questaos' => $questaos, 'questoes_discursivas' => $questoes_discursivas, 'disciplinas' => $disciplinas]);
	}

	public function editar(Request $request){
		$questao = \SimuladoENADE\Questao::find($request->id);
		$disciplinas = \SimuladoENADE\Disciplina::where('curso_id', '=', \Auth::user()->curso_id)->get();

		\Session::put('url.intended', \URL::previous());  // using the Facade
		session()->put('url.intended', \URL::previous()); // using the L5 helper

		return view('/QuestaoView/editarQuestaos', ['questao' => $questao], ['disciplinas'=>$disciplinas]);
	}

	public function atualizar(Request $request){
		try{
			QuestaoValidator::Validate($request->all()); 
			
			$alternativas = $request->input('alternativa');

			$questao = \SimuladoENADE\Questao::find($request->id);
			$questao->enunciado = $request->input('enunciado');
			$questao->alternativa_correta = $request->input('alternativa_correta');
			$questao->dificuldade = $request->input('dificuldade');
			$questao->disciplina_id = $request->input('disciplina_id');

			$questao->alternativa_a = $alternativas[0];
			$questao->alternativa_b = $alternativas[1];
			$questao->alternativa_c = $alternativas[2];
			$questao->alternativa_d = $alternativas[3];
			$questao->alternativa_e = $alternativas[4];

			$questao->update();

			return \Redirect::intended('/')->with('success', \SimuladoENADE\FlashMessage::alteracoesSuccess());;

		}catch(ValidationException $ex){
			return redirect("editar/questao")->withErrors($ex->getValidator())->withInput();
		}
	}

	public function remover(Request $request){
		$questao = \SimuladoENADE\Questao::find($request->id);

		try {
			$questao->delete();
			return redirect('\listar\questao')->with('success', \SimuladoENADE\FlashMessage::removeQuestaoSuccess());
		} catch(QueryException $ex) {
			return redirect('\listar\questao')->with('fail', \SimuladoENADE\FlashMessage::removeQuestaoFail());
		}
	}

	public function importarQuestao(Request $request){
		$cursos = \SimuladoENADE\Curso::where('id', '!=', \Auth::user()->curso_id)->orderBy('curso_nome')->get();
		$disciplinas = \SimuladoENADE\Disciplina::orderBy('nome')->get();
		$questaos = collect();
		$existe_no_curso = true;
		
		$list_cursos_id = DB::table('disciplinas')->pluck('curso_id')->toArray();
		$list_cursos_id = array_unique($list_cursos_id);
		
		if(in_array( \Auth::user()->curso_id, $list_cursos_id )) {
			$list_cursos_id = array_diff($list_cursos_id, array(\Auth::user()->curso_id));
		}

		if(!$request->all()){
			return view('/QuestaoView/importarQuestao', ['disciplinas' => $disciplinas, 'cursos' => $cursos, 'questaos' => $questaos, 'disc_existe' => $existe_no_curso, 'list_cursos_id' => $list_cursos_id]);
		} elseif ($request->input('disciplina_id')) {
			
			if($request['disciplina_id'] == "all") {

				$disciplinas_aux = \SimuladoENADE\Disciplina::where('curso_id', $request['curso_id'])->get();
				$ids = \SimuladoENADE\Disciplina::queryToArrayIds($disciplinas_aux);
				$questaos = \SimuladoENADE\Questao::whereIn('disciplina_id', $ids)->get();

				$condicoes = ['curso_id' => \Auth::user()->curso_id];
				$existe_no_curso = \SimuladoENADE\Disciplina::where($condicoes)->first() ? true : false;
			} else {
				$questaos = \SimuladoENADE\Disciplina::find($request->input('disciplina_id'))->questaos;
				$condicoes = ['curso_id' => \Auth::user()->curso_id, 'nome' => \SimuladoENADE\Disciplina::find($request->input('disciplina_id'))->nome];
				$existe_no_curso = \SimuladoENADE\Disciplina::where($condicoes)->first() ? true : false;
			}
			
			return view('/QuestaoView/importarQuestao', ['disciplinas' => $disciplinas, 'cursos' => $cursos, 'questaos' => $questaos, 'disc_existe' => $existe_no_curso, 'list_cursos_id' => $list_cursos_id]);
		} else {
			return redirect()->back()->with('fail', true)->with('message','Ocorreu um erro. Por favor, selecione uma disciplina.');
		}
	}

	public function importandoQuestoes(Request $request){
		// As questões são replicadas e salvas na disciplina temporaria
		foreach ($request->qsts as $qst_id) {
			$questao = \SimuladoENADE\Questao::find($qst_id); // Encontra a qst a ser importada

			$nova_qst = $questao->replicate(); // Duplica criando um novo model
			$nova_qst->disciplina_id = $request->disciplina_dst_id; // Altera a disciplna

			$nova_qst->save(); // Salva a nova questão no bd
		}

		$disciplina_dest = \SimuladoENADE\Disciplina::find($request->input('disciplina_dst_id'));

		$mensagem = 'Importação efetuada com sucesso! ';
		$mensagem .= (count($request->qsts) == 1) ? count($request->qsts).' questão importada ' : 
			count($request->qsts).' questões importadas ';
		$mensagem .= 'para a disciplina "'.$disciplina_dest->nome.'"!';

		return redirect()->route('import_qst')->with('success', true)->with('message', $mensagem);

	}
	
}