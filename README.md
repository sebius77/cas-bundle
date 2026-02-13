# cas-bundle
Bundle for SSO authentication in Symfony 6, 7 and inspired By PraynoCasAuthBundle and yraiso/casauth-bundle

# Installation

## Install the bundle via Composer by running the following command :

    composer require sebius77/cas-bundle

# Configuration

## Create the file config/packages/sebius77_cas.yaml and add :
    sebius77_cas:
        server_login_url: https://cas_server/cas
        server_validation_url: https://cas_server/cas/serviceValidate
        server_logout_url: https://cas_server/cas/logout
        xml_namespace: cas
        options: []
    

## Modify your security.yaml
    security:
        enable_authenticator_manager: true
        providers:
            cas_user_provider:
                id: sebius77.cas_user_provider

        firewalls:

        ...

        main:
            logout: ~
            provider: cas_user_provider
            custom_authenticator: sebius77.cas_authenticator
            entry_point: sebius77.cas_entry_point

        access_control:
            - { path: ^/, roles: ROLE_USER }


The changes to the package are :

    - autoloading (PSR-0 to PSR-4)
