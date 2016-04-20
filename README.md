entreprise
==========

Facturation pour auto-entreprise et micro-entreprise 

## Install

Installation de bundle

	gem install bundler
	
	
Installation des librairies (Capistrano 3, capistrano-symfony, ...). 

Voir fichier Gemfile pour plus de détail

	bundle install --clean --path=vendor/bundle
	
Creation de la structure distante

	bundle exec cap staging deploy:check:directories
	
Creation du fichier parameters.yml

	mkdir -p /var/websites/entreprise/shared/app/config
	vim /var/websites/app/shared/entreprise/config/parameters.yml
	
Deploiement

	bundle exec cap staging deploy
