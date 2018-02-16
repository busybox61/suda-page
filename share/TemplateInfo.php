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

use suda\template\Manager;

class TemplateInfo extends \suda\template\compiler\suda\Compiler
{
    protected $values=[];
    protected $includes=[];
    protected $includes_info=[];
    protected $name;
    protected $module=null;
    protected $path=null;
    protected static $templates;

    public function __construct(string $name, string $parent=null)
    {
        list($module_name, $basename)=router()->parseName($name, $parent);
        $this->name=$module_name.':'.$basename;
        $this->module=$module_name;
        if ($path=Manager::getInputFile($this->name)) {
            $this->path=$path;
            $this->compileText(file_get_contents($path));
        }
    }

    protected function echoValueCallback($matchs)
    {
        $name=$matchs[1];
        $args=isset($matchs[4])?','.$matchs[4]:'';
        $this->values[$name]=$matchs[4]??null;
    }

    // include
    protected function parseInclude($exp)
    {
        preg_match('/\((.+)\)/', $exp, $v);
        $name=str_replace('\'', '-', trim($v[1], '"\''));
        ($tpl=new self($name, $this->module));
        $this->includes[]=$tpl->name;
        $this->includes_info[$name]=$tpl;
        $this->values=array_merge($this->values, $tpl->values);
        $this->includes=array_merge($this->includes, $tpl->includes);
    }

    public function getValuesName()
    {
        return array_keys($this->values);
    }

    public static function getTemplates()
    {
        return \dxkite\support\template\Manager::getTemplates();
    }
}
