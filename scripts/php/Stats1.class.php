<?php

class Stats1 {
	public $ccnt;
	public $pmin;
	public $pavg;
	public $pmax;
	public $psum;
	public $tmin;
	public $tavg;
	public $tmax;
	public $tsum;
	public $dmin;
	public $davg;
	public $dmax;
	public $dsum;
	public $cmin;
	public $cavg;
	public $cmax;
	public $csum;
	public $vmin;
	public $vavg;
	public $vmax;
	public $vsum;
	
	
	public function info(){
		return '#' . $this->ccnt . " - libelle " . $this->vsum;
	}
}

?>