<?php

namespace App\Service;

use App\Entity\BankExtraction;
use Symfony\Component\Finder\Finder;
use League\Csv\Reader;

class BudgetFileParser implements BudgetFileParserInterface
{
    /**
     * This function iams to parse a bank extraction csv file to import transactions | Warning this only Works with Credit Mutuel formated files
     */
    public function parse(BankExtraction $bankExtraction): array
    {
        $transactions = [];

        if ($bankExtraction->getMediaObject() === null) {
            throw new \LogicException("The file is missing");
        }

        $finder = new Finder();
        $finder->in(__DIR__.'/public/media');
        $finder->name(.$bankExtraction->getMediaObject()->filePath);
        $finder->files();

        $month = $bankExtraction->getBudget()->getMonth();

        foreach ($finder as $file) {
            $csv = Reader::createFromPath($file->getRealPath())
                ->setHeaderOffset(0)
            ;

            $this->getTranslatedTransaction($csv);
        }

        // Isolate the lines from the requested month

        // Loop through each line : tanslate name thourgh the CRUDABLE api table translation and return empty if unkonwn

        // Foreach lines create a transactions that holds the amount of new lines with   
        
        // Return array of lines transactions 


        return $transactions;
    }

    private function getTranslatedTransactions(Reader $csv, Budget $budget): array
    {
        foreach ($csv as $record) {
            $transaction = new Transaction();

            $transaction->setAmount($record['montant']); // Check excel file to see if correct
            $transactions->setBudget($budget);
            // Parse Date to match API format
            // Parse Amount to set correct Transaction type
            // Retrieve Label & Category from Translation table
        }
    }
}
