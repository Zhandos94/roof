<?php
/**
 * Created by PhpStorm.
 * User: mrdos
 * Date: 08.01.2017
 * Time: 19:26
 */
namespace  frontend\actions;
use yii\base\Action;


class TestAction extends Action{
    public $viewName = 'index';


    public function run() {

        return $this->controller->render("@frontend/actions/views/" . $this->viewName);
    }


}
?>
