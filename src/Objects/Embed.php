<?php

declare(strict_types=1);

namespace Caroler\Objects;

/**
 * Embed object class
 *
 * @package Caroler\Objects
 */
class Embed extends AbstractObject implements ObjectInterface
{
    /**
     * @var string
     */
    public const COLOR_RED = '#FF0000';

    /**
     * @var string
     */
    public const COLOR_GREEN = '#008000';

    /**
     * @var string
     */
    public const COLOR_BLUE = '#0000FF';

    /**
     * @var string
     */
    public const COLOR_YELLOW = '#FFFF00';

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $url;

    /**
     * @var string ISO8601 timestamp
     */
    private $timestamp;

    /**
     * @var string Hexadecimal color code
     */
    private $color;

    /**
     * @var array
     * @see https://discord.com/developers/docs/resources/channel#embed-object-embed-footer-structure
     */
    private $footer;

    /**
     * @var array
     * @see https://discord.com/developers/docs/resources/channel#embed-object-embed-image-structure
     */
    private $image;

    /**
     * @var array
     * @see https://discord.com/developers/docs/resources/channel#embed-object-embed-thumbnail-structure
     */
    private $thumbnail;

    /**
     * @var array
     * @see https://discord.com/developers/docs/resources/channel#embed-object-embed-video-structure
     */
    private $video;

    /**
     * @var array
     * @see https://discord.com/developers/docs/resources/channel#embed-object-embed-provider-structure
     */
    private $provider;

    /**
     * @var array
     * @see https://discord.com/developers/docs/resources/channel#embed-object-embed-author-structure
     */
    private $author;

    /**
     * @var array
     */
    private $fields = [];

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
            throw new \InvalidArgumentException("Invalid footer icon parameter provided!");
        }

        return $this;
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

    /**
     * @param array[] $fields
     *
     * @return \Caroler\Objects\Embed
     */
    public function addFields(array $fields): Embed
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
                throw new \InvalidArgumentException("Invalid embed field parameters provided!");
            }
        }

        return $this;
    }

    public function toArray(): array
    {
        $array = [];
        $properties = get_object_vars($this);
        foreach ($properties as $key => $value) {
            is_null($value) ?: $array[$key] = $value;
        }

        return $array;
    }
}
