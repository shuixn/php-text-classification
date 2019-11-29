<?php
declare(strict_types=1);

namespace TextClassification;

use Fukuball\Jieba\Jieba as FukuballJieba;
use Fukuball\Jieba\Finalseg;
use Exception;

/**
 * Class Jieba
 */
class Jieba
{
    private $stopWords = [];

    /**
     * Jieba constructor.
     * @param string $stopWordsFile
     *
     * @throws Exception
     */
    public function __construct(string $stopWordsFile)
    {
        FukuballJieba::init();
        Finalseg::init();

        $this->loadStopWords($stopWordsFile);
    }

    /**
     * @param string $stopWordsFile
     *
     * @throws Exception
     */
    private function loadStopWords(string $stopWordsFile)
    {
        if (!file_exists($stopWordsFile)) {
            throw new Exception('file not exists');
        }

        $rawData = file_get_contents($stopWordsFile);
        if (empty($rawData)) {
            throw new Exception('file content empty');
        }

        $this->stopWords = explode(PHP_EOL, $rawData);
    }

    /**
     * @param string $str
     * @return array
     */
    public function cut(string $str): array
    {
        $words = FukuballJieba::cut($str);
        if (!empty($words)) {
            $data = [];
            foreach ($words as $word) {
                if (!in_array($word, $this->stopWords)) {
                    $data[] = $word;
                }
            }

            return $data;
        }

        return [];
    }
}