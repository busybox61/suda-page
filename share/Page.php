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

namespace dxkite\page;

use suda\core\Response;
use suda\core\Request;
use suda\core\{Storage,Router};
use dxkite\page\table\PageTable;
use suda\template\Manager;
use dxkite\support\template\Manager as ThemeManager;
use suda\core\route\Mapping;

class Page
{
    private static $router;

    /**
     * 加载静态页面配置
     *
     * @return void
     */
    public static function load()
    {
        return (new PageTable)->list();
    }

    /**
     * 获取页面链接
     *
     * @param string $name
     * @return void
     */
    public static function page(string $name)
    {
        return u('page:static_'.$name);
    }

    /**
     * 添加页面
     *
     * @param string $name 页面名
     * @param string $template 模板文件
     * @return void
     */
    public static function add(string $name, string  $match, string $template, array $data)
    {
        return (new PageTable)->add($name, $match, $template, $data);
    }

    /**
     * 渲染页面
     *
     * @param string $page
     * @param array $data
     * @return void
     */
    public static function render(string $page, Response $response)
    {
        $page=preg_replace('/^(?:.+?:)static_(.+)$/', '$1', $page);
        $data=self::getPageDate($page);
        $template=$data['template'];
        $values=$data['data'];
        return Manager::display($template)->response($response)->assign($values)->render();
    }

    public static function getPageDate(string $page)
    {
        return (new PageTable)->get($page);
    }

    public function addRouter(string $name, string $match)
    {
        self::$router->addRouter('static_'.$name, '/'.$match, 'dxkite\\page\\response\\PageResponse', 'dxkite/page');
    }

    public function init($router)
    {
        self::$router=$router;
        $pages=self::load();
        if (is_array($pages)) {
            foreach ($pages as $page) {
                self::addRouter($page['name'], $page['match']);
            }
        }
        $template=ThemeManager::instance()->getCurrentTheme();
        if (isset($template->config['page']) && is_array($template->config['page'])) {
            foreach ($template->config['page'] as $routeName=> $page) {
                $page['class']=$page['class']??'dxkite\\page\\response\\PageResponse';
                $mapping=Mapping::createFromRouteArray(Mapping::ROLE_SIMPLE, 'dxkite/page', $routeName, $page);
                $mapping->setParam(['templatePage'=>true,'template'=>$page['template'],'data'=>$page['data']??[]]);
                $mapping->build();
                self::$router->addMapping($mapping);
            }
        }
    }
    
    public static function getTemplate(int $id)
    {
        return (new PageTable)->getTemplate($id);
    }

    public static function update(int $id, array $value)
    {
        return (new PageTable)->updateByPrimaryKey($id, $value);
    }

    public static function getNew()
    {
        return (new PageTable)->getNew();
    }
    
    public static function staticBase()
    {
        list($admin_prefix, $prefix)=Router::getModulePrefix('dxkite/page');
        return Request::getInstance()->baseUrl(). trim($prefix, '/');
    }

    public static function template($template)
    {
        $template->addCommand('page', function ($exp) {
            return '<?php echo '.__CLASS__.'::page'.$exp.'; ?>';
        });
        $template->addCommand('pageBase', function () {
            return '<?php echo '.__CLASS__.'::staticBase(); ?>';
        });
    }

    public static function getTemplateInfo(string $name)
    {
        return new TemplateInfo($name);
    }

    public static function getHtmlPath(string $name)
    {
        $url=self::page($name);
        $path=preg_replace('/^'.preg_quote(request()->baseUrl(), '/').'/', '', $url);
        if (!preg_match('/\.html$/', $path)) {
            $path = trim($path, '/').'/index.html';
        }
        return APP_PUBLIC.'/'.$path;
    }

    public static function saveHtml(int $id, $response)
    {
        $data=(new PageTable)->getById($id);
        $template=$data['template'];
        $values=$data['data'];
        $html=Manager::display($template)->response($response)->assign($values)->getRenderedString();
        $path=self::getHtmlPath($data['name']);
        storage()->mkdirs(dirname($path));
        return storage()->put($path, $html);
    }
}
