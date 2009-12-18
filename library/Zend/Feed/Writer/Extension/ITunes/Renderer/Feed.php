<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Feed_Writer
 * @copyright  Copyright (c) 2005-2009 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */
 
/**
 * @see Zend_Feed_Writer_Extension_RendererAbstract
 */
require_once 'Zend/Feed/Writer/Extension/RendererAbstract.php';
 
/**
 * @category   Zend
 * @package    Zend_Feed_Writer
 * @copyright  Copyright (c) 2005-2009 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Feed_Writer_Extension_ITunes_Renderer_Feed
    extends Zend_Feed_Writer_Extension_RendererAbstract
{
    /**
     * Render feed
     * 
     * @return void
     */
    public function render()
    {
        $this->_appendNamespaces();
        $this->_setAuthors($this->_dom, $this->_base);
        $this->_setBlock($this->_dom, $this->_base);
        $this->_setCategories($this->_dom, $this->_base);
        $this->_setImage($this->_dom, $this->_base);
        $this->_setDuration($this->_dom, $this->_base);
        $this->_setExplicit($this->_dom, $this->_base);
        $this->_setKeywords($this->_dom, $this->_base);
        $this->_setNewFeedUrl($this->_dom, $this->_base);
        $this->_setOwners($this->_dom, $this->_base);
        $this->_setSubtitle($this->_dom, $this->_base);
        $this->_setSummary($this->_dom, $this->_base);
    }
    
    /**
     * Append feed namespaces
     * 
     * @return void
     */
    protected function _appendNamespaces()
    {
        $this->getRootElement()->setAttribute('xmlns:itunes',
            'http://www.itunes.com/dtds/podcast-1.0.dtd');  
    }

    /**
     * Set feed authors
     * 
     * @param  DOMDocument $dom 
     * @param  DOMElement $root 
     * @return void
     */
    protected function _setAuthors(DOMDocument $dom, DOMElement $root)
    {
        $authors = $this->getDataContainer()->getItunesAuthors();
        if (!$authors || empty($authors)) {
            return;
        }
        foreach ($authors as $author) {
            $el = $dom->createElement('itunes:author');
            $el->nodeValue = Zend_Feed_Writer::xmlentities(
                $author, $this->getEncoding()
            );
            $root->appendChild($el);
        }
    }
    
    /**
     * Set feed itunes block
     * 
     * @param  DOMDocument $dom 
     * @param  DOMElement $root 
     * @return void
     */
    protected function _setBlock(DOMDocument $dom, DOMElement $root)
    {
        $block = $this->getDataContainer()->getItunesBlock();
        if (is_null($block)) {
            return;
        }
        $el = $dom->createElement('itunes:block');
        $el->nodeValue = $block;
        $root->appendChild($el);
    }
    
    /**
     * Set feed categories
     * 
     * @param  DOMDocument $dom 
     * @param  DOMElement $root 
     * @return void
     */
    protected function _setCategories(DOMDocument $dom, DOMElement $root)
    {
        $cats = $this->getDataContainer()->getItunesCategories();
        if (!$cats || empty($cats)) {
            return;
        }
        foreach ($cats as $key=>$cat) {
            if (!is_array($cat)) {
                $el = $dom->createElement('itunes:category');
                $el->setAttribute('text', Zend_Feed_Writer::xmlentities(
                    $cat, $this->getEncoding()
                ));
                $root->appendChild($el);
            } else {
                $el = $dom->createElement('itunes:category');
                $el->setAttribute('text', Zend_Feed_Writer::xmlentities(
                    $key, $this->getEncoding()
                ));
                $root->appendChild($el);
                foreach ($cat as $subcat) {
                    $el2 = $dom->createElement('itunes:category');
                    $el2->setAttribute('text', Zend_Feed_Writer::xmlentities(
                        $subcat, $this->getEncoding()
                    ));
                    $el->appendChild($el2);
                }
            }
        }
    }
    
    /**
     * Set feed image (icon)
     * 
     * @param  DOMDocument $dom 
     * @param  DOMElement $root 
     * @return void
     */
    protected function _setImage(DOMDocument $dom, DOMElement $root)
    {
        $image = $this->getDataContainer()->getItunesImage();
        if (!$image) {
            return;
        }
        $el = $dom->createElement('itunes:image');
        $el->setAttribute('href', $image);
        $root->appendChild($el);
    }
    
    /**
     * Set feed cumulative duration
     * 
     * @param  DOMDocument $dom 
     * @param  DOMElement $root 
     * @return void
     */
    protected function _setDuration(DOMDocument $dom, DOMElement $root)
    {
        $duration = $this->getDataContainer()->getItunesDuration();
        if (!$duration) {
            return;
        }
        $el = $dom->createElement('itunes:duration');
        $el->nodeValue = $duration;
        $root->appendChild($el);
    }
    
    /**
     * Set explicit flag
     * 
     * @param  DOMDocument $dom 
     * @param  DOMElement $root 
     * @return void
     */
    protected function _setExplicit(DOMDocument $dom, DOMElement $root)
    {
        $explicit = $this->getDataContainer()->getItunesExplicit();
        if (is_null($explicit)) {
            return;
        }
        $el = $dom->createElement('itunes:explicit');
        $el->nodeValue = $explicit;
        $root->appendChild($el);
    }
    
    /**
     * Set feed keywords
     * 
     * @param  DOMDocument $dom 
     * @param  DOMElement $root 
     * @return void
     */
    protected function _setKeywords(DOMDocument $dom, DOMElement $root)
    {
        $keywords = $this->getDataContainer()->getItunesKeywords();
        if (!$keywords || empty($keywords)) {
            return;
        }
        $el = $dom->createElement('itunes:keywords');
        $el->nodeValue = Zend_Feed_Writer::xmlentities(
            implode(',', $keywords), $this->getEncoding()
        );
        $root->appendChild($el);
    }
    
    /**
     * Set feed's new URL
     * 
     * @param  DOMDocument $dom 
     * @param  DOMElement $root 
     * @return void
     */
    protected function _setNewFeedUrl(DOMDocument $dom, DOMElement $root)
    {
        $url = $this->getDataContainer()->getItunesNewFeedUrl();
        if (!$url) {
            return;
        }
        $el = $dom->createElement('itunes:new-feed-url');
        $el->nodeValue = Zend_Feed_Writer::xmlentities(
            $url, $this->getEncoding()
        );
        $root->appendChild($el);
    }
    
    /**
     * Set feed owners 
     * 
     * @param  DOMDocument $dom 
     * @param  DOMElement $root 
     * @return void
     */
    protected function _setOwners(DOMDocument $dom, DOMElement $root)
    {
        $owners = $this->getDataContainer()->getItunesOwners();
        if (!$owners || empty($owners)) {
            return;
        }
        foreach ($owners as $owner) {
            $el = $dom->createElement('itunes:owner');
            $name = $dom->createElement('itunes:name');
            $name->nodeValue = Zend_Feed_Writer::xmlentities(
                $owner['name'], $this->getEncoding()
            );
            $email = $dom->createElement('itunes:email');
            $email->nodeValue = Zend_Feed_Writer::xmlentities(
                $owner['email'], $this->getEncoding()
            );
            $root->appendChild($el);
            $el->appendChild($name);
            $el->appendChild($email);
        }
    }
    
    /**
     * Set feed subtitle
     * 
     * @param  DOMDocument $dom 
     * @param  DOMElement $root 
     * @return void
     */
    protected function _setSubtitle(DOMDocument $dom, DOMElement $root)
    {
        $subtitle = $this->getDataContainer()->getItunesSubtitle();
        if (!$subtitle) {
            return;
        }
        $el = $dom->createElement('itunes:subtitle');
        $el->nodeValue = Zend_Feed_Writer::xmlentities(
            $subtitle, $this->getEncoding()
        );
        $root->appendChild($el);
    }
    
    /**
     * Set feed summary
     * 
     * @param  DOMDocument $dom 
     * @param  DOMElement $root 
     * @return void
     */
    protected function _setSummary(DOMDocument $dom, DOMElement $root)
    {
        $summary = $this->getDataContainer()->getItunesSummary();
        if (!$summary) {
            return;
        }
        $el = $dom->createElement('itunes:summary');
        $el->nodeValue = Zend_Feed_Writer::xmlentities(
            $summary, $this->getEncoding()
        );
        $root->appendChild($el);
    }
}