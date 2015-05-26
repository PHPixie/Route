<?php

namespace PHPixie\Tests\Router;

/**
 * @coversDefaultClass \PHPixie\Router\Transator
 */
class TranslatorTest extends \PHPixie\Test\Testcase
{
    protected $builder;
    
    protected $translator;
    
    protected $basePath = 'path-base/';
    protected $baseHost = 'host-base';
    
    protected $routes;
    protected $route;
    
    public function setUp()
    {
        $this->builder = $this->quickMock('\PHPixie\Router\Builder');
        $configData = $this->getSliceData();
        
        $this->method($configData, 'get', $this->basePath, array('basePath', '/'), 0);
        $this->method($configData, 'get', $this->baseHost, array('baseHost', ''), 1);
        
        $routeConfig = $this->getSliceData();
        $this->method($configData, 'slice', $routeConfig, array('route'), 2);
        
        $this->routes = $this->quickMock('\PHPixie\Router\Routes');
        $this->method($this->builder, 'routes', $this->routes, array(), 0);
        
        $this->route = $this->quickMock('\PHPixie\Router\Routes\Route');
        $this->method($this->routes, 'buildFromConfig', $this->route, array($routeConfig), 0);
        
        $this->translator = new \PHPixie\Router\Translator(
            $this->builder,
            $configData
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
        $routePath  = 'pixie.trixie';
        $attributes = array('t' => 1);
        
        $fragment = $this->prepareGenerateFragment($routePath, $attributes);
        $this->method($fragment, 'path', 'pixie', array(), 0);
        
        $generated = $this->translator->generatePath($routePath, $attributes);
        $this->assertSame($this->basePath.'pixie', $generated);
    }
    
    /**
     * @covers ::generateUri
     * @covers ::<protected>
     */
    public function testGenerateUri()
    {
        $this->generateUriTest();
        $this->generateUriTest(true);
    }
    
    protected function matchTest($hostValid = false, $pathValid = false, $withServerRequest = false)
    {
        $serverRequest = $this->getServerRequest();
        
        $builderAt = 0;
        
        if(!$withServerRequest) {
            $this->prepareCurrentServerRequest($serverRequest, $builderAt);
        }
        
        $expected = $this->prepareMatchTest($serverRequest, $hostValid, $pathValid, $builderAt);
        
        if($withServerRequest) {
            $result = $this->translator->match($serverRequest);
        }else{
            $result = $this->translator->match();
        }
        
        $this->assertSame($expected, $result);
    }
    
    protected function prepareMatchTest($serverRequest, $hostValid, $pathValid, &$builderAt = 0)
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
        ), $builderAt++);
        
        $match = $this->getMatch();
        $this->method($this->route, 'match', $match, array($fragment), 0);
        return $match;
    }
    
    protected function generateUriTest($withHost = false)
    {
        $routePath  = 'pixie.trixie';
        $attributes = array('t' => 1);
        
        $builderAt = 0;
        
        $fragment = $this->prepareGenerateFragment($routePath, $attributes, $withHost, $builderAt);
        
        $serverRequest = $this->getServerRequest();
        $this->prepareCurrentServerRequest($serverRequest, $builderAt);

        $uri = $this->getUri();
        $this->method($serverRequest, 'getUri', $uri, array(), 0);
        
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
        
        if($withHost) {
            $result = $this->translator->generateUri($routePath, $attributes, $withHost);
        }else{
            $result = $this->translator->generateUri($routePath, $attributes);
        }
        
        $this->assertSame($uri, $result);
    }
    
    protected function prepareGenerateFragment($routePath, $attributes, $withHost = false, &$builderAt = 0)
    {
        $match = $this->getMatch();
        $this->method($this->builder, 'translatorMatch', $match, array(
            $routePath,
            $attributes
        ), $builderAt++);
        
        $fragment = $this->getFragment();
        $this->method($this->route, 'generate', $fragment, array($match, $withHost), 0);
        return $fragment;
    }
    
    protected function prepareCurrentServerRequest($serverRequest, &$builderAt = 0)
    {
        $context = $this->quickMock('\PHPixie\HTTP\Context', array('serverRequest'));
        $this->method($this->builder, 'getHttpContext', $context, array(), $builderAt++);
        $this->method($context, 'serverRequest', $serverRequest, array(), 0);
    }
    
    protected function getSliceData()
    {
        return $this->quickMock('\PHPixie\Slice\Data');
    }
    
    protected function getFragment()
    {
        return $this->quickMock('\PHPixie\Router\Translator\Fragment');
    }
    
    protected function getMatch()
    {
        return $this->quickMock('\PHPixie\Router\Translator\Match');
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