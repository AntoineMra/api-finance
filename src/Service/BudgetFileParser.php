<?php

namespace App\Service;

use App\Entity\Budget;
use League\Csv\Reader;
use App\Entity\Transaction;
use App\Entity\BankExtraction;
use App\Entity\Enum\TransactionType;
use Symfony\Component\Finder\Finder;
use App\Repository\BankTranslationRepository;

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
        $finder->name($bankExtraction->getMediaObject()->filePath);
        $finder->files();

        foreach ($finder as $file) {
            $csv = Reader::createFromPath($file->getRealPath())
                ->setHeaderOffset(0)
            ;

            $this->getTranslatedTransactions($csv, $bankExtraction->getBudget());
        }



        return $transactions;
    }

    private function getTranslatedTransactions(Reader $csv, Budget $budget): array
    {
        $transactions = [];

        foreach ($csv as $record) {
            $transaction = new Transaction();

            if (isset($record['Crédit'])) {
                $transaction->setType(TransactionType::Income);
                $transaction->setAmount($record['montant']);
            } else {
                $transaction->setType(TransactionType::Expense);
                $transaction->setAmount($record['montant']);
            }

            $transaction->setDate($record['Date']);
            $transaction->setBudget($budget);

            $formatedLabel = $this->formatLabel($record['Libellé']);
            $bankTranslation = $this->matchingTranslation($formatedLabel);
            if($bankTranslation !== null) {
                $transactions->setLabel($bankTranslation->getCustomLabel());
                $transactions->setCategory($bankTranslation->getCategory());
                $transactions->setStatus(TransactionType::Validated);
            }

            $transactions[] = $transaction;
        }

        return $transactions;
    }

    private function formatLabel(string $label): string 
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

    private function matchingTranslation(string $label): ?BankTranslation
    {
        $bankTranslation = $this->bankTranslationRepository->isLabelTranslated($label);

        return $bankTranslation ?? null;
    }
}
