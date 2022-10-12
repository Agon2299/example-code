# config valid for current version and patch releases of Capistrano
lock "~> 3.14.1"

set :application, "riviera_mobile_admin"
set :repo_url, "git@bitbucket.org:instadevteam/riviera-backend.git"
# Default branch is :master
set :branch, ENV["branch"] || "master"
# Default deploy_to directory is /var/www/laravel-capistrano
set :deploy_to, '/var/www/virtual-hosts/app.rivierus.ru'
set :laravel_dotenv_file, '/var/www/virtual-hosts/app.rivierus.ru/.env'
# Default value for keep_releases is 5
set :keep_releases, 5
append :linked_dirs, 
    'storage/app',
    'storage/framework/cache',
    'storage/framework/sessions',
    'storage/framework/views',
    'storage/logs'
namespace :composer do
    desc "Running Composer Install"
    task :install do
        on roles(:composer) do
            within release_path do
                execute :composer, "install --no-dev --quiet --prefer-dist --optimize-autoloader"
                execute :composer, "dumpautoload"
            end
        end
    end
end
namespace :laravel do
#    task :fix_permission do
 #       on roles(:laravel) do
  #          execute :chmod, "-R ug+rwx #{shared_path}/storage/ #{release_path}/bootstrap/cache/"
   #         execute :chgrp, "-R www-data #{shared_path}/storage/ #{release_path}/bootstrap/cache/"
    #    end
    #end
    task :configure_dot_env do
    dotenv_file = fetch(:laravel_dotenv_file)
        on roles (:laravel) do
        execute :cp, "#{dotenv_file} #{release_path}/.env"
        end
    end
    task :reload_laravel do
       on roles(:laravel) do
           within release_path  do
               execute :php, "artisan storage:link"
           end 
       end
    end
end
namespace :deploy do
    after :updated, "composer:install"
    after :updated, "laravel:reload_laravel"
    after :updated, "laravel:configure_dot_env"
end
