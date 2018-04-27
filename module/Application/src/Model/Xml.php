<?php
/**
 * Created by PhpStorm.
 * User: Lucas
 * Date: 26/04/2018
 * Time: 20:48
 */

namespace Application\Model;


use DOMDocument;

class Xml
{

    public function createXML($noeud = array()){
/*
 *
 *         $xml = new DOMDocument('1.0', 'utf-8');
        $root=$xml->createElement('name','value');
        $xml->appendChild($root);
        //$xml->appendChild($xml->createElement('nodename', 'nodevalue'));
        $xml->save("test.xml");
      //  $this->_response->setHeader('Content-Type', 'text/xml; charset=utf-8')->setBody($output);

 */


        $xml = new DOMDocument('1.0', 'utf-8');
        $root=$xml->createElement($noeud[0]->name);
        $xml->appendChild($root);

        $this->addElement($xml,$root,$noeud[0]->child);



        return $xml;

    }

    private function addElement(DOMDocument $xml,$root,$noeud = array()){
        foreach ($noeud as $n){
            $element = $xml->createElement($n->name);
            if(!empty($n->attribute)){
                $element->setAttribute($n->attribute['name'],$n->attribute['value']);
            }
            if(!empty($n->value)){
                $element->textContent=$n->value;
            }

            $root->appendChild($element);

            if(!empty($n->child)){
                $this->addElement($xml,$element,$n->child);
            }

        }
    }

}