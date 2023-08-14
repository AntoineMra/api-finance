<?php

namespace App\Service;

use App\Entity\BankExtraction;
use Symfony\Component\Finder\Finder;
use League\Csv\Reader;

class BudgetFileParser implements BudgetFileParserInterface
{
    public function __construct(
        private readonly BankTranslationRepository $bankTranslationRepository,
    ) {
    }

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

            $this->getTranslatedTransactions($csv);
        }



        return $transactions;
    }

    private function getTranslatedTransactions(Reader $csv, Budget $budget): array
    {
        foreach ($csv as $record) {
            $transaction = new Transaction();

            if (isset($record['Crédit'])) {
                $transactions->setType(TransactionType::Income)
                $transaction->setAmount($record['montant']);
            } else {
                $transactions->setType(TransactionType::Expense)
                $transaction->setAmount($record['montant']);
            }

            $transactions->setDate($record['Date'])
            $transactions->setBudget($budget);
            // Parse Amount to set correct Transaction type
            // Retrieve Label & Category from Translation table
        }
    }

    private function formatLabels(string $label): string 
    {
        $arrayWords = explode(" ", $label);

        if($arrayWords[0] === "PAIEMENT") {
            array_slice($arrayWords, 3);
        }
        if($arrayWords[0] === "VIR") {
            array_shift($arrayWords);
        }
        if(end($arrayWords) === "Carte") {
            array_slice($arrayWords, -2);
        }

        return implode(" ", $arrayWords);
    }

    private function isMatchingTranslation(string $label): boolean
    {
        $bankTranslation = $this->bankTranslationRepository->isLabelTranslated($label);
    }
}
