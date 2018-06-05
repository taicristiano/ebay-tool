<?php

namespace App\Services;

use GuzzleHttp\Client;

class AmazonMwsClient extends CommonService
{
    const METHOD_POST = 'POST';
    const SIGNATURE_METHOD = 'HmacSHA256';

    /**
     * @var string
     */
    private $accessKey;

    /**
     * @var string
     */
    private $secretKey;

    /**
     * @var string
     */
    private $sellerId;

    /**
     * @var array
     */
    private $marketplaceIds;

    /**
     * @var string
     */
    private $mwsAuthToken;

    /**
     * @var string
     */
    private $applicationName;

    /**
     * @var string
     */
    private $applicationVersion;

    /**
     * @var string
     */
    private $baseUrl;

    /**
     * AmazonMwsClient constructor.
     *
     * @param string $accessKey - also known as "AWS Access Key ID"
     * @param string $secretKey
     * @param string $sellerId
     * @param array $marketplaceIds
     * @param string $mwsAuthToken
     * @param string|null $baseUrl - default is US, see the possible values. UK is https://mws.amazonservices.co.uk for example
     */
    public function __construct(
        $accessKey,
        $secretKey,
        $sellerId,
        $marketplaceIds,
        $mwsAuthToken,
        $applicationName = 'YucaAmazonMwsClient',
        $applicationVersion = '1.0',
        $baseUrl = 'https://mws.amazonservices.com'
    ) {
        $needle = 'https://mws.amazonservices';

        if (strpos($baseUrl, $needle) === false) {
            throw new \InvalidArgumentException(
                sprintf('Base URl must contain "%s", received "%s"', $needle, $baseUrl)
            );
        }

        if (is_null($applicationName) || $applicationName === '') {
            throw new \InvalidArgumentException('Application name cannot be null');
        }

        if (is_null($applicationVersion) || $applicationVersion === "") {
            throw new \InvalidArgumentException('Application version cannot be null');
        }

        $this->accessKey = $accessKey;
        $this->secretKey = $secretKey;
        $this->sellerId = $sellerId;
        $this->marketplaceIds = $marketplaceIds;
        $this->mwsAuthToken = $mwsAuthToken;
        $this->applicationName = $applicationName;
        $this->applicationVersion = $applicationVersion;
        $this->baseUrl = $baseUrl;
    }

    /**
     * Sends the request to Amazon MWS API
     *
     * Check their documentation and scratchpad to learn all params and actions available
     * @link  http://docs.developer.amazonservices.com/en_UK/dev_guide/DG_Registering.html
     * @link  https://mws.amazonservices.co.uk/scratchpad/index.html
     *
     * @param string $action
     * @param string $versionUri
     * @param array $optionalParams
     * @param boolean
     *
     * @return mix
     */
    public function send($action, $versionUri, $optionalParams = [], $debug = false)
    {
        $params = array_merge($optionalParams, $this->buildRequiredParams($action, $versionUri, isset($optionalParams['MarketplaceId'])));

        $queryString = $this->genQuery($params, $versionUri);

        $client = new Client([
            'debug' => $debug,
            'base_uri'    => $this->baseUrl,
            'body'        => $queryString,
            'http_errors' => false,
            'headers'     => [
                'User-Agent' => $this->genUserAgent(),
                'Content-Type' => 'application/x-www-form-urlencoded; charset=UTF-8',
            ],
        ]);

        $response = $client->request(self::METHOD_POST, $versionUri);

        libxml_use_internal_errors(true);
        $content = $response->getBody()->getContents();
        $result = [];
        $content = explode("<AttributeSets>", $content);
        unset($content[0]);
        foreach ($content as $key => $item) {
            $item = explode("</AttributeSets>", $item);
            $item = simplexml_load_string($item[0]);
            if (!$item) {
                libxml_clear_errors();
            }

            if (isset($item->Error)) {
                throw new AmazonMwsClientException($item, $response->getHeaders());
            }
            $result[] = json_decode(json_encode($item), true);
        }
        return $result;
    }

    /**
     * Generate the user agent header
     *
     * @return string
     */
    protected function genUserAgent()
    {
        $userAgent = $this->applicationName . '/' . $this->applicationVersion;

        $userAgent .= ' (';
        $userAgent .= 'Language=PHP/' . phpversion();
        $userAgent .= '; ';
        $userAgent .= 'Platform=' . php_uname('s') . '/' . php_uname('m') . '/' . php_uname('r');
        $userAgent .= ')';

        return $userAgent;
    }

    /**
     * Formats the provided string using rawurlencode
     *
     * @param string $value
     *
     * @return string
     */
    protected function urlencode($value)
    {
        return rawurlencode($value);
    }

    /**
     * Fuses all of the parameters together into a string, copied from Amazon
     *
     * @param array $parameters
     *
     * @return string
     */
    protected function getParametersAsString($parameters)
    {
        $queryParameters = [];
        foreach ($parameters as $key => $value) {
            $queryParameters[] = $key . '=' . $this->urlencode($value);
        }

        return implode('&', $queryParameters);
    }

    /**
     * Generates the string to sign, copied from Amazon
     *
     * @param array $parameters
     * @param string $uri
     *
     * @return string
     */
    protected function calculateStringToSign($parameters, $uri)
    {
        $data = self::METHOD_POST;
        $data .= "\n";
        $endpoint = parse_url(sprintf('%s%s', $this->baseUrl, $uri));

        $data .= $endpoint['host'];
        $data .= "\n";
        $uri = array_key_exists('path', $endpoint) ? $endpoint['path'] : null;

        if (!isset($uri)) {
            $uri = "/";
        }

        $uriencoded = implode("/", array_map([$this, "urlencode"], explode("/", $uri)));
        $data .= $uriencoded;
        $data .= "\n";
        uksort($parameters, 'strcmp');

        $data .= $this->getParametersAsString($parameters);

        return $data;
    }

    /**
     * Handles generation of the signed query string.
     *
     * This method uses the secret key from the config file to generate the
     * signed query string.
     * It also handles the creation of the timestamp option prior.
     *
     * @param array $params
     * @param string $uri
     *
     * @return string query string to send in the body
     */
    protected function genQuery($params, $uri)
    {
        $params['Timestamp'] = $this->genTime();
        unset($params['Signature']);
        $params['Signature'] = $this->signParameters($params, $uri);

        return $this->getParametersAsString($params);
    }

    /**
     * Generates timestamp in ISO8601 format.
     *
     * This method creates a timestamp from the provided string in ISO8601 format.
     * The string given is passed through <i>strtotime</i> before being used. The
     * value returned is actually two minutes early, to prevent it from tripping up
     * Amazon. If no time is given, the current time is used.
     *
     * @param string $time [optional] <p>The time to use. Since this value passed through <i>strtotime</i> first,
     *                     values such as "-1 month" or "10 September 2000" are fine.
     *                     Defaults to the current time.</p>
     *
     * @return string Unix timestamp of the time, minus 2 minutes.
     */
    protected function genTime($time = null)
    {
        if ($time) {
            $timestamp = strtotime($time);
        } else {
            $timestamp = time();
        }

        return date('Y-m-d\TH:i:sO', $timestamp - 120);
    }

    /**
     * Validates signature and sets up signing of them, copied from Amazon
     *
     * @param array $parameters
     * @param string $uri
     *
     * @return string signed string
     */
    protected function signParameters($parameters, $uri)
    {
        $stringToSign = $this->calculateStringToSign($parameters, $uri);

        return $this->sign($stringToSign);
    }

    /**
     * Runs the hash, copied from Amazon
     * Only HmacSHA256 is available
     * Uses the Amazon Secret Key as key
     *
     * @param string $data
     *
     * @return string
     */
    protected function sign($data)
    {
        return base64_encode(
            hash_hmac('sha256', $data, $this->secretKey, true)
        );
    }

    /**
     * @param string $action
     * @param string $versionUri
     * @param boolean $ignoreMarketplaceIds
     *
     * @return array
     */
    protected function buildRequiredParams($action, $versionUri, $ignoreMarketplaceIds = false)
    {
        // extract version from url
        $version = (explode('/', $versionUri));

        $requiredParams = [
            'AWSAccessKeyId'     => $this->accessKey,
            'Action'             => $action,
            'SellerId'           => $this->sellerId,
            'MWSAuthToken'       => $this->mwsAuthToken,
            'SignatureVersion'   => 2,
            'Version'            => end($version),
            'SignatureMethod'    => self::SIGNATURE_METHOD
        ];

        if (! $ignoreMarketplaceIds) {
            $key = 1;
            foreach ($this->marketplaceIds as $marketplaceId) {
                $param = sprintf('MarketplaceId.Id.%s', $key);

                $requiredParams[$param] = $marketplaceId;
                $key++;
            }
        }

        return $requiredParams;
    }
}
