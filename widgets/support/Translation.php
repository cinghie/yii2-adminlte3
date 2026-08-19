<?php

namespace cinghie\adminlte3\widgets\support;

use Yii;
use yii\i18n\PhpMessageSource;

/**
 * Internal translation helper for package-owned widget strings.
 *
 * The package registers its own `adminlte3` category lazily only when the host
 * application has not already configured one. Applications therefore retain
 * full control over overrides while standalone widget rendering no longer
 * depends on translation categories supplied by unrelated packages.
 *
 * @internal
 */
final class Translation
{
    public const CATEGORY = 'adminlte3';

    /**
     * Translates one package-owned message.
     *
     * @param string $message Source message.
     * @param array $params Message parameters.
     * @param string|null $language Target language.
     * @return string
     */
    public static function t(string $message, array $params = [], $language = null): string
    {
        self::register();

        return Yii::t(self::CATEGORY, $message, $params, $language);
    }

    /**
     * Registers the package message source without replacing an application override.
     *
     * @return void
     */
    private static function register(): void
    {
        if (Yii::$app === null || !Yii::$app->has('i18n')) {
            return;
        }

        $i18n = Yii::$app->getI18n();
        if (isset($i18n->translations[self::CATEGORY])) {
            return;
        }

        $i18n->translations[self::CATEGORY] = [
            'class' => PhpMessageSource::class,
            'basePath' => dirname(__DIR__, 2) . '/messages',
            'sourceLanguage' => 'en-US',
            'fileMap' => [
                self::CATEGORY => 'adminlte3.php',
            ],
        ];
    }
}
