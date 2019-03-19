require 'yaml'

Vagrant.configure('2') do |vagrant|
  vagrant.vm.define 'proserver-dev'
  vagrant.vm.hostname = 'proserver-dev'
  vagrant.vm.box = 'punktde/proserver-blueprint015.19.1.1'
  vagrant.vm.synced_folder '.', '/vagrant', id: 'vagrant-root', disabled: true
  vagrant.vm.network 'private_network', ip: '172.17.78.40'

  vagrant.ssh.forward_agent = true

  vagrant.vm.provider 'virtualbox' do |virtualbox|
    virtualbox.name = 'proserver-dev'
    virtualbox.memory = '4096'
    virtualbox.cpus = '4'
  end

  vagrant.vm.provision 'ansible' do |ansible|
    ansible.compatibility_mode = '2.0'
    ansible.limit = 'all'
    ansible.playbook = File.join(__dir__, 'playbook.yaml')
    ansible.groups = YAML.load_file(File.join(__dir__, 'Vagrantinventory.yaml'))
    ansible.raw_arguments = ['--ssh-extra-args="-o StrictHostKeyChecking=no"']
  end
end
