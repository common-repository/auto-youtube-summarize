<?php
define('AYS_DEBUG',false);

/**
 * DEBUG スクリプト
 */
function ays_debug_mylog( $message , $filename = 'debug.txt' ){
	if(AYS_DEBUG){
		if( !is_string($message) ){
			$message = print_r( $message , true );
		}
		$message = date_i18n('Y-m-d H:i:s') . "\t" . $message . "\n";
		$log_file = dirname(__FILE__) . '/' . $filename;
		$fp = fopen( $log_file , 'a' );
		fwrite( $fp , $message );
		fclose( $fp );
	}
}
/**
 * esc_htmlの配列対応版
 */
function esc_htmls( $str ) {
    if ( is_array( $str ) ) {
        return array_map( "esc_html", $str );
    }else {
        return esc_html( $str );
    }
}