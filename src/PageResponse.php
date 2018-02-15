<?php
/**
 * Static Page Generator
 *
 * 
 * Copyright (c)  2017 DXkite<dxkite@qq.com>
 *
 * @category   PHP FrameWork
 * @package    Suda
 * @copyright  Copyright (c) DXkite
 * @license    MIT
 * @link       https://github.com/DXkite/suda
 * @version    since 1.2.9
 */

namespace dxkite\page\response;

use suda\core\{Session,Cookie,Request,Query};
use dxkite\page\Page;
use dxkite\support\visitor\response\Response;
use dxkite\support\visitor\Context;

class PageResponse extends Response
{

    public function onVisit(Context $context)
    {
        $mapping=request()->getMapping();
        if ($mapping && $mapping->getParam()){
            $param=$mapping->getParam();
            if (isset($param['templatePage'])) {
                return $this->page($param['template'],$param['data']??[])->render();
            }
        }
        return Page::render(self::$name,$this);
    }
}
