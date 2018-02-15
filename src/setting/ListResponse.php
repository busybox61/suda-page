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

use dxkite\page\Page;
use dxkite\page\TemplateInfo;
use dxkite\page\table\PageTable;
use dxkite\support\visitor\Context;

class ListResponse extends \dxkite\support\setting\Response
{
    /**
     *
     * @acl list_page,delete_page
     * @param Context $context
     * @return void
     */
    public function onAdminView($view, $context)
    {
        $request=$context->getRequest();
        $dao=new PageTable;
        if ($request->hasGet() && $request->get()->delete!=0) {
            $dao->deleteByPrimaryKey($request->get()->delete);
            $this->forward();
            return;
        }
        $now=$request->get()->page(1);
        $list=$dao->list($now, 10);
        $view->set('page.max', ceil($dao->count()/10));
        $view->set('page.now', $now);
        $view->set('page.router', 'pages:admin_list');
        $view->set('list', $list);
    }


    public function adminContent($template)
    {
        \suda\template\Manager::include('page:setting/list', $template)->render();
    }
}
