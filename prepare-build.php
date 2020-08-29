<?php

$documentRootDirectoryName = 'app';
$phpProjectNameSpace = 'Niirrty\\Example';

if ( 'app' !== $documentRootDirectoryName )
{
    rename( __DIR__ . '/app', __DIR__ . '/' . $documentRootDirectoryName );
    $gulpContent = file_get_contents( __DIR__ . '/gulpfile.js' );
    $gulpContent = preg_replace(
        '~var documentRoot\s+=\s+\'./app\';~',
        'var documentRoot                 = \'./' . $documentRootDirectoryName . '\';',
        $gulpContent
    );
    file_put_contents( __DIR__ . '/gulpfile.js', $gulpContent );
}

if ( 'Niirrty\\Example' !== $phpProjectNameSpace )
{

    $composerContent = file_get_contents( __DIR__ . '/composer.json' );
    $composerContent = str_replace(
        '"Niirrty\\\\Example\\\\"',
        '"' . str_replace( '\\', '\\\\', $phpProjectNameSpace ) . '"',
        $composerContent
    );
    file_put_contents( __DIR__ . '/composer.json', $composerContent );
    $composerContent = null;

    $phpFiles = [
        __DIR__ . '/src/php/ApplicationServiceProvider.php',
        __DIR__ . '/src/php/Routes/AbstractRoute.php',
        __DIR__ . '/src/php/Routes/Fallback.php',
        __DIR__ . '/src/php/Routes/Home.php',
    ];

    foreach ( $phpFiles as $file )
    {
        $phpFileContent = file_get_contents( $file );
        $phpFileContent = str_replace(
            'Niirrty\\Example',
            $phpProjectNameSpace,
            $phpFileContent
        );
        file_put_contents( $file, $phpFileContent );
        $phpFileContent = null;
    }


}

