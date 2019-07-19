# E/Code Crud

Para utilizar esse projeto é necessário ter instalado algumas ferramente para o desenvolvimento.

- PHP >= 7.0
- Apache/Nginx
- [Docker](https://docs.docker.com/install/)
- [Docker Compose](https://docs.docker.com/compose/install/)
- [Composer](https://getcomposer.org/download/)

## Rodar o projeto

O projeto já vem com o docker-compose.yml configurado com:

- PHP 7.3
- Apache 2.4.38
- MySQL 5.7
- Postgres (necessário descomentar)
- PHPMyAdmin (necessário descomentar)

1. Baixar esse repositório.
2. Entrar dentro da pasta **application**.
3. Rodar o comando `composer install -o` para baixar as dependências.
4. Para rodar o projeto vocẽ deve rodar o comando no diretório que está o **docker-compose.yml**:
   `docker-compose up -d --build`, esse comando acima irá dar start na máquina (docker) e quando finalizar ele irá
   liberar o terminal e não ficará travado.

5. Apos rodar o passo 1 acima você deve acessar em seu navegador de preferência a url **http://localhost** 
   e você irá cair em uma tela de login.

6. Para criar o banco e administrador você deve rodar alguns comandos que está logo a baixo.

Acessar o SH do docker
```bash
docker exec -it service_web bash
```

Entrar dentro da pasta da aplicação
```bash
cd /var/www/application
```

Criar o banco de dados
```bash
# Mac or Windows
vendor/bin/phinx migrate

# Linux
sudo -su www-data vendor/bin/phinx migrate
```

Criar o administrador padrão que terá o email: **admin@admin.com.br** e a senha: **admin@2019@**
```bash
# Mac or Windows
vendor/bin/phinx seed:run

# Linux
sudo -su www-data vendor/bin/phinx seed:run
```

Quando feito esses passos você estará apto a entrar no sistema e gerenciar o crud de pessoas físicas e jurídicas.

## Suporte

Caso tenha alguma dificuldade ao rodar o projeto pode entrar em contato atráves:

- E-mail: vagnercardosoweb@gmail.com
- WhatsApp: (44) 98452-9998
