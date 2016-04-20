set :stage, :staging

set :ssh_user, 'deployer'
server 'amant.pro', user: fetch(:ssh_user), roles: %w{web app db}

set :deploy_to, '/var/websites/festival2016'