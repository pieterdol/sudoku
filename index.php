<?php

use Models\Sudoku;

require 'vendor/autoload.php';
require_once 'vendor/twig/lib/Twig/Autoloader.php';
Twig_Autoloader::register();

$post = $_POST;

$sudoku = new Sudoku();

// Kijk of er een actie is, zoja stuur door
if(empty($post) || empty($post['action'])) {

    $loader = new Twig_Loader_Filesystem('views');
    $twig = new Twig_Environment($loader);

    $sudokuPuzzle = $sudoku->getPuzzle(1);

    if(empty($sudokuPuzzle['puzzle_value'])){
        exit("There is no data.");
    }
    $sudokuRows = [];
    foreach ($sudokuPuzzle['puzzle_value'] as $rowKey => $sudokuRowValues){
        if(!empty($sudokuRowValues)){
            foreach($sudokuRowValues as $columnKey => $value){
                $sudokuRows[$rowKey][$columnKey] = array();
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
    echo $twig->render('sudokuPuzzle.twig', $sudokuValues);
} else {
    switch($post['action']){
        case 'submitSudoku':
            $data = $sudoku->checkSudokuPuzzle($post);
            $returnArray = [
                'status' => $data['status'] ?? false,
                'message' => $data['message'] ?? 'Something went wrong'
            ];
            exit(json_encode($returnArray));
    }
}