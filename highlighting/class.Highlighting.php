<?php
/**
 * Created by IntelliJ IDEA.
 * User: michael.risher
 * Date: 7/10/14
 * Time: 2:38 PM
 * To change this template use File | Settings | File Templates.
 */

class Highlighting {
	private static $printQuotes = false;

	/**
	 * takes assoc array in spits out pretty json
	 * @param $json
	 */
	static function renderJson( $json ){
		echo '<ul class="json">';
		array_walk( $json, 'Highlighting::renderValue' );
		echo '</ul>';
	}

	/**
	 * takes string in spits out pretty html
	 * @param $htmlString
	 */
	static function renderHtml( $htmlString ){
		$htmlArray = explode( "\n", $htmlString );
		echo '<ul class="html">';
		array_walk( $htmlArray, 'Highlighting::renderHtmlValue' );
		echo '</ul>';
	}

	/**
	 * takes string in spits out pretty html
	 * @param $cssString
	 */
	static function renderCss( $cssString ){
		$cssArray = explode( "\n", $cssString );
		echo '<ul class="css">';
		array_walk( $cssArray, 'Highlighting::renderCssValue' );
		echo '</ul>';
	}

	private static function renderCssValue( $val, $key ){
		$val = preg_replace( '/\t/', '', $val );
		$val = preg_replace( '/\n/', '<br>', $val );
		$val = preg_replace( '/"(.+?)"|\'(.+?)\'/', '<span class="string">$1$2</span>', $val );
		$val = preg_replace( '/(-?\d+|#\w{6}\b(?!\s*{|,)|#\w{3}\b(?!\s*{|,))/', '<span class="integer">$1</span>', $val );
		$val = preg_replace( '/([a-zA-Z-]+?)(:)/', '<span class="property">$1</span>$2', $val );
		$val = preg_replace( '/(\d+|#\w{6}\b(?!\s*{|,)|#\w{3}\b(?!\s*{|,))/', '<span class="integer">$1</span>', $val );
		$val = preg_replace( '/(!important)/', '<span class="cssImportant">$1</span>', $val );
		if ( preg_match( '/{/', $val ) ) {
			$arr = explode( '{', $val );
			$arr[0] = preg_replace('/<[^>]*>/', '', $arr[0] );
			$val = preg_replace( '/(#|\.)?(\w*\b)/', '$1<span class="tagSelector">$2</span>$3', $arr[0] ) . ' { ' . $arr[1];
			if ( preg_match( '/}/', $val ) ) {
				echo '<li><pre>' . $val . '</pre></li>';
			}
			else{
				echo '<li>' . Highlighting::expand() . '<pre>' . $val . '</pre><ul>';
			}
		}
		else if ( preg_match( '/}/', $val ) ) {
			echo '</ul><li><pre>' . $val . '</pre></li>';
		}
		else{
			echo '<li><pre>' . $val . '</pre></li>';
		}

	}

	private static function renderHtmlValue( $val, $key ){
		$val = htmlentities( $val );
		$val = preg_replace( '/(&lt;.+?&gt;)/', '<span class="htmlTag">$1</span>', $val );
		$val = preg_replace( '/(&quot;.+?&quot;)/', '<span class="attribute">$1</span>', $val );
		echo '<li><pre>' . $val . '</pre></li>';

	}

	private static function renderValue( $val, $key ){
		$keyType = gettype( $key );
		$type = gettype( $val );

		if ( $type == 'array' ) {
			echo '<li>' . Highlighting::expand() . '<b><span class="' . $keyType . '">' .$key.'</span></b>';

			//echo ' <i>{' .count( $val ) .'} </i> :';
			echo ' : {';
		}
		else{
			echo "<li><b><span class=\"$keyType\">$key</span></b>";
			echo ' : <span class="' . strtolower($type) . '">';
			if ( $type == 'boolean' ) {
				echo $val ? 'true' : 'false';
			}
			else{
				echo $type == 'NULL' ? 'null' :  $val;
			}
			echo '</span>';
		}

		if ( $type == 'array' ) {
			echo '<ul>';

			if ( count( $val ) ) {
				array_walk( $val, 'Highlighting::renderValue');
			} else {
				echo '<li><span class="empty">empty</span></li>';
			}

			echo '</ul>';
		}
	}

	private static function expand(){
		return '<img src="http://www.lavote.net/Images/icon_collapse.gif" class="expand minus"/>';
	}
}