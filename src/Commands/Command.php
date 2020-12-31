<?php

declare(strict_types=1);

namespace Caroler\Commands;

use Caroler\Caroler;
use Caroler\Exceptions\CommandException;
use Caroler\Exceptions\IOException;
use Caroler\Objects\Embed;
use Caroler\Objects\Message;
use Caroler\Resources\Channel;
use Caroler\Resources\Guild;
use Caroler\Resources\ResourceInterface;
use Caroler\Traits\HasPermissions;
use OutOfBoundsException;

/**
 * Common Command functionality
 *
 * @package Caroler\Commands
 */
abstract class Command implements CommandInterface
{
    use HasPermissions;

    /**
     * @var string Command signature
     */
    protected $signature;

    /**
     * @var string Command description
     */
    protected $description;

    /**
     * @var string Command author id
     */
    protected $author;

    /**
     * @var string Command version – SemVer is recommended!
     */
    protected $version;

    /**
     * @var bool Whether or not this command requires super admin privileges
     */
    protected $superAdmin = false;

    /**
     * @var \Caroler\Objects\Message Received message object
     */
    private $message;

    /**
     * @var \Caroler\Caroler Application instance
     */
    private $caroler;

    /**
     * @var \Caroler\Resources\ResourceInterface[] Guild resource of the received message object
     */
    private $resources = [];

    /**
     * @var \Caroler\Objects\Embed Discord rich embed
     */
    private $embed;

    /**
     * @var string Derived command name
     */
    private $name;

    /**
     * @var array Derived command arguments blueprint
     */
    private $argumentsBlueprint = [];

    /**
     * @var array Derived command options blueprint
     */
    private $optionsBlueprint = [];

    /**
     * @var array Derived command arguments
     */
    private $arguments = [];

    /**
     * @var array Derived command options
     */
    private $options = [];

    /**
     * @inheritDoc
     * @throws \Caroler\Exceptions\CommandException
     * @throws \OutOfBoundsException
     * @todo Check if exiting stops the bot entirely.
     */
    public function prepare(Message $message, Caroler $caroler): bool
    {
        $this->message = $message;
        $this->caroler = $caroler;

        $this->resources['guild'] = new Guild();
        $this->resources['guild']->prepare($message, $caroler);

        $this->resources['channel'] = new Channel();
        $this->resources['channel']->prepare($message, $caroler);

        $this->embed = new Embed();

        $this->setName();
        try {
            $this->setArguments();
            $this->setOptions();
        } catch (IOException $e) {
            $this->showHelpDialog();
            return false;
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function validate(): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    abstract public function handle(): bool;

    /**
     * @inheritDoc
     */
    public function getSignature(): string
    {
        return $this->signature;
    }

    /**
     * @inheritDoc
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @inheritDoc
     */
    public function getAuthor(): string
    {
        return $this->author;
    }

    /**
     * @inheritDoc
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * @inheritDoc
     */
    public function requiresSuperAdmin(): bool
    {
        return $this->superAdmin;
    }

    /**
     * @return \Caroler\Objects\Message
     */
    protected function getMessage(): Message
    {
        return $this->message;
    }

    /**
     * @return \Caroler\Objects\Message
     * @uses \Caroler\Commands\Command::getMessage()
     */
    protected function message(): Message
    {
        return $this->getMessage();
    }

    /**
     * @return \Caroler\Caroler
     */
    protected function getCaroler(): Caroler
    {
        return $this->caroler;
    }

    /**
     * @return \Caroler\Caroler
     * @uses \Caroler\Commands\Command::getCaroler()
     */
    protected function caroler(): Caroler
    {
        return $this->getCaroler();
    }

    /**
     * @return \Caroler\Resources\ResourceInterface[]
     */
    protected function getResources(): array
    {
        return $this->resources;
    }

    /**
     * @return \Caroler\Resources\ResourceInterface[]
     * @uses \Caroler\Commands\Command::getResources()
     */
    protected function resources(): array
    {
        return $this->getResources();
    }

    /**
     * Returns the requested Discord REST API resource instance.
     *
     * @param string $resource
     *
     * @return \Caroler\Resources\ResourceInterface|\Caroler\Resources\Guild|\Caroler\Resources\Channel
     * @throws \OutOfBoundsException
     */
    protected function resource(string $resource): ResourceInterface
    {
        if (!isset($this->resources[$resource])) {
            throw new OutOfBoundsException(
                'Discord REST API resource "' . $resource . '" not available in command context.'
            );
        }

        return $this->resources[$resource];
    }

    /**
     * @return \Caroler\Objects\Embed
     */
    protected function getEmbed(): Embed
    {
        return $this->embed;
    }

    /**
     * @return \Caroler\Objects\Embed
     * @uses \Caroler\Commands\Command::getEmbed()
     */
    protected function embed(): Embed
    {
        return $this->getEmbed();
    }

    /**
     * @inheritDoc
     * @throws \Caroler\Exceptions\CommandException
     */
    public function getName(): string
    {
        !is_null($this->name) ?: $this->setName();

        return $this->name;
    }

    /**
     * @inheritDoc
     * @throws \Caroler\Exceptions\CommandException
     */
    public function setName(string $name = null): CommandInterface
    {
        if (!preg_match('/[^\s]+/', $this->signature, $matches)) {
            throw new CommandException(
                'Unable to determine default Command name from signature.',
                ['signature' => $this->getSignature()]
            );
        }

        $this->name = $name ?? $matches[0];

        return $this;
    }

    /**
     * @return array
     * @throws \Caroler\Exceptions\CommandException
     */
    public function getArgumentsBlueprint(): array
    {
        !empty($this->argumentsBlueprint) ?: $this->setArgumentsBlueprint();

        return $this->argumentsBlueprint;
    }

    /**
     * @return array
     * @throws \Caroler\Exceptions\CommandException
     * @uses \Caroler\Commands\Command::getArgumentsBlueprint()
     */
    public function argumentsBlueprint(): array
    {
        return $this->getArgumentsBlueprint();
    }

    /**
     * @return \Caroler\Commands\CommandInterface
     * @throws \Caroler\Exceptions\CommandException
     */
    private function setArgumentsBlueprint(): CommandInterface
    {
        preg_match_all(
            '/{\s*([a-z0-9]+)([?=]?)([a-z0-9]+)?\s*:?\s*(.*)}/i',
            $this->signature,
            $arguments
        );

        foreach ($arguments[0] as $key => $argument) {
            if (
                $arguments[2][$key] === '='
                && strlen($arguments[3][$key]) === 0
            ) {
                throw new CommandException(
                    'Default value for optional argument "' . $key . '" with required value not defined.',
                    ['signature' => $this->getSignature()]
                );
            }

            $this->argumentsBlueprint[$arguments[1][$key]] = [
                'required' => strlen($arguments[2][$key]) === 0,
                'default_value' => $arguments[2][$key] === '='
                    ? $arguments[3][$key] : null,
                'description' => strlen($arguments[4][$key]) > 0
                    ? $arguments[4][$key] : null,
            ];
        }

        return $this;
    }

    /**
     * @return array
     * @throws \Caroler\Exceptions\CommandException
     */
    public function getOptionsBlueprint(): array
    {
        !empty($this->optionsBlueprint) ?: $this->setOptionsBlueprint();

        return $this->optionsBlueprint;
    }

    /**
     * @return array
     * @throws \Caroler\Exceptions\CommandException
     * @uses \Caroler\Commands\Command::getOptionsBlueprint()
     */
    public function optionsBlueprint(): array
    {
        return $this->getOptionsBlueprint();
    }

    /**
     * @return \Caroler\Commands\CommandInterface
     * @throws \Caroler\Exceptions\CommandException
     */
    private function setOptionsBlueprint(): CommandInterface
    {
        preg_match_all(
            '/{\s*--([a-z0-9]?\|?[a-z0-9]+)(=?)([a-z0-9]+)?\s*:?\s*(.*)}/i',
            $this->signature,
            $options
        );

        foreach ($options[0] as $key => $option) {
            if (count($variations = explode('|', $options[1][$key])) > 2) {
                throw new CommandException(
                    'Multiple option shortcuts for option "' . $variations[count($variations) - 1] . '" defined.',
                    ['signature' => $this->getSignature()]
                );
            }

            $this->optionsBlueprint[$variations[count($variations) - 1]] = [
                'shortcut' => count($variations) === 2 ? $variations[0] : null,
                'value_required' => $options[2][$key] === '=',
                'default_value' => strlen($options[3][$key]) > 0
                    ? $options[3][$key] : null,
                'description' => strlen($options[4][$key]) > 0
                    ? $options[4][$key] : null,
            ];
        }

        return $this;
    }

    /**
     * @return array
     * @throws \Caroler\Exceptions\CommandException
     * @throws \Caroler\Exceptions\IOException
     */
    protected function getArguments(): array
    {
        !empty($this->arguments) ?: $this->arguments = $this->setArguments();

        return $this->arguments;
    }

    /**
     * @return array
     * @throws \Caroler\Exceptions\CommandException
     * @throws \Caroler\Exceptions\IOException
     * @uses \Caroler\Commands\Command::getArguments()
     */
    protected function arguments(): array
    {
        return $this->getArguments();
    }

    /**
     * Retrieves an argument's value, or null if it isn't set.
     *
     * @param string $argument
     *
     * @return string
     * @throws \OutOfBoundsException
     */
    protected function argument(string $argument): ?string
    {
        if (!isset($this->argumentsBlueprint[$argument])) {
            throw new OutOfBoundsException(
                'Argument blueprint for argument "' . $argument . '" does not exist.'
            );
        }

        return isset($this->arguments[$argument])
            ? $this->arguments[$argument] : null;
    }

    /**
     * @return \Caroler\Commands\CommandInterface
     * @throws \Caroler\Exceptions\CommandException
     * @throws \Caroler\Exceptions\IOException
     */
    private function setArguments(): CommandInterface
    {
        preg_match_all(
            '/\s+([\w<@!>]+|"[\w<@!>\s]+")/i',
            $this->message->getContent(),
            $arguments
        );

        $arguments = $arguments[1];
        $argumentsBlueprint = $this->getArgumentsBlueprint();

        if (count($argumentsBlueprint) < count($arguments)) {
            $lastIndex = count($argumentsBlueprint) - 1;
            $overflownArgument = [];

            foreach (range($lastIndex, count($arguments) - 1) as $index) {
                $overflownArgument[] = $arguments[$index];
                unset($arguments[$index]);
            }

            $arguments[$lastIndex] = implode(' ', $overflownArgument);
        }

        foreach ($argumentsBlueprint as $key => $argumentBlueprint) {
            if (count($arguments) > 0) {
                $this->arguments[$key] = array_shift($arguments);
            } elseif ($argumentBlueprint['required']) {
                throw new IOException(
                    'Required Command argument "' . $key . '" missing.',
                );
            } elseif (isset($argumentBlueprint['default_value'])) {
                $this->arguments[$key] = $argumentBlueprint['default_value'];
            }
        }

        return $this;
    }

    /**
     * @return array
     * @throws \Caroler\Exceptions\CommandException
     * @throws \Caroler\Exceptions\IOException
     */
    protected function getOptions(): array
    {
        !empty($this->options) ?: $this->options = $this->setOptions();

        return $this->options;
    }

    /**
     * @return array
     * @throws \Caroler\Exceptions\CommandException
     * @throws \Caroler\Exceptions\IOException
     * @uses \Caroler\Commands\Command::getOptions()
     */
    protected function options(): array
    {
        return $this->getOptions();
    }

    /**
     * Retrieves an option's value, or null if it isn't set.
     *
     * @param string $option
     *
     * @return string
     * @throws \OutOfBoundsException
     */
    protected function option(string $option): ?string
    {
        if (!isset($this->optionsBlueprint[$option])) {
            throw new OutOfBoundsException(
                'Options blueprint for option "' . $option . '" does not exist.'
            );
        }

        return isset($this->options[$option]) ? $this->options[$option] : null;
    }

    /**
     * @return \Caroler\Commands\CommandInterface
     * @throws \Caroler\Exceptions\CommandException
     * @throws \Caroler\Exceptions\IOException
     */
    private function setOptions(): CommandInterface
    {
        foreach ($this->getOptionsBlueprint() as $key => $optionBlueprint) {
            if (
                preg_match(
                    '/\s+--(' . $key . '|' . $optionBlueprint['shortcut'] . ')(=?)([a-z0-9\s]+)?(\s--|$)/i',
                    $this->message->getContent(),
                    $matches
                )
            ) {
                if ($optionBlueprint['value_required']) {
                    if ($matches[2] === '=' && strlen($matches[3]) > 0) {
                        $value = $matches[3];
                    } elseif (strlen($optionBlueprint['default_value']) > 0) {
                        $value = $optionBlueprint['default_value'];
                    } else {
                        throw new IOException(
                            'Incorrect value for option "' . $key . '" provided.'
                        );
                    }
                } else {
                    $value = true;
                }

                $this->options[$key] =
                    is_string($value) ? trim($value) : $value;
            }
        }

        return $this;
    }

    /**
     * @inheritDoc
     * @throws \Caroler\Exceptions\CommandException
     * @throws \OutOfBoundsException
     */
    public function showHelpDialog(
        Channel $channel = null,
        Caroler $caroler = null
    ): ?Message {
        $embed = new Embed();
        $cmdPrefix = $this->caroler->getConfig('command_prefix');

        $usage = $cmdPrefix . $this->name;
        $fields = [];

        foreach ($this->getArgumentsBlueprint() as $key => $argument) {
            $usage .= ' _' . $key . '_';

            $fields[] = [
                'name' => $key . ($argument['required'] ? '' : ' _(optional)_'),
                'value' => $argument['description']
                    . (!is_null($argument['default_value']) ? "\n" . $argument['default_value'] : ''),
            ];
        }

        foreach ($this->getOptionsBlueprint() as $key => $option) {
            $usage .= ' --' . $key . ($option['value_required'] ? '=_value_' : '');

            $fields[] = [
                'name' => '--' . $key . (!is_null($option['shortcut']) ? ' / --' . $option['shortcut'] : '')
                    . ' _(optional' . ($option['value_required'] ? ', requires value' : '') . ')_',
                'value' => $option['description'] . "\n"
                    . (!is_null($option['default_value']) ? $option['default_value'] : '')
            ];
        }

        return $this->resource('channel')->createMessage(
            null,
            $embed->setTitle('Help for __' . $cmdPrefix . $this->name . '__')
                ->setDescription($this->description)
                ->setColor(Embed::COLOR_DISCORD)
                ->addField('Usage', $usage)
                ->setFields($fields)
        );
    }

    /**
     * Computed permissions of the member executing the command.
     *
     * @return string
     * @throws \OutOfBoundsException
     * @see \Caroler\Traits\HasPermissions
     */
    private function getPermissionsNew(): string
    {
        $guildRoles = $this->resource('guild')->getGuildRoles();
        $permissions = 0;

        foreach ($guildRoles as $role) {
            if (
                in_array(
                    $role->getId(),
                    $this->message->getMember()->getRoles()
                )
            ) {
                $permissions = $permissions | $role->getPermissionsNew();
            }
        }

        return (string) $permissions;
    }
}
