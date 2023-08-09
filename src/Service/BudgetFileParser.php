<?php

namespace App\Service;

use App\Entity\BankExtraction;

class BudgetFileParser implements BudgetFileParserInterface
{
    public function parse(BankExtraction $bankExtraction): array
    {
        $transactions = [];
        // Get Requested Month

        // Get the excel file

        // Isolate the lines from the requested month

        // Loop through each line : tanslate name thourgh the CRUDABLE api table translation and return empty if unkonwn

        // Foreach lines create a transactions that holds the amount of new lines with   
        
        // Return array of lines transactions 


        return $transactions;
    }
}
