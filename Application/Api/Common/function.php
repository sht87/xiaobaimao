<?php
  /**
   * @功能说明:验证包名和版本号
   * @return string
   */
  function common_package($client,$package,$ver){
      $Common_Package = F('Common_Package');

      if($Common_Package[$client][$ver] <> $package){
           $result = array('result'=>0,'msg'=>'未查找到想对应的包名和版本号');
      }else{
          $result = array('result'=>1,'msg'=>'success');
      }
      return $result;
  }

  function get_login_info($key){
      $para=get_json_data(); //接收参数
      $AppInfo=\XBCommon\XBCache::GetCache($para['token']);
      if(!empty($AppInfo)){
          if(!empty($key)){
              return $AppInfo[$key];
          }else{
              return $AppInfo;
          }
      }else{
          return null;
      }
  }
  
	


