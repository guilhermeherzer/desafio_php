<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DateTime;

class HomeController extends Controller {
    //
    private $planos;
    
    public function __construct(){
        $planosTable = json_decode(file_get_contents(storage_path('app\planos.json'), true )); //mapeia a tabela planos
        $precosTable = json_decode(file_get_contents(storage_path('app\precos.json'), true )); //mapeia a tablea pecors
    
        $this->planos = []; //cria a array que armazenará os dados dos planos com suas tabelas de preços
    
        foreach ($planosTable as $p){
            $this->planos[$p->codigo] = $p; //armazena os planos na array
        }
    
        foreach ($precosTable as $p){
            $this->planos[$p->codigo]->precos[] = $p; //armazena as tabelas de preços para cada devido plano
        }
    }

    public function index(Request $request) {
        $planos = $this->planos; //variavel com os planos

        return view('home', compact('planos')); //renderiza a view
    }

    public function calculator(Request $request) {
        $plano_id = $request->plano;
        $planos = $this->planos;
        
        foreach($this->planos as $p):
            if($p->codigo == $plano_id): //verifica se o plano existe
                $plano = ['codigo' => $p->codigo, 'nome' => $p->nome];
                $precos = $p->precos; //preços disponíveis para o plano selecionado
        
                $quantidade = $request->quantidade; //quantidade de beneficiários
                
                foreach($precos as $p): //looping das tabelas de preços disponiveis, para o caso de existir mais de uma
                    if($quantidade >= $p->minimo_vidas): //verifica se cumpre o requisito do minimo de beneficiarios
                        $preco = $p; //seleciona a tabela de preços
                    endif;
                endforeach;
                
                $beneficiarios = []; //array com os beneficiários cadastrados
        
                $total= 0; //total do plano cadastrado, soma de todos os valores por beneficiário

                for($i = 0; $i < $quantidade; $i++): //looping de todos os beneficiários cadastrados
                    $hoje = new DateTime(); //data atual
                    $idade = new DateTime($request->idade[$i]); //data de nascimento
                    $idade = $hoje->diff($idade); //transformando a data de nascimento na quantidade de anos
                    
                    if($idade->y >= 0 && $idade->y <= 17): //verifica em que faixa etária o beneficiário entrará
                        $faixa = $preco->faixa1; //seleciona a faixa dentro da tabela de preços
                    elseif($idade->y >= 18 && $idade->y <= 40):
                        $faixa = $preco->faixa2;
                    else:
                        $faixa = $preco->faixa3;
                    endif;
                    
                    $beneficiarios[] = [ //inclui na array o beneficiário cadastrado
                        'nome' => $request->nome[$i], //nome do beneficiário
                        'data_nascimento' => date('d/m/Y', strtotime($request->idade[$i])), //idade do beneficiário
                        'valor' => number_format($faixa, 2, ',', '.') //valor individual para o plano selecionado
                    ];
                    
                    $total += $faixa; //atualiza o valor total do plano selecionado
                endfor;
        
                $total = number_format($total, 2, ',', '.'); //valor total do plano selecionado, formatado para moeda

                if($request->session()->has('contratos')):
                    $contrato_id = $request->session()->get('contratos')[count($request->session()->get('contratos')) - 1]['id'] + 1; //proxima id do contrato
                else:
                    $contrato_id = 1; //id do contrato caso não exista nenhum cadastrado
                endif;

                $contrato = [ //array com as informações do contrato
                    'id' => $contrato_id,
                    'plano_id' => $plano['codigo'],
                    'plano' => $plano['nome'],
                    'quantidade' => $quantidade,
                    'beneficiarios' => $beneficiarios,
                    'total' => $total,
                    'criado_em' => date('Y-m-d H:i:s')
                ];

                $request->session()->put('carrinho', $contrato); //armazena no carrinho o contrato com as suas informações
                
                $data['mensagem'] = "Verifique o status final do plano contratado."; //mensagem de sucesso
                $data['view'] = view('components/calculo', compact('contrato'))->render(); //renderiza a view com as informações do contrato
                
                break;
            else: //se não existe mostra o erro
                $data['mensagem'] = "O plano indicado não existe. Por favor, selecione um plano válido."; //mensagem de erro
            endif;
        endforeach;

        return json_encode($data);
    }

    public function store(Request $request, $id) {
        $plano_id = $id;

        foreach($this->planos as $p):
            if($p->codigo == $plano_id): //verifica se o plano existe
                $contratos = $request->session()->get('contratos'); //resgata as informações dos contratos ja existentes
                
                $contratos[] = $request->session()->get('carrinho'); //registra o novo contrato

                $request->session()->flush('carrinho'); //limpa o carrinho de compras
        
                $request->session()->put('contratos', $contratos); //salva o registro do contrato
            endif;
        endforeach;
        
        return redirect('/');
    }

    public function show(Request $request, $id) {
        $contrato_id = $id;

        $contratos = $request->session()->get('contratos'); //resgata os contratos existentes

        foreach($contratos as $c):
            if($c['id'] == $contrato_id): //seleciona o contrato
                $contrato = $c;
            endif;
        endforeach;

        $data['view'] = view('components/show', compact('contrato'))->render(); //renderiza a view com o contrato selecionado

        return json_encode($data);
    }
}
