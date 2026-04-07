<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Filiado;
use App\Models\Congregacao;
use App\Models\User;
use Carbon\Carbon;

class FiliadosRegioesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $congregacoes = Congregacao::all();
        $users = User::all();
        
        if ($congregacoes->isEmpty()) {
            $this->command->error('Nenhuma congregação encontrada!');
            return;
        }
        
        // Dados por região (SP, RJ, MG, BA, etc)
        $filiadosPorRegiao = [
            // São Paulo
            [
                'nome' => 'Antônio Carlos Menezes',
                'cidade' => 'São Paulo',
                'uf' => 'SP',
                'cep' => '01000-000',
                'endereco' => 'Av. Paulista',
                'numero' => '1000',
                'bairro' => 'Bela Vista',
            ],
            [
                'nome' => 'Lucia Helena Ferreira',
                'cidade' => 'Campinas',
                'uf' => 'SP',
                'cep' => '13000-000',
                'endereco' => 'Rua 13 de Maio',
                'numero' => '456',
                'bairro' => 'Centro',
            ],
            // Rio de Janeiro
            [
                'nome' => 'Marcos Vinicius Santos',
                'cidade' => 'Rio de Janeiro',
                'uf' => 'RJ',
                'cep' => '20000-000',
                'endereco' => 'Av. Atlântica',
                'numero' => '1500',
                'bairro' => 'Copacabana',
            ],
            [
                'nome' => 'Fernanda Costa Lima',
                'cidade' => 'Niterói',
                'uf' => 'RJ',
                'cep' => '24000-000',
                'endereco' => 'Rua da Conceição',
                'numero' => '789',
                'bairro' => 'Centro',
            ],
            // Minas Gerais
            [
                'nome' => 'José Augusto Pereira',
                'cidade' => 'Belo Horizonte',
                'uf' => 'MG',
                'cep' => '30000-000',
                'endereco' => 'Av. Afonso Pena',
                'numero' => '1234',
                'bairro' => 'Centro',
            ],
            [
                'nome' => 'Maria Aparecida Souza',
                'cidade' => 'Uberlândia',
                'uf' => 'MG',
                'cep' => '38400-000',
                'endereco' => 'Rua João Naves de Ávila',
                'numero' => '567',
                'bairro' => 'Santa Mônica',
            ],
            // Bahia
            [
                'nome' => 'João Batista Oliveira',
                'cidade' => 'Salvador',
                'uf' => 'BA',
                'cep' => '40000-000',
                'endereco' => 'Av. Sete de Setembro',
                'numero' => '890',
                'bairro' => 'Campo Grande',
            ],
            [
                'nome' => 'Tereza Cristina Santos',
                'cidade' => 'Feira de Santana',
                'uf' => 'BA',
                'cep' => '44000-000',
                'endereco' => 'Rua do Comércio',
                'numero' => '234',
                'bairro' => 'Centro',
            ],
            // Rio Grande do Sul
            [
                'nome' => 'Paulo Roberto Gomes',
                'cidade' => 'Porto Alegre',
                'uf' => 'RS',
                'cep' => '90000-000',
                'endereco' => 'Rua dos Andradas',
                'numero' => '345',
                'bairro' => 'Centro Histórico',
            ],
            [
                'nome' => 'Carla Beatriz Martins',
                'cidade' => 'Caxias do Sul',
                'uf' => 'RS',
                'cep' => '95000-000',
                'endereco' => 'Rua Sinimbu',
                'numero' => '678',
                'bairro' => 'Centro',
            ],
            // Paraná
            [
                'nome' => 'Ricardo Almeida Costa',
                'cidade' => 'Curitiba',
                'uf' => 'PR',
                'cep' => '80000-000',
                'endereco' => 'Rua XV de Novembro',
                'numero' => '901',
                'bairro' => 'Centro',
            ],
            [
                'nome' => 'Simone Aparecida Rocha',
                'cidade' => 'Londrina',
                'uf' => 'PR',
                'cep' => '86000-000',
                'endereco' => 'Av. Paraná',
                'numero' => '2345',
                'bairro' => 'Centro',
            ],
        ];
        
        $funcoes = ['Membro', 'Diácono', 'Presbítero', 'Missionário'];
        $statusList = ['ativo', 'inativo', 'transferido'];
        $estadosCivis = ['Solteiro', 'Casado', 'Divorciado', 'Viúvo'];
        $logradouros = ['Rua', 'Avenida', 'Praça', 'Travessa'];
        
        $matricula = 4000;
        
        foreach ($filiadosPorRegiao as $dados) {
            $funcao = $funcoes[array_rand($funcoes)];
            $status = $statusList[array_rand($statusList)];
            
            // Encontrar congregação na mesma região (simplificado)
            $congregacao = $congregacoes->where('cidade', $dados['cidade'])->first();
            if (!$congregacao) {
                $congregacao = $congregacoes->random();
            }
            
            Filiado::create([
                'matricula' => $matricula++,
                'nome' => $dados['nome'],
                'nome_carteira' => explode(' ', $dados['nome'])[0] . ' ' . explode(' ', $dados['nome'])[count(explode(' ', $dados['nome'])) - 1],
                'mae' => $this->getRandomMotherName(),
                'pai' => $this->getRandomFatherName(),
                'documento' => $this->gerarCpf(),
                'telefone' => $this->gerarTelefone($dados['uf']),
                'email' => strtolower(str_replace(' ', '.', $dados['nome'])) . '@email.com',
                'dataNascimento' => $this->gerarDataNascimento(),
                'estadoCivil' => $estadosCivis[array_rand($estadosCivis)],
                'funcao' => $funcao,
                'status' => $status,
                'datCadastro' => Carbon::now()->subYears(rand(1, 10)),
                'dataBatismo' => rand(0, 1) ? Carbon::now()->subYears(rand(5, 20)) : null,
                'data_Consagracao' => in_array($funcao, ['Diácono', 'Presbítero', 'Missionário']) && rand(0, 1) ? Carbon::now()->subYears(rand(1, 5)) : null,
                'logradouro' => $logradouros[array_rand($logradouros)],
                'endereco' => $dados['endereco'],
                'numero' => $dados['numero'],
                'bairro' => $dados['bairro'],
                'cidade' => $dados['cidade'],
                'uf' => $dados['uf'],
                'cep' => $dados['cep'],
                'congregacao_id' => $congregacao->id,
                'user_id' => $users->random()->id,
            ]);
        }
        
        $this->command->info('✅ ' . count($filiadosPorRegiao) . ' filiados por região criados!');
    }
    
    private function getRandomMotherName(): string
    {
        $mothers = ['Maria da Silva', 'Ana Santos', 'Lucia Ferreira', 'Tereza Costa', 'Rosa Oliveira', 'Helena Lima', 'Clara Souza'];
        return $mothers[array_rand($mothers)];
    }
    
    private function getRandomFatherName(): string
    {
        $fathers = ['José Santos', 'Antonio Silva', 'Carlos Lima', 'Paulo Costa', 'Roberto Oliveira', 'Marcos Souza'];
        return $fathers[array_rand($fathers)];
    }
    
    private function gerarCpf(): string
    {
        $cpf = '';
        for ($i = 0; $i < 11; $i++) {
            $cpf .= rand(0, 9);
        }
        return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $cpf);
    }
    
    private function gerarTelefone(string $uf): string
    {
        $ddd = [
            'SP' => ['11', '12', '13', '14', '15', '16', '17', '18', '19'],
            'RJ' => ['21', '22', '24'],
            'MG' => ['31', '32', '33', '34', '35', '37', '38'],
            'BA' => ['71', '73', '74', '75', '77'],
            'RS' => ['51', '53', '54', '55'],
            'PR' => ['41', '42', '43', '44', '45', '46'],
        ];
        
        $ddds = $ddd[$uf] ?? ['11'];
        $ddd = $ddds[array_rand($ddds)];
        
        return '(' . $ddd . ') 9' . rand(1000, 9999) . '-' . rand(1000, 9999);
    }
    
    private function gerarDataNascimento(): string
    {
        $ano = rand(1940, 2005);
        $mes = rand(1, 12);
        $dia = rand(1, 28);
        return sprintf('%d-%02d-%02d', $ano, $mes, $dia);
    }
}