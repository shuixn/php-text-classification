<?php
declare(strict_types=1);
ini_set('memory_limit', '1024M');

require_once __DIR__ . '/vendor/autoload.php';

use Phpml\Metric\ClassificationReport;
use TextClassification\Sample;
use TextClassification\Jieba;
use Phpml\ModelManager;
use Phpml\SupportVectorMachine\Kernel;
use Phpml\Tokenization\WhitespaceTokenizer;
use Phpml\FeatureExtraction\TokenCountVectorizer;
use Phpml\Classification\Classifier;
use Phpml\Classification\KNearestNeighbors;
use Phpml\Classification\MLPClassifier;
use Phpml\Classification\NaiveBayes;
use Phpml\Classification\SVC;
use Phpml\SupportVectorMachine\Kernel as SVCKernel;
use Phpml\Math\Distance\Minkowski;
use Phpml\NeuralNetwork\ActivationFunction\PReLU;
use Phpml\NeuralNetwork\ActivationFunction\Sigmoid;
use Phpml\NeuralNetwork\Layer;
use Phpml\NeuralNetwork\Node\Neuron;

$stopWordsFile = __DIR__ . '/data/stop_words.txt';
$textFile = __DIR__ . '/data/toutiao_cat_data.txt';

$offset = 0;
$length = 100;

$sample = new Sample($textFile, $stopWordsFile, $offset, $length);
$sampleX = $sample->getSplitTrainX();
$sampleY = $sample->getSplitTrainY();

$vectorizer = new TokenCountVectorizer(new WhitespaceTokenizer());

$vectorizer->fit($sampleX);

$vectorizer->transform($sampleX);

$classifier = new SVC(
    Kernel::LINEAR, // $kernel
    1.0,            // $cost
    3,              // $degree
    null,           // $gamma
    0.0,            // $coef0
    0.001,          // $tolerance
    100,            // $cacheSize
    true,           // $shrinking
    false            // $probabilityEstimates, set to true
);
$classifier->train($sampleX, $sampleY);


$testX = $sample->getSplitTestX();
$testY = $sample->getSplitTestY();

$jieba = new Jieba($stopWordsFile);
$predictY = [];

foreach ($testX as $test) {
    $testSampleText = [$test];

    $vectorizer->transform($testSampleText);

    $predictY[] = current($classifier->predict($testSampleText));
}

var_dump($testY, $predictY);

$report = new ClassificationReport($testY, $predictY);
var_dump($report->getAverage());