<?php
return array(
    // The following section is new and should be added to your file
    'routes' => array(
        'home' => array(
            'type'    => 'Literal',
            'options' => array(
                'route'    => '/document/demo',
                'defaults' => array(
                    '__NAMESPACE__' => 'NpDocument\Controller',
                    'controller'    => 'demo',
                    'action'        => 'index',
                ),
            ),
            'may_terminate' => true,
        ),
    ),
);