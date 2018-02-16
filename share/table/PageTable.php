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

namespace dxkite\page\table;

use  suda\archive\Table;

class PageTable extends Table
{
    const TEMP_NEW=3;

    public function __construct()
    {
        parent::__construct('pages');
    }
    
    public function onBuildCreator($table)
    {
        return $table->fields(
            $table->field('id', 'bigint', 20)->primary()->auto(),
            $table->field('name', 'varchar', 255)->key()->comment("页面名称"),
            $table->field('match', 'varchar', 255)->key()->comment("页面名称"),
            $table->field('template', 'varchar', 255)->key()->comment("匹配URL"),
            $table->field('data', 'text')-> comment("模板文件"),
            $table->field('status', 'tinyint', 1)->key()->comment("状态")
        );
    }

    public function add(string $name, string  $match, string $template, array $data)
    {
        $get=$this->select('id', ['name'=>$name]);
        if ($get && ($val=$get->fetch())) {
            $id=$val['id'];
            $this->updateByPrimaryKey($id, ['data'=>$data]);
        } else {
            $id=$this->insert(['name'=>$name,'match'=>$match,'template'=>$template,'data'=>$data]);
        }
        return $id;
    }

    public function get(string $name)
    {
        $get=$this->select(['id','match','template','data','status'], ['name'=>$name]);
        if ($get && ($val=$get->fetch())) {
            return $val;
        }
        return null;
    }

    public function getById(int $id)
    {
        $val=$this->getByPrimaryKey($id);
        return $val;
    }
    
    public function getTemplate(int $id)
    {
        $val=$this->getByPrimaryKey($id);
        if ($val) {
            return $val['template'];
        }
        return null;
    }

    public function setValue(int $id, string $name, $value)
    {
        $val=$this->getByPrimaryKey($id);
        if ($val) {
            $data[$name]=$value;
            return $this->updateByPrimaryKey($id, ['data'=>$data]);
        }
        return false;
    }
    
    public function getValue(int $id, string $name)
    {
        $val=$this->getByPrimaryKey($id);
        if ($val) {
            return $data[$name]??null;
        }
        return null;
    }

    public function getNew()
    {
        $get=$this->select(['id','name','match','template'], ['status'=>self::TEMP_NEW]);
        if ($value=$get->fetch()) {
            return $value;
        }
        $data=['name'=>'untitied','match'=>'untitied.html','template'=>'','status'=>self::TEMP_NEW];
        $id=$this->insert($data);
        $data['id']=$id;
        return $data;
    }

    public function setStatus(int $id, int $statu=0)
    {
        return $this->updateByPrimaryKey($id, ['status'=>$statu]);
    }

    public function list(int $page=null, int $rows=10,bool $offset=false)
    {
        if (is_null($page)) {
            $list=parent::listWhere(['status'=>0]);
        } else {
            $list=parent::listWhere(['status'=>0], [], $page, $rows,$offset);
        }
        return $list;
    }

    protected function _inputDataField($value)
    {
        return serialize($value);
    }

    protected function _outputDataField($value)
    {
        return unserialize($value);
    }
}
