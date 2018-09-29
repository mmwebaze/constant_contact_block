<?php
namespace Drupal\Tests\constant_contact_block;

use Drupal\Tests\UnitTestCase;
use Drupal\constant_contact_block\services\AuthenticationService;
use Drupal\Core\Config\ConfigFactoryInterface;
use GuzzleHttp;

/**
 * Class AuthenticationServiceTest
 *
 * @package Drupal\Tests\constant_contact_block
 *
 * @group constant_contact_block
 */
class AuthenticationServiceTest extends UnitTestCase {
    /**
     * @var Drupal\constant_contact_block\services\AuthenticationService
     */
    protected $authenticationService;
    /**
     * The mocked config object
     *
     * @var \Drupal\Core\Config\ConfigFactoryInterface PHPUnit_Framework_MockObject_MockObject
     */
    protected $configFactory;
    private $httpClient;

    public function setUp() {
        $this->httpClient = new GuzzleHttp\Client();
        $map = [
            ['auth_request_url', 'https://oauth2.constantcontact.com/oauth2/oauth/siteowner/authorize'],
            ['client_secret', 't8tjrrCVhWAwgDYvguzSABdy'],
            ['api_key', 'g2jnh338hrwqxtzkuhxzkrqt'],
            ['redirect_uri', 'http://drupal-8-6-1.dd:8083/constant_contact_block/getCode']
        ];
        /*$this->configFactory = $this->getConfigFactoryStub(
            [
                'constant_contact_block.constantcontantconfig' => [
                    'auth_request_url' => 'https://api.constantcontact.com/v2/',
                    'client_secret' => 't8tjrrCVhWAwgDYvguzSABdy',
                    'api_key' => 'g2jnh338hrwqxtzkuhxzkrqt',
                    'redirect_uri' => 'http://drupal-8-6-1.dd:8083/constant_contact_block/getCode'
                ]
            ]
        );*/
        $config = $this->configFactory = $this->createMock(ConfigFactoryInterface::class);
        $config->expects($this->any())
            ->method('get')
            ->will($this->returnValueMap($map));

        $config->expects($this->any())
            ->method('getEditable')
            ->willReturn($config);



        //$this->configFactory->getEditable('constant_contact_block.constantcontantconfig');//->set('auth_request_url', 'https://api.constantcontact.com/v2/');
        $this->authenticationService = new AuthenticationService($config);
    }
    public function testGetAuthorizationUrl(){
        $url = $this->authenticationService->getAuthorizationUrl();
        $regEx  = "((https?|http)\:\/\/)?";
        $regEx .= "([a-z0-9+!*(),;?&=\$_.-]+(\:[a-z0-9+!*(),;?&=\$_.-]+)?@)?([a-z0-9-.]*)\.([a-z]{2,3})(\:[0-9]{2,5})?";
        $regEx .= "(\/([a-z0-9+\$_-]\.?)+)*\/?(\?[a-z+&\$_.-][a-z0-9;:@&%=+\/\$_.-]*)?(#[a-z_.-][a-z0-9+\$_.-]*)?";

        $this->assertTrue((boolean) preg_match("/^$regEx$/i", $url));
    }

    public function testGetAccessToken(){
        $url = $this->authenticationService->getAuthorizationUrl();
        //print_r($url);
        //$accessToken = $this->authenticationService->getAccessToken();
        //$http = new GuzzleHttp\Client(['base_uri' => $url]);
        $request = $this->httpClient->get($url);
        print_r((string)$request->getBody());
    }

    public function tearDown() {
        unset($this->authenticationService);
    }
}
//https://api.constantcontact.com/v2/?response_type=code&client_id=g2jnh338hrwqxtzkuhxzkrqt&oauthSignup=true&redirect_uri=http://drupal-8-6-1.dd:8