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

namespace  dxkite\page\response\setting;

use dxkite\page\{Page,TemplateInfo};
use dxkite\page\table\PageTable;
use suda\template\Manager;

class AjaxResponse extends \dxkite\support\visitor\response\CallableResponse
{
    /**
     * 获取模板值
     *  
     * @param string $name
     * @return void
     */
    public function getTemplateValues(string $name)
    {
        return Page::getTemplateInfo($name)->getValuesName();
    }

    /**
     * @cal edit_page
     */
    public function setPageValue(int $id, string $name, string $value)
    {
        return (new PageTable)->setValue($id, $name, $value);
    }

    /**
     * @cal edit_page
     */
    public function getPageValue(int $id, string $value)
    {
        return (new PageTable)->getValue($id, $value);
    }

    /**
     * @cal edit_page
     *
     * @param int $id
     * @return void
     */
    public function save(int $id)
    {
        return (new PageTable)->setStatus($id);
    }


    /**
     * @acl gen_html
     */
    public function saveHtml(int $id)
    {
        return Page::saveHtml($id,$this);
    }
    
    /**
     * @cal edit_page
     *
     * @param int $id
     * @param array $value
     * @return void
     */
    public function update(int $id, array $value)
    {
        return Page::update($id, $value);
    }
}
