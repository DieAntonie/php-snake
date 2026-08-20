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
        $head = $this->getHead();
        $food = $board->getFood();

        $nearestFood = empty($food) ? null : $this->findNearestFood($head, $food);
        $occupied = $this->getOccupiedCoordinates($board);
        $safeMoves = [];
        $nonHazardMoves = [];

        foreach ($this->getPossibleMoves($head) as $move => $coordinate) {
            if (!$board->isValidCoordinate($coordinate) || $this->containsCoordinate($occupied, $coordinate)) {
                continue;
            }

            $safeMoves[$move] = $coordinate;
            if (!$board->isHazard($coordinate)) {
                $nonHazardMoves[$move] = $coordinate;
            }
        }

        $movesToScore = !empty($nonHazardMoves) ? $nonHazardMoves : $safeMoves;
        if (empty($movesToScore)) {
            $movesToScore = $this->getPossibleMoves($head);
        }

        $move = $this->chooseBestMove($movesToScore, $nearestFood);

        return [
            'move' => $move,
            'shout' => $nearestFood === null
                ? 'Looking for food from (' . $head['x'] . ', ' . $head['y'] . ').'
                : 'Moving safely towards food at (' . $nearestFood['x'] . ', ' . $nearestFood['y'] . ').'
        ];
    }

    /**
     * Return all cells occupied by this snake and its opponents.
     * @param Board $board The current game board
     * @return array List of occupied coordinates
     */
    private function getOccupiedCoordinates(Board $board): array
    {
        $occupied = $this->body;

        foreach ($board->getSnakes() as $snake) {
            if (($snake['id'] ?? null) === $this->id) {
                continue;
            }

            foreach (($snake['body'] ?? []) as $segment) {
                $occupied[] = $segment;
            }
        }

        return $occupied;
    }

    /**
     * Build the four possible one-cell moves from the head.
     * @param array $head Head position
     * @return array Move names mapped to destination coordinates
     */
    private function getPossibleMoves(array $head): array
    {
        return [
            'up' => ['x' => $head['x'], 'y' => $head['y'] + 1],
            'right' => ['x' => $head['x'] + 1, 'y' => $head['y']],
            'down' => ['x' => $head['x'], 'y' => $head['y'] - 1],
            'left' => ['x' => $head['x'] - 1, 'y' => $head['y']]
        ];
    }

    /**
     * Select the move whose destination is closest to the target.
     * @param array $moves Legal candidate moves
     * @param array|null $target Food target, if one exists
     * @return string Move direction
     */
    private function chooseBestMove(array $moves, ?array $target): string
    {
        reset($moves);
        $bestMove = key($moves);
        if ($target === null) {
            return $bestMove;
        }

        $bestDistance = PHP_INT_MAX;
        foreach ($moves as $move => $coordinate) {
            $distance = $this->manhattanDistance($coordinate, $target);
            if ($distance < $bestDistance) {
                $bestDistance = $distance;
                $bestMove = $move;
            }
        }

        return $bestMove;
    }

    /**
     * Check whether a coordinate is occupied.
     * @param array $coordinates List of coordinates
     * @param array $coordinate Coordinate to find
     * @return bool True when occupied
     */
    private function containsCoordinate(array $coordinates, array $coordinate): bool
    {
        foreach ($coordinates as $occupied) {
            if (($occupied['x'] ?? null) === $coordinate['x'] && ($occupied['y'] ?? null) === $coordinate['y']) {
                return true;
            }
        }

        return false;
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