<?php
declare(strict_types=1);

namespace TextClassification\Models;

use Phpml\Classification\Classifier;
use Phpml\Classification\SVC;
use Phpml\SupportVectorMachine\Kernel;

/**
 * Class LinearSvc
 *
 * @package TextClassification\Models
 */
class LinearSvc extends Base
{
    /** @var array */
    private $sampleX;

    /** @var array */
    private $sampleY;

    /**
     * LinearSvc constructor.
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
     * @throws \Phpml\Exception\LibsvmCommandException
     */
    public function train(): Classifier
    {
        $this->classifier = new SVC(
            Kernel::LINEAR,        // $kernel
            1.0,                    // $cost
            3,                    // $degree
            null,                 // $gamma
            0.0,                   // $coef0
            0.001,               // $tolerance
            100,                // $cacheSize
            true,                // $shrinking
            false        // $probabilityEstimates, set to true
        );

        $this->classifier->train($this->sampleX, $this->sampleY);

        return $this->classifier;
    }
}