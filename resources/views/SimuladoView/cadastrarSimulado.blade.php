@extends('layouts.default')
@section('content')

	<form class="shadow p-3 mb-5 bg-white rounded" action= "/adicionar/simulado" method="post">
		<input type="hidden" name="_token" value="{{csrf_token()}}">
		
		<h1 class="text-center"> Cadastrar simulado </h1><br><br>	

		<div class="form-group justify-content-center row">
			<div class="col-md-6 text-center">
				<label for="descricao_simulado">Descrição simulado</label>
				<input type="text" class="form-control{{ $errors->has('descricao_simulado') ? ' is-invalid' : '' }}"  name="descricao_simulado" id="descricao_simulado" placeholder="Descrição" value="{{ old('descricao_simulado') }}" required autofocus>
				@if ($errors->has('descricao_simulado'))
					<span class = "invalid-feedback" role="alert">
						{{$errors->first('descricao_simulado')}}
					</span>
				@endif
			</div> 
		</div>

		<div class="col-md-12 text-center">
			<br><button type="submit" name="cadastrar" class="btn btn-primary">Cadastrar</button><br><br>
		</div>
	</form>

@stop