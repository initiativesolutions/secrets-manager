# Secrets Manager

> The **Secrets Manager** application is a command-line tool that allows you to encrypt sensitive values for your application's `.env` files. This ensures that sensitive values are not stored in plain text, enhancing the security of your applications.

## Installation

To install the Secrets Manager application, follow these steps:

1. Clone this repository: `git clone https://github.com/initiativesolutions/secrets-manager.git`
2. Install dependencies: `composer install`

## Configuration

The default configuration of the application is set in the `config.yaml` file. Make sure to adjust these values according to your needs.

Example:

```yaml
encryption_key:
  location: /var/keys/secrets-manager/encryption
  file_name: encrypt.key
secrets_files:
  location: /var/keys/secrets-manager/secrets
  prefix: secrets_
encrypt:
  algorithm: aes256
```

## Usage

> Ensure you run the application with a user having **necessary rights** on the machine or server, as the application performs file read and write operations.

Here are the available commands in the application:

- `bin/secretctl keygen`: Generates a security key that will be used to encrypt and decrypt tokens. This command should be run first.

- `bin/secretctl encrypt [TOKEN_NAME] -app [application name] -env [environnement name]`: Encrypts token one by one.

- `bin/secretctl encrypt -file [file path to .env] -app [application name] -env [environnement name] [--remove-file]`: Encrypts json file by passing path (you can use --remove-file to delete .env file after encryption)

- `bin/secretctl rotate`: Re-encrypts tokens and generates a new security key. This command re-runs the `keygen` and `encrypt` commands.

- `bin/secretctl delete`: Delete a token.

- `bin/secretctl list -app [application name] -env [environnement name]`: List tokens.

- `bin/secretctl help`: See help for all commands

## Tests

You can run tests with `vendor/bin/phpunit tests/`.