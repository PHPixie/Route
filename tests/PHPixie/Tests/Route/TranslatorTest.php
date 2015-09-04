<?php

namespace PHPixie\Tests\Route;

/**
 * @coversDefaultClass \PHPixie\Route\Translator
 */
class TranslatorTest extends \PHPixie\Test\Testcase
{
    protected $builder;
    protected $resolver;
    protected $httpContextContainer;
    
    protected $translator;
    
    protected $basePath = 'path-base/';
    protected $baseHost = 'host-base';
    
    public function setUp()
    {
        $this->builder              = $this->quickMock('\PHPixie\Route\Builder');
        $this->resolver             = $this->quickMock('\PHPixie\Route\Resolvers\Resolver');
        $this->httpContextContainer = $this->quickMock('\PHPixie\HTTP\Context\Container');
        $configData                 = $this->prepareConfigData();
        
        $this->translator = new \PHPixie\Route\Translator(
            $this->builder,
            $this->resolver,
            $configData,
            $this->httpContextContainer
        );
    }
    
    /**
     * @covers ::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
    
    }
    
    /**
     * @covers ::match
     * @covers ::<protected>
     */
    public function testMatch()
    {
        $this->matchTest();
        $this->matchTest(true);
        $this->matchTest(true, true);
        $this->matchTest(true, true, true);
    }
    
    /**
     * @covers ::generatePath
     * @covers ::<protected>
     */
    public function testGeneratePath()
    {
        $this->generatePathTest();
        $this->generatePathTest(true);
    }
    
    /**
     * @covers ::generateUri
     * @covers ::<protected>
     */
    public function testGenerateUri()
    {
        $this->generateUriTest();
        $this->generateUriTest(true);
        $this->generateUriTest(true, true);
        $this->generateUriTest(true, true, true);
    }
    
    /**
     * @covers ::match
     * @covers ::generateUri
     * @covers ::<protected>
     */
    public function testNoHttpContextException()
    {
        $configData = $this->prepareConfigData();
        $translator = new \PHPixie\Route\Translator(
            $this->builder,
            $this->resolver,
            $configData
        );
        
        $this->assertException(function() use($translator) {
            $translator->match();
        }, '\PHPixie\Route\Exception');
        
        $this->assertException(function() use($translator) {
            $translator->generateUri('pixie');
        }, '\PHPixie\Route\Exception');
    }
    
    protected function matchTest($hostValid = false, $pathValid = false, $withServerRequest = false)
    {
        $serverRequest = $this->getServerRequest();
        
        if(!$withServerRequest) {
            $this->prepareCurrentServerRequest($serverRequest);
        }
        
        $expected = $this->prepareMatchTest($serverRequest, $hostValid, $pathValid);
        
        if($withServerRequest) {
            $result = $this->translator->match($serverRequest);
        }else{
            $result = $this->translator->match();
        }
        
        $this->assertSame($expected, $result);
    }
    
    protected function prepareMatchTest($serverRequest, $hostValid, $pathValid)
    {
        $uri = $this->getUri();
        $this->method($serverRequest, 'getUri', $uri, array(), 0);
        
        foreach(array('host', 'path') as $key => $name) {
            $valid = ${$name.'Valid'};
            $$name = $valid ? $this->{'base'.ucfirst($name)}.'tail-'.$name : 'pixie';
            $this->method($uri, 'get'.ucfirst($name), $$name, array(), $key);
            if(!$valid) {
                return null;
            }
        }
        
        $fragment = $this->getFragment();
        $this->method($this->builder, 'translatorFragment', $fragment, array(
            'tail-path',
            'tail-host',
            $serverRequest
        ), 0);
        
        $match = $this->getMatch();
        $this->method($this->resolver, 'match', $match, array($fragment), 0);
        return $match;
    }
    
    protected function generatePathTest($withAttributes = false)
    {
        $resolverPath  = 'pixie.trixie';
        $attributes = $withAttributes ? array('t' => 1) : array();
        
        $fragment = $this->prepareGenerateFragment($resolverPath, $attributes);
        $this->method($fragment, 'path', 'pixie', array(), 0);
        
        $params = array($resolverPath);
        if($withAttributes) {
            $params[] = $attributes;
        }
        $generated = call_user_func_array(array($this->translator, 'generatePath'), $params);
        $this->assertSame($this->basePath.'pixie', $generated);
    }
    
    protected function generateUriTest(
        $withAttributes    = false,
        $withHost          = false,
        $withServerRequest = false
    )
    {
        $resolverPath  = 'pixie.trixie';
        
        $attributes = $withAttributes ? array('t' => 1) : array();
        
        $serverRequest = $this->getServerRequest();
        if(!$withServerRequest) {
            $this->prepareCurrentServerRequest($serverRequest);
        }
        
        $uri = $this->getUri();
        $this->method($serverRequest, 'getUri', $uri, array(), 0);
        
        $fragment = $this->prepareGenerateFragment($resolverPath, $attributes, $withHost);
        
        $this->method($fragment, 'path', 'pixie-path', array(), 0);
        $pathUri = $this->getUri();
        $this->method($uri, 'withPath', $pathUri, array($this->basePath.'pixie-path'), 0);
        $uri = $pathUri;
        
        if($withHost) {
            $this->method($fragment, 'host', 'pixie-host', array(), 1);
            $hostUri = $this->getUri();
            $this->method($uri, 'withHost', $hostUri, array($this->baseHost.'pixie-host'), 0);
            $uri = $hostUri;
        }
        
        $params = array($resolverPath);
        if($withAttributes) {
            $params[] = $attributes;
        }
        
        if($withHost) {
            $params[] = $withHost;
        }
        
        if($withServerRequest) {
            $params[] = $serverRequest;
        }
        
        $result = call_user_func_array(array($this->translator, 'generateUri'), $params);
        
        $this->assertSame($uri, $result);
    }
    
    protected function prepareGenerateFragment($resolverPath, $attributes, $withHost = false)
    {
        $match = $this->getMatch();
        $this->method($this->builder, 'translatorMatch', $match, array(
            $attributes,
            $resolverPath
        ), 0);
        
        $fragment = $this->getFragment();
        $this->method($this->resolver, 'generate', $fragment, array($match, $withHost), 0);
        return $fragment;
    }
    
    protected function prepareCurrentServerRequest($serverRequest)
    {
        $context = $this->quickMock('\PHPixie\HTTP\Context');
        $this->method($this->httpContextContainer, 'httpContext', $context, array(), 0);
        
        $request = $this->quickMock('\PHPixie\HTTP\Request');
        $this->method($context, 'request', $request, array(), 0);
        
        $this->method($request, 'serverRequest', $serverRequest, array(), 0);
    }
    
    protected function prepareConfigData()
    {
        $configData = $this->getSliceData();
        
        $this->method($configData, 'get', $this->basePath, array('basePath', '/'), 0);
        $this->method($configData, 'get', $this->baseHost, array('baseHost', ''), 1);
        
        return $configData;
    }
    
    protected function getSliceData()
    {
        return $this->quickMock('\PHPixie\Slice\Data');
    }
    
    protected function getFragment()
    {
        return $this->quickMock('\PHPixie\Route\Translator\Fragment');
    }
    
    protected function getMatch()
    {
        return $this->quickMock('\PHPixie\Route\Translator\Match');
    }
    
    protected function getServerRequest()
    {
        return $this->quickMock('\Psr\Http\Message\ServerRequestInterface');
    }
    
    protected function getUri()
    {
        return $this->quickMock('\Psr\Http\Message\UriInterface');
    }
}