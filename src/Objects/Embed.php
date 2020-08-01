<?php

declare(strict_types=1);

namespace Caroler\Objects;

use InvalidArgumentException;

/**
 * Embed object class
 *
 * @package Caroler\Objects
 * @see https://discord.com/developers/docs/resources/channel#embed-object
 */
class Embed extends AbstractObject implements ObjectInterface
{
    /**
     * @var string
     */
    public const COLOR_BLUE = '#0000FF';

    /**
     * @var string
     */
    public const COLOR_GREEN = '#008000';

    /**
     * @var string
     */
    public const COLOR_ORANGE = '#FFA500';

    /**
     * @var string
     */
    public const COLOR_PURPLE = '#800080';

    /**
     * @var string
     */
    public const COLOR_RED = '#FF0000';

    /**
     * @var string
     */
    public const COLOR_YELLOW = '#FFFF00';

    /**
     * @var string|null
     */
    protected $title;

    /**
     * @var string|null
     */
    protected $description;

    /**
     * @var string|null
     */
    protected $url;

    /**
     * @var string|null ISO8601 timestamp
     */
    protected $timestamp;

    /**
     * @var string|null Hexadecimal color code
     */
    protected $color;

    /**
     * @var array|null
     */
    protected $footer;

    /**
     * @var string|array|null
     */
    protected $image;

    /**
     * @var array|null
     */
    protected $thumbnail;

    /**
     * @var array|null
     */
    protected $video;

    /**
     * @var array|null
     */
    protected $provider;

    /**
     * @var array|null
     */
    protected $author;

    /**
     * @var array|null
     */
    protected $fields = [];

    public function __construct()
    {
        $this->setFooter("Powered by Caroler – https://carolerbot.com", "https://i.imgur.com/DAfvGyp.png");
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return \Caroler\Objects\Embed
     */
    public function setTitle(string $title): Embed
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return \Caroler\Objects\Embed
     */
    public function setDescription(string $description): Embed
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @param string $url
     *
     * @return \Caroler\Objects\Embed
     */
    public function setUrl(string $url): Embed
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getTimestamp(): ?string
    {
        return $this->timestamp;
    }

    /**
     * @param string $timestamp
     *
     * @return \Caroler\Objects\Embed
     */
    public function setTimestamp(string $timestamp): Embed
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getColor(): ?string
    {
        return $this->color;
    }

    /**
     * @param string $color
     *
     * @return \Caroler\Objects\Embed
     */
    public function setColor(string $color): Embed
    {
        $this->color = hexdec(ltrim($color, '#'));

        return $this;
    }

    /**
     * @return array|null
     */
    public function getFooter(): ?array
    {
        return $this->footer;
    }

    /**
     * @param string $text
     * @param string|null $iconUrl
     * @param string|null $proxyIconUrl
     *
     * @return \Caroler\Objects\Embed
     */
    public function setFooter(string $text, string $iconUrl = null, string $proxyIconUrl = null): Embed
    {
        $this->footer = [];
        $this->footer['text'] = $text;
        !isset($iconUrl) ?: $this->footer['icon_url'] = $iconUrl;
        !isset($proxyIconUrl) ?: $this->footer['proxy_icon_url'] = $proxyIconUrl;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getImage(): ?array
    {
        return $this->image;
    }

    /**
     * @param string $url
     * @param int|null $height
     * @param int|null $width
     * @param string|null $proxyUrl
     *
     * @return \Caroler\Objects\Embed
     */
    public function setImage(string $url, int $height = null, int $width = null, string $proxyUrl = null): Embed
    {
        $this->image['url'] = $url;
        !isset($height) ?: $this->image['height'] = $height;
        !isset($width) ?: $this->image['width'] = $width;
        !isset($proxyUrl) ?: $this->image['proxy_url'] = $proxyUrl;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getThumbnail(): ?array
    {
        return $this->thumbnail;
    }

    /**
     * @param string $url
     * @param int|null $height
     * @param int|null $width
     * @param string|null $proxyUrl
     *
     * @return \Caroler\Objects\Embed
     */
    public function setThumbnail(string $url, int $height = null, int $width = null, string $proxyUrl = null): Embed
    {
        $this->thumbnail['url'] = $url;
        !isset($height) ?: $this->thumbnail['height'] = $height;
        !isset($width) ?: $this->thumbnail['width'] = $width;
        !isset($proxyUrl) ?: $this->thumbnail['proxy_url'] = $proxyUrl;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getVideo(): ?array
    {
        return $this->video;
    }

    /**
     * @param string $url
     * @param int|null $height
     * @param int|null $width
     *
     * @return \Caroler\Objects\Embed
     */
    public function setVideo(string $url, int $height = null, int $width = null): Embed
    {
        $this->video['url'] = $url;
        !isset($height) ?: $this->video['height'] = $height;
        !isset($width) ?: $this->video['width'] = $width;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getProvider(): ?array
    {
        return $this->provider;
    }

    /**
     * @param string $name
     * @param string|null $url
     *
     * @return \Caroler\Objects\Embed
     */
    public function setProvider(string $name, string $url = null): Embed
    {
        $this->provider['name'] = $name;
        !isset($url) ?: $this->provider['url'] = $url;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getAuthor(): ?array
    {
        return $this->author;
    }

    /**
     * @param string $name
     * @param string|null $url
     * @param string|null $iconUrl
     * @param string|null $proxyIconUrl
     *
     * @return \Caroler\Objects\Embed
     */
    public function setAuthor(
        string $name,
        string $url = null,
        string $iconUrl = null,
        string $proxyIconUrl = null
    ): Embed {
        $this->author['name'] = $name;
        !isset($url) ?: $this->author['url'] = $url;
        !isset($iconUrl) ?: $this->author['icon_url'] = $iconUrl;
        !isset($proxyIconUrl) ?: $this->author['proxy_icon_url'] = $proxyIconUrl;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getFields(): ?array
    {
        return $this->fields;
    }

    /**
     * @param array[] $fields
     *
     * @return \Caroler\Objects\Embed
     */
    public function setFields(array $fields): Embed
    {
        foreach ($fields as $field) {
            if (
                isset($field['name'])
                && is_string($field['name'])
                && isset($field['value'])
                && is_string($field['value'])
            ) {
                $this->addField($field['name'], $field['value'], $field['inline'] ?? false);
            } else {
                throw new InvalidArgumentException("Invalid embed field parameters provided!");
            }
        }

        return $this;
    }

    /**
     * @param string $name
     * @param string $value
     * @param bool $inline
     *
     * @return \Caroler\Objects\Embed
     */
    public function addField(string $name, string $value, bool $inline = false): Embed
    {
        $this->fields[] = [
            'name' => $name,
            'value' => $value,
            'inline' => $inline
        ];

        return $this;
    }
}
