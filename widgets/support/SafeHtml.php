<?php

namespace cinghie\adminlte3\widgets\support;

use yii\helpers\Url;

/**
 * Internal normalization helpers for security-sensitive HTML attributes.
 *
 * This class deliberately stays outside the public widget API. It centralizes
 * validation that would otherwise be duplicated across widgets and drift over
 * time. Callers should still HTML-encode visible text with Yii helpers.
 *
 * @internal
 */
final class SafeHtml
{
    /**
     * Removes characters that are unsafe or meaningless in a CSS class list.
     *
     * The returned value may contain multiple classes separated by spaces.
     *
     * @param mixed $value Candidate class list.
     * @param string $default Value returned when the normalized list is empty.
     * @return string
     */
    public static function cssClass($value, string $default = ''): string
    {
        if ($value === null || $value === '') {
            return $default;
        }

        $normalized = preg_replace('/[^A-Za-z0-9_\- ]/', '', (string) $value);
        $normalized = trim((string) preg_replace('/\s+/', ' ', (string) $normalized));

        return $normalized !== '' ? $normalized : $default;
    }

    /**
     * Normalizes an icon class list using the same policy as other CSS classes.
     *
     * @param mixed $value Candidate icon class list.
     * @param string $default Fallback icon classes.
     * @return string
     */
    public static function iconClass($value, string $default = ''): string
    {
        return self::cssClass($value, $default);
    }

    /**
     * Returns whether a URL begins with an explicitly unsafe executable scheme.
     *
     * @param mixed $url Candidate URL.
     * @return bool
     */
    public static function hasDangerousScheme($url): bool
    {
        return preg_match('#^\s*(?:javascript|data|vbscript):#i', (string) $url) === 1;
    }

    /**
     * Normalizes a general link target used by widgets.
     *
     * Route arrays are resolved through Yii's URL helper. Relative URLs,
     * fragments and valid absolute HTTP(S) URLs are allowed. Protocol-relative
     * URLs and unsupported explicit schemes are rejected unless a scheme is
     * explicitly listed in $allowedSchemes.
     *
     * @param mixed $url String URL or Yii route array.
     * @param string $fallback Safe fallback used for rejected targets.
     * @param string[] $allowedSchemes Additional explicit schemes to allow.
     * @return string
     */
    public static function linkUrl($url, string $fallback = '#', array $allowedSchemes = []): string
    {
        if (is_array($url)) {
            return Url::to($url);
        }

        if ($url === null || $url === '') {
            return $fallback;
        }

        $url = trim((string) $url);
        if ($url === '' || self::hasDangerousScheme($url) || str_starts_with($url, '//')) {
            return $fallback;
        }

        if (preg_match('#^([a-z][a-z0-9+.-]*):#i', $url, $matches) === 1) {
            $scheme = strtolower($matches[1]);
            if (in_array($scheme, ['http', 'https'], true)) {
                return self::httpUrl($url) ?? $fallback;
            }
            if (!in_array($scheme, $allowedSchemes, true)) {
                return $fallback;
            }
        }

        return $url;
    }

    /**
     * Validates an absolute HTTP(S) URL.
     *
     * @param mixed $url Candidate URL.
     * @return string|null Normalized URL, or null when invalid.
     */
    public static function httpUrl($url): ?string
    {
        $url = trim((string) $url);
        if ($url === '' || filter_var($url, FILTER_VALIDATE_URL) === false) {
            return null;
        }

        $scheme = strtolower((string) parse_url($url, PHP_URL_SCHEME));
        $host = parse_url($url, PHP_URL_HOST);

        return in_array($scheme, ['http', 'https'], true) && is_string($host) && $host !== ''
            ? $url
            : null;
    }

    /**
     * Returns a mailto URL only for syntactically valid email addresses.
     *
     * @param mixed $email Candidate email address.
     * @return string|null
     */
    public static function emailHref($email): ?string
    {
        $email = trim((string) $email);

        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false ? 'mailto:' . $email : null;
    }

    /**
     * Returns safe attributes for links opened in a new browser tab.
     *
     * @param string|null $target Anchor target.
     * @return array<string,string>
     */
    public static function externalLinkOptions(?string $target): array
    {
        if ($target === null || $target === '') {
            return [];
        }

        $options = ['target' => $target];
        if ($target === '_blank') {
            $options['rel'] = 'noopener noreferrer';
        }

        return $options;
    }
}
