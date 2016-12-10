<?php

namespace Boitata\Console\Commands;

use File;
use Illuminate\Console\Command;

/**
 * Generate API Swagger documentation file in JSON format.
 */
class ApiDocumentation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api:docs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scan application annotations to generate APIs documentation files';

    /**
     * This attribute defines which paths must be scanned in order to generate
     * multiple documentation files with Swagger.
     * The key is the destination file and the value is the `Swagger\scan()`
     * args.
     *
     * @var array
     */
    protected $pathsToScan = [
        'app/public-api-documentation.json' => [
            ['app'],
        ],
    ];

    /**
     * Execute the console command.
     *
     * @return bool
     */
    public function handle(): bool
    {
        $this->defineConstants();
        $result = true;

        foreach ($this->pathsToScan as $destinationFile => $swaggerScanArgs) {
            $documentation = $this->swaggerScan(...$swaggerScanArgs);

            $result = $this->writeDocumentation(
                    $documentation,
                    $destinationFile
                ) && $result;
        }

        return $result;
    }

    /**
     * Define constants relevant to •all• API Documentations.
     *
     * Avoid defining constants separately for each documentation since there's
     * no (easy) way to undefine constants (hourly?) and each definition might
     * impact each other.
     */
    protected function defineConstants()
    {
        $scheme = true === config('app.debug') ? 'http' : 'https';
        $appUrl = $this->buildApiUrl();

        $configs = [
            'API_HOST'              => $appUrl,
            'API_SCHEME'            => $scheme,
            'API_VERSION'           => config('api.version'),
            'API_BASE_PATH'         => $this->buildBasePath(),
            'BOITATA_API_SCHEME'    => $scheme,
            'BOITATA_API_HOST'      => $appUrl,
            'BOITATA_API_BASE_PATH' => '/boitata/api/v1',
            'BOITATA_API_VERSION'   => '1.0.0',
        ];

        array_map([$this, 'define'], array_keys($configs), $configs);
    }

    /**
     * Build Public API URL.
     *
     * @return string
     */
    protected function buildApiUrl(): string
    {
        $appUrl = config('app.url');

        return preg_replace('/^https?:\/\//i', '', $appUrl);
    }

    /**
     * Build API Base Path, based on `prefix` and `version` from config.
     * E.g.: for prefix 'public/api' and version '2.1.43', base path will be:
     * `public/api/v2`.
     *
     * @see config/api.php
     *
     * @return string
     */
    protected function buildBasePath(): string
    {
        $version = explode('.', config('api.version'))[0] ?: '1';
        $prefix = config('api.prefix');

        return "/{$prefix}/v{$version}";
    }

    /**
     * Encapsulate `\Swagger\scan()` function for unit testing purpose.
     *
     * @codeCoverageIgnore
     *
     * @param array $args `\Swagger\scan()` args
     *
     * @return string
     */
    protected function swaggerScan(...$args)
    {
        return \Swagger\scan(...$args);
    }

    /**
     * @param string $documentation
     * @param string $file
     *
     * @return bool
     */
    protected function writeDocumentation(
        string $documentation,
        string $file
    ): bool {
        $path = storage_path($file);

        return $this->writeFile($path, $documentation);
    }

    /**
     * Write scanning result to json documentation file.
     *
     * @param string $path
     * @param string $documentation
     *
     * @return bool
     */
    protected function writeFile(string $path, string $documentation): bool
    {
        if (true === File::exists($path)) {
            $this->warn("File already exists, overwriting. ($path)");
        }

        if (0 === $result = File::put($path, $documentation)) {
            $this->error("Failed to generate documentation file. ($path)");
        } else {
            $this->info(
                "API documentation file generated successfully. ($path)"
            );
        }

        return (bool) $result;
    }

    /**
     * Encapsulate `define()` function for unit testing purpose.
     *
     * @param array ...$args
     *
     * @return bool
     */
    protected function define(...$args): bool
    {
        return define(...$args);
    }
}
