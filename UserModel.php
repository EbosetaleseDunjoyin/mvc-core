<?php


namespace edj\mvcframecore;


use edj\mvcframecore\db\DbModel;

abstract class UserModel extends DbModel
{
    abstract public function getDisplayName(): string ;
}