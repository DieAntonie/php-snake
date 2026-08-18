<?php

/**
 * Game class represents the current game state
 */
class Game
{
    private $id;
    private $ruleset;
    private $map;
    private $source;
    private $timeout;

    /**
     * Constructor
     * @param array $data Game data from API request
     */
    public function __construct(array $data = [])
    {
        $this->id = $data['id'] ?? 'game-id';
        $this->ruleset = $data['ruleset'] ?? ['name' => 'standard', 'version' => 'v1.1.0'];
        $this->map = $data['map'] ?? 'standard';
        $this->source = $data['source'] ?? 'custom';
        $this->timeout = $data['timeout'] ?? 500;
    }

    /**
     * Get game ID
     * @return string Game ID
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Get game ruleset
     * @return array Ruleset information
     */
    public function getRuleset(): array
    {
        return $this->ruleset;
    }

    /**
     * Get response timeout in milliseconds
     * @return int Timeout in ms
     */
    public function getTimeout(): int
    {
        return $this->timeout;
    }
}
?>