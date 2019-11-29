<?php
declare(strict_types=1);

namespace TextClassification;

use Exception;

/**
 * Class Text
 *
 * @package TextClassification
 */
class Text
{
    const CATEGORY_ID = 'category_id';
    const CATEGORY_NAME = 'category_name';
    const TEXT = 'text';

    /** @var string */
    private $file = '';

    /** @var array */
    private $data = [];

    /** @var array */
    private $categoryIds = [];

    /** @var array */
    private $categoryNames = [];

    /** @var array */
    private $categoryMapNames = [];

    /** @var int */
    private $count = 0;

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @return array
     */
    public function getCategoryIds(): array
    {
        return $this->categoryIds;
    }

    /**
     * @return array
     */
    public function getCategoryNames(): array
    {
        return $this->categoryNames;
    }

    /**
     * @return array
     */
    public function getCategoryMapNames(): array
    {
        return $this->categoryMapNames;
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }

    /**
     * Text constructor.
     * @param string $file
     * @throws Exception
     */
    public function __construct(string $file)
    {
        $this->file = $file;

        $this->load();
    }

    /**
     * @throws Exception
     */
    private function load()
    {
        if (!file_exists($this->file)) {
            throw new Exception('file not exists');
        }

        $rawData = file_get_contents($this->file);
        if (empty($rawData)) {
            throw new Exception('empty file content');
        }

        $lines = explode(PHP_EOL, $rawData);

        $this->count = count($lines) - 1;

        foreach ($lines as $line) {
            list(, $categoryId, $categoryName, $text,) = array_pad(explode('_!_', $line), 5, null);

            if (empty($categoryId)) {
                continue;
            }

            if (!in_array($categoryId, $this->categoryIds)) {
                $this->categoryIds[] = $categoryId;
            }

            if (!in_array($categoryName, $this->categoryNames)) {
                $this->categoryNames[] = $categoryName;
            }

            if (!isset($this->categoryMapNames[$categoryId])) {
                $this->categoryMapNames[$categoryId] = $categoryName;
            }

            $this->data[] = [
                self::CATEGORY_ID => $categoryId,
                self::CATEGORY_NAME => $categoryName,
                self::TEXT => $text
            ];
        }
    }

    /**
     * @param int $categoryId
     * @param int $offset
     * @param int $length
     *
     * @return array
     *
     * @throws Exception
     */
    public function getDataSlice(int $categoryId = -1, int $offset = 0, int $length = 100): array
    {
        if ($categoryId !== -1 && !in_array($categoryId, $this->categoryIds)) {
            throw new Exception('category id not exists');
        }

        if ($categoryId === -1) {
            return array_slice($this->data, $offset, $length);
        }

        $categoryData = [];

        foreach ($this->data as $datum) {
            if ($datum[self::CATEGORY_ID] == $categoryId) {
                $categoryData[] = $datum;
            }
        }

        return array_slice($categoryData, $offset, $length);
    }
}
