<?php

namespace App\Helpers;

class AppHelper
{
    /**
     * Function: addScheme
     * Returns an url with http:// or https://
     */
    public static function addScheme($url, $scheme = 'http://')
    {
        if (! empty($url) && strpos($url, '@') !== false) {
            return $url;
        }

        if (empty($url)) {
            return $url;
        }

        return parse_url($url, PHP_URL_SCHEME) === null ? $scheme.$url : $url;
    }

    /**
     * Function: setLinkTarget
     * Checks whether or not a link is internal (i.e. https://wbg.ch) or external
     * and sets the target accordingly
     */
    public static function linkTarget($url)
    {
        if (strpos($url, 'wbg.ch') !== false) {
            return 'rel="canonical"';
        } else {
            return 'target="_blank"';
        }
    }

    /**
     * Function: sanitize
     * Returns a sanitized string, typically for URLs.
     *
     * Parameters:
     *     $string - The string to sanitize.
     *     $force_lowercase - Force the string to lowercase?
     *     $anal - If set to *true*, will remove all non-alphanumeric characters.
     */
    public static function sanitizeFilename($string, $force_lowercase = true, $anal = false)
    {
        $strip = ['~', '`', '!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '_', '=', '+', '[', '{', ']', '}', '\\', '|', ';', ':', '"', "'", '&#8216;', '&#8217;', '&#8220;', '&#8221;', '&#8211;', '&#8212;', 'â€”', 'â€“', ',', '<', '>', '/', '?'];
        $clean = trim(str_replace($strip, '', strip_tags($string)));
        $clean = preg_replace('/\s+/', '-', $clean);
        $clean = ($anal) ? preg_replace('/[^a-zA-Z0-9]/', '', $clean) : $clean;

        return ($force_lowercase) ? (function_exists('mb_strtolower')) ? mb_strtolower($clean, 'UTF-8') : strtolower($clean) : $clean;
    }

    public static function transliterate($string = null)
    {
        $search = [
            'ä', 'ö', 'ü', 'é', 'è', 'â', 'à', 'ç',
        ];

        $replace = [
            'ae', 'oe', 'ue', 'e', 'e', 'a', 'a', 'c',
        ];

        return (string) str_replace($search, $replace, mb_strtolower($string, 'UTF-8'));
    }

    /**
     * Absolute URL on the canonical host (APP_URL), independent of the host
     * the request came in on (e.g. www.).
     */
    public static function canonical(string $path = '/'): string
    {
        $path = trim($path, '/');

        return rtrim(config('app.url'), '/').($path === '' ? '/' : '/'.$path);
    }

    public static function slug($project = null)
    {
        $slug = '';

        if (is_array($project)) {
            // $slug = $project['id'] .'/'.
            //     \Str::slug(
            //         AppHelper::transliterate($project['name']['de']) . '-' .
            //         AppHelper::transliterate($project['location']['de']) . '-' .
            //         $project['year']
            //         , '-'
            //     );
        } else {
            $slug = $project->id.'/'.\Str::slug(AppHelper::transliterate($project->name), '-');
        }

        return $slug;
    }

    public static function nl2p($string = null)
    {
        $string = nl2br($string, false);

        // return $string;

        return '<p>'.preg_replace('#(<br>[\r\n\s]+){2}#', '</p><p>', $string).'</p>';
    }
}
