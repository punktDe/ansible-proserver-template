# proServer Ansible Template

This repository contains Ansible playbook examples for your proServer.
It depends on our open [open source Ansible roles](https://github.com/punktDe?q=topic%3Aproserver+topic%3Aansible), which are included as submodules.
As of now, there are two supported applications:

- [Neos](https://www.neos.io/)
- [TYPO3](https://typo3.org/)

There are several components (roles):

**Relational databases**

- [PostgreSQL](https://github.com/punktDe/ansible-proserver-postgresql)
- [MySQL / MariaDB](https://github.com/punktDe/ansible-proserver-mariadb)

**Full text search databases**

- [Elasticsearch](https://github.com/punktDe/ansible-proserver-elasticsearch)
- Apache Solr (planned)

**In-memory databases**

- [Redis](https://github.com/punktDe/ansible-proserver-redis)

**Web servers**

You can choose which web server to install by adding your host to the respective group (`apache` or `nginx`) in `inventory.ini`. Default is nginx

- [nginx](https://github.com/punktDe/ansible-proserver-nginx)
- [Apache](https://github.com/punktDe/ansible-proserver-apache)

**Mail servers**

- [Sendmail](https://github.com/punktDe/ansible-proserver-mail) (for production)
- [MailHog](https://github.com/punktDe/ansible-proserver-mailhog) (for testing)

**Other components**

- [System](https://github.com/punktDe/ansible-proserver-system) (base system configuration)
- [PHP and PHP-FPM](https://github.com/punktDe/ansible-proserver-php)
- [Supervisor](https://github.com/punktDe/ansible-proserver-supervisord) (to manage custom daemons)
- [OAuth2 Proxy](https://github.com/punktDe/ansible-proserver-oauth2-proxy) (for advanced access control)
- [Dehydrated](https://github.com/punktDe/ansible-proserver-dehydrated) (for acquiring X.509 certificates using ACME / Let's Encrypt)

## Getting Started

**1)** Clone this repository and submodules

```bash
git clone --recurse-submodules git@github.com:punktDe/ansible-proserver-template.git
cd ansible-proserver-template
```

**2)** Install Ansible on your local machine. Ansible >=2.15 should work. See the [Ansible Installation Guide](https://docs.ansible.com/ansible/latest/installation_guide/intro_installation.html) for detailed instructions for your operating system. If you have Python 3 and venv installed, you can use this command:

```bash
python3 -m venv venv
venv/bin/pip install -r requirements.txt
source .envrc  # Hint: Install Direnv (direnv.net) to do this automatically in the future.
```

**3)** Adapt Ansible configuration

Basically there are two files, that define the services and configuration for your proServer instance:

[**inventory.ini**](inventory.ini)

Your inventory contains a list of hosts (proServers) and the groups each host belongs to.
The groups are later used by the playbook to determine which roles
(applications and components) to provision on a host.

Replace at least any occurrence of `vpro0000` with your proServer ID(s) and
uncomment `staging`/`production` within the application groups section.

[**host_vars/**](host_vars/)

The [`host_vars`](host_vars/) directory contains a number of files, each file represents a host from your inventory.
You can copy examples from the [`host_vars_examples`](host_vars_examples/) directory.
`development.yaml` represents the development environment (Vagrant+VirtualBox).

```bash
mv host_vars_examples/neos/* host_vars/
```

Then replace at least any occurrence of `vpro0000` with your proServer ID(s).

## Start provisioning of your proServer

```bash
ansible-playbook --ssh-extra-args=-oProxyJump=jumping@ssh-jumphost.karlsruhe.punkt.de --limit=staging playbook.yaml
```

Replace `--limit=staging` with `--limit=production` to provision the production environment.
You can also remove the limit parameter to provision all environments from your [`inventory.ini`](inventory.ini).

## Neos configuration hints

The `neos` role will template the file [`/usr/local/etc/neos.env`](roles/neos/templates/neos.env.j2), which contains useful information about your environment (e.g. domain name, database type and credentials).
You can use the [`helhum/dotenv-connector`](https://github.com/helhum/dotenv-connector) package to read the file and use any variable it contains in your Neos configuration.

```bash
composer require helhum/dotenv-connector
composer config extra.helhum/dotenv-connector.env-file /usr/local/etc/neos.env
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

## TYPO3 configuration hints

The `typo3` role will template the file [`/usr/local/etc/typo3.env`](roles/typo3/templates/typo3.env.j2), which contains useful information about your environment (e.g. domain name, database type and credentials).
You can use the [`helhum/dotenv-connector`](https://github.com/helhum/dotenv-connector) package to read the file and use any variable it contains in your TYPO3 configuration.

```bash
composer require helhum/dotenv-connector
composer config extra.helhum/dotenv-connector.env-file /usr/local/etc/typo3.env
```

```php
# htdocs/typo3conf/AdditionalConfiguration.php
$GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['dbname'] = getenv('DB_NAME');
$GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['user'] = getenv('DB_USER');
$GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['password'] = getenv('DB_PASS');
$GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['host'] = strpos(getenv('DB_HOST'), ':') === false ? getenv('DB_HOST') : '[' . getenv('DB_HOST') . ']';
$GLOBALS['TYPO3_CONF_VARS']['SYS']['trustedHostsPattern'] = getenv('SITE_DOMAIN');
```

## Deployment

[Deployer](https://deployer.org/) can be used to deploy Neos or TYPO3 to a proServer.
[`deployer_examples/`](deployer_examples/) contains a set of Deployer configuration examples.

## Helpful links

- [Ansible Installation Guide](https://docs.ansible.com/ansible/latest/installation_guide/intro_installation.html)
- [Ansible Getting Started](https://docs.ansible.com/ansible/latest/user_guide/intro_getting_started.html)
