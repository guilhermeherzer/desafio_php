<div class="card">
    <div class="card-header">
        <i class="fas fa-table mr-1"></i>
        Resumo do Contrato
    </div>
    <div class="card-body">
        <form action="{{ route('beneficiario.store', ['id' => $contrato['plano_id']]) }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group row">
                    <div class="col-sm-5">
                        <label>Plano de Saúde</label>
                        <input class="form-control" value="{{ $contrato['plano'] }}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-6">
                        <label>Quantidade de Beneficiários</label>
                        <input class="form-control" value="{{ $contrato['quantidade'] }}" readonly>
                    </div>
                </div>
                <div class="form-group row mb-0">
                    <div class="col-sm-5">
                        <label>Nome do Beneficiário</label>
                    </div>
                    <div class="col-sm-4">
                        <label>Data de Nascimento</label>
                    </div>
                    <div class="col-sm-3">
                        <label>Valor</label>
                    </div>
                </div>
                @foreach($contrato['beneficiarios'] as $b)
                    <div class="form-group row">
                        <div class="col-sm-5">
                            <input class="form-control" value="{{ $b['nome'] }}" readonly>
                        </div>
                        <div class="col-sm-4">
                            <input class="form-control" value="{{ $b['data_nascimento'] }}" readonly>
                        </div>
                        <div class="col-sm-3">
                            <input class="form-control" value="{{ $b['valor'] }}" readonly>
                        </div>
                    </div>
                @endforeach
                <div class="form-group row">
                    <div class="col-sm-6">
                        <label>Total do Plano</label>
                        <input class="form-control" value="{{ $contrato['total'] }}" readonly>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a class="btn btn-secondary" href="{{ route('index') }}">Cancelar</a>
                <button class="btn btn-primary">Contratar</button>
            </div>
        </form>
    </div>
</div>