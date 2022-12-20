<?php
/**
 * Created by PhpStorm.
 * User: mdomarecki
 * Date: 19.09.17
 * Time: 02:56
 */

namespace Settings\Table;


use Hr\Entity\Subchain;
use Hr\Table\TableService;

class SubchainTable extends TableService
{
    public function init()
    {
        $this->setId('subchainTable');
        $this->configureColumns([
            'name' => [
                'label' => 'Nazwa',
                'value' => 'getName',
                'sort' => 's.name',
            ],
            'chain' => [
                'label' => 'Sieć',
                'value' => 'getChain',
                'sort' => 'ddc.name',
            ],
            'varaible1' => [
                'label' => 'Zmienna 1',
                'value' => 'getVariable1',
                'sort' => 's.variable1',
            ],
            'varaible2' => [
                'label' => 'Zmienna 2',
                'value' => 'getVariable2',
                'sort' => 's.variable2',
            ],
            'varaible3' => [
                'label' => 'Zmienna 3',
                'value' => 'getVariable3',
                'sort' => 's.variable3',
            ],
            'varaible4' => [
                'label' => 'Zmienna 4',
                'value' => 'getVariable4',
                'sort' => 's.variable4',
            ],
            'varaible5' => [
                'label' => 'Zmienna 5',
                'value' => 'getVariable5',
                'sort' => 's.variable5',
            ],
            'varaible6' => [
                'label' => 'Zmienna 6',
                'value' => 'getVariable6',
                'sort' => 's.variable6',
            ],
        ]);
    }

    public function getName(Subchain $subchain)
    {
        return $subchain->getName();
    }

    public function getChain(Subchain $subchain)
    {
        return (!$subchain->getChain()) ? '' : $subchain->getChain()->getName();
    }

    public function getVariable1(Subchain $subchain)
    {
        return $subchain->getVariable1();
    }

    public function getVariable2(Subchain $subchain)
    {
        return $subchain->getVariable2();
    }

    public function getVariable3(Subchain $subchain)
    {
        return $subchain->getVariable3();
    }

    public function getVariable4(Subchain $subchain)
    {
        return $subchain->getVariable4();
    }

    public function getVariable5(Subchain $subchain)
    {
        return $subchain->getVariable5();
    }

    public function getVariable6(Subchain $subchain)
    {
        return $subchain->getVariable6();
    }
}