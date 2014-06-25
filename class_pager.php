<?php
/**
 * DS Pager v2.0.0
 *
 * @name Pager Class
 * @version 2.0.0
 * @author Narong Rammanee, <ranarong@live.com>
 * @copyright Copyright (c) 2010, Narong Rammanee
 * @license No Licence Free 2010
 */
class Pager {

  private $_start;
  private $_stop;
	private $_all_page;
	private $_tmp_page;
	private $_config = array();

  /**
	 * Initialization
   *
   * @param string $config
   */
	public function __construct($config) {

  	$this->_config = $config;

  	(!is_numeric($this->_config['cur_page'])) ? $this->_config['cur_page'] = 1
  																						: $this->_config['cur_page'] = $config['cur_page'];
  	$this->_all_page = self::totalPage();
  }

 	/**
	 * Create Pager
	 *
	 * @return void
   */
  public function createPager() {

  	if(!$this->_tmp_page = self::compilePager())
			throw new Exception( 'Error obtaining pager!' );

		echo $this->_tmp_page;
  }

  /**
	 * define private 'compilePager()' method
	 *
	 * @return complete pager
   */
  private function compilePager() {


  	$this->_tmp_page = '<p '.$this->_config['css_page'].'>';
  	if ($this->_all_page > 1 && $this->_config['cur_page'] > 1) {
  		$this->_tmp_page .= '<a href="'.$this->_config['url_page'].'1">'.$this->_config['first'].'</a>';
  	}
  	if ($this->_all_page > 1 && $this->_config['cur_page'] > 1) {
  		$this->_tmp_page .= '<a href="'.$this->_config['url_page'].($this->_config['cur_page'] - 1).'">'.$this->_config['previous'].'</a>';
  	}

  	if ($this->_all_page <= $this->_config['scr_page']) {
    	if($this->_config['all_recs'] <= $this->_config['per_page']) {
      	$this->_start = 1;
        $this->_stop  = $this->_all_page;
      } else {
     		$this->_start = 1;
        $this->_stop  = $this->_all_page;
      }
    } else {
    	if($this->_config['cur_page'] < intval($this->_config['scr_page'] / 2) + 1) {
	      $this->_start = 1;
	     	$this->_stop  = $this->_config['scr_page'];
      } else {
      	$this->_start = $this->_config['cur_page'] - intval($this->_config['scr_page'] / 2);
        $this->_stop  = $this->_config['cur_page'] + intval($this->_config['scr_page'] / 2);
        if($this->_stop > $this->_all_page) $this->_stop = $this->_all_page;
      }
    }
    if ($this->_all_page > 1) {
	    for ($i = $this->_start; $i <= $this->_stop; $i++) {
	    	if ($i == $this->_config['cur_page']) {
	      	$this->_tmp_page .= '<span '.$this->_config['act_page'].'>'.$i.'</span>';
	      } else {
	        $this->_tmp_page .= '<a href="'.$this->_config['url_page'].$i.'">'.$i.'</a>';
	      }
	    }
    }

  	if ($this->_config['cur_page'] < $this->_all_page) {
  		$this->_tmp_page .= '<a href="'.$this->_config['url_page'].($this->_config['cur_page'] + 1).'">'.$this->_config['next'].'</a>';
  	}
  	if ($this->_config['cur_page'] < $this->_all_page) {
  		$this->_tmp_page .= '<a href="'.$this->_config['url_page'].$this->_all_page.'">'.$this->_config['last'].'</a>';
  	}
  	return $this->_tmp_page.'</p>';
  }

  /**
	 * Limit Start
	 *
	 * @return limit start
   */
  public function limitStart() {
  	return ($this->_all_page <= 1) ? 0 : ($this->_config['cur_page'] - 1) * $this->_config['per_page'];
  }

  /**
	 * Total Page
	 *
	 * @return total page
   */
  public function totalPage() {
  	return ($this->_config['all_recs']) ? ceil($this->_config['all_recs'] / $this->_config['per_page']) : 0;
  }
}
