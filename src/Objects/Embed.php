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
     * @var array|null
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
     * @param string|array $icon
     *
     * @return \Caroler\Objects\Embed
     */
    public function setFooter(string $text, $icon): Embed
    {
        $this->footer['text'] = $text;

        if (is_string($icon)) {
            $this->footer['icon_url'] = $icon;
        } elseif (is_array($icon)) {
            !isset($icon['icon_url']) ?: $this->footer['icon_url'] = $icon['icon_url'];
            !isset($icon['proxy_icon_url']) ?: $this->footer['proxy_icon_url'] = $icon['proxy_icon_url'];
        } else {
            throw new InvalidArgumentException("Invalid footer icon parameter provided!");
        }

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
     * @param array $image
     *
     * @return \Caroler\Objects\Embed
     */
    public function setImage(array $image): Embed
    {
        $this->image = $image;

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
     * @param array $thumbnail
     *
     * @return \Caroler\Objects\Embed
     */
    public function setThumbnail(array $thumbnail): Embed
    {
        $this->thumbnail = $thumbnail;

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
     * @param array $video
     *
     * @return \Caroler\Objects\Embed
     */
    public function setVideo(array $video): Embed
    {
        $this->video = $video;

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
     * @param array $provider
     *
     * @return \Caroler\Objects\Embed
     */
    public function setProvider(array $provider): Embed
    {
        $this->provider = $provider;

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
     * @param array $author
     *
     * @return \Caroler\Objects\Embed
     */
    public function setAuthor(array $author): Embed
    {
        $this->author = $author;

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
