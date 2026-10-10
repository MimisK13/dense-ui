<?php

namespace Ui\Dense;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

final class DenseServiceProvider extends ServiceProvider
{
    public static function assetPath(string $filename): string
    {
        return __DIR__.'/../dist/'.$filename;
    }

    public static function assetVersion(string $filename): string
    {
        $path = self::assetPath($filename);

        return (string) (is_file($path) ? filemtime($path) : 0);
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'dense');
        Blade::anonymousComponentPath(__DIR__.'/../resources/views/components', 'dense');

        Route::get('/vendor/dense/dense.css', function () {
            return response()->file(self::assetPath('dense.css'), [
                'Content-Type' => 'text/css',
                'Cache-Control' => 'public, max-age=31536000, immutable',
            ]);
        })->name('dense.assets.css');

        Route::get('/vendor/dense/dense.js', function () {
            return response()->file(self::assetPath('dense.js'), [
                'Content-Type' => 'application/javascript',
                'Cache-Control' => 'public, max-age=31536000, immutable',
            ]);
        })->name('dense.assets.js');

        Blade::directive('denseStyles', static function (): string {
            return '<?php if (! request()->attributes->get("_dense_styles_rendered")) { request()->attributes->set("_dense_styles_rendered", true); ?><link rel="stylesheet" href="<?php echo e(route("dense.assets.css", ["v" => \\Ui\\Dense\\DenseServiceProvider::assetVersion("dense.css")])); ?>"><?php } ?>';
        });

        Blade::directive('denseScripts', static function (): string {
            return '<?php if (! request()->attributes->get("_dense_scripts_rendered")) { request()->attributes->set("_dense_scripts_rendered", true); ?><script src="<?php echo e(route("dense.assets.js", ["v" => \\Ui\\Dense\\DenseServiceProvider::assetVersion("dense.js")])); ?>" defer></script><?php } ?>';
        });
    }
}
