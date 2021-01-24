#mLearn back-end challenge

Para testar o desafio 1, basta acessar a URL /matriz (ex:http://127.0.0.1:8000/matriz)

Para testar o desafio 2, ao acessar o sistema clique no menu Usuários

## Tecnologia

- Laravel 8
- Vue 2 + VueRouter + vue-progressbar + sweetalert2 + laravel-vue-pagination
- Laravel Passport
- Admin LTE 3 + Bootstrap 4 + Font Awesome 5
- PHPUnit Test Case/Test Coverage
- Mysql

## Instalação
- git clone https://github.com/marcoaoc83/mlearn
- cd mlearn/
- composer install
- cp .env.example .env
- Update .env and set your database credentials
- php artisan key:generate
- php artisan migrate
- php artisan db:seed
- php artisan passport:install
- npm install
- (caso de algum erro de cross-env, executar "npm install --global cross-env")
- npm run dev
- php artisan serve

## Acesso

- E-Mail Address = admin@gmail.com
- Password = 123456
