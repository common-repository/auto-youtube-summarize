<?php
switch($this->request('view')):
	case 'addon':
		$this->get_template('/admin/addon');
		break;
	case 'install':
		$this->get_template('/admin/install');
		break;
	default:
		$this->get_template('/admin/setting');
		break;
endswitch;
?>