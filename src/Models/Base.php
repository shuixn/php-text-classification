<?php
declare(strict_types=1);

namespace TextClassification\Models;

use Phpml\Classification\Classifier;

/**
 * Class Base
 *
 * @package TextClassification\Models
 */
class Base
{
    /** @var Classifier */
    protected $classifier;
}