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
use dxkite\support\visitor\Context;

class AddResponse extends \dxkite\support\setting\Response
{
    public function onAdminView($view, $context)
    {
        $new=Page::getNew();
        $view->set('page',$new);
    }

    public function adminContent($template)
    {
        \suda\template\Manager::include('page:setting/add', $template)->render();
    }

    public function getTemplateOptions(int $id)
    {
        $templates=TemplateInfo::getTemplates();
        $select=Page::getTemplate($id);
        if (empty($select)) {
            $list[] = '<option selected="selected">' . __('请选择模板') . '</option>';
        }
        foreach ($templates as $module=>$items) {
            $list[]= '<optgroup label="'. $module .'">';
            foreach ($items as $name=>$path) {
                $fullname=$module.':'.$name;
                $show= $fullname==$select?' selected="selected" ':'';
                $list[]='<option value="'.$fullname.'" '.$show.' title="'.$path.'">'.$fullname. '</option>';
            }
            $list[] = '</optgroup>';
        }
        return join("\n", $list);
    }
}
