# Mex
===

Mex는 PHP 프레임워크인 Codeigniter용 라이브러리입니다.  Mex 라이브러리를 사용하여 CI에서 AJAX 통신시 PUT과 DELETE 메소드를 손쉽게 사용할 수 있습니다.

기본적인 사용법은 CI의 내장 라이브러리인 Input과 동일합니다.

## 0. 설치
application/library 디렉토리에 Mex.php를 복사합니다.

## 1. 라이브러리 호출

먼저 사용을 위해 라이브러리를 호출합니다.

'''
$this->load->library('mex');
'''

## 2. 메서드

Mex의 메서드는 put()과 delete()가 존재합니다.  사용법은 CI 내장 라이브러리 Input의 메서드인 get()과 post()와 동일합니다.

### a. put()
PUT 메서드로 전송된 데이터를 호출합니다.  아무런 매개변수 없이 사용하면 PUT으로 전송된 모든 데이터를 Array로 반환할 것입니다.  PUT으로 전송된 데이터가 없다면 false 를 반환합니다.

'''
$result = $this->mex->put();
'''

Input의 get()과 post()와 마찬가지로 첫번째 매개변수를 통해 특정한 데이터에 접근할 수 있습니다.  예를들어 { name : 'neko', race : 'cat' } 이라는 데이터가 put으로 전송되었을 때 다음과 같은 방법을 통해 name의 값만을 가져올 수 있습니다.

'''
$name = $this->mex->put('name');
'''

역시 get(), post()와 마찬가지로 두번째 매개변수를 통해 전송된 데이터를 xss_clean 할 수 있습니다.  기본값은 false입니다.

'''
$name = $this->mex->put('name', true);
'''

### b. delete()
DELETE 메서드로 전송된 데이터를 호출합니다.  사용방법은 put()과 동일합니다.
