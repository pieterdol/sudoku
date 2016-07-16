<?php
namespace Controllers\Puzzles;

use Controllers\Pages\DefaultData;
use Models\Puzzles\Sudoku;

class Sudoku
{
    public static function getSudokuOverview()
    {
        $sudoku = new Sudoku();
        $sudokuPuzzles = $sudoku->loadPuzzle();
        
        $loader = new \Twig_Loader_Filesystem('views');
        $twig = new \Twig_Environment($loader);
        
        $template_data = DefaultData::getDefaultData();
        $template_data['title']          = 'Sudoku overview';
        $template_data['sudokupuzzles']  = $sudokuPuzzles;
        
        return $twig->render('Puzzles/SudokuOverview.twig', $template_data);
    }
    
    public static function getSudokuPuzzle($puzzleID)
    {
        $loader = new \Twig_Loader_Filesystem('views');
        $twig = new \Twig_Environment($loader);

        $sudoku = new Sudoku();
        $sudokuPuzzle = $sudoku->getPuzzle($puzzleID);

        if (empty($sudokuPuzzle['puzzle_value'])){
            return "There is no data.";
        }
        $sudokuRows = [];
        foreach ($sudokuPuzzle['puzzle_value'] as $rowKey => $sudokuRowValues){
            if (!empty($sudokuRowValues)){
                foreach($sudokuRowValues as $columnKey => $value){
                    $sudokuRows[$rowKey][$columnKey] = [];
                    $sudokuRows[$rowKey][$columnKey]['value'] = $value;
                    $sudokuRows[$rowKey][$columnKey]['id'] = 'sudoku-' . (string)($rowKey) . '-' . (string)($columnKey);
                    $sudokuRows[$rowKey][$columnKey]['disabled'] = ($value !== "0") ? 'disabled' : '';
                }
            }
        }

        $template_data = DefaultData::getDefaultData();
        $template_data['sudokuTitle']   = $sudokuPuzzle['puzzle_name'];
        $template_data['sudokuID']      = $sudokuPuzzle['sudoku_id'];
        $template_data['sudokuRows']    = $sudokuRows;
        return $twig->render('Puzzles/Sudoku.twig', $template_data);
    }
    
    public static function validateSudokuPuzzle()
    {
        return (new Sudoku())->checkSudokuPuzzle();
    }
}