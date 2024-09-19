<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Models\Product;
use Illuminate\Http\Request;
use DB;
use Redirect;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use App\Models\tb_meus_trabalhos;
use App\Models\tb_meus_materiais;
use App\Models\tb_meus_servicos;
use App\Models\tb_base_trabalhos;
use App\Models\tb_base_materiais;
use App\Models\tb_base_servicos;
use App\Models\view_tb_dre;
use App\Models\tb_dre;
use App\Models\User;
use App\Models\tb_empresa;
use App\Models\tb_producao;
use App\Models\tb_tipo_conta_dre;

class FinanceiroController extends Controller
{

# dre
    public function dre()
{
    $empresa_id = auth()->user()->empresa_id; // ID da empresa

    if(auth()->user()->empresa_id == null){

        return view('inicio'); 

    }else{
    // 1. Buscar todos os dados da tabela tb_dre
    $empresa_id = auth()->user()->empresa_id;
    $tb_dre = view_tb_dre::where('deleted', '0')->where('empresa_id',$empresa_id )->get();
    #dd($tb_dre);
    $conta_view_tb_dre = count($tb_dre);
    #dd($conta_view_tb_dre);
    // 2. Adicionar a coluna 'anomes'
    foreach ($tb_dre as $item) {
        // Garante que o mês tenha dois dígitos com zero à esquerda se necessário
        $item->anomes = $item->ano . '-' . str_pad($item->mes, 2, '0', STR_PAD_LEFT);
    }

    // 3. Inicializar a estrutura para os dados transpostos
    $transposedData = [];

    // 4. Reorganizar os dados para que cada 'anomes' se torne uma coluna
    foreach ($tb_dre as $item) {
              $period = $item->anomes;

        // Se o período não estiver presente, inicializa as colunas
        if (!isset($transposedData[$period])) {
            $transposedData[$period] = [
                'entrada' => 0,
                'folha' => 0,
                'materiais' => 0,
                'depreciacao' => 0,
                'manutencao' => 0,
                'funcionarios_qtd' => 0,
                'horas_mes_func' => 0,
                'custos_fixos' => 0,
            ];
        }

        // Atualiza os valores para cada período
        $transposedData[$period]['entrada'] = $item->entrada;
        $transposedData[$period]['folha'] = $item->folha;
        $transposedData[$period]['materiais'] = $item->materiais;
        $transposedData[$period]['depreciacao'] = $item->depreciacao;
        $transposedData[$period]['manutencao'] = $item->manutencao;
        $transposedData[$period]['funcionarios_qtd'] = $item->funcionarios_qtd;
        $transposedData[$period]['horas_mes_func'] = $item->horas_mes_func;
        $transposedData[$period]['custos_fixos'] = $item->custos_fixos;
    }

    // 5. Ordenar os períodos por 'anomes' em ordem crescente
    ksort($transposedData);

    // 6. Inicializar a estrutura final para a tabela transposta
    $finalTable = [
        'Categoria' => ['Entradas', 'Folha de Pessoal', 'Custo Materiais','Custo Depreciação','Custo Manutenção', 'Funcionários no Período', 'Horas por Mês', 'Custos Fixos'],
        'Períodos' => array_keys($transposedData), // Cabeçalhos dos períodos
        'Dados' => [
            'Entradas' => [],
            'Folha de Pessoal' => [],
            'Custo Materiais' => [],
            'Custo Depreciação' => [],
            'Custo Manutenção' => [],
            'Funcionários no Período' => [],
            'Horas por Mês' => [],
            'Custos Fixos' => [],
        ]
    ];

    // 7. Preencher a estrutura final com os dados transpostos
    foreach ($finalTable['Períodos'] as $period) {
        $finalTable['Dados']['Entradas'][] = $transposedData[$period]['entrada'];
        $finalTable['Dados']['Folha de Pessoal'][] = $transposedData[$period]['folha'];
        $finalTable['Dados']['Custo Materiais'][] = $transposedData[$period]['materiais'];
        $finalTable['Dados']['Custo Depreciação'][] = $transposedData[$period]['depreciacao'];
        $finalTable['Dados']['Custo Manutenção'][] = $transposedData[$period]['manutencao'];
        $finalTable['Dados']['Funcionários no Período'][] = $transposedData[$period]['funcionarios_qtd'];
        $finalTable['Dados']['Horas por Mês'][] = $transposedData[$period]['horas_mes_func'];
        $finalTable['Dados']['Custos Fixos'][] = $transposedData[$period]['custos_fixos'];
    }
    #dd($finalTable);
    // 8. Retornar a visualização com os dados transpostos
    return view('financeiro.dre', ['finalTable' => $finalTable],compact('conta_view_tb_dre','tb_dre'));

}}

# updateCell
    public function updateCell(Request $request)
    {
        // Registrar o início da requisição
        Log::info('Início da atualização de célula na DRE.', [
            'user_id' => auth()->id(),
            'request_data' => $request->all()
        ]);

        // Validação da entrada
        $validatedData = $request->validate([
            'key' => 'required|string',
            'periodIndex' => 'required|integer',
            'value' => 'required|numeric',
        ]);

        // Dados recebidos da requisição
        $key = $request->input('key');
        $periodIndex = $request->input('periodIndex');
        $newValue = $request->input('value');

        // Informação de períodos e identificação da empresa
        // Assumindo que 'periodos' e 'empresa_id' estão na sessão ou na estrutura de dados
        $periodos = $request->session()->get('periodos');
        $empresaId = $request->session()->get('empresa_id');

        // Verificar se o índice do período está dentro do intervalo
        if (!isset($periodos[$periodIndex])) {
            Log::error('Índice de período inválido.', [
                'periodIndex' => $periodIndex,
                'periodos' => $periodos
            ]);
            return response()->json(['success' => false, 'message' => 'Período inválido.'], 400);
        }

        // Se o período é no formato 'YYYY-MM'
        $anoMes = explode('-', $periodos[$periodIndex]);
        $ano = $anoMes[0];
        $mes = $anoMes[1];

        try {
            // Atualização no banco de dados
            DB::table('tb_dre')
                ->where('ano', $ano)
                ->where('mes', $mes)
                ->where('empresa_id', $empresaId)
                ->update([$key => $newValue, 'updated_at' => now()]);

            Log::info('Célula atualizada com sucesso.', [
                'key' => $key,
                'period' => $periodos[$periodIndex],
                'new_value' => $newValue,
                'empresa_id' => $empresaId
            ]);

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            // Logar o erro
            Log::error('Erro ao atualizar a célula na DRE.', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => auth()->id(),
                'ano' => $ano,
                'mes' => $mes,
                'empresa_id' => $empresaId,
                'key' => $key,
                'new_value' => $newValue
            ]);

            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
# DREnovoPeriodo
    public function DREnovoPeriodo(Request $request)
    {
        $empresa_id = auth()->user()->empresa_id; // ID da empresa
        $alt_user_id = auth()->user()->id; // ID do usuário logado

        // Divide o campo 'anomes' em ano e mês
        $anomes = explode('-', $request->anomes);
        $ano = $anomes[0];
        $mes = $anomes[1];

        // Cria uma nova instância do modelo tb_dre
        $x = new tb_dre;
        $x->ano = $ano;
        $x->mes = $mes;
        $x->empresa_id = $empresa_id;
        $x->entrada = str_replace(['R$', '.',','], ['', '','.'], $request->entrada);
        $x->folha = str_replace(['R$', '.',','], ['', '','.'], $request->folha);
        $x->materiais = str_replace(['R$', '.',','], ['', '','.'],  $request->materiais);
        $x->depreciacao = str_replace(['R$', '.',','], ['', '','.'],  $request->depreciacao);
        $x->manutencao = str_replace(['R$', '.',','], ['', '','.'],  $request->manutencao);
        $x->funcionarios_qtd = str_replace(['R$', '.',','], ['', '','.'], $request->funcionarios_qtd);
        $x->horas_mes_func = str_replace(['R$', '.',','], ['', '','.'], $request->horas_mes_func);
        $x->custos_fixos = str_replace(['R$', '.',','], ['', '','.'],$request->custos_fixos);
        $x->alt_user_id = $alt_user_id;
        $x->save();

        return redirect()->to('dre'); // Redireciona para a página 'dre'
    }

# producao
    public function producao()
        {
            $empresa_id = auth()->user()->empresa_id; // ID da empresa

            if(auth()->user()->empresa_id == null){

                return view('inicio'); 

            }else{
                $empresa_id = auth()->user()->empresa_id; // ID da empresa

                $dadosProducao = DB::table('tb_producao')
                    ->where('tb_producao.deleted','0')
                    ->where('tb_producao.empresa_id',$empresa_id)
                    ->get();
                    #dd($dadosProducao);

                    $tb_meus_trabalhos = tb_meus_trabalhos::where('deleted', '0')->where('empresa_id',$empresa_id )->get();

                if($dadosProducao->isEmpty()){
                    $tbProducaoVazia = 'sim';
    
                } else {
                    $tbProducaoVazia = 'nao';
                    $dadosProducao = DB::table('tb_producao')
                    ->leftJoin('tb_meus_trabalhos', 'tb_producao.id', '=', 'tb_meus_trabalhos.id')
                    ->select('tb_producao.*', 'tb_meus_trabalhos.trabalho')
                    ->where('tb_producao.deleted','0')
                    ->where('tb_producao.empresa_id',$empresa_id)
                    ->where('tb_meus_trabalhos.deleted','0')
                    ->where('tb_meus_trabalhos.empresa_id',$empresa_id)
                    ->orderBy('anomes', 'desc')
                    ->get();
                }
                $grouped = $dadosProducao->groupBy('anomes');
                $view_tb_dre = view_tb_dre::where('deleted', '0')->where('empresa_id',$empresa_id )->get();
                $view_tb_dre = $view_tb_dre->sortBy('anomes');

                // Eliminar o primeiro item da coleção
                $view_tb_dre->shift();
                $view_tb_dre = $view_tb_dre->sortByDesc('anomes');
                #dd($view_tb_dre);
                $valoresUnicosProducao = [];
                $tb_producao = tb_producao::where('deleted', '0')->where('empresa_id', $empresa_id)->orderBy('anomes', 'DESC')->get();
                #dd(($tb_producao));
                // Percorre os itens da coleção
                foreach ($tb_producao as $item) {
                    // Obtém o valor de trabalho_nome do item atual
                    $trabalhoNome = $item->trabalho_nome;
                    
                    // Adiciona o valor de trabalho_nome ao array de valores únicos, se ainda não estiver presente
                    if (!in_array($trabalhoNome, $valoresUnicosProducao)) {
                        $valoresUnicosProducao[] = $trabalhoNome;
                    }
                }

                $tb_empresa = tb_empresa::all();
            #dd($grouped);
            return view('financeiro.producao',compact('tb_empresa','valoresUnicosProducao','view_tb_dre','grouped','tbProducaoVazia','tb_meus_trabalhos'));
        }}

# refatorarProducao
    public function refatorarProducao(Request $request){
    
        $empresa_id = $request->empresa; // ID da empresa
        $alt_user_id = auth()->user()->id; // ID da empresa
        $tb_producao = tb_producao::where('empresa_id', $empresa_id)->get();
    

        foreach ($tb_producao as $tp){

        $trabalho = tb_meus_trabalhos::where('id', $tp->id_trabalho)->first(); // Buscar o registro da tabela 'trabalhos' pelo ID

        $novo_valor_cobrado = $trabalho->valor_cobrado;
        
        # MATERIAIS
            $materiaisArray = json_decode($trabalho->materiais, true); // Decodificar o JSON dos materiais associados ao trabalho
            #dd($materiaisArray);
            $tb_meus_materiais = tb_meus_materiais::where('deleted', '0')->where('empresa_id', $empresa_id)->get();
            $materiaisFiltrados = collect();
            foreach ($materiaisArray as $ma) {
                $materialFiltrado = $tb_meus_materiais->firstWhere('id', $ma['id']);
                if ($materialFiltrado) {
                    $materialFiltrado['qtd'] = $ma['qtd']; // Adiciona a quantidade do material
                    $materiaisFiltrados->push($materialFiltrado); // Adiciona o material filtrado à coleção
                }
            }
        #dd($materiaisFiltrados);

        # SERVIÇOS
            $servicosArray = json_decode($trabalho->servicos, true);
            #$servicosFiltrados = tb_meus_servicos::where('id', $servicosArray)->get()->all();
            $tb_meus_servicos = tb_meus_servicos::where('deleted', '0')->where('empresa_id', $empresa_id)->get();
            $servicosFiltrados = collect();
            foreach ($servicosArray as $ma) {
                $servicoFiltrado = $tb_meus_servicos->firstWhere('id', $ma['id']);
                if ($servicoFiltrado) {
                    $servicoFiltrado['t'] = $ma['t']; // Adiciona a quantidade do material
                    $servicosFiltrados->push($servicoFiltrado); // Adiciona o material filtrado à coleção
                }
            }
            #dd($servicosArray);
            $anomesInicial = view_tb_dre::where('empresa_id', $empresa_id)
            ->where('id', $tp->dre)
            ->first();
            
        
            $view_tb_dre = view_tb_dre::where('empresa_id', $empresa_id)
                ->where('anomes', '<=', $anomesInicial->anomes)
                ->orderBy('anomes', 'desc')
                ->take(6)
                ->get();

                #dd($view_tb_dre);


            // Calcular a média ponderada
            $totalPeriodos = $view_tb_dre->count();
            $somaPeso = 0;
            $somaPonderada = 0;

            foreach ($view_tb_dre as $key => $dre) {
                $peso = $totalPeriodos - $key; // Peso decrescente, começando do total de períodos até 1
                $somaPeso += $peso;
                $somaPonderada += $dre->custoMinuto * $peso;
            }


            $mediaPonderada = $somaPonderada / $somaPeso;
            
        # VALOR DO SERVICO
            $duracaoTotal = 0;
            $custoServicos = 0;
            foreach ($servicosFiltrados as $servico){
                $duracaoTotal += floatval($servico['t']/60);
                $custoServicos += $servico['t']*$mediaPonderada/60;
            }
        #dd($duracaoTotal,$mediaPonderada);
            

        # VALOR DO MATERIAL
            $custoMateriais = 0;
            foreach ($materiaisFiltrados as $material){
                $valorPorcao = floatval($material['custo']) / floatval($material['qtd_calculada']);
                $custoParcial = $valorPorcao * floatval($material['qtd']);
                $custoMateriais += $custoParcial;
            }
        
                        
            $valorCobrado = floatval($trabalho['valor_cobrado']) ?? 0; // Se a variável não estiver setada, será 0
            #$custosFixos = (floatval($view_tb_dre[0]->custos_fixos)/floatval($view_tb_dre[0]->funcionarios_qtd)/($view_tb_dre[0]->horas_mes_func*60))*($duracaoTotal/60);
            #dd( $duracaoTotal);$duracaoTotal/60/
            $custoFinal = $custoMateriais + $custoServicos;
            $frete = floatval($trabalho['frete']) ?? 0; // Se a variável não estiver setada, será 0
            $resultado = $valorCobrado - $custoFinal - $frete;
            
            $custoTotal = number_format($frete + $custoServicos + $custoMateriais, 2, '.', '');
            if ($valorCobrado==0){$margem = 0;}elseif($custoTotal==0){$margem = 0;}else {$margem = $valorCobrado/$custoTotal*100-100; }
            
            $custoFinal = number_format($custoFinal, 2, '.', '');
            $valorCobrado = number_format($valorCobrado, 2, '.', '');
            $lucro_bruto = number_format($valorCobrado-$custoTotal, 2, '.', '');
            $custoMateriais = number_format($custoMateriais, 2, '.', '');
            $custoServicos = number_format($custoServicos, 2, '.', '');

            #dd($tp->id_trabalho,$novo_valor_cobrado, $novo_lucro_bruto,$custoServicos,$custoMateriais, $frete,$custoFinal );
            $update = tb_producao::where('id', $tp->id)->update([
            'valor_cobrado' => $novo_valor_cobrado,
            'lucro_bruto' => $lucro_bruto ,
            'valor_servicos' => $custoServicos,
            'valor_materiais' => $custoMateriais,
            'frete' => $frete,
            'custo_total' => $custoFinal,
            'alt_user_id' => $alt_user_id

            ]);
            
            if ($update) {
        
            } else {
                return response()->json(['error' => false, 'message' => 'Falha ao atualizar os dados']);
            }
        }
        return Redirect::to('producao');

    }

}
