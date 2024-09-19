<!-- Button trigger modal -->
<button type="button" class="my-4 btn bg-gradient-primary" data-bs-toggle="modal" data-bs-target="#modal-EBETServicoAdd">
        + Servico
    </button>
<!-- MODAL COMPLEXO 1 inicio MATERIAIS-->
    <div class="modal fade" id="modal-EBETServicoAdd" tabindex="-1" role="dialog" aria-labelledby="modal-EBETServicoAdd-label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-normal" id="modal-EBETServicoAdd-label">Adicionar Servico ao Trabalho</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{asset('/EBETServicoAdd/'.$trabalho->id)}}" method="POST">
                        @csrf
                        <div class="my-3">
                            <select class="input-group custom-select pr-2 gray" id="servico" name="servico" placeholder="escolha...">
                                @foreach ($tb_base_servicos as $t)
                                    <option value="{{ $t['id'] }}">{{ $t['servico'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="input-group input-group-outline my-3" style="width: 40%; max-width: 150px;">
                            <label class="form-label">Tempo (Min)</label>
                            <input type="number" class="form-control" id="t" name="t" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Fechar</button>
                            <button type="button" class="btn bg-gradient-warning" data-bs-toggle="modal" data-bs-target="#modal-EBETServicoNovo">Novo Servico</button>
                            <button type="submit" class="btn bg-gradient-primary">Salvar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
<!-- MODAL COMPLEXO 1 MATERIAIS fim -->
<!-- MODAL COMPLEXO 2 MATERIAIS inicio -->
    <div class="modal fade" id="modal-EBETServicoNovo" tabindex="-1" role="dialog" aria-labelledby="modal-EBETServicoNovo-label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-normal" id="modal-EBETServicoNovo-label">Cadastrar Novo Servico na Base</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{asset('/EBETServicoNovo/'.$trabalho->id)}}" method="POST">
                        @csrf
                        
                        <div class="input-group input-group-outline  my-3" >
                            <label class="form-label">Nome do Novo Serviço</label>
                            <input type="text" class="form-control" id="servico" name="servico" required>
                        </div>
                        
                        <div class="modal-footer">
                            <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Fechar</button>
                            <button type="submit" class="btn bg-gradient-primary">Salvar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<!-- MODAL COMPLEXO 2 MATERIAIS fim -->
                              