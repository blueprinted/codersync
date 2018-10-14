<?php
/**
 *	自定义 session 文件存储
 *	zouchao@sogou-inc.com
 *	2015-07-25 00:03:00
 */
//这个自定义session文件存储驱动在高并发的情况下还是会导致session文件被清空 如：ab -c 100 -n 10000

namespace Think\Session\Driver;

class Filer {

    /**
     * session文件保存路径
     */
    protected $savePath  = '';
    protected $sessID = null;
    protected $debuglogfile = '/tmp/session_debug.txt';
    protected $processid = null;
	protected $readfail = false;//是否因为不可读或获取共享锁失败而导致读失败（出现这种情况需要控制写session文件，让写文件也失败，否则会造成session文件被清空）
	protected $lock_nb = false;//flock时是否非阻塞锁
    protected $debug = true;

    /**
     * 打开Session
     * @access public
     * @param string $savePath
     * @param mixed $sessName
     */
    public function open($savePath, $sessName) {
        $this->savePath = $savePath;
        if($this->debug) {
            $this->processid = getmypid();
            $loginfo = "pid={$this->processid} session_open {$sessName} {$this->savePath}";
            $this->debugger($loginfo);
        }
        return true;
    }

    /**
     * 关闭Session
     * @access public
     */
	public function close() {
		if($this->debug) {
			$loginfo = "pid={$this->processid} session_close {$this->sessID}";
			$this->debugger($loginfo);
		}
		return true;
	}

    /**
     * 读取Session
     * @access public
     * @param string $sessID
     */
	public function read($sessID) {
		$this->sessID = $sessID;
		$is_readable = true;
		$counter = $times = 0;//计次 计时
		clearstatcache();
		while(file_exists("{$this->savePath}/sess_{$sessID}") && !is_readable("{$this->savePath}/sess_{$sessID}")) {
			$is_readable = false;
			clearstatcache();
			$delta = 1000*rand(1,5);//1ms ~ 5ms
            if($counter++ > 200 || $times > 300000) {
				break;
            }
            $times += $delta;
            usleep($delta);
		}
		if(!$is_readable) {
			if($this->debug) {
			    $loginfo = "pid={$this->processid} session_read !is_readable fail {$sessID}";
			    $this->debugger($loginfo);
			}
			$this->readfail = true;
			return '';
		}
		if(false === ($handle = @fopen("{$this->savePath}/sess_{$sessID}", 'rb'))) {
            if($this->debug) {
			    $loginfo = "pid={$this->processid} session_read fopen fail {$sessID}";
			    $this->debugger($loginfo);
			}
			return '';
		}
		if($this->lock_nb) {
			$counter = $times = 0;//计次 计时
			$break = false;
			while(!flock($handle, LOCK_SH|LOCK_NB)) {//获取非阻塞共享锁
				$delta = 1000*rand(1,5);//1ms ~ 5ms
				if($counter++ > 200 || $times > 300000) {//超过100次或达到300毫秒
					$break = true;
					break;
				}
				$times += $delta;
				usleep($delta);
			}
			if($break) {
				$this->readfail = true;
				if($this->debug) {
					$loginfo = "pid={$this->processid} session_read flock(LOCK_SH|LOCK_NB) fail {$sessID}";
					$this->debugger($loginfo);
				}
				fclose($handle);
				return '';
			}	
		} else {
			if(!flock($handle, LOCK_SH)) {
				$this->readfail = true;
				if($this->debug) {
					$loginfo = "pid={$this->processid} session_read flock(LOCK_SH) fail {$sessID}";
					$this->debugger($loginfo);
				}
				fclose($handle);
				return '';
			}
		}
		$contents = '';
		while(!feof($handle)) {
			$contents .= fread($handle, 1024);
		}
		if($this->debug) {
			if(strlen($contents) < 1) {
				$loginfo = "pid={$this->processid} session_read empty {$sessID}";
			} else {
				$loginfo = "pid={$this->processid} session_read strlen {$sessID}";
			}
			$this->debugger($loginfo);
		}
		flock($handle, LOCK_UN);
		fclose($handle);
		return $contents;
	}

    /**
     * 写入Session
     * @access public
     * @param string $sessID
     * @param String $sessData
    写文件的时候先请求独占锁，锁定后方可写文件，写完再解除锁定；
    如果不加锁，碰到并发的情况会造成用户的session被清空；
    例如：
    有a跟b两个请求并发执行：
    请求a此时正在执行write session文件，由于是w模式，打开session文件时会将session文件截断为0（即清空），
    这个时候请求b正好执行到read session文件，如果请求a在write session文件的时候没有加锁，那么请求b可以直接read session文件，从而读到了空的session数据。
    往往a请求要先结束，然后在b请求结束的时候会将读进内存的空的session数据重新write到session文件，然后session文件就是空的了，当下一个请求再过来，
    用户的会话信息就丢失了。
     */
	public function write($sessID, $sessData) {
		if($this->readfail) {
			if($this->debug) {
			    $loginfo = "pid={$this->processid} session_write readfail fail {$sessID}";
			    $this->debugger($loginfo);
			}
			return false;
		}
		$is_writable = true;
		$counter = $times = 0;//计次 计时
		clearstatcache();
		while(file_exists("{$this->savePath}/sess_{$sessID}") && !is_writable("{$this->savePath}/sess_{$sessID}")) {
			$is_writable = false;
			clearstatcache();
			$delta = 1000*rand(1,5);//1ms ~ 5ms
            if($counter++ > 200 || $times > 300000) {
				break;
            }
            $times += $delta;
            usleep($delta);
		}
		if(!$is_writable) {
			if($this->debug) {
			    $loginfo = "pid={$this->processid} session_write !is_writable fail {$sessID}";
			    $this->debugger($loginfo);
			}
			return false;
		}
		if(false === ($handle = @fopen("{$this->savePath}/sess_{$sessID}", 'wb'))) {
			if($this->debug) {
			    $loginfo = "pid={$this->processid} session_write fopen fail {$sessID}";
			    $this->debugger($loginfo);
			}
			return false;
		}
		if($this->lock_nb) {
			$counter = $times = 0;//计次 计时
			$break = false;
			while(!flock($handle, LOCK_EX|LOCK_NB)) {//获取非阻塞独占锁
				$delta = 1000*rand(1,5);//1ms ~ 10ms
				if($counter++ > 200 || $times > 300000) {
					$break = true;
					break;
				}
				$times += $delta;
				usleep($delta);
			}
			if($break) {
				if($this->debug) {
					$loginfo = "pid={$this->processid} session_write flock(LOCK_EX|LOCK_NB) fail {$sessID}";
					$this->debugger($loginfo);
				}
				fclose($handle);
				return false;
			}
		} else {
			if(!flock($handle, LOCK_EX)) {
				if($this->debug) {
					$loginfo = "pid={$this->processid} session_write flock(LOCK_EX) fail {$sessID}";
					$this->debugger($loginfo);
				}
				fclose($handle);
				return false;
			}
		}
		
		$resu = fwrite($handle, $sessData, strlen($sessData));
		if($this->debug) {
			if($resu === false) {
				$loginfo = "pid={$this->processid} session_write fail {$sessID}";
			} else {
				if(strlen($sessData) < 1) {
					$loginfo = "pid={$this->processid} session_write empty {$sessID}";
				} else {
					$loginfo = "pid={$this->processid} session_write strlen {$sessID}";
				}
			}
			$this->debugger($loginfo);
		}
		flock($handle, LOCK_UN);
		fclose($handle);
		return $resu === false ? false : true;
	}

    /**
     * 删除Session
     * @access public
     * @param string $sessID
     */
	public function destroy($sessID) {
		if($this->debug) {
			$loginfo = "pid={$this->processid} session_destroy {$sessID}";
			$this->debugger($loginfo);
		}
		return @unlink("{$this->savePath}/sess_{$sessID}");
	}

    /**
     * Session 垃圾回收
     * @access public
     * @param string $sessMaxLifeTime
     */
	public function gc($sessMaxLifeTime) {
		if($this->debug) {
			$loginfo = "pid={$this->processid} session_gc {$sessID}";
			$this->debugger($loginfo);
		}
		foreach (glob("{$this->savePath}/sess_*") as $filename) {
			if(filemtime($filename) + $sessMaxLifeTime < time()) {
				@unlink($filename);
			}
		}
		return true;
	}

	public function debugger($string, $wmode = 'append') {
		return file_put_contents($this->debuglogfile, "[".date("Y-m-d H:i:s")." ".date_default_timezone_get()."] {$string}\r\n", $wmode == 'append' ? FILE_APPEND : 0);
	}
}
