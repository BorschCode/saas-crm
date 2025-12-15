<?php

namespace App\Services;

use Smalot\PdfParser\Parser as PdfParser;

class FileTextExtractor
{
    public function __construct(
        protected PdfParser $pdfParser = new PdfParser
    ) {}

    public function extractPdf(string $filePath): string
    {
        try {
            $pdf = $this->pdfParser->parseFile($filePath);
            $text = $pdf->getText();

            return $this->normalizeText($text);
        } catch (\Exception $e) {
            throw new \RuntimeException("Failed to extract text from PDF: {$e->getMessage()}", 0, $e);
        }
    }

    public function extractCsv(string $filePath): string
    {
        try {
            $encoding = $this->detectEncoding($filePath);
            $delimiter = $this->detectDelimiter($filePath, $encoding);

            $file = fopen($filePath, 'r');
            if ($file === false) {
                throw new \RuntimeException('Could not open CSV file');
            }

            $headers = [];
            $rows = [];
            $lineNumber = 0;

            while (($data = fgetcsv($file, 0, $delimiter)) !== false) {
                if ($lineNumber === 0) {
                    $headers = $data;
                } else {
                    $rows[] = $data;
                }
                $lineNumber++;
            }

            fclose($file);

            return $this->formatCsvAsMarkdown($headers, $rows);
        } catch (\Exception $e) {
            throw new \RuntimeException("Failed to extract text from CSV: {$e->getMessage()}", 0, $e);
        }
    }

    protected function detectEncoding(string $filePath): string
    {
        $sample = file_get_contents($filePath, false, null, 0, 10000);
        $encoding = mb_detect_encoding($sample, ['UTF-8', 'ISO-8859-1', 'Windows-1252'], true);

        return $encoding ?: 'UTF-8';
    }

    protected function detectDelimiter(string $filePath, string $encoding): string
    {
        $file = fopen($filePath, 'r');
        if ($file === false) {
            return ',';
        }

        $firstLine = fgets($file);
        fclose($file);

        if ($firstLine === false) {
            return ',';
        }

        $delimiters = [',', ';', "\t", '|'];
        $maxCount = 0;
        $bestDelimiter = ',';

        foreach ($delimiters as $delimiter) {
            $count = substr_count($firstLine, $delimiter);
            if ($count > $maxCount) {
                $maxCount = $count;
                $bestDelimiter = $delimiter;
            }
        }

        return $bestDelimiter;
    }

    protected function formatCsvAsMarkdown(array $headers, array $rows): string
    {
        if (empty($headers)) {
            return '';
        }

        $markdown = '';

        // Add headers
        $markdown .= '| '.implode(' | ', $headers)." |\n";
        $markdown .= '|'.str_repeat(' --- |', count($headers))."\n";

        // Add rows
        foreach ($rows as $row) {
            // Pad row to match header count
            $row = array_pad($row, count($headers), '');
            $markdown .= '| '.implode(' | ', $row)." |\n";
        }

        return $markdown;
    }

    protected function normalizeText(string $text): string
    {
        // Remove excessive whitespace
        $text = preg_replace('/[ \t]+/', ' ', $text);

        // Normalize line breaks
        $text = preg_replace('/\r\n/', "\n", $text);
        $text = preg_replace('/\r/', "\n", $text);

        // Remove more than 2 consecutive line breaks
        $text = preg_replace('/\n{3,}/', "\n\n", $text);

        return trim($text);
    }
}
