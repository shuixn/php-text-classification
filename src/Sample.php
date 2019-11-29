<?php
declare(strict_types=1);

namespace TextClassification;

use Phpml\CrossValidation\StratifiedRandomSplit;
use Phpml\Dataset\ArrayDataset;
use Exception;

/**
 * Class Sample
 *
 * @package TextClassification
 */
class Sample
{
    /** @var string */
    private $stopWordsFile = '';

    /** @var string */
    private $textFile = '';

    /** @var int */
    private $offset = 0;

    /** @var int */
    private $length = 100;

    /** @var array */
    private $sampleX = [];

    /** @var array */
    private $sampleY = [];

    /** @var array */
    private $splitTrainX = [];

    /** @var array */
    private $splitTrainY = [];

    /** @var array */
    private $splitTestX = [];

    /** @var array */
    private $splitTestY = [];

    /**
     * Sample constructor.
     * @param string $textFile
     * @param string $stopWordsFile
     * @param int $offset
     * @param int $length
     *
     * @throws Exception
     */
    public function __construct(string $textFile, string $stopWordsFile, int $offset = 0, int $length = 100)
    {
        $this->textFile = $textFile;

        $this->stopWordsFile = $stopWordsFile;

        $this->offset = $offset;

        $this->length = $length;

        $this->build();
    }

    /**
     * @return array
     */
    public function getSampleX(): array
    {
        return $this->sampleX;
    }

    /**
     * @return array
     */
    public function getSampleY(): array
    {
        return $this->sampleY;
    }

    /**
     * @return array
     */
    public function getSplitTrainX(): array
    {
        return $this->splitTrainX;
    }

    /**
     * @return array
     */
    public function getSplitTrainY(): array
    {
        return $this->splitTrainY;
    }

    /**
     * @return array
     */
    public function getSplitTestX(): array
    {
        return $this->splitTestX;
    }

    /**
     * @return array
     */
    public function getSplitTestY(): array
    {
        return $this->splitTestY;
    }

    /**
     * @throws Exception
     */
    private function build()
    {
        $this->validateParameter();

        $jieba = new Jieba($this->stopWordsFile);
        $text = new Text($this->textFile);

        $categoryIds = $text->getCategoryIds();

        foreach ($categoryIds as $categoryId) {
            $categoryData = $text->getDataSlice(intval($categoryId), $this->offset, $this->length);

            foreach ($categoryData as $categoryDatum) {
                $words = $jieba->cut($categoryDatum['text']);
                if (!empty($words)) {
                    $this->sampleX[] = implode(' ', $words);
                    $this->sampleY[] = $categoryId;
                }
            }
        }

        $this->getSplitDataSet();
    }

    /**
     * @throws \Phpml\Exception\InvalidArgumentException
     */
    private function getSplitDataSet()
    {
        $sampleDataSet = new ArrayDataset(
            $this->sampleX,
            $this->sampleY
        );

        $spilt = new StratifiedRandomSplit($sampleDataSet, 0.3);

        $this->splitTrainX = $spilt->getTrainSamples();

        $this->splitTestX = $spilt->getTestSamples();

        $this->splitTrainY = $spilt->getTrainLabels();

        $this->splitTestY = $spilt->getTestLabels();

        $this->intvalY();
    }

    /**
     * trans string to int
     */
    private function intvalY()
    {
        foreach ($this->sampleY as &$sampleYItem) {
            $sampleYItem = intval($sampleYItem);
        }
        foreach ($this->splitTrainY as &$splitTrainYItem) {
            $splitTrainYItem = intval($splitTrainYItem);
        }
        foreach ($this->splitTestY as &$splitTestYItem) {
            $splitTestYItem = intval($splitTestYItem);
        }
    }

    /**
     * @throws Exception
     */
    private function validateParameter()
    {
        if (!file_exists($this->stopWordsFile) || !file_exists($this->textFile)) {
            throw new Exception('file not exists');
        }

        if ($this->offset < 0 || $this->length <= 0) {
            throw new Exception('invalid offset or length');
        }
    }
}