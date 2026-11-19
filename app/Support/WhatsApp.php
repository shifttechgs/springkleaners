<?php

namespace App\Support;

/**
 * Links that open a WhatsApp chat with SpringKleaners with a message already typed, so the client only has to
 * press send (and we see straight away what they want). Each page passes the message that fits where the client
 * is; anything without one gets the general quote message.
 */
class WhatsApp
{
    public const NUMBER = '27815274711';

    public const DEFAULT_MESSAGE = "Hi SpringKleaners, I'd like a quote for a clean.";

    public static function link(?string $message = null): string
    {
        return 'https://wa.me/'.static::NUMBER.'?text='.rawurlencode($message ?: static::DEFAULT_MESSAGE);
    }

    public static function forService(string $service): string
    {
        return static::link("Hi SpringKleaners, I'd like a quote for {$service}.");
    }

    public static function forSuburb(string $suburb): string
    {
        return static::link("Hi SpringKleaners, I'd like a quote for a clean in {$suburb}.");
    }

    public static function question(): string
    {
        return static::link('Hi SpringKleaners, I have a question about your cleaning: ');
    }

    /** The bare base, for links built in the browser: base + encodeURIComponent(message). */
    public static function base(): string
    {
        return 'https://wa.me/'.static::NUMBER.'?text=';
    }
}
