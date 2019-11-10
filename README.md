# Descrição do projeto

Código fonte de um projeto feito em PHP, utilizando o Framework Laravel na sua versão 5.5. O projeto possui uma fácil manutenção, pois criei pensando nisso, foi desenvolvido com o Framework mais utilizado no mundo, isso quer dizer que a documentação do mesmo é atualizada frequentemente.
O sistema possui a parte Admin, pois para cadastrar as marcas, categorias e os produtos com as imagens que serão exibidas na vitrine.
O Front-end do mesmo poderá ser customizado por sua conta e risco, caso isso aconteça isso não vai ser custoso, pois utilizei o Framework knockout JS, as partes que compõem a tela principal a maioria é componente do KO-js.
A parte de CSS foi utilizado o Framework Bootstrap v3.3.7, caso queira mudar isso não será um problema. O site é totalmente responsivo, ou seja, em qualquer tamanho de tela dos dispositivos ele se adequa sozinho.

# Instalação

Como disse, o projeto foi desenvolvido com o Framework Laravel na sua versão 5.5, então para rodar localmente na máquina do programador ou em um servidor que suporte o composer, segue abaixo o passo a passo.

# Configurando

1- Rodar comando "composer install"

2- Configurando o .env

1- Renomeie o arquivo "env.example" para ".env"
2- Setar a url do projeto na chave "APP_URL" (exemplo abaixo)

APP_URL=http://localhost:8000

3- Setar as configurações de conexão com a base de dados (exemplo abaixo)

DB_CONNECTION=mysql<br />
DB_HOST=127.0.0.1<br />
DB_PORT=3306<br />
DB_DATABASE=sigres<br />
DB_USERNAME=root<br />
DB_PASSWORD=<br />

3- Rodar comando "php artisan migrate"

4- Rodar comando "php artisan key:generate"

5- Rodar comando "php artisan passport:install"

6- Rodar comando "php artisan passport:keys"

7- Rodar comando "php artisan db:seed --class=DatabaseSeeder"

8- Rodar comando "php artisan storage:link"
