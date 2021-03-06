<?php
declare(strict_types=1);
ini_set('memory_limit', '2048M');

require_once __DIR__ . '/vendor/autoload.php';

use Phpml\Metric\ClassificationReport;
use Phpml\Metric\Accuracy;
use Phpml\Metric\ConfusionMatrix;
use TextClassification\Sample;
use TextClassification\Models\KNearestNeighbors;
use TextClassification\Models\NaiveBayes;
use TextClassification\Models\LinearSvc;
use TextClassification\Models\MLPClassifier;
use TextClassification\Text;
use Phpml\ModelManager;
use Phpml\Tokenization\WhitespaceTokenizer;
use Phpml\FeatureExtraction\TokenCountVectorizer;
use Phpml\FeatureExtraction\TfIdfTransformer;
use Phpml\Pipeline;

$stopWordsFile = __DIR__ . '/data/stop_words.txt';
$textFile = __DIR__ . '/data/toutiao_cat_data.txt';

$offset = 0;
$length = 100;

$sample = new Sample($textFile, $stopWordsFile, $offset, $length);
$trainX = $sample->getSplitTrainX();
$trainY = $sample->getSplitTrainY();
$testX = $sample->getSplitTestX();
$testY = $sample->getSplitTestY();

// pipeline
//$transformers = [
//    new TokenCountVectorizer(new WhitespaceTokenizer()),
//    new TfIdfTransformer()
//];
//
//$pipeline = new Pipeline($transformers, new \Phpml\Classification\NaiveBayes());
//$pipeline->train($trainX, $trainY);
//$predictY = $pipeline->predict($testX);
//Accuracy::score($testY, $predictY);

$vectorizer = new TokenCountVectorizer(new WhitespaceTokenizer());
$vectorizer->fit($trainX);
$vectorizer->transform($trainX);

$transformer = new TfIdfTransformer($trainX);
$transformer->transform($trainX);

function getMetrics($model) {
    global $vectorizer, $testX, $testY, $textFile, $stopWordsFile;

    $classifier = $model->train();

    $predictY = [];
    foreach ($testX as $test) {
        $testSampleText = [$test];

        $vectorizer->transform($testSampleText);

        $predictY[] = current($classifier->predict($testSampleText));
    }

//    $text = new Text($textFile);
//    $categoryIds = $text->getCategoryIds();
//    var_dump(ConfusionMatrix::compute($testY, $predictY, $categoryIds));

    var_dump(['score' => Accuracy::score($testY, $predictY)]);

    $report = new ClassificationReport($testY, $predictY);
    var_dump(['report' => $report->getAverage()]);
}

function getModelsMetrics()
{
    global $trainX, $trainY;
    $models = [
        new NaiveBayes($trainX, $trainY),
        new KNearestNeighbors($trainX, $trainY),
        new LinearSvc($trainX, $trainY),
//        new MLPClassifier($trainX, $trainY)
    ];

    foreach ($models as $model) {
        echo "model: " . get_class($model) . PHP_EOL;

        getMetrics($model);
    }
}

getModelsMetrics();