set :stage, :production

set :ssh_user, 'arnaud'
server 'amant.cc', user: fetch(:ssh_user), roles: %w{web app db}

set :deploy_to, '/var/websites/entreprise'