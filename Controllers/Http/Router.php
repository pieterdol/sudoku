<?php
namespace Controllers\Http;

use Controllers\Puzzles\Sudoku;
use Controllers\Pages;

class Router
{
    
    private $local;
    private $klein;
    
    function __construct()
    {
        // @todo - check if local
        $this->local = true;
        $this->klein = new \Klein\Klein();
    }
    
    public function handleRequests()
    {
        $route = $this->local === true ? '/Sudoku' : '';
        $this->klein->respond('GET', $route . '/', function() {
            return Pages\HomePage::getHomePageHtml();
        });
        
        $this->klein->respond('GET', $route . '/sudokus/?', function() {
            return Sudoku::getSudokuOverview();
        });
        $this->klein->respond('GET', $route . '/sudokus/[:id]', function($request, $response) {
            return Sudoku::getSudokuPuzzle($request->id);
        });
        $this->klein->respond('POST', $route . '/sudokus/validate', function() {
            return Sudoku::validateSudokuPuzzle();
        });

        return $this->klein->dispatch();
    }
}