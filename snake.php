<?php

/**
 * Snake class represents the player's snake entity
 */
class Snake
{
    private $id;
    private $name;
    private $health;
    private $body;
    private $latency;
    private $head;
    private $length;
    private $shout;
    private $customizations;

    /**
     * Constructor
     * @param array $data Snake data from API request
     */
    public function __construct(array $data = [])
    {
        $this->id = $data['id'] ?? 'snake-id-string';
        $this->name = $data['name'] ?? 'PHP Snake';
        $this->health = $data['health'] ?? 100;
        $this->body = $data['body'] ?? [['x' => 0, 'y' => 0]];
        $this->latency = $data['latency'] ?? '0';
        $this->head = $data['head'] ?? ['x' => 0, 'y' => 0];
        $this->length = $data['length'] ?? 1;
        $this->shout = $data['shout'] ?? '';
        $this->customizations = $data['customizations'] ?? [];
    }

    /**
     * Get snake's head position
     * @return array Head coordinates [x, y]
     */
    public function getHead(): array
    {
        return !empty($this->head) ? $this->head : $this->body[0];
    }

    /**
     * Get snake's body segments
     * @return array List of body segments
     */
    public function getBody(): array
    {
        return $this->body;
    }

    /**
     * Get snake's current health
     * @return int Health value 0-100
     */
    public function getHealth(): int
    {
        return $this->health;
    }

    /**
     * Calculate the next move
     * @param Board $board The current game board
     * @return array Move response [move, shout]
     */
    public function calculateMove(Board $board): array
    {
        // Simple AI: Move towards nearest food
        $head = $this->getHead();
        $food = $board->getFood();

        if (empty($food)) {
            // Default move if no food
            return ['move' => 'down', 'shout' => 'No food found!'];
        }

        // Find nearest food
        $nearestFood = $this->findNearestFood($head, $food);
        $move = $this->getMoveTowardsCoor($head, $nearestFood);

        return [
            'move' => $move,
            'shout' => 'Moving my head (' . $head['x'] . ', ' . $head['y'] . ') towards food at (' . $nearestFood['x'] . ', ' . $nearestFood['y'] . ')!'
        ];
    }

    /**
     * Find the nearest food on the board
     * @param array $head Head position
     * @param array $food List of food positions
     * @return array Nearest food coordinates
     */
    private function findNearestFood(array $head, array $food): array
    {
        $nearest = $food[0];
        $minDistance = $this->manhattanDistance($head, $nearest);

        foreach ($food as $f) {
            $distance = $this->manhattanDistance($head, $f);
            if ($distance < $minDistance) {
                $minDistance = $distance;
                $nearest = $f;
            }
        }

        return $nearest;
    }

    /**
     * Calculate Manhattan distance between two points
     * @param array $from Starting coordinates
     * @param array $to Ending coordinates
     * @return int Distance
     */
    private function manhattanDistance(array $from, array $to): int
    {
        return abs($from['x'] - $to['x']) + abs($from['y'] - $to['y']);
    }

    /**
     * Determine the move direction towards target coordinates
     * @param array $from Starting coordinates
     * @param array $to Target coordinates
     * @return string Move direction: up, down, left, right
     */
    private function getMoveTowardsCoor(array $from, array $to): string
    {
        $dx = $to['x'] - $from['x'];
        $dy = $to['y'] - $from['y'];

        // Prioritize vertical movement if significant
        if (abs($dy) > abs($dx)) {
            return $dy > 0 ? 'up' : 'down';
        } else {
            return $dx > 0 ? 'right' : 'left';
        }
    }
}
?>