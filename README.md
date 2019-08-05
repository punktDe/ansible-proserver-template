# proServer Ansible Template

This repository contains Ansible playbook examples for your proServer.
It depends on our open [open source Ansible roles](https://github.com/punktDe?q=topic%3Aproserver+topic%3Aansible), which are included as submodules.
As of now, there are two supported applications:

- [Neos](https://www.neos.io/)
- [TYPO3](https://typo3.org/)

There are several components (roles):

**Relational databases**

- [PostgreSQL](https://github.com/punktDe/proserver-ansible-postgresql)
- [MySQL / MariaDB](https://github.com/punktDe/proserver-ansible-mariadb)

**Full text search databases**

- [Elasticsearch](https://github.com/punktDe/proserver-ansible-elasticsearch)
- Apache Solr (planned)

**In-memory databases**

- [Redis](https://github.com/punktDe/proserver-ansible-redis)

**Web servers**

- [nginx](https://github.com/punktDe/proserver-ansible-nginx)

**Mail servers**

- [Sendmail](https://github.com/punktDe/proserver-ansible-mail) (for production)
- [MailHog](https://github.com/punktDe/proserver-ansible-mailhog) (for testing)

**Other components**

- [MOTD](https://github.com/punktDe/proserver-ansible-motd) (show host info upon login)
- [PHP and PHP-FPM](https://github.com/punktDe/proserver-ansible-php)
- [Supervisor](https://github.com/punktDe/proserver-ansible-supervisord) (to manage custom daemons)
- [OAuth2 Proxy](https://github.com/punktDe/proserver-ansible-oauth2-proxy) (for advanced access control)
- [Dehydrated](https://github.com/punktDe/proserver-ansible-dehydrated) (for acquiring X.509 certificates using ACME / Let's Encrypt)

## Getting Started

**1)** Clone this repository and submodules

```bash
git clone --recurse-submodules https://github.com/punktDe/proserver-ansible-template.git
cd proserver-ansible-template
```

**2)** Install Ansible on your local machine. Ansible >=2.8 should work. See the [Ansible Installation Guide](https://docs.ansible.com/ansible/latest/installation_guide/intro_installation.html) for detailed instructions for your operating system. If you have Python 3 and venv installed, you can use this command:

```bash
python3 -m venv venv
venv/bin/pip install ansible
source .envrc  # Hint: Install Direnv (direnv.net) to do this automatically in the future.
```

**3)** Adapt Ansible configuration

Basically there are two files, that define the services and configuration for your proServer instance:

**inventory.ini**

Your inventory contains a list of hosts (proServers) and the groups each host belongs to.
The groups are later used by the playbook to determine which roles
(applications and components) to provision on a host.

Replace at least any occurrence of `vpro0000` with your proServer ID(s) and
uncomment `staging`/`production` within the application groups section.

**host_vars/**

The `host_vars` directory contains a number of files, each file represents a host from your inventory.
You can copy examples from the `host_vars_examples` directory.
`development.yaml` represents the development environment (Vagrant+VirtualBox).

```bash
mv host_vars_examples/neos/* host_vars/
```

Then replace at least any occurrence of `vpro0000` with your proServer ID(s).

## Local development environment with Vagrant and VirtualBox

**1)** Install Vagrant and VirtualBox

On macOS, you can install Vagrant and VirtualBox via Homebrew:

```bash
brew cask install vagrant virtualbox
```

Ubuntu:

```bash
sudo apt-get install vagrant virtualbox
```

**2)** Clone this repository and it's submodules

```bash
git clone --recurse-submodules https://github.com/punktDe/proserver-ansible-example.git
cd proserver-ansible-example
```

**3)** Start proServer as a virtual machine using Vagrant

```bash
vagrant up --provision
```

**4)** Update `/etc/hosts` to include virtual host names used in your playbook

You can also use your own domain if you like.
Just update `neos.domain` and `mailhog.domain` in your host vars.

```bash
echo "172.17.78.40 neos.proserver-dev.local mailhog.proserver-dev.local" | sudo tee -a /etc/hosts
```

**5)** Go to [http://neos.proserver-dev.local](http://neos.proserver-dev.local)

## Start provisioning of your proServer

```bash
ansible-playbook --ssh-extra-args=-oProxyJump=jumping@ssh-jumphost.karlsruhe.punkt.de --limit=staging playbook.yaml
```

Replace `--limit=staging` with `--limit=production` to provision the production environment.
You can also remove the limit parameter to provision all environments from your `inventory.ini`.

## Neos configuration hints

The `neos` role will render a file `/usr/local/etc/neos.env`, which will contain useful information about your environment
(e.g. domain name, database type and credentials).
You can use the [`helhum/dotenv-connector`](https://github.com/helhum/dotenv-connector) package to read the file
and use any variable it contains in your Neos configuration.

```bash
composer require helhum/dotenv-connector
```

```yaml
# Configuration/Settings.yaml
Neos:
  Flow:
    persistence:
      backendOptions:
        driver: "%env:DB_DRIVER%"
        dbname: "%env:DB_NAME%"
        user: "%env:DB_USER%"
        password: "%env:DB_PASS%"
        host: "%env:DB_HOST%"
        charset: "%env:DB_CHARSET%"
```

## Helpful links

- [Ansible Installation Guide](https://docs.ansible.com/ansible/latest/installation_guide/intro_installation.html)
- [Ansible Getting Started](https://docs.ansible.com/ansible/latest/user_guide/intro_getting_started.html)
- [Vagrant Getting Started](https://www.vagrantup.com/intro/getting-started/index.html)
