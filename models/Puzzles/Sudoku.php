<?php
namespace Puzzles;

use Db\DbConnection;

class Sudoku
{
    public $db;
    
    public function __construct()
    {
        $this->db = new DbConnection();
    }
    
    /**
     * Haal sudoku puzzle uit db op, vul hierbij lege waardes aan met "0"
     * @param int $puzzleID
     * @return array
     */
    public function getPuzzle($puzzleID)
    {
        $emptySudokuPuzzle = $this->getEmptySudokuPuzzle();
        $sudokuPuzzle = $this->loadPuzzle($puzzleID);
        if (empty($sudokuPuzzle) || empty($sudokuPuzzle['puzzle_value'])) {
            return false;
        }
        $dbValues = [];
        foreach (json_decode($sudokuPuzzle['puzzle_value']) as $rowNumber => $rowValues) {
            foreach (str_split($rowValues) as $columnNumber => $value) {
                if ((string)$value === "0") {
                    continue;
                }
                if (!isset($dbValues[$rowNumber])) {
                    $dbValues[$rowNumber] = [];
                }
                $dbValues[$rowNumber][$columnNumber+1] = $value;
            }
        }
        $returnData = [];
        $returnData['puzzle_value'] = $this->combineEmptyAndDbSudokuData($emptySudokuPuzzle, $dbValues, true);
        if (empty($returnData['puzzle_value'])) {
            return false;
        }
        $returnData['sudoku_id']    = $sudokuPuzzle['puzzle_id'];
        $returnData['puzzle_name']  = $sudokuPuzzle['puzzle_name'];
        return $returnData;
    }
    
    /**
     * Maak een array met alle vakken van een sudoku puzzle waarbij alle vakken "0" zijn
     * @return array
     */
    public function getEmptySudokuPuzzle()
    {
        $emptyPuzzleArray = [];
        foreach (range(1, 9) as $rowNumber) {
            foreach (range(1, 9) as $columnNumber) {
                if (!isset($emptyPuzzleArray[$rowNumber])) {
                    $emptyPuzzleArray[$rowNumber] = [];
                }
                $emptyPuzzleArray[$rowNumber][$columnNumber] = "0";
            }
        }
        return $emptyPuzzleArray;
    }
    
    /**
     * Laad puzzle uit db
     * 
     * @param int $puzzleID     Sudoku puzzle ID
     * @return array            Puzzle data
     */
    public function loadPuzzle($puzzleID)
    {
        return $this->db->get(
                "puzzle_id, puzzle_name, puzzle_value", 
                "sudoku_puzzles", 
                "puzzle_id = :puzzleID", 
                array(':puzzleID' => $puzzleID)
        );
    }
    
    /**
     * Haal random sudoku puzzle uit db op
     * 
     * @todo - deze functie moet gebouwd worden
     * 
     * @return array            Random sudoku puzzle data
     */
    public function getRandomSudokuPuzzle()
    {
        $sudokuPuzzle = ['000123456','987654321','987654321','987654321','987654321','987654321','987654321','987654321','987654321'];
        return $sudokuPuzzle;
    }
    
    /**
     * Check of de sudoku puzzle klopt op basis van sudoku puzzle id
     * 
     * @param array $data       Data waarmee gecontroleerd wordt, dit bevat het sudoku puzzle id en de ingevulde data
     * @return array
     */
    public function checkSudokuPuzzle($data)
    {
        if (empty($data) || empty($data['sudokuID']) || empty($data['sudokuPuzzle'])) {
            return false;
        }
        return $this->checkSudokuDataBasedOnPuzzleId($data['sudokuID'], $data['sudokuPuzzle']);
    }
    
    /**
     * 
     * @param int $sudokuID
     * @param array $data
     * @return array
     */
    public function checkSudokuDataBasedOnPuzzleId($sudokuID, $data)
    {
        $errorArray = [
            'status' => false,
            'message' => 'Something went wrong!'
        ];
        $sudokuData = $this->getPuzzle($sudokuID);
        if (empty($sudokuData['puzzle_value'])) {
            return $errorArray;
        }
        // Voeg post en DB waardes samen - ingevulde DB waardes overschrijven de post waardes
        // in het geval de gebruiker disabled input velden heeft aangepast
        $combinedArray = $this->combinePostAndDbSudokuData($data, $sudokuData['puzzle_value'], true);
        if (empty($combinedArray)) {
            return $errorArray;
        }
        return $this->checkSudokuData($combinedArray);
    }
    
    /**
     * Check sudoku puzzle
     * @param array $data
     * @return array
     */
    public function checkSudokuData($data)
    {
        // Check rijen
        $checkRows = $this->checkSudokuRows($data);
        if ($checkRows['status'] === false) {
            return $checkRows;
        }
        
        // Check kolommen
        $checkColumns = $this->checkSudokuColumns($data);
        if ($checkColumns['status'] === false) {
            return $checkColumns;
        }
        
        // Check blokken (3 x 3)
        $checkBlocks = $this->checkSudokuBlocks($data);
        if ($checkBlocks['status'] === false) {
            return $checkBlocks;
        }
        $resultArray = [
            'status' => true,
            'message' => 'Good job!'
        ];
        return $resultArray;
    }
    
    public function combineEmptyAndDbSudokuData($emptySudoku, $dbData, $dbDataOverwrites = true)
    {
        $combinedArray = $emptySudoku;
        // De sudoku data zoals in db opgeslagen toevoegen.
        foreach ($dbData as $rowNumber => $rowData) {
            if (!isset($combinedArray[$rowNumber])) {
                $combinedArray[$rowNumber] = [];
            }
            foreach ($rowData as $columnNumber => $dbValue) {
                if (($dbDataOverwrites === false && isset($combinedArray[$rowNumber][$columnNumber+1])) === false) {
                    $combinedArray[$rowNumber][$columnNumber] = $dbValue;
                }
            }
        }
        return $combinedArray;
    }
    
    public function combinePostAndDbSudokuData($postData, $dbData, $dbDataOverwrites = true)
    {
        $combinedArray = [];
        foreach ($postData as $key => $value) {
            list($inputName, $row, $column) = explode("-", $key);
            if (!isset($combinedArray[$row])) {
                $combinedArray[$row] = [];
            }
            $combinedArray[$row][$column] = $value;
        }
        // De sudoku data zoals in db opgeslagen toevoegen.
        foreach ($dbData as $rowNumber => $rowData) {
            if (!isset($combinedArray[$rowNumber])) {
                $combinedArray[$rowNumber] = [];
            }
            foreach ($rowData as $columnNumber => $dbValue) {
                if ($dbDataOverwrites === true && $dbValue !== '0') {
                    $combinedArray[$rowNumber][$columnNumber] = $dbValue;
                }
            }
        }
        return $combinedArray;
    }
    
    public function createCheckArray()
    {
        $resultArray = [];
        foreach (range(1, 9) as $row) {
            $resultArray[$row] = range(1, 9);
        }
        return $resultArray;
    }
    
    
    public function checkSudokuRows($data)
    {
        $returnArray = ['status' => false];
        if (empty($data)) {
            return $returnArray;
        }
        $checkArray = $this->createCheckArray();
        $rowsAreCorrect = null;
        foreach ($data as $rowNumber => $rowData) {
            foreach ($rowData as $columnNumber => $value) {
                if (isset($checkArray[$rowNumber]) && in_array($value, $checkArray[$rowNumber])) {
                    $key = array_search($value, $checkArray[$rowNumber]);
                    unset($checkArray[$rowNumber][$key]);
                }
            }
            if (!empty($checkArray[$rowNumber])) {
                $rowsAreCorrect = false;
                $returnArray['type'] = 'row';
                $returnArray['rowNumber'] = $rowNumber;
            }
        }
        if ($rowsAreCorrect === false) {
            $returnArray['message'] = 'Rows invalid';
        } else {
            $returnArray['status'] = true;
        }
        return $returnArray;
    }
    
    public function checkSudokuColumns($data)
    {
        $checkArray = $this->createCheckArray();
        // @todo - controle bouwen voor kolommen
        $returnArray = [
            'status' => true
        ];
        return $returnArray;
    }
    
    public function checkSudokuBlocks($data)
    {
        $returnArray = ['status' => false];
        if (empty($data)) {
            return $returnArray;
        }
        
        $valuesSortedPerBlock = $this->sortValuesPerBlock($data);
        if ($valuesSortedPerBlock === false) {
            return $returnArray;
        }
        
        $valuesNeeded = range(1, 9);
        foreach ($valuesSortedPerBlock as $block => $values) {
            $check = array_diff($valuesNeeded, $values);
            if (count($check) > 0) {
                $returnArray['type'] = 'block';
                list($blockRow, $blockColumn) = explode('-', $block);
                $returnArray['blockRow'] = $blockRow;
                $returnArray['blockColumn'] = $blockColumn;
                $returnArray['message'] = 'Block invalid';
                return $returnArray;
            }
        }
        $returnArray['status'] = true;
        return $returnArray;
        
        // Op onderstaande manier wordt de array opgebouwd.
        // Per blok worden de waardes in een key gezet, vervolgens wordt gekeken of dat blok alle waardes heeft.
//        $array = [
//            '1-1' => [1,2,3,4,5,6,7,8,9], // vak linksbovenin
//            '3-3' => [1,2,3,4,5,6,7,8,9], // vak rechtsonderin
//        ];
    }
    
    private function sortValuesPerBlock($data)
    {
        if (empty($data)) {
            return false;
        }
        $valuesSortedPerBlock = [];
        $valuesSortedPerBlock['1-1'] = [];
        $valuesSortedPerBlock['1-2'] = [];
        $valuesSortedPerBlock['1-3'] = [];
        $valuesSortedPerBlock['2-1'] = [];
        $valuesSortedPerBlock['2-2'] = [];
        $valuesSortedPerBlock['2-3'] = [];
        $valuesSortedPerBlock['3-1'] = [];
        $valuesSortedPerBlock['3-2'] = [];
        $valuesSortedPerBlock['3-3'] = [];
        foreach ($data as $rowNumber => $rowData) {
            $rowNumberCalculated = ceil($rowNumber / 3);
            foreach ($rowData as $columnNumber => $value) {
                if ($value === '0') {
                    continue;
                }
                $columnNumberCalculated = ceil($columnNumber / 3);
                if (!isset($valuesSortedPerBlock[$rowNumberCalculated . '-' . $columnNumberCalculated])) {
                    $valuesSortedPerBlock[$rowNumberCalculated . '-' . $columnNumberCalculated] = [];
                }
                $valuesSortedPerBlock[$rowNumberCalculated . '-' . $columnNumberCalculated][$value] = (int)$value;
            }
        }
        return $valuesSortedPerBlock;
    }
}