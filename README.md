# php-text-classification
:snowflake: text classification based on php-ml

使用头条数据集，基于php-ml写中文文本分类

- SVM
- KNN
- NaiveBayes

### Installation

```bash
unzip data/toutiao_cat_data.txt.zip
composer install
```

### Test Report(100 of samples)

#### SVC

```vim
model: TextClassification\Models\LinearSvc
array(1) {
  ["score"]=>
  float(0.72222222222222)
}
array(1) {
  ["report"]=>
  array(3) {
    ["precision"]=>
    float(0.85198473935693)
    ["recall"]=>
    float(0.72222222222222)
    ["f1score"]=>
    float(0.75601694560694)
  }
}
```

#### NaiveBayes

```vim
model: TextClassification\Models\NaiveBayes
array(1) {
  ["score"]=>
  float(0.76444444444444)
}
array(1) {
  ["report"]=>
  array(3) {
    ["precision"]=>
    float(0.77484672622763)
    ["recall"]=>
    float(0.76444444444444)
    ["f1score"]=>
    float(0.76481474408685)
  }
}
```

#### KNearestNeighbors

```php

model: TextClassification\Models\KNearestNeighbors
array(1) {
  ["score"]=>
  float(0.19777777777778)
}
array(1) {
  ["report"]=>
  array(3) {
    ["precision"]=>
    float(0.71794737986519)
    ["recall"]=>
    float(0.19777777777778)
    ["f1score"]=>
    float(0.22515611166428)
  }
}
```

