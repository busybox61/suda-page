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
use dxkite\support\visitor\Context;

class EditResponse extends \dxkite\support\setting\Response
{
    /**
     * 
     * @acl page.edit
     * @param Context $context
     * @return void
     */
    public function onAdminView($view, $context)
    {
        $request=$context->getRequest();
        $id=$request->get()->id(0);
        $dao=new PageTable;
        if ($item=$dao->getById($id)) {
            $view->set('page', $item);
        } else {
            $view->set('invaildId', true);
        }
    }


    public function adminContent($template)
    {
        \suda\template\Manager::include('page:setting/edit', $template)->render();
    }


    public function getTemplateValues(string $name)
    {
        $values=Page::getTemplateInfo($name)->getValuesName();
        foreach ($values as $name) {
            $list[]='<option value="'.$name.'">'.$name. '</option>';
        }
        return join("\n", $list);
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
            foreach ($items as $name) {
                $fullname=$module.':'.$name;
                $show= $fullname==$select?' selected="selected" ':'';
                $list[]='<option value="'.$fullname.'" '.$show.'>'.$fullname. '</option>';
            }
            $list[] = '</optgroup>';
        }
        return join("\n", $list);
    }
}
