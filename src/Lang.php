<?php

namespace Kalnoy\Cruddy;

use Illuminate\Contracts\Translation\Translator as TranslatorContract;

/**
 * A class that manages translations.
 */
class Lang
{
    /**
     * The translator.
     *
     * @var TranslatorContract
     */
    protected $translator;

    /**
     * Some UI text lines for JavaScript.
     *
     * @var array
     */
    protected $lang = [ ];

    /**
     * Init lang.
     *
     * @param TranslatorContract $translator
     */
    public function __construct(TranslatorContract $translator)
    {
        $this->translator = $translator;
    }

    /**
     * Translate a key.
     *
     * @param string $key
     * @param string $default
     *
     * @return string
     */
    public function translate($key, $default = null)
    {
        $line = $this->translator->get($key);

        return $line === $key ? $default : $line;
    }

    /**
     * Try to translate a key.
     *
     * @param string $key
     *
     * @return string
     */
    public function tryTranslate($key)
    {
        return $this->translator->get($key);
    }

    /**
     * Add some lines for JavaScript ui.
     *
     * @param array $items
     *
     * @return $this
     */
    public function lang(array $items)
    {
        $this->lang += array_map(function ($string) {
            return $this->tryTranslate($string);
        }, $items);

        return $this;
    }

    /**
     * Get built-in UI strings.
     *
     * @return array
     */
    protected function getDefaultLang()
    {
        $keys = array_keys(include __DIR__.'/../resources/lang/en/js.php');

        $strings = array_map(function ($key) {
            return $this->translator->get("cruddy::js.{$key}");
        }, $keys);

        return array_combine($keys, $strings);
    }

    /**
     * Get ui language lines.
     *
     * @return array
     */
    public function ui()
    {
        return $this->getDefaultLang() + $this->lang;
    }

    /**
     * Get current locale.
     *
     * @return string
     */
    public function getLocale()
    {
        return $this->translator->getLocale();
    }

    /**
     * Set current locale.
     *
     * @param string $locale
     */
    public function setLocale($locale)
    {
        $this->translator->setLocale($locale);
    }

}