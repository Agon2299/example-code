server '185.137.232.64', user: 'deployer', roles: %w{web app laravel composer}, port: 60000
set :ssh_options, {
    keys: %w(~/.ssh/id_rsa),
    forward_agent: true,
    auth_methods: %w(publickey)
  }