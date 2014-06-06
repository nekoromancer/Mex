<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * Method EXtentions : MEX
 * www.nekoromancer.kr
 *
 * Author : Nekoromancer
 * Email : nekonitrate@gmail.com
 *
 * Released under the MIT license
 *
 * Date: 2014-06-01
 */

/*
 * Helper functions
 */

function encJson( $array = array() )
{
  echo json_encode( $array );
}

/*
 * Mex library class
 */

class Mex {
  private $CI;
  private $request;
  private $security;
  private $put = array();
  private $delete = array();

  public function __construct() 
  {
    global $SEC;
    $this->security =& $SEC;
    $this->CI = get_instance();

    header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE');
    header('charset=UTF-8');

    $this->request = $this->method();
    $this->arrayFromData( $this->request );
  }

  private function method()
  {
    return $_SERVER['REQUEST_METHOD'];
  }

  private function getPutData()
  {
    $putdata = fopen("php://input", "r");
    $result = '';
    
    while ( $data = fread($putdata, 1024) )
    {
      $result .= $data;
    }

    fclose($putdata);

    return $result;
  }

  private function arrayFromData( $method )
  {
    $string = $this->getPutData();

    if( empty( $string ) )
    {
      return false;
    }

    $data = explode('&', $string);
    $result = array();

    foreach( $data as $each )
    {
      $_temp = array();
      $_temp = explode('=', $each);
      $key = array_shift($_temp);
      $value = array_shift($_temp);
      $result[$key] = urldecode($value);
    }

    if( $method === 'PUT' ) 
    {
      $this->put = $result;
    }
    else if( $method === 'DELETE')
    {
      $this->delete = $result;
    }
  }

  private function xssFiltering( $data )
  {
    if( !is_array( $data ) )
    {
      $data = $this->security->xss_clean( $data );
    }
    else if( $data )
    {
      foreach( $this->delete as $key => $value )
      {
        $data[$key] = $this->security->xss_clean( $value );
      }
    }

    return $data;
  }

  public function request( $method, $callback, $xss_clean = false )
  {
    $method = strtolower($method);
    switch( $method )
    {
      default:
        $data = false;
      break;
      case '/' :
        $data = true;
      break;
      case 'get':
        $data = & $this->get(null, $xss_clean);
      break;

      case 'post':
        $data = & $this->post(null, $xss_clean);
      break;

      case 'put':
        $data = & $this->put(null, $xss_clean);
      break;

      case 'delete':
        $data = & $this->delete(null, $xss_clean);
      break;
    }

    if( !$data ) return;

    call_user_func( $callback, $data );
  }

  public function get( $index = null, $xss_clean = false )
  {
    return $this->CI->input->get( $index, $xss_clean );
  }

  public function post( $index = null, $xss_clean = false )
  {
    return $this->CI->input->post( $index, $xss_clean );
  }

  public function put( $index = null, $xss_clean = false )
  {
    $data = false;

    if( $index && isset( $this->put[$index] ) )
    {
      $data = & $this->put[$index];
    }
    else if( !$index && !empty( $this->put ) )
    {
      $data = & $this->put;
    }

    if( $xss_clean )
    {
      $data = $this->xssFiltering( $data );
    }

    return $data;
  }

  public function delete( $index = null, $xss_clean = false )
  {
    $data = false;

    if( $index && isset( $this->delete[$index] ) )
    {
      $data = & $this->delete[$index];
    }
    else if( !$index && !empty( $this->delete ) )
    {
      $data = & $this->delete;
    }

    if( $xss_clean )
    {
      $data = $this->xssFiltering( $data );
    }

    return $data;
  }
}
