<?php
require_once MODX_CORE_PATH . 'model/modx/modrequest.class.php';

/**
 * Encapsulates the interaction of MODx manager with an HTTP request.
 *
 * {@inheritdoc}
 *
 * @package gatewaymanager
 * @extends modRequest
 */
class gatewaymanagerControllerRequest extends modRequest {

	public $discuss = null;
	public $actionVar = 'action';
	public $defaultAction = 'index';
	
	function __construct(GatewayManager &$gatewaymanager) {
		parent::__construct($gatewaymanager->modx);
		$this->gatewaymanager =& $gatewaymanager;
    }
	
    /**
     * Extends modRequest::handleRequest and loads the proper error handler and
     * actionVar value.
     *
     * {@inheritdoc}
     */
    public function handleRequest() {
		$this->loadErrorHandler();
	   
		// save page to manager object. allow custom actionVar choice for extending classes
		$this->action = isset($_REQUEST[$this->actionVar]) ? $_REQUEST[$this->actionVar] : $this->defaultAction;
		
		return $this->_prepareResponse();
    }
	
	/**
	 * Prepares the MODx response to a mgr request that is being handled.
	 *
	 * @access public
	 * @return boolean True if the response is properly prepared.
	 */
	protected function _prepareResponse() {
		$modx =& $this->modx;
		$gatewaymanager =& $this->gatewaymanager;
		
		$viewHeader = include $this->gatewaymanager->config['corePath'].'controllers/mgr/header.php';
		
		$f = $this->gatewaymanager->config['corePath'].'controllers/mgr/'.$this->action.'.php';
		
		if(file_exists($f)) {
			$viewOutput = include $f;
		}
		else {
			$viewOutput = 'Action not found: '.$f;
		}
		
		return $viewHeader.$viewOutput;
	}
}

?>