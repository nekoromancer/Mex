# Mex

Mex는 PHP 프레임워크인 Codeigniter용 라이브러리입니다.  Mex 라이브러리를 사용하여 CI에서 AJAX 통신시 PUT과 DELETE 메소드를 손쉽게 사용할 수 있습니다.

기본적인 사용법은 CI의 내장 라이브러리인 Input과 동일합니다.

## 0. 설치
application/library 디렉토리에 Mex.php를 복사합니다.

## 1. 라이브러리 호출

먼저 사용을 위해 라이브러리를 호출합니다.

```php
$this->load->library('mex');
```

## 2. 메서드

Mex의 메서드는 put()과 delete()가 존재합니다.  사용법은 CI 내장 라이브러리 Input의 메서드인 get()과 post()와 동일합니다.

### a. put( $index, $xss_clean )
PUT 메서드로 전송된 데이터를 호출합니다.  아무런 매개변수 없이 사용하면 PUT으로 전송된 모든 데이터를 Array로 반환할 것입니다.  PUT으로 전송된 데이터가 없다면 false 를 반환합니다.

```php
$result = $this->mex->put();
```

Input의 get()과 post()와 마찬가지로 첫번째 매개변수를 통해 특정한 데이터에 접근할 수 있습니다.  예를들어 { name : 'neko', race : 'cat' } 이라는 데이터가 put으로 전송되었을 때 다음과 같은 방법을 통해 name의 값만을 가져올 수 있습니다.

```php
$name = $this->mex->put('name');
```

역시 get(), post()와 마찬가지로 두번째 매개변수를 통해 전송된 데이터를 xss_clean 할 수 있습니다.  기본값은 false입니다.

```php
$name = $this->mex->put('name', true);
```

### b. delete( $index, $xss_clean )
DELETE 메서드로 전송된 데이터를 호출합니다.  사용방법은 put()과 동일합니다.

### c. get(), post()
각각 GET 메서드와 POST 메서드로 전송된 데이터를 호출합니다.  CI 내장 Input의 get()과 post() 호출하여 그대로 사용하기 때문에 사용방법이나 기능상의 차이는 없습니다.

### d. requset( $method, $callback_function, $xss_clean )
$method에 대한 호출이 있을 때 사용자가 정의한 Callback 함수를 호출합니다.  Callback 함수에는 첫번째 매개변수로 전송된 데이터가 배열 형태로 전달됩니다.

세번째 매개변수인 $xss_clean의 기본값은 false 입니다. true로 지정하면 반환되는 값을 xss 필터링하여 반환합니다.

```php
$this->mex->request('get', function( $req ) {
  echo json_encode( $req );
}, true );
```

위의 코드는 GET으로 데이터가 전송되었을 때 전송된 데이터를 xss 필터링 후 json 형식으로 인코딩하여 보여줄 것입니다. POST, PUT, DELETE로 같은 형태로 사용하실 수 있습니다.

$method 를 / 로 지정하면 아무런 리퀘스트가 없었을 때의 동작을 지정할 수 있습니다.

```php
$this->mex->request('/', function(){
  $this->load->view('default');
});
```
## 4. 헬퍼(Helper) 함수
### a. encJson()
encJson()은 echo json_encode(); 의 축약형입니다.

```php
$this->mex->request('get', function( $req ) {
  encJson( $req );
  // echo json_encode( $req ); 와 동일합니다.
});
```
