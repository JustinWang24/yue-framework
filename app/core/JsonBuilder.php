<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 1/8/18
 * Time: 2:27 PM
 */

namespace App\core;


class JsonBuilder
{
    const CODE_SUCCESS      = 100;
    const CODE_SUCCESS_ALT  = 101;
    const CODE_ERROR = 99;
    /**
     * 返回成功JSON消息
     * @param  array|String $dataOrMessage
     * @return string
     */
    public static function Success($dataOrMessage = 'OK'){
        if(is_array($dataOrMessage)){
            return json_encode([
                'error_no' => self::CODE_SUCCESS,
                'data' => $dataOrMessage
            ]);
        }else{
            return json_encode([
                'error_no' => self::CODE_SUCCESS,
                'msg' => $dataOrMessage
            ]);
        }
    }

    /**
     * 返回成功JSON消息, 但是结果有了变形, 在同样成功,但是返回的数据的结构有区别的时候使用
     * @param  array|String $dataOrMessage
     * @return string
     */
    public static function SuccessAlternative($dataOrMessage = 'OK'){
        if(is_array($dataOrMessage)){
            return json_encode([
                'error_no' => self::CODE_SUCCESS_ALT,
                'data' => $dataOrMessage
            ]);
        }else{
            return json_encode([
                'error_no' => self::CODE_SUCCESS_ALT,
                'msg' => $dataOrMessage
            ]);
        }
    }

    /**
     * 返回错误JSON消息
     * @param  array|String $dataOrMessage
     * @return string
     */
    public static function Error($dataOrMessage = 'Err'){
        if(is_array($dataOrMessage)){
            return json_encode([
                'error_no' => self::CODE_ERROR,
                'data' => $dataOrMessage
            ]);
        }else{
            return json_encode([
                'error_no' => self::CODE_ERROR,
                'msg' => $dataOrMessage
            ]);
        }
    }
}