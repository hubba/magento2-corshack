# Yireo CorsHack
The new Magento 2.3 GraphQL system could be used with GraphQL clients
(like Apollo or even Axios) to fetch data from Magento. Most of these
clients use an HTTP request OPTIONS to see if CORS restrictions apply.
This module adds an OPTIONS check to the GraphQL API. Also, this module
adds Cross Origin headers (currently hard-coded to
`http://localhost:3000`).

### Installation
```
composer require yireo-training/magento2-corshack
./bin/magento module:enable Yireo_CorsHack
```

### Configuration
Navigate to **Advanced > Yireo CorsHack** and add your URLs to the **Origin Domain** option (comma-separated).

URLs are matched using `preg_match()` so you can enter regular expressions to get technical on your matching.
