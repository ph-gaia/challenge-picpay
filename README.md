## Desafio PicPay Backend

## Problema

**https://picpay.com/jobs/desafio-backend-php**

### Solução
- Banco de dados:
Iniciei a solução definindo o banco de dados e escolhi o MySQL 5.7, então passei para a modelagem do banco, segue abaixo o MER da solução:


- Arquitetura:
A solução foi desenvolvida baseado em micro serviços, a solução apresenta uma API gateway que recebe todas as requisições e controlando o acesso, alimentando a comunidade de serviços.
A solução apresenta 3 serviços, são eles:
1. **user-service-picpay**, esse serviço tem a responsabilidade de suprir todas as demandas de **Usuários**, como busca, cadastro, atualizações e exclusão.
A API é capaz de listar todos os usuários, além de conseguir trazer informações detalhadas de um usuário específico. Durante a listagem, é possível filtar os resultados por Nome ou Username. A busca considera apenas resultados que comecem com a string especificada na busca. Como exemplo, ``` GET /users?q=joao ```

2. **transaction-service-picpay**, esse serviço tem a responsabilidade de suprir a demanda de transações entre usuários, esse serviço é comunicado através de um servidor de fila, o **RabbitMQ**.
Assim dispomos de menos acoplamento entre as duas aplicações, porque ambas têm os parâmetros de configuração do gerenciador de mensagens.
Caso tenhamos muitas requisições chegando em um curto espaço de tempo, o sistema irá processar algumas requisições, mesmo se a quantidade de requisições for realmente grande.
Nessa comunicação é utilizado o **AMQP** que é um protocolo de comunicação em rede.

3. **notification-service-picpay**, esse serviço tem a responsabilidade de suprir as demandas de notificações para o usuário, então ele suprir todas as necessidades de ambos serviços.
O serviço é utilizado quando um usuário é criado e um pagamento é realizado.

#### Dependências
- Docker
  - MySQL 5.7
  - Apache Server 2.4
    - mod_rewrite
  - PHP 7.2
    - PHP Unit
  - RabbitMQ
  - Mailhog

#### Instalação
A instalação do sistema pode ser feita seguindo os seguintes passos:
> ATENÇÃO: Os passos para instalação descritos nesta documentação, assumem que a aplicação rodará em uma máquina Linux (preferencialmente Ubuntu 16.04 LTS) e que com as dependências do docker já instaladas e configuradas.

1. Clonar ou Baixar o projeto diretamente na `Home` de usuário
```bash
$ cd ~/
```
Caso você tenha optado por baixar o arquivo zipado, descompacte o mesmo e entre no diretório criado por este processo.
```bash
$ cd ~/challenge-picpay
```
2. Para testar a solução, utilize o comando ``` docker-composer up ```. A API estará mapeada para a porta ``` 4000 ``` do seu host local

3. Configuração do Banco de Dados
O SGDB usado é o `MySQL`, e para que o sistema possa usá-lo, é necessário alterar algumas entradas no arquivo `.env`, de acordo com as suas credenciais de acesso.
O valor que deve ser alterado é `DB_HOST`. Você deve colocar o seu endereço IP.
 - DB_HOST=127.0.0.1
 - DB_PORT=3306
 - DB_DATABASE=challenge_veus
 - DB_USERNAME=root
 - DB_PASSWORD=root

4. Migração do banco
Acesse o container da aplicação com o seguinte comando: ``` docker exec -it challenge-apache-picpay bash ```.
Depois acesse a aplicação gateway com o seguinte comando:
```bash
$ cd ~/gateway-picpay
```
Em seguinda rode o seguinte comando:
```bash
$ php artisan migrate
```
Esse comando irá executar todos os arquivos de migração da aplicação e todas as tabelas do banco estarão disponíveis para ser utilizadas.

4. Atualização

#### Créditos
Esta aplicação foi desenvolvida por [Paulo Henrique Coelho Gaia](mailto:phcgaia11@yahoo.com.br).
