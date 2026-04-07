<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Filiado;
use App\Models\Congregacao;
use App\Models\User;
use Carbon\Carbon;

class FiliadosMassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('');
        $this->command->info('╔═══════════════════════════════════════════════════════════════╗');
        $this->command->info('║           SEEDER DE FILIADOS EM MASSA                        ║');
        $this->command->info('╚═══════════════════════════════════════════════════════════════╝');
        $this->command->info('');
        
        // Buscar congregações e usuários existentes
        $congregacoes = Congregacao::all();
        $usuarios = User::all();
        
        if ($congregacoes->isEmpty()) {
            $this->command->error('❌ Nenhuma congregação encontrada! Execute o seeder de congregações primeiro.');
            return;
        }
        
        if ($usuarios->isEmpty()) {
            $this->command->error('❌ Nenhum usuário encontrado! Execute o seeder de usuários primeiro.');
            return;
        }
        
        // Verificar se as congregações têm os campos obrigatórios
        foreach ($congregacoes as $cong) {
            if (!$cong->uf || !$cong->cidade) {
                $this->command->warn("⚠️  Congregação '{$cong->nome}' está sem UF ou Cidade. Corrigindo...");
                
                // Corrigir dados da congregação se necessário
                if (!$cong->uf) $cong->uf = 'SP';
                if (!$cong->cidade) $cong->cidade = 'São Paulo';
                $cong->save();
            }
        }
        
        // Perguntar quantos filiados criar
        $quantidade = $this->command->ask('Quantos filiados deseja criar?', 50);
        $quantidade = (int) $quantidade;
        
        if ($quantidade <= 0) {
            $this->command->error('Quantidade inválida!');
            return;
        }
        
        $this->command->info("📝 Criando {$quantidade} filiados...");
        $this->command->newLine();
        
        // Dados para geração aleatória
        $nomesMasc = ['André', 'Bruno', 'Carlos', 'Daniel', 'Eduardo', 'Felipe', 'Gabriel', 'Heitor', 'Igor', 'João', 'Lucas', 'Marcos', 'Nathan', 'Otávio', 'Paulo', 'Rafael', 'Samuel', 'Thiago', 'Vinícius', 'Wagner'];
        $nomesFem = ['Amanda', 'Beatriz', 'Camila', 'Daniela', 'Elaine', 'Fabiana', 'Gabriela', 'Helena', 'Isabela', 'Juliana', 'Kátia', 'Letícia', 'Mariana', 'Natália', 'Olivia', 'Patrícia', 'Renata', 'Sabrina', 'Tatiane', 'Vanessa'];
        $sobrenomes = ['Silva', 'Santos', 'Oliveira', 'Souza', 'Rodrigues', 'Ferreira', 'Alves', 'Pereira', 'Lima', 'Gomes', 'Costa', 'Ribeiro', 'Martins', 'Carvalho', 'Almeida', 'Nunes', 'Soares', 'Vieira', 'Barbosa', 'Freitas'];
        
        $statusList = ['ativo', 'inativo', 'transferido'];
        $funcoes = ['Membro', 'Diácono', 'Presbítero', 'Missionário'];
        $estadosCivis = ['Solteiro', 'Casado', 'Divorciado', 'Viúvo'];
        $logradouros = ['Rua', 'Avenida', 'Praça', 'Travessa', 'Estrada', 'Sítio', 'Alameda', 'Rodovia'];
        $bairros = ['Centro', 'Jardim América', 'Vila Nova', 'Bela Vista', 'Industrial', 'Santa Cecília', 'Liberdade', 'Brooklin', 'Morumbi', 'Tatuapé', 'Vila Mariana', 'Santana', 'Butantã', 'Pinheiros', 'Moema'];
        
        // Obter a última matrícula
        $ultimaMatricula = Filiado::max('matricula') ?? 1000;
        $matriculaInicial = $ultimaMatricula + 1;
        
        $bar = $this->command->getOutput()->createProgressBar($quantidade);
        $bar->start();
        
        $criados = 0;
        $erros = 0;
        
        for ($i = 0; $i < $quantidade; $i++) {
            try {
                $isMale = rand(0, 1);
                $nome = ($isMale ? $nomesMasc[array_rand($nomesMasc)] : $nomesFem[array_rand($nomesFem)]);
                $sobrenome1 = $sobrenomes[array_rand($sobrenomes)];
                $sobrenome2 = $sobrenomes[array_rand($sobrenomes)];
                $nomeCompleto = $nome . ' ' . $sobrenome1 . ' ' . $sobrenome2;
                
                $funcao = $funcoes[array_rand($funcoes)];
                $status = $statusList[array_rand($statusList)];
                
                // Datas
                $dataNascimento = Carbon::now()->subYears(rand(18, 80))->subDays(rand(0, 365));
                $dataCadastro = Carbon::now()->subYears(rand(0, 10))->subDays(rand(0, 365));
                
                $dataBatismo = null;
                if (rand(0, 1)) {
                    $dataBatismo = Carbon::now()->subYears(rand(5, 30))->subDays(rand(0, 365));
                }
                
                $dataConsagracao = null;
                if (in_array($funcao, ['Diácono', 'Presbítero', 'Missionário']) && rand(0, 1)) {
                    $dataConsagracao = Carbon::now()->subYears(rand(1, 5))->subDays(rand(0, 365));
                }
                
                // Congregação e usuário aleatórios
                $congregacao = $congregacoes->random();
                $usuario = $usuarios->random();
                
                // GARANTIR que UF e Cidade não sejam null
                $uf = $congregacao->uf ?? 'SP';
                $cidade = $congregacao->cidade ?? 'São Paulo';
                
                // Gera CPF formatado
                $cpf = sprintf('%03d.%03d.%03d-%02d', rand(0, 999), rand(0, 999), rand(0, 999), rand(0, 99));
                
                // Gera telefone
                $ddd = ['11', '12', '13', '14', '15', '16', '17', '18', '19', '21', '22', '24', '27', '28', '31', '32', '33', '34', '35', '37', '38', '41', '42', '43', '44', '45', '46', '47', '48', '49', '51', '53', '54', '55', '61', '62', '63', '64', '65', '66', '67', '68', '69', '71', '73', '74', '75', '77', '79', '81', '82', '83', '84', '85', '86', '87', '88', '89', '91', '92', '93', '94', '95', '96', '97', '98', '99'];
                $dddEscolhido = $ddd[array_rand($ddd)];
                $telefone = sprintf('(%s) 9%04d-%04d', $dddEscolhido, rand(1000, 9999), rand(1000, 9999));
                
                // Endereço
                $cep = sprintf('%05d-%03d', rand(10000, 99999), rand(100, 999));
                
                // Nome da mãe (sempre presente)
                $nomeMae = $nomesFem[array_rand($nomesFem)] . ' ' . $sobrenomes[array_rand($sobrenomes)];
                
                // Nome do pai (opcional)
                $nomePai = null;
                if (rand(0, 1)) {
                    $nomePai = $nomesMasc[array_rand($nomesMasc)] . ' ' . $sobrenomes[array_rand($sobrenomes)];
                }
                
                // Nome na carteirinha (opcional)
                $nomeCarteira = null;
                if (rand(0, 1)) {
                    $nomeCarteira = $nome . ' ' . substr($sobrenome1, 0, 1) . '.';
                }
                
                // Garantir que todos os campos obrigatórios estão preenchidos
                $filiadoData = [
                    'matricula' => $matriculaInicial + $i,
                    'nome' => $nomeCompleto,
                    'nome_carteira' => $nomeCarteira,
                    'mae' => $nomeMae,
                    'pai' => $nomePai,
                    'documento' => $cpf,
                    'telefone' => $telefone,
                    'email' => strtolower($nome . '.' . $sobrenome1 . '@email.com'),
                    'dataNascimento' => $dataNascimento->format('Y-m-d'),
                    'estadoCivil' => $estadosCivis[array_rand($estadosCivis)],
                    'funcao' => $funcao,
                    'status' => $status,
                    'datCadastro' => $dataCadastro->format('Y-m-d'),
                    'dataBatismo' => $dataBatismo ? $dataBatismo->format('Y-m-d') : null,
                    'data_Consagracao' => $dataConsagracao ? $dataConsagracao->format('Y-m-d') : null,
                    'logradouro' => $logradouros[array_rand($logradouros)],
                    'endereco' => 'Rua ' . $sobrenomes[array_rand($sobrenomes)],
                    'numero' => (string) rand(1, 9999),
                    'bairro' => $bairros[array_rand($bairros)],
                    'cidade' => $cidade,
                    'uf' => $uf,
                    'cep' => $cep,
                    'congregacao_id' => $congregacao->id,
                    'user_id' => $usuario->id,
                    'created_at' => Carbon::now()->subDays(rand(0, 365)),
                    'updated_at' => Carbon::now()->subDays(rand(0, 30)),
                ];
                
                // Validar dados antes de inserir
                if (empty($filiadoData['uf']) || empty($filiadoData['cidade'])) {
                    $filiadoData['uf'] = 'SP';
                    $filiadoData['cidade'] = 'São Paulo';
                }
                
                Filiado::create($filiadoData);
                $criados++;
                
            } catch (\Exception $e) {
                $erros++;
                // Não mostrar cada erro individualmente para não poluir a tela
                if ($erros <= 5) {
                    $this->command->error("\n❌ Erro: " . $e->getMessage());
                } elseif ($erros == 6) {
                    $this->command->error("\n❌ (Mais erros omitidos...)");
                }
            }
            
            $bar->advance();
        }
        
        $bar->finish();
        
        $this->command->newLine(2);
        $this->command->info('╔═══════════════════════════════════════════════════════════════╗');
        $this->command->info('║                    RESULTADO DO SEEDER                        ║');
        $this->command->info('╚═══════════════════════════════════════════════════════════════╝');
        $this->command->newLine();
        
        $this->command->info("✅ Filiados criados com sucesso: {$criados}");
        
        if ($erros > 0) {
            $this->command->error("❌ Erros durante a criação: {$erros}");
        }
        
        $this->command->newLine();
        $this->command->info("📊 Total de filiados no banco: " . Filiado::count());
        
        // Estatísticas
        $this->command->newLine();
        $this->command->info('📊 ESTATÍSTICAS DOS FILIADOS:');
        $this->command->line("   • Ativos: " . Filiado::where('status', 'ativo')->count());
        $this->command->line("   • Inativos: " . Filiado::where('status', 'inativo')->count());
        $this->command->line("   • Transferidos: " . Filiado::where('status', 'transferido')->count());
        
        $this->command->newLine();
        $this->command->table(
            ['Função', 'Quantidade'],
            [
                ['Membro', Filiado::where('funcao', 'Membro')->count()],
                ['Diácono', Filiado::where('funcao', 'Diácono')->count()],
                ['Presbítero', Filiado::where('funcao', 'Presbítero')->count()],
                ['Missionário', Filiado::where('funcao', 'Missionário')->count()],
            ]
        );
        
        // Mostrar algumas congregações e seus dados
        $this->command->newLine();
        $this->command->info('📌 CONGREGAÇÕES UTILIZADAS:');
        foreach ($congregacoes as $cong) {
            $count = Filiado::where('congregacao_id', $cong->id)->count();
            $this->command->line("   • {$cong->nome} - {$cong->cidade}/{$cong->uf} - {$count} filiados");
        }
        
        $this->command->newLine();
        $this->command->info('🎉 Seeder concluído com sucesso!');
    }
}