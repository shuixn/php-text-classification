<?php
declare(strict_types=1);

namespace TextClassification\Models;

use Phpml\Classification\Classifier;
use Phpml\Classification\NaiveBayes as NB;

/**
 * Class NaiveBayes
 *
 * @package TextClassification\Models
 */
class NaiveBayes extends Base
{
    /** @var array */
    private $sampleX;

    /** @var array */
    private $sampleY;

    /**
     * NaiveBayes constructor.
     * @param array $sampleX
     * @param array $sampleY
     */
    public function __construct(array $sampleX, array $sampleY)
    {
        $this->sampleX = $sampleX;

        $this->sampleY = $sampleY;
    }

    /**
     * @return Classifier
     */
    public function train(): Classifier
    {
        $this->classifier = new NB();

        $this->classifier->train($this->sampleX, $this->sampleY);

        return $this->classifier;
    }
}