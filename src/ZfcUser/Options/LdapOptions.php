<?php

namespace ZfcUser\Options;

use Zend\Stdlib\AbstractOptions;

class LdapOptions extends AbstractOptions {
    
    /**
     * Turn off strict options mode
     */
    protected $__strictMode__ = false;
    
    /**
     *@var array 
     */
    protected $servers = array(
        'server1' => array(
            /**
             * Required hostname or IP address of the LDAP server 
             */
            'host' => 'ldap.example.tld',
            /**
             * Optional port number
             **/
            //'port' => 1234,
            /**
             * Optional username and password to connect to the server
             */
            //'username' => '',
            //'password' => '',
            /**
             * Optional to use encription or not default value is false
             */
            //'useStartTls' => true,
            /**
             * Required The FQDN domain name for which the target LDAP server is an authority
             */
            'accountDomainName' => 'example.tld',
            /**
             * Optional  The 'short' domain for which the target LDAP server is an authority 
             */
            //'accountDomainNameShort' => 'EXAMPLE',
            /**
             * Optional Indicates the form to which account names should be 
             * canonicalized after successful authentication, the default value is 4
             * This could be: 
             *      2 => username; 
             *      3 => DOMAIN\username; 
             *      4 => username@domain
             */
            //'accountCanonicalForm' => 4, 
            /**
             * Optional
             */
            //'accountFilterFormat' => The LDAP search filter used to search for accounts, default value is '(&(objectClass=user)(sAMAccountName=%s))'
            /**
             * Required The DN under which all accounts being authenticated are located. 
             */
            'baseDn' => 'dc=example,dc=tld'
        )        
    );
    
    public function setServers($servers) {
        $this->servers = $servers;
    }
    
    public function getServers() {
        return $this->servers;
    }
    
}

?>
