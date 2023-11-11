<h1>Sistema de Carteira Estudantil em PHP</h1>

Este é um sistema de carteira estudantil desenvolvido em PHP, projetado para facilitar a emissão e gerenciamento de carteiras estudantis para instituições de ensino. A carteira estudantil é uma ferramenta essencial para os estudantes, permitindo-lhes acessar diversos benefícios e descontos em serviços e eventos.

Carteira Estudantil

Recursos Principais
Cadastro de Estudantes: O sistema permite o cadastro fácil e rápido de estudantes, incluindo informações como nome, foto, curso e data de matrícula.

Emissão de Carteiras: Com apenas alguns cliques, você pode gerar carteiras estudantis personalizadas para cada aluno, com a foto e informações necessárias.

Validação de Carteiras: A carteira pode ser facilmente validada por meio de um código QR único, garantindo sua autenticidade.

Benefícios: Benefícios disponíveis para os estudantes, como descontos em transporte, cinema, restaurantes, entre outros.

<h3>Instalação</h3>
Siga estas etapas para instalar o sistema em seu servidor:<br>

Clone o repositório para o seu servidor web.

git clone
<a href="https://github.com/ojoues/carteira_estudantil.git
"> https://github.com/ojoues/carteira_estudantil.git
</a>

Crie um banco de dados MySQL (db_alunos) com as tabelas necessárias.

Tabelas: estudante, usuarios.

Estruturas<br>
estudante: id (int), nome, data_nascimento (date), sexo, instituicao, curso, cpf, validade, imagem, modificado (datetime), criado (datetime).<br>
usuario: id (int), nome, email, usuario, senha, modificado (datetime), criado (datetime).

Configure as informações de conexão com o banco de dados no arquivo conexao.php.

$servidor = "localhost";<br>
$usuario = "root";<br>
$senha = "";<br>
$dbname = "db_alunos";<br>

//Criar a conexao<br>
$conn = mysqli_connect($servidor, $usuario, $senha, $dbname);<br>

// Substitua 'America/Sao_Paulo' pelo fuso horário desejado
date_default_timezone_set('America/Sao_Paulo');

<h3>Contribuição</h3>
Se você deseja contribuir para o desenvolvimento deste sistema, sinta-se à vontade para criar um fork do repositório, fazer as alterações necessárias e enviar um pull request. Ficaremos felizes em revisar suas contribuições.<br><br>

<h3>Suporte</h3>
Se você encontrar problemas ou tiver dúvidas sobre o sistema, entre em contato conosco através do e-mail de suporte: <a href="mailto:ojoues@icloud.com">ojoues@icloud.com</a>.<br><br>