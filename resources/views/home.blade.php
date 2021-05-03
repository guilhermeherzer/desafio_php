@extends('layouts.layout')

@section('container')
<div class="container-fluid">
    <div class="row mt-5">
        <div class="col-xl-6" id="card">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-table mr-1"></i>
                    Cadastro de Beneficiários
                </div>
                <div class="card-body">
                    <form action="{{ route('beneficiario.calculator') }}" method="GET">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group row">
                                <div class="col-sm-5">
                                    <label>Plano de Saúde</label>
                                    <select class="form-control" name="plano" required>
                                        <option value=""></option>
                                        @foreach($planos as $plano)
                                        <option value="{{ $plano->codigo }}">{{ $plano->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-6">
                                    <label>Quantidade de Beneficiários</label>
                                    <input type="number" class="form-control" name="quantidade" min="1" value="1" required>
                                </div>
                            </div>
                            <div id="beneficiario">
                                <div class="form-group row">
                                    <div class="col-sm-7">
                                        <label>Nome do Beneficiário</label>
                                        <input type="text" class="form-control" name="nome[]" autocomplete="off" required>
                                    </div>
                                    <div class="col-sm-5">
                                        <label>Data de Nascimento</label>
                                        <input type="date" class="form-control" name="idade[]" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                            <button class="btn btn-primary">Calcular</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-xl-6" id="card2">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-table mr-1"></i>
                    Contratos Cadastrados
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered text-center" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Nome</th>
                                    <th>Data</th>
                                    <th>Valor</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(Session::has('contratos'))
                                    @foreach(Session::get('contratos') as $c)
                                        <tr>
                                            <td>{{ $c['id'] }}</td>
                                            <td>{{ $c['plano'] }}</td>
                                            <td>{{ date('d/m/Y H:i:s', strtotime($c['criado_em'])) }}</td>
                                            <td>{{ $c['total'] }}</td>
                                            <td>
                                                <a href="#" data-action="{{ route('beneficiario.show', ['id' => $c['id']]) }}">
                                                    <i class="fas fa-search"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
    let value = $('input[type=number]').val();
    
    var beneficiario = $('#beneficiario')
    
    $('input[type=number]').on('change',function(){
        var input = '<div class="form-group row" id="beneficiario' + value + '">'
            input += '<div class="col-sm-7">'
            input += '<input class="form-control" name="nome[]" required>'
            input += '</div>'
            input += '<div class="col-sm-5">'
            input += '<input type="date" class="form-control" name="idade[]" required>'
            input += '</div>'
            input += '</div>'
        
        if($(this).val() > value){
            console.log('Input was incremented')
            beneficiario.append(input)
        }else{
            console.log('Input was decremented')
            $('#beneficiario' + (value - 1)).remove()
        }
        
        value = $(this).val();
    });
</script>
<script>
	$('body').on('click', '[data-action]', function(e){
		e.preventDefault()
		
		var data = $(this).data()
		var div = $('#card2')
		$.ajax({
			url: data.action,
			type: data.type,
			dataType: 'json',
			beforeSend: function() {
			},
			success: function(res) {
                div.html(res.view)
			},
			complete: function() {
			}
		})
	})

    $('form').submit(function(e){
        e.preventDefault()

        var form = $(this)
        var div = $('#card')

        $.ajax({
            url: form.attr('action'),
            data: form.serialize(),
            type: 'GET',
            dataType: 'json',
            beforeSend: function() {
            },
            success: function(res) {
                div.html(res.view)
                alert(res.mensagem)
            },
            complete: function() {
            }
        })
    })
</script>
@endsection
