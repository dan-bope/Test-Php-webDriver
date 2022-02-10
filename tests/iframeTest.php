<?php

namespace iframeTest;

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\WebDriverWait;
use PHPUnit\Framework\TestCase;



$serverUrl = 'http://localhost:4444';
$url = 'https://reetags.com/dashboard/iframeTest.html';

class iframeTest extends TestCase{
    public function start()
    {
        $capabilities = DesiredCapabilities::chrome();
        $webDriver = RemoteWebDriver::create($GLOBALS['serverUrl'], $capabilities);
        return $webDriver;

    }

    public function testiframeTitre($webDriver = null)
    {
        if(is_null($webDriver)){
            $start = $this->start();
            $webDriver = $start->get($GLOBALS['url']);
            // Find an iframe element
            $iframe = $webDriver->findElement(WebDriverBy::id('reetags_iframe'));
            // Switch to iframe inside this element
            $sessionIframe = $webDriver->switchTo()->frame($iframe);
            // Get the value of a body element contained in the iframe
            $classBodyIframe = $sessionIframe->findElement(WebDriverBy::className('body'));
            // Get the value of a div element contained in the body which in turn is contained in the iframe
            $idDivClassBodyIframe = $classBodyIframe->findElement(WebDriverBy::id('content'));
            // Get the value of an h3 element contained in the div which in turn is contained in the body which is also contained in the iframe.
            $titreIframe = $idDivClassBodyIframe->findElement(WebDriverBy::className('title'));
            // get the text(title) of the iframe 
            $result = array('title' => $titreIframe->getText());
            // Go back to the main document
            $webDriver->switchTo()->defaultContent();
            
            // end the session and close the browser
            $webDriver->quit();
            // we check if the iframe has the title.
            if(empty($result['title'])){
                // if the iframe does not have a title.
                $this->assertContains('', $result, 'the iframe does not have a title.');
            } else{
                // if the iframe has a title.
                $this->assertContains('All Tigers', $result, "the iframe does have a title");
            }
        }
        
    }

    public function testiframeVideo($webDriver = null)
    {
        if(is_null($webDriver)){
            $start = $this->start();
            $webDriver = $start->get($GLOBALS['url']);
            // we stock the type of video extension that the iframe has.
            $videoExtension = array('find' => '.gif');
            $postImgVideo = array();
            $result = array();
            // Find an iframe element
            $iframe = $webDriver->findElement(WebDriverBy::id('reetags_iframe'));
            // Switch to iframe inside this element
            $sessionIframe = $webDriver->switchTo()->frame($iframe);
            // Get the value of a body element contained in the iframe
            $classBodyIframe = $sessionIframe->findElement(WebDriverBy::className('body'));
            // Get the value of a div element contained in the body which in turn is contained in the iframe
            $idDivClassBodyIframe = $classBodyIframe->findElement(WebDriverBy::id('content'));
            // selects the div that contains all the divs containing all the videos
            $postsIframe = $idDivClassBodyIframe->findElement(WebDriverBy::className('posts-container'));

            $post = $postsIframe->findElement(WebDriverBy::className('post'));
        
            $postImg = $post->findElement(WebDriverBy::className('post-img'));
            // We get the link of the video contained in the src attribute of the img element
            $postImgVideo = array('src' => $postImg->getAttribute('src'));
            // Go back to the main document
            $webDriver->switchTo()->defaultContent();
            // end the session and close the browser
            $webDriver->quit();
            // we check if the iframe has the video.
            if(empty($postImgVideo['src'])){
                // if the iframe does not have a video.
                $this->assertContains('', $postImgVideo, 'the iframe does not have a video.');
            } else{
                // The extension of the video is retrieved.
                $findVideoExtension = strstr($postImgVideo['src'], $videoExtension['find']);
                $result = array('extensionVideo' => $findVideoExtension);
                // if the iframe has a video.
                $this->assertContains('.gif', $result, "the iframe does have a video");
            }
        }
    }

    public function testIframeProduitEnMiniature($webDriver = null)
    {
        if(is_null($webDriver)){
            $start = $this->start();
            $webDriver = $start->get($GLOBALS['url']);
            // we stock the type of video extension that the iframe has.
            $videoExtension = array('find_jpg' => '.jpg', 'find_png' => '.png');
            $postImgVideo = array();
            // Find an iframe element
            $iframe = $webDriver->findElement(WebDriverBy::id('reetags_iframe'));
            // Switch to iframe inside this element
            $sessionIframe = $webDriver->switchTo()->frame($iframe);
            // Get the value of a body element contained in the iframe
            $classBodyIframe = $sessionIframe->findElement(WebDriverBy::className('body'));
            // Get the value of a div element contained in the body which in turn is contained in the iframe
            $idDivClassBodyIframe = $classBodyIframe->findElement(WebDriverBy::id('content'));
            // selects the div that contains all the divs containing all the videos
            $postsIframe = $idDivClassBodyIframe->findElement(WebDriverBy::className('posts-container'));

            $post = $postsIframe->findElement(WebDriverBy::className('post'));
        
            $postImg = $post->findElement(WebDriverBy::className('post-overlay'));

            $postProductContainerImg = $postImg->findElement(WebDriverBy::className('products-container'));

            $productImgMiniature = $postProductContainerImg->findElement(WebDriverBy::className('product-img'));
            // We get the link of the video contained in the src attribute of the img element
            $postImgVideo = array('src' => $productImgMiniature->getAttribute('src'));
            
            $isProductImgMiniatureJpg = substr_count($postImgVideo['src'], $videoExtension['find_jpg']);

            $isProductImgMiniaturePng = substr_count($postImgVideo['src'], $videoExtension['find_png']);

            // Go back to the main document
            $webDriver->switchTo()->defaultContent();
            // end the session and close the browser
            $webDriver->quit();

            if($isProductImgMiniatureJpg == 0 ){
                // We check if the iframe does not have the image in .jpg format as a miniature.
                $this->assertEquals(0, $isProductImgMiniatureJpg, ' the iframe does not have miniature products');
            } else{
                // We check if the iframe has the image in .jpg format as a miniature.
                $this->assertEquals(1, $isProductImgMiniatureJpg, ' the iframe has many products in miniature');
            }

            if($isProductImgMiniaturePng == 0){
                // We check if the iframe does not have the image in .png format as a miniature.
                $this->assertEquals(0, $isProductImgMiniaturePng, ' the iframe does not have miniature products');
            } else{
                // We check if the iframe has the image in .png format as a miniature.
                $this->assertEquals(1, $isProductImgMiniaturePng, ' the iframe has many products in miniature');
            }
        }
        
    }

    public function testiframeClick($webDriver = null)
    {
        if(is_null($webDriver)){
            $start = $this->start();
            $webDriver = $start->get($GLOBALS['url']);
            // Find an iframe element
            $iframe = $webDriver->findElement(WebDriverBy::id('reetags_iframe'));
            $iframe->click();
            // After clicking on the iframe we get the attribute.
            // Depending on the value of the attribute you can tell if the iframe is clicked or not.
            $getAttributIfIsCliked = $webDriver->findElement(WebDriverBy::id('reetags_post_details_overlay'))->getAttribute('draggable');
            // end the session and close the browser
            $webDriver->quit();
            // Indicates an error if the expected result after clicking on the iframe does not have the expected type and value. 
            $this->assertSame('false', $getAttributIfIsCliked);
        }
        
    }

    public function testiframeClickVideoSoundAppears($webDriver = null)
    {
        if(is_null($webDriver)){
        $start = $this->start();
            $webDriver = $start->get($GLOBALS['url']);
            // Find an iframe element
            $iframe = $webDriver->findElement(WebDriverBy::id('reetags_iframe'));
            $iframe->click();
            // After clicking on the iframe we get the attribute muted.
            // We get the value of the muted which returns a boolean true if the sound appears.
            $getValueSoundAfterClick = $webDriver->executeScript("return document.getElementById('reetags_video').muted");
            // end the session and close the browser
            $webDriver->quit();
            // Indicates an error if the expected result after clicking on the iframe does not have the expected type and value. 
            $this->assertTrue($getValueSoundAfterClick);
        }
        
    }

    
    public function testiframeClickInformationBar($webDriver = null)
    {
        if(is_null($webDriver)){
            $start = $this->start();
            $webDriver = $start->get($GLOBALS['url']);
            // Find an iframe element
            $iframe = $webDriver->findElement(WebDriverBy::id('reetags_iframe'));
            $sessionIframe = $webDriver->switchTo()->frame($iframe);
            // Switch to iframe inside this element
            $clickIframe = $sessionIframe->findElement(WebDriverBy::className('button'))->click();
            // if a button in the iframe is clicked.
            if($clickIframe){
                // Go back to the main document
                $webDriver->switchTo()->defaultContent();
                // We wait for the targeted page to be loaded.
                $webDriver->wait()->until(
                    WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::className('reetagsRightIconInformationFullScreen'))
                );
                // Once the page is loaded, we get the class of the information bar which is used to print the information of product on the iframe.
                $informationTitle = $webDriver->findElement(WebDriverBy::className('reetagsRightIconInformationFullScreen'));
                // We check if the information bar we have selected is visible on the loaded page.
                if ($informationTitle->isDisplayed()) {
                    // If the information bar you are looking for exists, click on it. 
                    $informationTitleClick = $informationTitle->click();
                    if($informationTitleClick){
                        // Information about the clicked product is recuperated.
                        $afterClickedInformationTitle = $webDriver->findElement(WebDriverBy::className('reetagsRightTitleFullScreen'))->getText();
                    }
                }
            } 
            // end the session and close the browser
            $webDriver->quit();
            $this->assertIsString($afterClickedInformationTitle);
        }
    }

    
    // Y revenir
    public function testiframeDemuteSmallScreen($webDriver = null)
    {
            $onlyFunction = false;
            if(is_null($webDriver)){
                $onlyFunction  = true;
                $start = $this->start();
                $webDriver = $start->get($GLOBALS['url']);
            }
            // Find an iframe element
            $iframe = $webDriver->findElement(WebDriverBy::id('reetags_iframe'));
            $sessionIframe = $webDriver->switchTo()->frame($iframe);
            // Switch to iframe inside this element
            $clickIframe = $sessionIframe->findElement(WebDriverBy::className('button'))->click();
            // if a button in the iframe is clicked.
            if($clickIframe){
                    // Go back to the main document
                    $webDriver->switchTo()->defaultContent();
                    // We wait for the targeted page to be loaded.
                    $webDriver->wait()->until(
                            WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::className('reetagsCloseButtonFullScreen'))
                        );
                        // Once the page is loaded, we get the class of the cross which is used to close the selected video on the iframe.
                        $crossButton = $webDriver->findElement(WebDriverBy::className('reetagsRightCloseFullScreen'));
                        // We check if the item we have selected is visible on the loaded page.
                        if ($crossButton->isDisplayed()) {
                                // If the item (the cross for closing a video) you are looking for exists, click on it. 
                                $crossButtonClick = $crossButton->click();
                                if($crossButtonClick){
                                        // We wait for the targeted page to be loaded.
                                        $webDriver->wait()->until(
                                                WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::className('reetags_post_details_overlay'))
                    );
    
    
                    // The value of the cross is recovered when you switch to the small screen.
                    $demuteButtonSmallScreen = $webDriver->findElement(WebDriverBy::cssSelector('.post_detail_container.reetagsPlayerPostContainer'));
                    // The event is triggered by hovering the mouse over the small screen.
                    //$crossButtonSmallScreenAfterMouseOver = $webDriver->getMouse()->mouseMove($demuteButtonSmallScreen->getCoordinates());
                    // Click on the cross that appears after hovering the mouse on the small screen.
                    // $webDriver->manage()->timeouts()->implicitlyWait(10);
                    //     $webDriver->manage()->timeouts()->implicitlyWait(10);
                    //     $webDriver->manage()->timeouts()->implicitlyWait(10);
                    //     $webDriver->manage()->timeouts()->implicitlyWait(100000);
                    //$demuteButtonSmallScreenClick = $demuteButtonSmallScreen->click();
                    $demuteButtonSmallScreenClick = $demuteButtonSmallScreen->click();
                    // if the click does not produce an error, then :
                    if($demuteButtonSmallScreenClick){
        
                            // We get the value of the attribute that allows us to see that the small screen has been closed.
                            //$smallScreenClosed = $webDriver->findElement(WebDriverBy::id('reetags_post_details_overlay'))->getAttribute('draggable');
                            // If we can click then we get the value of the sound.
                            $smallScreenDemuted = $webDriver->executeScript("return document.getElementById('reetags_video').muted");
                            
                        }
                    }
                }
            } 
        
            if($onlyFunction){
                // end the session and close the browser
                $webDriver->quit();
            }
            // Indicates an error if the expected result after clicking on the iframe does not have the expected type and value. 
            //$this->assertSame('false', $smallScreenClosed);
            // Indicates an error if the expected result after clicking on the iframe does not have the expected type and value. 
            $this->assertTrue($smallScreenDemuted);
        
        }
        
        
        public function testiframeClickProductRedirection($webDriver = null)
        {
            $onlyFunction = false;
            if(is_null($webDriver)){
                $onlyFunction  = true;
                $start = $this->start();
                $webDriver = $start->get($GLOBALS['url']);
            }
            // Find an iframe element
            $iframe = $webDriver->findElement(WebDriverBy::id('reetags_iframe'));
            $sessionIframe = $webDriver->switchTo()->frame($iframe);
            // Switch to iframe inside this element
            $clickIframe = $sessionIframe->findElement(WebDriverBy::className('button'))->click();
            // if a button in the iframe is clicked.
            if($clickIframe){
                    // Go back to the main document
                    $webDriver->switchTo()->defaultContent();
                    // We wait for the targeted page to be loaded.
                    $webDriver->wait()->until(
                            WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::className('reetagsProductFullScreen'))
                        );
                        // Get current window handles first(Before clicking on the product).
                        $windowHandlesBefore = $webDriver->getWindowHandles();
                        // we get the url of the current window
                        $urlBefore = $webDriver->getCurrentURL();
                        // Once the page is loaded, we get the class of product.
                        $product = $webDriver->findElement(WebDriverBy::className('reetagsProductFullScreen'));
                        // We check if the product we have selected is visible on the loaded page.
                        if ($product->isDisplayed()) {
                    // If the product are looking for exists, click on it. 
                    $productClick = $product->click();
                    if($productClick){
                            // Then we get the list of new window handles and search for the new window.
                            $windowHandlesAfter = $webDriver->getWindowHandles();
                            // We get the new window.
                            $newWindowRedirect = array_diff($windowHandlesAfter, $windowHandlesBefore);
                            // We switch to the new window.
                            $webDriver->switchTo()->window(reset($newWindowRedirect));
                            // we get the url of the new current window.
                            $urlAfter = $webDriver->getCurrentURL();
                    }
                }
            } 
            
            if($onlyFunction){
                // end the session and close the browser
                $webDriver->quit();
            }
            // Indicates an error if the two variables containing the equal urls.
            $this->assertNotEquals($urlBefore, $urlAfter);
        }
        
        public function testiframeClickCloseVideoSoundStaySmallScreen($webDriver = null)
        {
            //$onlyFunction = false;
            if(is_null($webDriver)){
                //$onlyFunction  = true;
                $start = $this->start();
                $webDriver = $start->get($GLOBALS['url']);
                // Find an iframe element
                $iframe = $webDriver->findElement(WebDriverBy::id('reetags_iframe'));
                $sessionIframe = $webDriver->switchTo()->frame($iframe);
                // Switch to iframe inside this element
                $clickIframe = $sessionIframe->findElement(WebDriverBy::className('button'))->click();
                // if a button in the iframe is clicked.
                if($clickIframe){
                    // Go back to the main document
                    $webDriver->switchTo()->defaultContent();
                    // We wait for the targeted page to be loaded.
                    $webDriver->wait()->until(
                        WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::className('reetagsCloseButtonFullScreen'))
                    );
                    // Once the page is loaded, we get the class of the cross which is used to close the selected video on the iframe.
                    $crossButton = $webDriver->findElement(WebDriverBy::className('reetagsRightCloseFullScreen'));
                    // We check if the item we have selected is visible on the loaded page.
                    if ($crossButton->isDisplayed()) {
                        // If the item (the cross for closing a video) you are looking for exists, click on it. 
                        $crossButtonClick = $crossButton->click();
                        if($crossButtonClick){
                            // If we can click then we get the value of the sound.
                            $getValueSoundAfterClick = $webDriver->executeScript("return document.getElementById('reetags_video').muted");
                        }
                    }
                } 
                //if($onlyFunction){
                    // end the session and close the browser
                    $webDriver->quit();
                //}
                // Indicates an error if the expected result after clicking on the iframe does not have the expected type and value. 
                $this->assertTrue($getValueSoundAfterClick);
            }
            
            
        }


        public function testiframeClickArrowsLeftWorkWell($webDriver = null)
        {
            
            $onlyFunction = false;
            if(is_null($webDriver)){
                $onlyFunction  = true;
                $start = $this->start();
                $webDriver = $start->get($GLOBALS['url']);
                // Find an iframe element
                $iframe = $webDriver->findElement(WebDriverBy::id('reetags_iframe'));
                $sessionIframe = $webDriver->switchTo()->frame($iframe);
                // Switch to iframe inside this element
                $clickIframe = $sessionIframe->findElement(WebDriverBy::className('button'))->click();
                // if a button in the iframe is clicked.
                if($clickIframe){
                    // Go back to the main document
                    $webDriver->switchTo()->defaultContent();
                    // We wait for the targeted page to be loaded.
                    $webDriver->wait()->until(
                        WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::className('reetagsSvgArrow'))
                    );
                    // Nous recupérons la position de la prémière video avant le click sur la fleche.
                    $locationVideoArrowsBeforeClick = $webDriver->executeScript('return last_iterator'); 
                    // Once the page is loaded, we get the class of arrow.
                    $arrows = $webDriver->findElement(WebDriverBy::className('reetagsSvgArrow'));
                    // We check if the arrow we have selected is visible on the loaded page.
                    if ($arrows->isDisplayed()) {
                        // If the product are looking for exists, click on it. 
                        $arrowsClick = $arrows->click();
                        if($arrowsClick){
                            // Then we get the list of new window handles and search for the new window.
                            $locationVideoArrowsAfterClick = $webDriver->executeScript('return last_iterator'); 
                        }
                    }
                } 
    
                //if($onlyFunction){
                    // end the session and close the browser
                    $webDriver->quit();
                //}
                // Indicates an error if the two variables containing the equal location of arrow.
                $this->assertNotEquals($locationVideoArrowsBeforeClick, $locationVideoArrowsAfterClick);
            }
    }
    
    public function testiframeClickArrowsRightWorkWell($webDriver = null)
    {
        $onlyFunction = false;
        if(is_null($webDriver)){
            $onlyFunction  = true;
            $start = $this->start();
            $webDriver = $start->get($GLOBALS['url']);
            // Find an iframe element
            $iframe = $webDriver->findElement(WebDriverBy::id('reetags_iframe'));
            $sessionIframe = $webDriver->switchTo()->frame($iframe);
            // Switch to iframe inside this element
            $clickIframe = $sessionIframe->findElement(WebDriverBy::className('button'))->click();
            // if a button in the iframe is clicked.
            if($clickIframe){
                // Go back to the main document
                $webDriver->switchTo()->defaultContent();
                // We wait for the targeted page to be loaded.
                $webDriver->wait()->until(
                    WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::className('reetagsSvgArrow'))
                );
                // Nous recupérons la position de la prémière video avant le click sur la fleche.
                $locationVideoArrowsBeforeClick = $webDriver->executeScript('return last_iterator'); 
                // Once the page is loaded, we get the class of arrow.
                //$arrows = $webDriver->findElement(WebDriverBy::className('reetagsSvgArrow'));
                $arrows =  $webDriver->findElements(WebDriverBy::cssSelector('.reetagsSvgArrow'))[1];
                // We check if the arrow we have selected is visible on the loaded page.
                if ($arrows->isDisplayed()) {
                    // If the product are looking for exists, click on it. 
                    $arrowsClick = $arrows->click();
                    if($arrowsClick){
                        // Then we get the list of new window handles and search for the new window.
                        $locationVideoArrowsAfterClick = $webDriver->executeScript('return last_iterator'); 
                    }
                }
            } 
            //if($onlyFunction){
                // end the session and close the browser
                $webDriver->quit();
            //}
            // Indicates an error if the two variables containing the equal location of arrow.
            $this->assertNotEquals($locationVideoArrowsBeforeClick, $locationVideoArrowsAfterClick);
        }
    }

    public function testiframeClickSmallScreen($webDriver = null)
    {
        
        $onlyFunction = false;
        if(is_null($webDriver)){
            $onlyFunction  = true;
            $start = $this->start();
            $webDriver = $start->get($GLOBALS['url']);
            // Find an iframe element
            $iframe = $webDriver->findElement(WebDriverBy::id('reetags_iframe'));
            $sessionIframe = $webDriver->switchTo()->frame($iframe);
            // Switch to iframe inside this element
            $clickIframe = $sessionIframe->findElement(WebDriverBy::className('button'))->click();
            // if a button in the iframe is clicked.
            if($clickIframe){
                // Go back to the main document
                $webDriver->switchTo()->defaultContent();
                // We wait for the targeted page to be loaded.
                $webDriver->wait()->until(
                    WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::className('reetagsCloseButtonFullScreen'))
                );
                // Once the page is loaded, we get the class of the cross which is used to close the selected video on the iframe.
                $crossButton = $webDriver->findElement(WebDriverBy::className('reetagsRightCloseFullScreen'));
                // We check if the item we have selected is visible on the loaded page.
                if ($crossButton->isDisplayed()) {
                    // If the item (the cross for closing a video) you are looking for exists, click on it. 
                    $crossButtonClick = $crossButton->click();
                    if($crossButtonClick){
                        // We wait for the targeted page to be loaded.
                        $webDriver->wait()->until(
                            WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::className('reetags_post_details_overlay'))
                        );
                        // The value of the cross is recovered when you switch to the small screen.
                        //$crossButtonSmallScreen = $webDriver->findElement(WebDriverBy::className('reetagsControlsButton'));
                        $crossButtonSmallScreen =  $webDriver->findElements(WebDriverBy::cssSelector('#reetags_post_details_overlay .reetagsControlsButton'))[1];
                        // The event is triggered by hovering the mouse over the small screen.
                        $crossButtonSmallScreenAfterMouseOver = $webDriver->getMouse()->mouseMove($crossButtonSmallScreen->getCoordinates());
                        // Click on the cross that appears after hovering the mouse on the small screen.
                        $crossButtonSmallScreenAfterMouseOverClick = $crossButtonSmallScreenAfterMouseOver->click();
                        // if the click does not produce an error, then :
                        if($crossButtonSmallScreenAfterMouseOverClick){
                            // We get the value of the attribute that allows us to see that the small screen has been closed.
                            $smallScreenClosed = $webDriver->findElement(WebDriverBy::id('reetags_post_details_overlay'))->getAttribute('draggable');
                        }
                    }
                }
            } 
             
            //if($onlyFunction){
                // end the session and close the browser
                $webDriver->quit();
            //}
            // Indicates an error if the expected result after clicking on the iframe does not have the expected type and value. 
            $this->assertSame('false', $smallScreenClosed);
        }
        
    }

    public function testiframeClickFullScreen($webDriver = null)
    {
        $onlyFunction = false;
        if(is_null($webDriver)){
            $onlyFunction  = true;
            $start = $this->start();
            $webDriver = $start->get($GLOBALS['url']);
            // Find an iframe element
            $iframe = $webDriver->findElement(WebDriverBy::id('reetags_iframe'));
            $sessionIframe = $webDriver->switchTo()->frame($iframe);
            // Switch to iframe inside this element
            $clickIframe = $sessionIframe->findElement(WebDriverBy::className('button'))->click();
            // if a button in the iframe is clicked.
            if($clickIframe){
                // Go back to the main document
                $webDriver->switchTo()->defaultContent();
                // We wait for the targeted page to be loaded.
                $webDriver->wait()->until(
                    WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::className('reetagsCloseButtonFullScreen'))
                );
                // Once the page is loaded, we get the class of the cross which is used to close the selected video on the iframe.
                $crossButton = $webDriver->findElement(WebDriverBy::className('reetagsRightCloseFullScreen'));
                // We check if the item we have selected is visible on the loaded page.
                if ($crossButton->isDisplayed()) {
                    // If the item (the cross for closing a video) you are looking for exists, click on it. 
                    $crossButtonClick = $crossButton->click();
                    if($crossButtonClick){
                        // We wait for the targeted page to be loaded.
                        $webDriver->wait()->until(
                            WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::className('reetags_post_details_overlay'))
                        );
                        // // The value of the button fullscreen is recovered when you switch to the small screen..
                        $fullScreenButton =  $webDriver->findElements(WebDriverBy::cssSelector('#reetags_post_details_overlay .reetagsControlsButton'))[0];
                        // The event is triggered by hovering the mouse over the small screen.
                        $fullScreenButtonAfterMouseOver = $webDriver->getMouse()->mouseMove($fullScreenButton->getCoordinates());
                        // Click on the button fullscreen that appears after hovering the mouse on the small screen.
                        $fullScreenButtonAfterMouseOverClick = $fullScreenButtonAfterMouseOver->click();
                        // if the click does not produce an error, then :
                        if($fullScreenButtonAfterMouseOverClick){
                            // We get the value of the attribute that allows us to see that the small screen has been closed.
                            $fullScreenClicked = $webDriver->findElement(WebDriverBy::id('reetags_post_details_overlay'))->getAttribute('draggable');
                        }
                    }
                }
            }
            //if($onlyFunction){
                // end the session and close the browser
                $webDriver->quit();
            //}
            // Indicates an error if the expected result after clicking on the iframe does not have the expected type and value. 
            $this->assertSame('false', $fullScreenClicked);
        }
        
    }

    
    function testMain(){
        //TO DO retirer refresh et faire les enchainement en logique
        $start = $this->start();
        $webDriver = $start->get($GLOBALS['url']);
        $this->testiframeTitre($webDriver);
        $webDriver->navigate()->refresh();
        $this->testiframeVideo($webDriver);
        $webDriver->navigate()->refresh();
        $this->testIframeProduitEnMiniature($webDriver);
        $webDriver->navigate()->refresh();
        $this->testiframeClick($webDriver);
        $webDriver->navigate()->refresh();
        $this->testiframeClickVideoSoundAppears($webDriver);
        $webDriver->navigate()->refresh();
        $this->testiframeClickInformationBar($webDriver);
        $webDriver->navigate()->refresh();
        $this->testiframeDemuteSmallScreen($webDriver);
        $webDriver->navigate()->refresh();
        $this->testiframeClickProductRedirection($webDriver);
        $webDriver->navigate()->refresh();
        $this->testiframeClickCloseVideoSoundStaySmallScreen($webDriver);
        $webDriver->navigate()->refresh();
        $this->testiframeClickArrowsLeftWorkWell($webDriver);
        $webDriver->navigate()->refresh();
        $this->testiframeClickArrowsRightWorkWell($webDriver);
        $webDriver->navigate()->refresh();
        $this->testiframeClickSmallScreen($webDriver);
        $webDriver->navigate()->refresh();
        $this->testiframeClickFullScreen($webDriver);
        $webDriver->quit();
    }
}
