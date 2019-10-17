##### PRODUCTION VERSION

#### Config file

### knp
knp_paginator:
    page_range: 5
    default_options:
        page_name: page
        sort_field_name: sort
        sort_direction_name: direction
        distinct: true
        filter_field_name: filterField
        filter_value_name: filterValue
    template:
        pagination: '@KnpPaginator/Pagination/twitter_bootstrap_v4_pagination.html.twig'
        sortable: '@KnpPaginator/Pagination/sortable_link.html.twig'
        filtration: '@KnpPaginator/Pagination/filtration.html.twig'

### twig
add this to your twig config, so we have a global configuration service

twig:
    globals:
        config: 'Mh\PageBundle\Helper\TwigHelper'


### security

/config/packages/securty.yaml

security:
    encoders:
        Mh\PageBundle\Entity\User:
            algorithm: auto
    providers:
        app_user_provider:
            entity:
                class: Mh\PageBundle\Entity\User
                property: email
    firewalls:
        dev:
            pattern: ^/(_(profiler|wdt)|css|images|js)/
            security: false
        main:
            anonymous: true
            guard:
                authenticators:
                    - Mh\PageBundle\Security\AppCustomAuthenticator
            logout:
                path: mh_page_app_logout
    access_control:
        - { path: ^/admin, roles: ROLE_ADMIN }
        - { path: ^/profile, roles: ROLE_USER }

### routes

/config/routes.yaml

mh_page:
    resource: '@MhPageBundle/Resources/config/routes.yaml'


### doctrine

/config/packages/doctrine.yaml

doctrine:
    orm:
        mappings:
            MhPageBundle:
                is_bundle: true
                type: annotation
                dir: 'Entity'
                prefix: 'Mh\PageBundle\Entity'
                alias: 'MhPageBundle'


### change the redirect after login

we redirect to the route mh_page_login_home, just override that


### template base.html.twig

{% extends "@MhPage/base.html.twig" %}


### template layout.html.twig

{% extends "@MhPage/layout.html.twig" %}


### promote user to admin

./bin/console app:promote EMAIL


### neat features

Copy clipboard, make a:

<input type="text" style="display:none" id="copy-me" value="YAS">

and on your element

<a href="path.php" onclick="copyText('copy-me')">click</a>


### Remember

## Assets

./bin/console assets:install --symlink

## Extend admin menu

If you need to add more admin items, make a subscriber with

make:subscriber for the request

And then extend Mh\PageBundle\Helper\SiteHelper


## Make a user

make:user login


## Sitemap

If you need to add more sitemaps, copy the Mh\PageBundle\Command\SitemapCommand


##### DEV VERSION

#### Config file

### routes

/config/routes.yaml

mh_page:
    resource: '../src/Controller'
    name_prefix: mh_page_

mh_page_wildcard:
    path: /{page}
    methods: [GET]
    controller: Mh\PageBundle\Controller\MainController::page
    name_prefix: mh_page_


### maker

/config/packages/maker.yaml

maker:
    root_namespace: Mh\PageBundle


### doctrine

/config/packages/doctrine.yaml

doctrine:
    orm:
        mappings:
            MhPageBundle:
                is_bundle: false
                type: annotation
                dir: '%kernel.project_dir%/src/Entity'
                prefix: 'Mh\PageBundle\Entity'
                alias: 'MhPageBundle'
