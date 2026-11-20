<?php

// The name of the document root folder (default = app)
$documentRootDirectoryName = 'app';

// The PHP Namespace of the project classes etc. depending to the project (inside the src/php folder)
$phpProjectNameSpace = 'Niirrty\\Example';

$composerPackageName = 'niirrty/web-app-template';
$composerPackageDesc = 'MyTemplate for building a new web application';


/* ************************************************************************************
 * DO NOT CHANGE ANYTHING BELOW!                                                      *
 * ************************************************************************************/

// Remove leading and trailing Namespace separator from project namespace, if defined
$phpProjectNameSpace = \trim( $phpProjectNameSpace, '\\' );

// Rename DOCUMENT ROOT folder if required
if ( 'app' !== $documentRootDirectoryName )
{
    \rename( __DIR__ . '/app', __DIR__ . '/' . $documentRootDirectoryName );
}

$composerContent = \file_get_contents( __DIR__ . '/composer.json' );

#region - - -   C H A N G E   c o m p o s e r   p a c k a g e   n a m e   a n d   d e s c r  i p t i o n   - - -

$composerContent = \str_replace(
    '"niirrty/web-app-template"',
    '"' . $composerPackageName . '"',
    $composerContent
);
echo "\n- Composer package name changed to: " . $composerPackageName;
$composerContent = \str_replace(
    '"MyTemplate for building a new web application"',
    '"' . $composerPackageDesc . '"',
    $composerContent
);
echo "\n- Composer package description changed to: " . $composerPackageDesc;

#endregion

#region - - -   C H A N G E   c o m p o s e r   p r o j e c t   n a m e s p a c e   - - -

$composerContent = str_replace(
    '"Niirrty\\\\Example\\\\"',
    '"' . \str_replace( '\\', '\\\\', $phpProjectNameSpace ) . '\\\\"',
    $composerContent
);
\file_put_contents( __DIR__ . '/composer.json', $composerContent );
$composerContent = null;
echo "\n- Composer project namespace changed to: " . $phpProjectNameSpace;

#endregion

$phpFiles = [
    __DIR__ . '/src/php/ApplicationServiceProvider.php',
    __DIR__ . '/src/php/Routes/AbstractRoute.php',
    __DIR__ . '/src/php/Routes/Fallback.php',
    __DIR__ . '/src/php/Routes/Home.php',
    __DIR__ . '/' . $documentRootDirectoryName . '/index.php',
];

foreach ( $phpFiles as $file )
{
    $phpFileContent = \file_get_contents( $file );
    $phpFileContent = \str_replace(
        'Niirrty\\Example',
        $phpProjectNameSpace,
        $phpFileContent
    );
    \file_put_contents( $file, $phpFileContent );
    $phpFileContent = null;
    echo "\n- project namespace changed inside: " . $file;
}

