<?php

/**
 * Board class represents the game board state
 */
class Board
{
    private $height;
    private $width;
    private $food;
    private $snakes;
    private $hazards;

    /**
     * Constructor
     * @param array $data Board data from API request
     */
    public function __construct(array $data = [])
    {
        $this->height = $data['height'] ?? 15;
        $this->width = $data['width'] ?? 15;
        $this->food = $data['food'] ?? [];
        $this->snakes = $data['snakes'] ?? [];
        $this->hazards = $data['hazards'] ?? [];
    }

    /**
     * Get board height
     * @return int Board height
     */
    public function getHeight(): int
    {
        return $this->height;
    }

    /**
     * Get board width
     * @return int Board width
     */
    public function getWidth(): int
    {
        return $this->width;
    }

    /**
     * Get food positions
     * @return array List of food coordinates
     */
    public function getFood(): array
    {
        return $this->food;
    }

    /**
     * Get all snakes on the board
     * @return array List of snake objects
     */
    public function getSnakes(): array
    {
        return $this->snakes;
    }

    /**
     * Get hazard positions
     * @return array List of hazard coordinates
     */
    public function getHazards(): array
    {
        return $this->hazards;
    }

    /**
     * Check if a coordinate is on the board
     * @param array $coord Coordinate to check
     * @return bool True if within board boundaries
     */
    public function isValidCoordinate(array $coord): bool
    {
        return $coord['x'] >= 0 && $coord['x'] < $this->width &&
               $coord['y'] >= 0 && $coord['y'] < $this->height;
    }

    /**
     * Check if a coordinate contains a hazard
     * @param array $coord Coordinate to check
     * @return bool True if coordinate is a hazard
     */
    public function isHazard(array $coord): bool
    {
        foreach ($this->hazards as $hazard) {
            if ($hazard['x'] === $coord['x'] && $hazard['y'] === $coord['y']) {
                return true;
            }
        }
        return false;
    }
}
?>