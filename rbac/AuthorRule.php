<?php

namespace app\rbac;

use yii\rbac\Item;
use yii\rbac\Rule;


/**
 * Class AuthorRule
 * @package app\rbac
 */
class AuthorRule extends Rule
{
    public $name = 'isAuthor';

    /**
     * @param int|string $user
     * @param Item $item
     * @param array $params
     * @return bool
     */
    public function execute($user, $item, $params)
    {
        return isset($params['note']) ? $params['note']->author == $user : false;
    }
}