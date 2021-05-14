 ------ -------------------------------------------------------------------- 
  Line   jbdump/class.jbdump.php                                             
 ------ -------------------------------------------------------------------- 
  689    Result of function var_dump (void) is used.                         
  1300   Undefined variable: $params                                         
  3603   Instantiated class JConfig not found.                               
         💡 Learn more at https://phpstan.org/user-guide/discovering-symbols  
 ------ -------------------------------------------------------------------- 

 ------ -------------------------------------------------------------------- 
  Line   jbdump/joomla/jbdump.php                                            
 ------ -------------------------------------------------------------------- 
         Reflection error: JPlugin not found.                                
         💡 Learn more at https://phpstan.org/user-guide/discovering-symbols  
 ------ -------------------------------------------------------------------- 

 ------ ------------------------------------------------------ 
  Line   jbdump/test/_ajaxtest.php                             
 ------ ------------------------------------------------------ 
  15     Class JBDump referenced with incorrect case: jbdump.  
  22     Class JBDump referenced with incorrect case: jbdump.  
  24     Class JBDump referenced with incorrect case: jbdump.  
 ------ ------------------------------------------------------ 

 ------ -------------------------------------------------------------------- 
  Line   jbdump/test/_benchmark.php                                          
 ------ -------------------------------------------------------------------- 
  21     Function GetPHPFilesMark not found.                                 
         💡 Learn more at https://phpstan.org/user-guide/discovering-symbols  
 ------ -------------------------------------------------------------------- 

 ------ ------------------------------------------------------ 
  Line   jbdump/test/_chain.php                                
 ------ ------------------------------------------------------ 
  22     Class JBDump referenced with incorrect case: jbdump.  
  25     Class JBDump referenced with incorrect case: jbdump.  
  28     Class JBDump referenced with incorrect case: jbdump.  
  31     Class JBDump referenced with incorrect case: jbdump.  
 ------ ------------------------------------------------------ 

 ------ ------------------------------------------------------ 
  Line   jbdump/test/included_file.php                         
 ------ ------------------------------------------------------ 
  203    Class JBDump referenced with incorrect case: jbdump.  
  235    Class JBDump referenced with incorrect case: jbdump.  
  273    Class JBDump referenced with incorrect case: jbdump.  
 ------ ------------------------------------------------------ 

 ------ --------------------------------------------- 
  Line   phpunit/src/CovCatcher.php                   
 ------ --------------------------------------------- 
  106    No error to ignore is reported on line 106.  
  112    No error to ignore is reported on line 112.  
  124    No error to ignore is reported on line 124.  
 ------ --------------------------------------------- 

 ------ ----------------------------------------------------------------------------------------------------- 
  Line   phpunit/src/functions/tools.php                                                                      
 ------ ----------------------------------------------------------------------------------------------------- 
  97     Access to constant GET on an unknown class JBZoo\HttpClient\Request.                                 
         💡 Learn more at https://phpstan.org/user-guide/discovering-symbols                                   
  97     Return typehint of function JBZoo\PHPUnit\httpRequest() has invalid type JBZoo\HttpClient\Response.  
 ------ ----------------------------------------------------------------------------------------------------- 

 [ERROR] Found 20 errors                                                                                                

