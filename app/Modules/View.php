<?php

namespace App\Modules;

use Exception;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;

class View
{
    public static function display(string $template, $vars = [])
    {
        // dd(memory_get_peak_usage(true));
        $config = new Config();
        if ($config->get('template.cache.enable'))
            $cache = ['cache' => $config->get('template.cache.dir'), 'auto_reload' => true];
        else
            $cache = ['auto_reload' => true];

        $loader = new FilesystemLoader($config->get('template.dir'));
        $twig = new Environment($loader, $cache);

        /**  функция аналог MIX blade laravel */
        $base_url = $config->get('base_url');
        $functionMix = new TwigFunction('mix', function ($path) use($base_url) {
            try {
                $file = file_get_contents(__DIR__ . "/../../public/mix-manifest.json");
            } catch (Exception $e) {
                return $base_url . '/' . $path;
            }

            $json = json_decode($file, true);

            foreach ($json as $key => $a)
                if('/' . $path == $key)
                    return $base_url . $a;

            return $base_url . '/' . $path;
        });
        $twig->addFunction($functionMix);

        try {
            echo $twig->render($template, $vars);
        } catch (LoaderError $e) {
            return false;
        } catch (RuntimeError $e) {
            return false;
        } catch (SyntaxError $e) {
            return false;
        }

        return true;
    }
}