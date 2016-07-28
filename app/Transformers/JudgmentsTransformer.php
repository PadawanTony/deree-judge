<?php
namespace Judge\Transformers;
/**
 * Created by PhpStorm.
 * User: antony
 * Date: 7/28/16
 * Time: 3:21 AM
 */

class JudgmentsTransformer
{
    public function transformToArrayAndRemoveParentheses($data)
    {
        $judgments = array();
        foreach ($data as $key=>$value)
        {
            $a = explode("(", $key);
            $a = implode("_", $a);
            $a = explode(")", $a);
            $a = implode("", $a);

            $judgments[$a] = $value;
        }

        return $judgments;
    }
    
}