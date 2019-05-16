<?php
declare(strict_types=1);

namespace Yireo\CorsHack\Utils;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Response\HttpInterface as HttpResponse;
use Magento\Framework\App\Response\RedirectInterface;
use Psr\Log\LoggerInterface;

/**
 * Class ResponseGenerator
 * @package Yireo\CorsHack\Utils
 */
class ResponseGenerator
{
    /**
     * @var RedirectInterface
     */
    private $redirect;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    private $logger;

    /**
     * HeaderGenerator constructor.
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        RedirectInterface $redirect,
        ScopeConfigInterface $scopeConfig,
        LoggerInterface $logger
    ) {
        $this->redirect = $redirect;
        $this->scopeConfig = $scopeConfig;
        $this->logger = $logger;
    }

    /**
     * @param HttpResponse $response
     * @return HttpResponse
     */
    public function modifyResponse(HttpResponse $response): HttpResponse
    {
        $domain = $this->getAccessControlAllowOriginDomain();
        $response->setHeader('Access-Control-Allow-Origin', $domain);

        $headers = $this->getAccessControlAllowHeaders();
        $response->setHeader('Access-Control-Allow-Headers', implode(',', $headers), true);
        $response->setHeader('Access-Control-Allow-Credentials', 'true');
        $response->setHeader('X-Fart-Signal', 'butts');

        return $response;
    }

    /**
     * @return string
     */
    private function getAccessControlAllowOriginDomain(): string
    {
        $storedOrigins = (string) $this->scopeConfig->getValue('corshack/settings/origin');

        $url = isset($_SERVER['HTTP_ORIGIN'])
            ? $_SERVER['HTTP_ORIGIN']
            : (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null);

        if (!$url) {
            return '';
        }

        $storedOrigins = explode(',', $storedOrigins);

        foreach (array_unique($storedOrigins) as $origin) {
            $pattern = '~' . trim($origin) . '~';

            $this->logger->error($pattern);

            if (preg_match($pattern, $url) === 1) {
                $url = parse_url($url);

                return $url['scheme'] . '://' . $url['host'] . (isset($url['port']) ? ':' . $url['port'] : '');
            }
        }

        return '';
    }

    /**
     * @return array
     */
    private function getAccessControlAllowHeaders(): array
    {
        $headers = [];
        $headers[] = 'Content-Type';
        $headers[] = 'Authorization';

        return $headers;
    }
}
