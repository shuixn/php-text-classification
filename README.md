# php-text-classification
:snowflake: text classification based on php-ml

- SVM
- KNN
- NaiveBayes
- MLPClassifier

### Installation

```bash
unzip data/toutiao_cat_data.txt.zip
composer install
```

### Test Report(100 of samples)

```php
model: TextClassification\Models\NaiveBayes
array(1) {
  ["score"]=>
  float(0.69111111111111)
}
array(1) {
  ["report"]=>
  array(3) {
    ["precision"]=>
    float(0.70512472512699)
    ["recall"]=>
    float(0.69111111111111)
    ["f1score"]=>
    float(0.69060989408364)
  }
}
model: TextClassification\Models\KNearestNeighbors
array(1) {
  ["score"]=>
  float(0.43777777777778)
}
array(1) {
  ["report"]=>
  array(3) {
    ["precision"]=>
    float(0.76090004609655)
    ["recall"]=>
    float(0.43777777777778)
    ["f1score"]=>
    float(0.49691418502695)
  }
}
model: TextClassification\Models\LinearSvc
array(1) {
  ["score"]=>
  float(0.69111111111111)
}
array(1) {
  ["report"]=>
  array(3) {
    ["precision"]=>
    float(0.74841797069747)
    ["recall"]=>
    float(0.69111111111111)
    ["f1score"]=>
    float(0.70254435831638)
  }
}
```

