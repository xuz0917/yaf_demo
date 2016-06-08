<?php
/**
 * 异常处理
 * @author 张洋 2050479@qq.com
 */
class ErrorController extends Ctrl_Base {
	public function errorAction($exception) {
		$this->getView()->assign("exception", $exception);
	}
}