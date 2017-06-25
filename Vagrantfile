Vagrant.configure(2) do |config|

  config.vm.box = "v0rtex/xenial64"
  
  # Mentioning the SSH Username/Password:
  config.vm.boot_timeout = 100000000000
  config.vm.synced_folder "src/", "/var/www/error_api/src", owner: "www-data", group: "www-data"
  config.vm.synced_folder "build/", "/var/www/error_api/build", owner: "vagrant", group: "vagrant"
  config.vm.synced_folder "vagrant/", "/home/vagrant/install", owner: "vagrant", group: "vagrant"
  config.vm.synced_folder "sql/", "/home/vagrant/sql", owner: "vagrant", group: "vagrant"

  # Begin Configuring
  config.vm.define "error_api" do|error_api|
    error_api.vm.hostname = "errorapi.dev" # Setting up hostname
    error_api.vm.network "private_network", ip: "192.168.201.72" # Setting up machine's IP Address
    error_api.vm.provision :shell, path: "vagrant/install.sh" # Provisioning with script.sh
  end

  config.vm.provider :virtualbox do |vb|
    vb.gui = true
  end

  config.vm.post_up_message = "You can access Error Api at http://192.168.201.72"

  # End Configuring
end