##### PRODUCTION VERSION

#### Config file

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
                is_bundle: false
                type: annotation
                dir: '%kernel.project_dir%/vendor/mh/page-bundle/Entity'
                prefix: 'Mh\PageBundle\Entity'
                alias: Mh\PageBundle



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
                dir: '%kernel.project_dir%/vendor/mh/page-bundle/Entity'
                prefix: 'Mh\PageBundle\Entity'
                alias: Mh\PageBundle


