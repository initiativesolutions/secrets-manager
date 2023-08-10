# Secrets Manager 🔐

> The **Secrets Manager** application is a command-line tool that allows you to encrypt sensitive values for your application's `.env` files. This ensures that sensitive values are not stored in plain text, enhancing the security of your applications.

> This project has two different uses:
>- for token encryption (command line use)
>- to decrypt the tokens from the application (secrets provider)

## Installation

### For tokens encryption 
To install the command line tool application, follow these steps:

1. Clone this repository: `git clone https://github.com/initiativesolutions/secrets-manager.git`
2. Install dependencies: `composer install`

### For tokens provider
From the application where you want to decrypt the tokens :

1. `composer require initiativesolutions/secrets-manager`

## Usage

### For tokens encryption

> Ensure you run the application with a user having **necessary rights** on the machine or server, as the application performs file read and write operations.

#### Configuration

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

#### Commands

Here are the available commands in the application:

- `bin/secretctl encrypt [TOKEN_NAME] -app [APPLICATION_NAME] -env [ENVIRONNEMENT_NAME]`: Encrypts token one by one.

- `bin/secretctl encrypt -file [PATH_TO_JSON_TOKENS] -app [APPLICATION_NAME] -env [ENVIRONNEMENT_NAME] [--remove-file]`: Encrypts json file by passing path (you can use --remove-file to delete .json file after encryption)

- `bin/secretctl rotate`: Re-encrypts tokens and generates a new security key.

- `bin/secretctl delete [TOKEN_NAME] -app [APPLICATION_NAME] -env [ENVIRONNEMENT_NAME]`: Delete a token.

- `bin/secretctl list -app [APPLICATION_NAME] -env [ENVIRONNEMENT_NAME]`: List tokens.

- `bin/secretctl help`: See help for all commands

> If you have a problem when running `bin/secretctl` then run : `dos2unix bin/secretctl` or `chmod +x bin/secretctl`

### For tokens provider

````php
$tokens = (new SecretsProvider())
    ->decrypt('path/to/encrypt.key', 'path/to/secrets/tokens');
    
$_ENV = array_merge($_ENV, $tokens);
````

## Tests

You can run tests with `vendor/bin/phpunit tests/`.