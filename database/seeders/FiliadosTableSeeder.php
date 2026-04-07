<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Filiado;
use App\Models\Congregacao;
use App\Models\User;
use Carbon\Carbon;

class FiliadosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpar a tabela antes de popular (opcional)
        // Filiado::truncate();

        // Buscar congregações e usuários existentes
        $congregacoes = Congregacao::all();
        $users = User::all();

        if ($congregacoes->isEmpty()) {
            $this->command->error('Nenhuma congregação encontrada! Execute CongregacoesTableSeeder primeiro.');
            return;
        }

        if ($users->isEmpty()) {
            $this->command->error('Nenhum usuário encontrado! Execute UsersTableSeeder primeiro.');
            return;
        }

        // Lista de filiados fictícios
        $filiados = [
            // Membros Ativos
            [
                'matricula' => 1001,
                'nome' => 'João Silva Santos',
                'nome_carteira' => 'João Silva',
                'mae' => 'Maria Silva Santos',
                'pai' => 'José Santos',
                'documento' => '123.456.789-00',
                'telefone' => '(11) 98765-4321',
                'email' => 'joao.silva@email.com',
                'dataNascimento' => '1990-05-15',
                'estadoCivil' => 'Casado',
                'funcao' => 'Presbítero',
                'status' => 'ativo',
                'datCadastro' => '2015-03-10',
                'dataBatismo' => '2005-07-20',
                'data_Consagracao' => '2020-11-15',
                'logradouro' => 'Rua',
                'endereco' => 'das Flores',
                'numero' => '123',
                'bairro' => 'Jardim Primavera',
                'cidade' => 'São Paulo',
                'uf' => 'SP',
                'cep' => '01234-567',
                'congregacao_id' => $congregacoes->first()->id,
                'user_id' => $users->first()->id,
            ],
            [
                'matricula' => 1002,
                'nome' => 'Maria Oliveira Costa',
                'nome_carteira' => 'Maria Oliveira',
                'mae' => 'Ana Oliveira',
                'pai' => 'Carlos Oliveira',
                'documento' => '234.567.890-11',
                'telefone' => '(11) 97654-3210',
                'email' => 'maria.oliveira@email.com',
                'dataNascimento' => '1985-08-22',
                'estadoCivil' => 'Casada',
                'funcao' => 'Membro',
                'status' => 'ativo',
                'datCadastro' => '2018-02-15',
                'dataBatismo' => '1995-12-10',
                'data_Consagracao' => null,
                'logradouro' => 'Avenida',
                'endereco' => 'Paulista',
                'numero' => '1000',
                'bairro' => 'Bela Vista',
                'cidade' => 'São Paulo',
                'uf' => 'SP',
                'cep' => '01310-100',
                'congregacao_id' => $congregacoes->first()->id,
                'user_id' => $users->first()->id,
            ],
            [
                'matricula' => 1003,
                'nome' => 'Pedro Santos Lima',
                'nome_carteira' => 'Pedro Lima',
                'mae' => 'Rosa Santos',
                'pai' => 'Antonio Lima',
                'documento' => '345.678.901-22',
                'telefone' => '(11) 96543-2109',
                'email' => 'pedro.lima@email.com',
                'dataNascimento' => '1995-12-03',
                'estadoCivil' => 'Solteiro',
                'funcao' => 'Diácono',
                'status' => 'ativo',
                'datCadastro' => '2020-01-20',
                'dataBatismo' => '2008-04-15',
                'data_Consagracao' => '2022-06-10',
                'logradouro' => 'Rua',
                'endereco' => 'Aurora',
                'numero' => '456',
                'bairro' => 'Centro',
                'cidade' => 'São Paulo',
                'uf' => 'SP',
                'cep' => '01234-567',
                'congregacao_id' => $congregacoes->first()->id,
                'user_id' => $users->first()->id,
            ],
            [
                'matricula' => 1004,
                'nome' => 'Ana Paula Rodrigues',
                'nome_carteira' => 'Ana Rodrigues',
                'mae' => 'Lucia Rodrigues',
                'pai' => 'Roberto Rodrigues',
                'documento' => '456.789.012-33',
                'telefone' => '(11) 95432-1098',
                'email' => 'ana.rodrigues@email.com',
                'dataNascimento' => '1988-03-18',
                'estadoCivil' => 'Divorciada',
                'funcao' => 'Membro',
                'status' => 'ativo',
                'datCadastro' => '2019-08-05',
                'dataBatismo' => '2000-06-25',
                'data_Consagracao' => null,
                'logradouro' => 'Praça',
                'endereco' => 'da Sé',
                'numero' => '789',
                'bairro' => 'Sé',
                'cidade' => 'São Paulo',
                'uf' => 'SP',
                'cep' => '01001-000',
                'congregacao_id' => $congregacoes->first()->id,
                'user_id' => $users->first()->id,
            ],
            // Membros Inativos
            [
                'matricula' => 2001,
                'nome' => 'Carlos Eduardo Ferreira',
                'nome_carteira' => 'Carlos Ferreira',
                'mae' => 'Marisa Ferreira',
                'pai' => 'José Ferreira',
                'documento' => '567.890.123-44',
                'telefone' => '(11) 94321-0987',
                'email' => 'carlos.ferreira@email.com',
                'dataNascimento' => '1975-11-28',
                'estadoCivil' => 'Casado',
                'funcao' => 'Membro',
                'status' => 'inativo',
                'datCadastro' => '2010-05-12',
                'dataBatismo' => '1988-09-30',
                'data_Consagracao' => null,
                'logradouro' => 'Rua',
                'endereco' => 'Tupis',
                'numero' => '321',
                'bairro' => 'Centro',
                'cidade' => 'São Paulo',
                'uf' => 'SP',
                'cep' => '01234-567',
                'congregacao_id' => $congregacoes->first()->id,
                'user_id' => $users->first()->id,
            ],
            [
                'matricula' => 2002,
                'nome' => 'Fernanda Lima Souza',
                'nome_carteira' => 'Fernanda Souza',
                'mae' => 'Tereza Lima',
                'pai' => 'Ricardo Souza',
                'documento' => '678.901.234-55',
                'telefone' => '(11) 93210-9876',
                'email' => 'fernanda.souza@email.com',
                'dataNascimento' => '1992-07-14',
                'estadoCivil' => 'Solteira',
                'funcao' => 'Membro',
                'status' => 'inativo',
                'datCadastro' => '2021-03-18',
                'dataBatismo' => '2005-11-12',
                'data_Consagracao' => null,
                'logradouro' => 'Avenida',
                'endereco' => 'Brasil',
                'numero' => '555',
                'bairro' => 'Jardim América',
                'cidade' => 'São Paulo',
                'uf' => 'SP',
                'cep' => '01234-567',
                'congregacao_id' => $congregacoes->first()->id,
                'user_id' => $users->first()->id,
            ],
            // Membros Transferidos
            [
                'matricula' => 3001,
                'nome' => 'Roberto Alves Mendes',
                'nome_carteira' => 'Roberto Mendes',
                'mae' => 'Clara Alves',
                'pai' => 'Paulo Mendes',
                'documento' => '789.012.345-66',
                'telefone' => '(11) 92109-8765',
                'email' => 'roberto.mendes@email.com',
                'dataNascimento' => '1982-09-05',
                'estadoCivil' => 'Casado',
                'funcao' => 'Presbítero',
                'status' => 'transferido',
                'datCadastro' => '2012-11-20',
                'dataBatismo' => '1995-03-15',
                'data_Consagracao' => '2018-08-22',
                'logradouro' => 'Rua',
                'endereco' => 'Augusta',
                'numero' => '789',
                'bairro' => 'Cerqueira César',
                'cidade' => 'São Paulo',
                'uf' => 'SP',
                'cep' => '01412-000',
                'congregacao_id' => $congregacoes->first()->id,
                'user_id' => $users->first()->id,
            ],
            [
                'matricula' => 3002,
                'nome' => 'Patrícia Gomes Ribeiro',
                'nome_carteira' => 'Patrícia Ribeiro',
                'mae' => 'Helena Gomes',
                'pai' => 'Marcos Ribeiro',
                'documento' => '890.123.456-77',
                'telefone' => '(11) 91098-7654',
                'email' => 'patricia.ribeiro@email.com',
                'dataNascimento' => '1998-04-25',
                'estadoCivil' => 'Solteira',
                'funcao' => 'Missionário',
                'status' => 'transferido',
                'datCadastro' => '2022-01-10',
                'dataBatismo' => '2010-08-20',
                'data_Consagracao' => '2023-02-14',
                'logradouro' => 'Rua',
                'endereco' => 'Haddock Lobo',
                'numero' => '1234',
                'bairro' => 'Cerqueira César',
                'cidade' => 'São Paulo',
                'uf' => 'SP',
                'cep' => '01414-001',
                'congregacao_id' => $congregacoes->first()->id,
                'user_id' => $users->first()->id,
            ],
        ];

        // Inserir os filiados
        foreach ($filiados as $filiado) {
            // Se tiver mais de uma congregação, distribui os filiados
            if ($congregacoes->count() > 1 && isset($filiado['congregacao_id'])) {
                // Mantém a congregação definida
            } else {
                // Distribui entre as congregações disponíveis
                $filiado['congregacao_id'] = $congregacoes->random()->id;
            }

            // Distribui entre os usuários disponíveis
            $filiado['user_id'] = $users->random()->id;

            Filiado::create($filiado);
        }

        $this->command->info('✅ ' . count($filiados) . ' filiados criados com sucesso!');
    }
}