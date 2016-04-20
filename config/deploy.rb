# config valid only for current version of Capistrano
lock '3.4.1'

set :application, 'Entreprise'
set :repo_url, 'git@github.com:aamant/entreprise.git'

set :symfony_directory_structure, 2

set :linked_files, %w{app/config/parameters.yml}
set :linked_dirs, %w{app/logs vendor}

namespace :deploy do

    after 'deploy:updated', 'deploy:migrate' do
        on roles(:db) do
            symfony_console('doctrine:schema:update', '--force')
        end
    end

    before 'symfony:cache:warmup', 'symfony:assets:install'
    before 'symfony:cache:warmup', 'symfony:assetic:dump' do
        on roles(:web) do
            symfony_console('assetic:dump')
        end
    end
end