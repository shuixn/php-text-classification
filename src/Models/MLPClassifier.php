<?php
declare(strict_types=1);

namespace TextClassification\Models;

use Phpml\Classification\Classifier;
use Phpml\Classification\MLPClassifier as MLP;
use Phpml\Math\Distance\Minkowski;
use Phpml\NeuralNetwork\ActivationFunction\PReLU;
use Phpml\NeuralNetwork\ActivationFunction\Sigmoid;
use Phpml\NeuralNetwork\Layer;
use Phpml\NeuralNetwork\Node\Neuron;

/**
 * Class MLPClassifier
 *
 * @package TextClassification\Models
 */
class MLPClassifier extends Base
{
    /** @var array */
    private $sampleX;

    /** @var array */
    private $sampleY;

    /**
     * MLPClassifier constructor.
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
     * @throws \Phpml\Exception\InvalidArgumentException
     */
    public function train(): Classifier
    {
        $labels = array_unique($this->sampleY);

        $layer1 = new Layer(2, Neuron::class, new PReLU);
        $layer2 = new Layer(2, Neuron::class, new Sigmoid);
        $this->classifier = new MLP(4, [$layer1, $layer2], $labels);

        $this->classifier->train($this->sampleX, $this->sampleY);

        return $this->classifier;
    }
}