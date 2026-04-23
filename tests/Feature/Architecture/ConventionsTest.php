<?php

use Illuminate\Support\Facades\File;
use Symfony\Component\Finder\SplFileInfo;

it('does not use dd/dump/ray in production code', function () {
    $paths = [
        app_path(),
        base_path('routes'),
    ];

    $forbiddenPatterns = [
        '/\bdd\s*\(/',
        '/\bdump\s*\(/',
        '/\bray\s*\(/',
    ];

    foreach ($paths as $path) {
        /** @var SplFileInfo $file */
        foreach (File::allFiles($path) as $file) {
            if ($file->getExtension() !== 'php') {
                continue;
            }

            $contents = $file->getContents();

            foreach ($forbiddenPatterns as $pattern) {
                expect($contents)->not->toMatch($pattern, $file->getRealPath().' matches '.$pattern);
            }
        }
    }
});

it('does not use DB facade in module Controllers or UseCases', function () {
    $paths = [
        app_path('Modules'),
    ];

    $forbiddenPatterns = [
        '/use\s+Illuminate\\\\Support\\\\Facades\\\\DB;/',
        '/\bDB::/',
    ];

    /** @var SplFileInfo $file */
    foreach (File::allFiles($paths[0]) as $file) {
        if ($file->getExtension() !== 'php') {
            continue;
        }

        $relative = str_replace(app_path().DIRECTORY_SEPARATOR, '', $file->getRealPath());
        $relative = str_replace(['\\', '/'], '/', $relative);

        $isControllerOrUseCase = str_contains($relative, 'Controllers/')
            || str_contains($relative, 'UseCases/');

        if (! $isControllerOrUseCase) {
            continue;
        }

        $contents = $file->getContents();

        foreach ($forbiddenPatterns as $pattern) {
            expect($contents)->not->toMatch($pattern, $relative.' matches '.$pattern);
        }
    }
});

it('keeps UseCases independent from Http layer', function () {
    $useCasesPath = app_path('Modules');

    /** @var SplFileInfo $file */
    foreach (File::allFiles($useCasesPath) as $file) {
        if ($file->getExtension() !== 'php') {
            continue;
        }

        $relative = str_replace(app_path().DIRECTORY_SEPARATOR, '', $file->getRealPath());
        $relative = str_replace(['\\', '/'], '/', $relative);

        if (! str_contains($relative, 'UseCases/')) {
            continue;
        }

        $contents = $file->getContents();

        expect($contents)->not->toContain('Illuminate\\Http', $relative.' references Illuminate\\Http');
        expect($contents)->not->toContain('\\Requests\\', $relative.' references Requests');
        expect($contents)->not->toContain('\\Controllers\\', $relative.' references Controllers');
    }
});
