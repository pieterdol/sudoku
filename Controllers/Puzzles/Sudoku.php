<?php
namespace Controllers\Puzzles;

use Models\Puzzles\Sudoku;

class Sudoku
{
    public static function getSudokuOverview()
    {
        $sudoku = new Sudoku();
        $sudokuPuzzles = $sudoku->loadPuzzle();
        
        $loader = new \Twig_Loader_Filesystem('views');
        $twig = new \Twig_Environment($loader);
        
        return $twig->render(
            'Puzzles/SudokuOverview.twig', 
            [
                'title' => 'Sudoku overview',
                'sudokupuzzles' => $sudokuPuzzles,
            ]
        );
    }
    
    public static function getSudokuPuzzle($puzzleID)
    {
        $loader = new \Twig_Loader_Filesystem('views');
        $twig = new \Twig_Environment($loader);

        $sudoku = new Sudoku();
        $sudokuPuzzle = $sudoku->getPuzzle($puzzleID);

        if(empty($sudokuPuzzle['puzzle_value'])){
            return "There is no data.";
        }
        $sudokuRows = [];
        foreach ($sudokuPuzzle['puzzle_value'] as $rowKey => $sudokuRowValues){
            if(!empty($sudokuRowValues)){
                foreach($sudokuRowValues as $columnKey => $value){
                    $sudokuRows[$rowKey][$columnKey] = [];
                    $sudokuRows[$rowKey][$columnKey]['value'] = $value;
                    $sudokuRows[$rowKey][$columnKey]['id'] = 'sudoku-' . (string)($rowKey) . '-' . (string)($columnKey);
                    $sudokuRows[$rowKey][$columnKey]['disabled'] = ($value !== "0") ? 'disabled' : '';
                }
            }
        }

        $sudokuValues = [
            'sudokuTitle'   => $sudokuPuzzle['puzzle_name'], 
            'sudokuID'      => $sudokuPuzzle['sudoku_id'], 
            'sudokuRows'    => $sudokuRows,
        ];
        $data = $twig->render('Puzzles/Sudoku.twig', $sudokuValues);
        return $data;
    }
    
    public static function validateSudokuPuzzle()
    {
        $sudoku = new Sudoku();
        return $sudoku->checkSudokuPuzzle();
    }
}