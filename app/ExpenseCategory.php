<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Moonlight\Main\ElementInterface;
use Moonlight\Main\ElementTrait;

class ExpenseCategory extends Model implements ElementInterface
{
    use ElementTrait;
}
