<div class="card">
    <div class="card-header">
        <i class="fas fa-table mr-1"></i>
        Contrato
    </div>
    <div class="card-body">
        <div class="modal-body">
            <div class="form-group row">
                <div class="col-sm-5">
                    <label>Plano de Saúde</label>
                    <input class="form-control" value="{{ $contrato['plano'] }}" disabled>
                </div>
            </div>
            <div id="beneficiario">
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
                            <input class="form-control" value="{{ $b['nome'] }}" disabled>
                        </div>
                        <div class="col-sm-4">
                            <input class="form-control" value="{{ date('d/m/Y', strtotime($b['data_nascimento'])) }}" disabled>
                        </div>
                        <div class="col-sm-3">
                            <input class="form-control" value="{{ $b['valor'] }}" disabled>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="form-group row">
                <div class="col-sm-5">
                    <label>Valor Total</label>
                    <input class="form-control" value="{{ $contrato['total'] }}" disabled>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <a class="btn btn-secondary" href="{{ route('index') }}">Voltar</a>
        </div>
    </div>
</div>