@extends('layouts.app')
@section('titulo','Simulado')
@section('content')

    <form id="form_questao_anterior" class="d-none" action="{{route('voltar_qst_simulado')}}" method="post">
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <input type="hidden" name="resposta_anterior" value="{{$questao['questao_ant']}}">
    </form>


    <form class="shadow p-3 bg-white rounded" action="{{route('salvar_voltar_qst_simulado')}}" method="post">
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <input type="hidden" name="simulado_id" value="{{$simulado_id}}">
        <input type="hidden" name="questao_id" value="{{$questao['id']}}">
        <input type="hidden" name="resposta_aluno_id" value="{{$questao['resposta_aluno_id']}}">
        <div class="container">
            <div class="shadow p-3 bg-white" style="border-radius: 10px">
                <div class="row"
                     style="background: #1B2E4F; margin-top: -15px; margin-bottom:  30px; border-radius: 10px 10px 0 0; color: white">
                    <div class="col-sm">
                        <h1 style="margin-left: 15px; margin-top: 15px; margin-bottom: 25px">Responda a Questão</h1>
                    </div>
                </div>
                <div class="card row">
                    <div class="card-header">
                        <h4 class="card-text"> {!! $questao['enunciado']!!} </h4>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Selecione a resposta correta:</h5>
                        <div class="list-group container">

                            <input type="radio" name="alternativa" id="radioa" value="0" required autofocus/>
                            <label class="list-group-item" for="radioa">A
                                - {!! nl2br($questao['alternativa_a'])!!}</label>

                            <input type="radio" name="alternativa" id="radiob" value="1"/>
                            <label class="list-group-item" for="radiob">B
                                - {!!nl2br($questao['alternativa_b'])!!}</label>

                            <input type="radio" name="alternativa" id="radioc" value="2"/>
                            <label class="list-group-item" for="radioc">C
                                - {!! nl2br($questao['alternativa_c'])!!}</label>

                            <input type="radio" name="alternativa" id="radiod" value="3"/>
                            <label class="list-group-item" for="radiod">D
                                - {!! nl2br($questao['alternativa_d'])!!}</label>

                            <input type="radio" name="alternativa" id="radioe" value="4"/>
                            <label class="list-group-item" for="radioe">E
                                - {!! nl2br($questao['alternativa_e'])!!}</label>
                        </div>
                        <div class="col-md-12 mt-4 text-center">
                            @if($questao["questao_ant"])
                                <button type="button" class="btn btn-success pull-center"
                                        onclick="document.getElementById('form_questao_anterior').submit()"> << Anterior
                                </button>
                            @endif
                            <button onclick="atLeastOneRadio()" id="confirmar-btn" type="submit"
                                    class="btn btn-success pull-center" data-container="body" data-toggle="popover"
                                    data-placement="right" data-content="Selecione uma alternativa para prosseguir.">
                                Salvar resposta
                            </button>
                            <button type="button" onclick="document.getElementById('retomar_btn').click()"
                                    class="btn btn-success pull-center"> Retormar simulado >>
                            </button>
                            <a class="d-none" id="retomar_btn" href="{{route('qst_simulado', $simulado_id)}}"></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>


    @if($questao['respondido'] == 0)
        <script>
            document.getElementById('radioa').click();
        </script>
    @endif
    @if($questao['respondido'] == 1)
        <script>
            document.getElementById('radiob').click();
        </script>
    @endif
    @if($questao['respondido'] == 2)
        <script>
            document.getElementById('radioc').click();
        </script>
    @endif
    @if($questao['respondido'] == 3)
        <script>
            document.getElementById('radiod').click();
        </script>
    @endif
    @if($questao['respondido'] == 4)
        <script>
            document.getElementById('radioe').click();
        </script>
    @endif



    <style type="text/css">
        .list-group-item {
            user-select: none;
        }

        .list-group input[type="radio"] {
            display: none;
        }

        .list-group input[type="radio"] + .list-group-item {
            cursor: pointer;
        }

        .list-group input[type="radio"] + .list-group-item:before {
            display: none;
        }

        .list-group input[type="radio"]:checked + .list-group-item {
            background-color: #e9ecef;
            color: #000;
        }

        .list-group input[type="radio"]:checked + .list-group-item:before {
            display: none;
        }
    </style>

    <script type="text/javascript">
        function atLeastOneRadio() {
            var chx = document.getElementsByTagName('input');
            for (var i = 0; i < chx.length; i++) {
                // If you have more than one radio group, also check the name attribute
                // for the one you want as in && chx[i].name == 'choose'
                // Return true from the function on first match of a checked item
                if (chx[i].type == 'radio' && chx[i].checked) {
                    return true;
                }
            }
            // End of the loop, return false
            $('#confirmar-btn').popover('show');
            setTimeout(function () {
                $('#confirmar-btn').popover('hide');
            }, 1500);
        }

        $(function () {
            $('#confirmar-btn').popover({
                trigger: 'manual'
            })
        })
    </script>

@stop
