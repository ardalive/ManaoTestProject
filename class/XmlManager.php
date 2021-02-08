<?php
namespace app;
use DOMDocument;
class XmlManager
{
    public $doc, $filename;

    function __construct($filename){
        $this->filename = $filename;
        $this->doc = new DOMDocument("1.0", "UTF-8");
        $this->doc->preserveWhiteSpace = false;
        $this->doc->formatOutput = true;
        $this->doc->load($this->filename);
    }

    private function applyChanges(){
        $this->doc->saveXML($this->doc->documentElement);
        $this->doc->save($this->filename);
    }

    public function getNewUserNode($userCredentials){
        $salt = trim($this->getValuesByTagName('salt')[0]);
        $password = crypt($userCredentials['password'], $salt);
        $userCredentials['password'] = $password;

        $userNode = $this->doc->createElement('user');

        $this->appendNodeValue($userNode, 'login' ,$userCredentials['login']);
        $this->appendNodeValue($userNode, 'password' ,$userCredentials['password']);
        $this->appendNodeValue($userNode, 'email' ,$userCredentials['email']);
        $this->appendNodeValue($userNode, 'name' ,$userCredentials['name']);
        $this->appendNodeValue($userNode, 'token' ,'');
        $this->appendNodeValue($userNode, 'sid' ,'');

        return $userNode;
    }

    public function createUser($userCredentials){
        $users = $this->doc->documentElement;
        $id = $this->doc->getElementsByTagName('user')->count() + 1;

        $userNode = $users->appendChild($this->getNewUserNode($userCredentials));

        $userNode->setAttribute('id', $id);

        $this->applyChanges();
    }

    public function readUser($user){
        $userCredentials = $user->childNodes;
        $userCredentialsArray = array();
        foreach($userCredentials as $item){
            array_push($userCredentialsArray, $item->nodeValue);
        }
        return $userCredentialsArray;
    }

    public function deleteUser($user){
        $this->doc->removeChild($user);
        $this->applyChanges();
    }

    public function updateUser($user, $userCredentials){
        $this->doc->replaceChild($this->getNewUserNode($userCredentials), $user);
        $this->applyChanges();
    }

    public function getValuesByTagName($tagName){
        $values = array();
        $elements = $this->doc->getElementsByTagName($tagName);
        foreach ($elements as $element) {
            array_push($values, $element->nodeValue);
        }
        return $values;
    }

    public function appendNodeValue($parentNode, $nodeName, $value){
        $node = $parentNode->appendChild($this->doc->createElement($nodeName));
        $node->appendChild($this->doc->createTextNode($value));
    }

    public function generateToken($user){
        return md5(date('Ymd').$user->getElementsByTagName('login')->item(0)->nodeValue);
    }

    public function insertToken($user){
        $token = $this->generateToken($user);
        if($user->getElementsByTagName('token')->count() > 0){
            $this->removeToken($user);
        }
        $this->appendNodeValue($user, 'token', $token);
        $this->applyChanges();
        return $token;
    }

    public function insertSessionId($user, $sid){
        if($user->getElementsByTagName('sid')->count() > 0){
            $this->removeSessionId($user);
        }
        $this->appendNodeValue($user, 'sid', $sid);
        $this->applyChanges();
    }

    public function tokenIsValid($token){
        $tokenArray = array();
        foreach($this->doc->getElementsByTagName('token') as $token){
            array_push($tokenArray, $token);
        }
        return array_search($token, $tokenArray);
    }

    public function removeToken($user){
        $user->removeChild($user->getElementsByTagName('token')->item(0));
        $this->appendNodeValue($user, 'token' ,'');
        $this->applyChanges();
    }

    public function removeSessionId($user){
        $user->removeChild($user->getElementsByTagName('sid')->item(0));
        $this->appendNodeValue($user, 'sid' ,'');
        $this->applyChanges();
    }

    public function getUserByIndex($index){
        return $this->doc->getElementsByTagName('user')->item($index);
    }

    public function getUserIndexBySessionId($sid){
        $sidArray = array();
        foreach($this->doc->getElementsByTagName('sid') as $id){
            array_push($sidArray, trim($id->nodeValue));
        }
        return $this->getUserByIndex(array_search($sid, $sidArray));
    }
}