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

class Mex {
  private $CI;
  private $request;
  private $put = array();

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

  public function request( $method, $callback )
  {
    switch( $method )
    {
      default:
        $data = false;
      break;

      case 'get':
        $data = $this->get();
      break;

      case 'post':
        $data = $this->post();
      break;

      case 'put':
        $data = $this->put();
      break;

      case 'delete':
        $data = $this->delete();
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
    if( $index === null && !empty( $this->put )  ) 
    {
      if( $xss_clean )
      {
        foreach( $this->put as $key => $value )
        {
          $this->put[$key] = $this->security->xss_clean( $value );
        }
      }
      return $this->put;
    }
    else if ( $index )
    {
      if( isset( $this->put[$index] ) )
      {
        if( $xss_clean )
        {
          return $this->security->xss_clean( $this->put[$index] );
        }
        return $this->put[$index];
      }
      else
      {
        return false;
      }
    }
    else
    {
      return false;
    }
  }

  public function delete( $index = null, $xss_clean = false )
  {
    if( $index === null && !empty( $this->delete )  ) 
    {
      if( $xss_clean )
      {
        foreach( $this->delete as $key => $value )
        {
          $this->delete[$key] = $this->security->xss_clean( $value );
        }
      }
      return $this->delete;
    }
    else if ( $index )
    {
      if( isset( $this->delete[$index] ) )
      {
        if( $xss_clean )
        {
          return $this->security->xss_clean( $this->delete[$index] );
        }
        return $this->delete[$index];
      }
      else
      {
        return false;
      }
    }
    else
    {
      return false;
    }
  }
}
