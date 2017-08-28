<?php
namespace Library;

/**
 * Class     Log
 * 日志类
 * '
 */
class Log {
	
	/**
	 * Variable  _file_template
	 * 文件模板
	 * '
	 *
	 * @static
	 * @var      null
	 */
	private static $_file_template = null;
	
	/**
	 * Variable  _default_file_template
	 * 默认文件模板
	 * '
	 *
	 * @static
	 * @var      string
	 */
	private static $_default_file_template = '{type}/{date}(Ym/d).log';
	
	/**
	 * Variable  _hour_file_template
	 * 以小时分割日志文件模板
	 * '
	 *
	 * @static
	 * @var      string
	 */
	private static $_hour_file_template = '{type}/{date}(Ym/d).log';
	
	/**
	 * Variable  _content_template
	 * 内容模板
	 * '
	 *
	 * @static
	 * @var      null
	 */
	private static $_content_template = null;
	
	/**
	 * Variable  _default_content_template
	 * 默认内容模板
	 * '
	 *
	 * @static
	 * @var      string
	 */
	private static $_default_content_template = "{date}(Y-m-d H:i:s) {content} in {file} at {line}\n";
	
	/**
	 * Variable  _module_name
	 * 模块名称
	 * '
	 *
	 * @static
	 * @var      null
	 */
	private static $_module_name = null;
	
	/**
	 * Variable  _default_module_name
	 * 默认模块名称
	 * '
	 *
	 * @static
	 * @var      string
	 */
	private static $_default_module_name = 'default';
	
	/**
	 * Variable  _type_list
	 * 类型列表
	 * '
	 *
	 * @static
	 * @var      array
	 */
	private static $_type_list = array(
		'trace',
		'debug',
		'info',
		'warning',
		'error',
		'message',
		'mail',
		'api',
		'called',
		'post',
		'mc',
		'access',
		'sql',
	);
	
	/**
	 * Variable  _default_type
	 * 默认类型
	 * '
	 *
	 * @static
	 * @var      string
	 */
	private static $_default_type = 'default';
	
	/**
	 * Method  setConfig
	 * 设置配置
	 *
	 * @author
	 * @static
	 *
	 * @param array $config
	 */
	public static function setConfig($config = array()) {
		
		$log_path = dirname(__DIR__) . '/logs/';
		if(!file_exists($log_path)) {
			mkdir($log_path, 0777, true);
		}
		
		if(null === self::$_file_template) {
			self::$_file_template = $log_path . self::$_default_file_template;
		}
		
		if(empty($config['content_template'])) {
			if(null === self::$_content_template) {
				self::$_content_template = self::$_default_content_template;
			}
		} else {
			self::$_content_template = $config['content_template'];
		}
		
		if(empty($config['module_name'])) {
			if(null === self::$_module_name) {
				self::$_module_name = self::$_default_module_name;
			}
		} else {
			self::$_module_name = $config['module_name'];
		}
	}
	
	/**
	 * Method  setFileTemplate
	 * 设置文件模板
	 *
	 * @author
	 * @static
	 *
	 * @param $file_template
	 */
	public static function setFileTemplate($file_template) {
		self::$_file_template = Yaf_Registry::get('config')->application->logs . '/' . $file_template;
	}
	
	/**
	 * Method  setContentemplate
	 * 设置内容模板
	 *
	 * @author
	 * @static
	 *
	 * @param $content_template
	 */
	public static function setContentemplate($content_template) {
		self::$_content_template = $content_template;
	}
	
	/**
	 * Method  setModuleName
	 * 设置模块名称
	 *
	 * @author
	 * @static
	 *
	 * @param $module_name
	 */
	public static function setModuleName($module_name) {
		self::$_module_name = $module_name;
	}
	
	/**
	 * Method  trace
	 * 写入trace类型日志
	 *
	 * @author
	 * @static
	 *
	 * @param $content
	 */
	public static function trace($content) {
		self::write('trace', $content, true);
	}
	
	/**
	 * Method  debug
	 * 写入debug类型日志
	 *
	 * @author
	 * @static
	 *
	 * @param $content
	 */
	public static function debug($content) {
		self::write('debug', $content, true);
	}
	
	/**
	 * Method  info
	 * 写入info类型日志
	 *
	 * @author
	 * @static
	 *
	 * @param $content
	 */
	public static function info($content) {
		self::write('info', $content, true);
	}
	
	/**
	 * Method  warning
	 * 写入warning类型日志
	 *
	 * @author
	 * @static
	 *
	 * @param $content
	 */
	public static function warning($content) {
		self::write('warning', $content, true);
	}
	
	/**
	 * Method  error
	 * 写入error类型日志
	 *
	 * @author
	 * @static
	 *
	 * @param $content
	 */
	public static function error($content) {
		self::write('error', $content, true);
	}
	
	/**
	 * Method  message
	 * 写入message类型日志
	 *
	 * @author
	 * @static
	 *
	 * @param $content
	 */
	public static function message($content) {
		self::write('message', $content, true);
	}
	
	/**
	 * Method  mail
	 * 写入mail类型日志
	 *
	 * @author
	 * @static
	 *
	 * @param $content
	 */
	public static function mail($content) {
		self::write('mail', $content, true);
	}
	
	/**
	 * Method  api
	 * 写入api类型日志
	 *
	 * @author
	 * @static
	 *
	 * @param $content
	 */
	public static function api($content) {
		self::$_file_template = Yaf_Registry::get('config')->application->logs . '/' . self::$_hour_file_template;
		self::write('api', $content, true);
		self::$_file_template = null;
	}
	
	/**
	 * Method  called
	 * 写入被调用接口日志
	 *
	 * @author
	 * @static
	 *
	 * @param $content
	 */
	public static function called($content) {
		self::$_file_template = Yaf_Registry::get('config')->application->logs . '/' . self::$_default_file_template;
		self::write('called', $content, true);
		self::$_file_template = null;
	}
	
	/**
	 * Method  post
	 * 写入post类型日志
	 *
	 * @author
	 * @static
	 *
	 * @param $content
	 */
	public static function post($content) {
		self::write('post', $content, true);
	}
	
	/**
	 * Method  mc
	 * 写入mc类型日志
	 *
	 * @author
	 * @static
	 *
	 * @param $content
	 */
	public static function mc($content) {
		self::write('mc', $content, true);
	}
	
	/**
	 * Method  redis
	 * 写入访问日志
	 *
	 * @author
	 * @static
	 *
	 * @param $content
	 */
	public static function access($content) {
		self::write('access', $content, true);
	}
	
	/**
	 * Method  redis
	 * 写入sql日志
	 *
	 * @author
	 * @static
	 *
	 * @param $content
	 */
	public static function sql($content) {
		self::write('sql', $content, true);
	}
	
	/**
	 * Method  write
	 * 写入日志
	 *
	 * @author
	 * @static
	 *
	 * @param string $type
	 * @param string $content
	 * @param bool   $is_self_call
	 */
	public static function write($type, $content, $is_self_call = true) {
		//验证所需变量
		if(empty(self::$_file_template) || empty(self::$_content_template) || empty(self::$_module_name)) {
			self::setConfig();
		}
		
		//过滤日志类型
		if(!in_array(strtolower($type), self::$_type_list)) {
			$type = self::$_default_type;
		}
		
		//获取back trace
		$backtrace_list = debug_backtrace();
		
		//验证是否为类内调用
		if(true === $is_self_call && isset($backtrace_list[1])) {
			//如果是类内调用, 取下标为1的元素
			$file = $backtrace_list[1]['file'];
			$line = $backtrace_list[1]['line'];
		} else {
			//如果非类内调用, 取下标为0的元素
			$file = $backtrace_list[0]['file'];
			$line = $backtrace_list[0]['line'];
		}
		
		//替换内容
		$search = array(
			'{content}',
			'{file}',
			'{line}'
		);
		
		$replace = array(
			$content,
			$file,
			$line
		);
		
		$content = self::_replaceTemplate($search, $replace, self::$_content_template);
		
		//替换文件
		$search = array(
			'{module}',
			'{type}'
		);
		
		$replace = array(
			self::$_module_name,
			$type
		);
		
		$file     = self::_replaceTemplate($search, $replace, self::$_file_template);
		$dir_name = pathinfo($file, PATHINFO_DIRNAME);
		
		if(!is_dir($dir_name)) {
			mkdir($dir_name, 0777, true);
		}
		self::_writeToFile($file, $content);
	}
	
	/**
	 * Method  _replaceTemplate
	 * 解析模板
	 *
	 * @static
	 * @return mixed
	 *
	 * @param $search
	 * @param $replace
	 * @param $template
	 */
	private static function _replaceTemplate($search, $replace, $template) {
		
		$template = preg_replace_callback('/{date}\((.*)\)/', function($matches) {
			$date_format = isset($matches[1]) ? $matches[1] : 'Y-m-d H:i:s';
			
			return date($date_format);
		}, $template);
		
		return str_replace($search, $replace, $template);
	}
	
	/**
	 * Method  _writeToFile
	 * 写入文件
	 *
	 * @author
	 * @static
	 *
	 * @param string $file
	 * @param string $content
	 * @param string $mode
	 *
	 * @return bool
	 */
	private static function _writeToFile($file, $content, $mode = 'a') {
		$handle = fopen($file, $mode);
		
		if(false === $handle) {
			return false;
		}
		
		$is_locked = flock($handle, LOCK_EX);
		
		$micro_start_time = microtime(true);
		
		do {
			if(false === $is_locked) {
				usleep(round(rand(0, 100) * 100));
			}
		} while(false === $is_locked && (microtime(true) - $micro_start_time) < 1000);
		
		if(true === $is_locked) {
			fwrite($handle, $content);
			
			flock($handle, LOCK_UN);
		}
		
		fclose($handle);
		
		return true;
	}
}