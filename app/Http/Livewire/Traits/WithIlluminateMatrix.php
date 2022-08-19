<?php

namespace App\Http\Livewire\Traits;

use Dotenv\Store\StoreInterface;

trait WithIlluminateMatrix
{
    public int $maxRow = 0;
    public int $maxColumn = 0;
    public bool $left = false;
    public bool $up = false;
    public bool $right = false;
    public bool $down = false;
    public int $totalLightbulbs = 0;

    public function setMaxValues()
    {
        $this->maxRow = sizeof($this->matrix) - 1;
        $this->maxColumn = sizeof($this->matrix[0]) - 1;
    }

    // Illuminate the largest spaces of the room
    public function firstStageToIlluminateMatrix()
    {
        // Avoid first row, last row, first column and last column
        for ($row = 1; $row < $this->maxRow; $row++) {
            for ($column = 1; $column < $this->maxColumn; $column++) {
                // Check if the current space hasn't a wall
                if ($this->matrix[$row][$column] != 1 && $this->matrix[$row][$column] != 7) {
                    // Check if I can illuminate to all directions (left, up, right, down)
                    $this->checkAllDirections($row, $column);

                    if ($this->left && $this->up && $this->right && $this->down) {
                        // Put a light bulb
                        $this->matrix[$row][$column] = 2;
                        $this->totalLightbulbs++;
                        // Illuminate left spaces
                        $this->illuminateLeftAndUp($row, $column);
                        // Illuminate spaces above
                        $this->illuminateLeftAndUp($row, $column, true);
                        // Illuminate right spaces
                        $this->illuminateRightAndDown($row, $column, false, $this->maxColumn);
                        // Illuminate spaces below
                        $this->illuminateRightAndDown($row, $column, true, $this->maxRow);
                    }
                }
            }
        }
    }

    // Illuminate the medium sized spaces of the room
    public function secondStageToIlluminateMatrix() {
        for ($row = 0; $row <= $this->maxRow; $row++) {
            for ($column = 0; $column <= $this->maxColumn; $column++) {
                // Check if the current space hasn't a wall
                if ($this->matrix[$row][$column] == 0 ) {
                    // Check if I can illuminate to these directions (left, up, right, down)
                    $this->checkAllDirections($row, $column);

                    if ($this->left && $this->up && $this->down) {
                        // Put a light bulb
                        $this->matrix[$row][$column] = 2;
                        $this->totalLightbulbs++;
                        // Illuminate in all three directions
                        $this->illuminateLeftAndUp($row, $column);
                        $this->illuminateLeftAndUp($row, $column, true);
                        $this->illuminateRightAndDown($row, $column, true, $this->maxRow);
                    } elseif ($this->left && $this->up && $this->right) {
                        // Put a light bulb
                        $this->matrix[$row][$column] = 2;
                        $this->totalLightbulbs++;
                        // Illuminate in all three directions
                        $this->illuminateLeftAndUp($row, $column);
                        $this->illuminateLeftAndUp($row, $column, true);
                        $this->illuminateRightAndDown($row, $column, false, $this->maxColumn);
                    } elseif ($this->up && $this->right && $this->down) {
                        // Put a light bulb
                        $this->matrix[$row][$column] = 2;
                        $this->totalLightbulbs++;
                        // Illuminate in all three directions
                        $this->illuminateLeftAndUp($row, $column, true);
                        $this->illuminateRightAndDown($row, $column, false, $this->maxColumn);
                        $this->illuminateRightAndDown($row, $column, true, $this->maxRow);
                    } elseif ($this->left && $this->right && $this->down) {
                        // Put a light bulb
                        $this->matrix[$row][$column] = 2;
                        $this->totalLightbulbs++;
                        // Illuminate in all three directions
                        $this->illuminateLeftAndUp($row, $column);
                        $this->illuminateRightAndDown($row, $column, false, $this->maxColumn);
                        $this->illuminateRightAndDown($row, $column, true, $this->maxRow);
                    } elseif ($this->left && $this->up) { // Check if I can illuminate to the left and up from this space
                        // Put a light bulb
                        $this->matrix[$row][$column] = 2;
                        $this->totalLightbulbs++;
                        // Illuminate left spaces and spaces above
                        $this->illuminateLeftAndUp($row, $column);
                        $this->illuminateLeftAndUp($row, $column, true);
                    } elseif ($this->right && $this->up) { // Check if I can illuminate to the right and up from this space
                        // Put a light bulb
                        $this->matrix[$row][$column] = 2;
                        $this->totalLightbulbs++;
                        // Illuminate right spaces and spaces above
                        $this->illuminateRightAndDown($row, $column, false, $this->maxColumn);
                        $this->illuminateLeftAndUp($row, $column, true);
                    } elseif ($this->right && $this->down) { // Check if I can illuminate to the right and down from this space
                        // Put a light bulb
                        $this->matrix[$row][$column] = 2;
                        $this->totalLightbulbs++;
                        // Illuminate right and down spaces
                        $this->illuminateRightAndDown($row, $column, false, $this->maxColumn);
                        $this->illuminateRightAndDown($row, $column, true, $this->maxRow);
                    } elseif ($this->left && $this->down) { // Check if I can illuminate to the left and down from this space
                        // Put a light bulb
                        $this->matrix[$row][$column] = 2;
                        $this->totalLightbulbs++;
                        // Illuminate right and down spaces
                        $this->illuminateLeftAndUp($row, $column);
                        $this->illuminateRightAndDown($row, $column, true, $this->maxRow);
                    }
                }
            }
        }
    }

    // Illuminate the remaining spaces of the room
    public function lastStageToIlluminateMatrix()
    {
        for ($row = 0; $row <= $this->maxRow; $row++) {
            for ($column = 0; $column <= $this->maxColumn; $column++) {
                // Check if the current space hasn't a wall
                if ($this->matrix[$row][$column] == 0 ) {
                    // Check if I can illuminate to any directions (left, up, right or down)
                    $this->checkAllDirections($row, $column);

                    if ($this->left) {
                        // Put a light bulb
                        $this->matrix[$row][$column] = 2;
                        $this->totalLightbulbs++;
                        // Illuminate left spaces
                        $this->illuminateLeftAndUp($row, $column);
                    } elseif ($this->up) {
                        // Put a light bulb
                        $this->matrix[$row][$column] = 2;
                        $this->totalLightbulbs++;
                        // Illuminate spaces above
                        $this->illuminateLeftAndUp($row, $column, true);
                    } elseif ($this->right) {
                        // Put a light bulb
                        $this->matrix[$row][$column] = 2;
                        $this->totalLightbulbs++;
                        // Illuminate right spaces
                        $this->illuminateRightAndDown($row, $column, false, $this->maxColumn);
                    } elseif ($this->down) {
                        // Put a light bulb
                        $this->matrix[$row][$column] = 2;
                        $this->totalLightbulbs++;
                        // Illuminate down spaces
                        $this->illuminateRightAndDown($row, $column, true, $this->maxRow);
                    } else {
                        // Put a light bulb in only one space
                        $this->matrix[$row][$column] = 2;
                        $this->totalLightbulbs++;
                    }
                }
            }
        }
    }

    // Check if each path can be illuminated (left, up, right, down)
    public function checkAllDirections($row, $column)
    {
        $this->left = $this->seeLeftOrUp($row, $column);
        $this->up = $this->seeLeftOrUp($row, $column, true);
        $this->right = $this->seeRightOrDown($row, $column, false, $this->maxColumn);
        $this->down = $this->seeRightOrDown($row, $column, true, $this->maxRow);
    }

    // Checks for each space if there are paths that can be illuminated (to the left and up)
    public function seeLeftOrUp($row, $column, $rowOrColumn = false): bool
    {
        $result = false;

        $row = $rowOrColumn ? $row-1 : $row;
        $column = !$rowOrColumn ? $column-1 : $column;

        // while value is not: the border, a wall or a light bulb
        while(($rowOrColumn ? $row : $column) >= 0 && $this->matrix[$row][$column] != 1 && $this->matrix[$row][$column] != 2) {
            if ($this->matrix[$row][$column] == 0) {
                $result = true;
                break;
            }
            $row = $rowOrColumn ? $row-1 : $row;
            $column = !$rowOrColumn ? $column-1 : $column;
        }
        return $result;
    }

    // Checks for each space if there are paths that can be illuminated (to the right and down)
    public function seeRightOrDown($row, $column, $rowOrColumn = false, $max = 0): bool
    {
        $result = false;

        $row = $rowOrColumn ? $row+1 : $row;
        $column = !$rowOrColumn ? $column+1 : $column;

        // while value is not: the border, a wall or a light bulb
        while(($rowOrColumn ? $row : $column) <= $max && $this->matrix[$row][$column] != 1 && $this->matrix[$row][$column] != 2) {
            if ($this->matrix[$row][$column] == 0) {
                $result = true;
                break;
            }
            $row = $rowOrColumn ? $row+1 : $row;
            $column = !$rowOrColumn ? $column+1 : $column;
        }
        return $result;
    }

    // Illuminate the spaces to the left and the spaces above
    public function illuminateLeftAndUp($row, $column, $rowOrColumn = false) : void {
        $row = $rowOrColumn ? $row-1 : $row;
        $column = !$rowOrColumn ? $column-1 : $column;
        while(($rowOrColumn ? $row : $column) >= 0 && $this->matrix[$row][$column] != 1) {
            $this->matrix[$row][$column] = 7;
            $row = $rowOrColumn ? $row-1 : $row;
            $column = !$rowOrColumn ? $column-1 : $column;
        }
    }

    // Illuminate the spaces to the right and the spaces below
    public function illuminateRightAndDown($row, $column, $rowOrColumn = false, $max = 0) : void {
        $row = $rowOrColumn ? $row+1 : $row;
        $column = !$rowOrColumn ? $column+1 : $column;
        while(($rowOrColumn ? $row : $column) <= $max && $this->matrix[$row][$column] != 1) {
            $this->matrix[$row][$column] = 7;
            $row = $rowOrColumn ? $row+1 : $row;
            $column = !$rowOrColumn ? $column+1 : $column;
        }
    }
}
