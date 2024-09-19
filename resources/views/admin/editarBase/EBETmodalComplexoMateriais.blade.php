<!-- Button trigger modal -->
    <button type="button" class="my-4 btn bg-gradient-primary" data-bs-toggle="modal" data-bs-target="#modal-EBETMaterialAdd">
        + Material
    </button>
<!-- MODAL COMPLEXO 1 inicio MATERIAIS-->
    <div class="modal fade" id="modal-EBETMaterialAdd" tabindex="-1" role="dialog" aria-labelledby="modal-EBETMaterialAdd-label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-normal" id="modal-EBETMaterialAdd-label">Adicionar Material ao Trabalho</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{asset('/EBETMaterialAdd/'.$trabalho->id)}}" method="POST">
                        @csrf
                        <div class="my-3">
                            <select class="input-group custom-select pr-2 gray" id="material" name="material" placeholder="escolha...">
                                @foreach ($tb_base_materiais as $t)
                                    <option value="{{ $t['id'] }}">{{ $t['material']." - ".$t['apresentacao'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="input-group input-group-outline my-3" style="width: 40%; max-width: 150px;">
                            <label class="form-label">Quantidade</label>
                            <input type="number" class="form-control" id="quantidade" name="quantidade" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Fechar</button>
                            <button type="button" class="btn bg-gradient-warning" data-bs-toggle="modal" data-bs-target="#modal-EBETMaterialNovo">Novo Material</button>
                            <button type="submit" class="btn bg-gradient-primary">Salvar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
<!-- MODAL COMPLEXO 1 MATERIAIS fim -->
<!-- MODAL COMPLEXO 2 MATERIAIS inicio -->
    <div class="modal fade" id="modal-EBETMaterialNovo" tabindex="-1" role="dialog" aria-labelledby="modal-EBETMaterialNovo-label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-normal" id="modal-EBETMaterialNovo-label">Cadastrar Novo Material na Base</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{asset('/EBETMaterialNovo/'.$trabalho->id)}}" method="POST">
                        @csrf
                        
                        <div class="input-group input-group-outline  my-3" >
                            <label class="form-label">Nome do Novo Material</label>
                            <input type="text" class="form-control" id="material" name="material" required>
                        </div>
                        <div class="input-group input-group-outline  my-3"  >
                            <label class="form-label">Apresentação</label>
                            <input type="text" class="form-control" id="apresentacao" name="apresentacao" required>
                        </div>
                    
                        <div class="row justify-between my-3 ">
                            <div class="input-group input-group-outline " style="max-width: 50%;" >
                                <label class="form-label">Custo</label>
                                <input type="number" class="form-control" id="custo" name="custo"  required>
                            </div>
                            <div class="input-group input-group-outline " style="max-width: 50%;" >
                                <label class="form-label">Quantidade Calculada</label>
                                <input type="number" class="form-control" id="quantidade" name="quantidade"  required>
                            </div>
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
                              